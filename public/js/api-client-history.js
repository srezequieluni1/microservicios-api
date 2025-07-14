/**
 * API Client History Manager
 * Maneja el historial de consultas API almacenadas en localStorage
 */

class ApiHistoryManager {
    constructor() {
        this.storageKey = 'api_client_history';
        this.maxHistoryItems = 50;
        this.currentQueryId = null;
        this.isUpdating = false;

        this.initializeEventListeners();
        this.loadHistoryList();

        // Auto-guardar cuando se modifiquen los campos
        this.setupAutoSave();
    }

    /**
     * Inicializar event listeners
     */
    initializeEventListeners() {
        const historySelect = document.getElementById('queryHistory');
        const deleteButton = document.getElementById('deleteHistoryButton');
        const form = document.getElementById('apiForm');

        // Cargar consulta seleccionada
        historySelect.addEventListener('change', (e) => {
            if (e.target.value) {
                this.loadQuery(e.target.value);
            }
        });

        // Eliminar consulta
        deleteButton.addEventListener('click', () => {
            this.deleteSelectedQuery();
        });

        // Guardar al enviar formulario
        form.addEventListener('submit', (e) => {
            this.saveCurrentQuery();
        });

        // Actualizar estado del botón eliminar cuando cambie la selección
        historySelect.addEventListener('change', () => {
            this.updateDeleteButtonState();
        });
    }

    /**
     * Configurar auto-guardado cuando se modifiquen los campos
     */
    setupAutoSave() {
        const fieldsToWatch = [
            'queryName',
            'httpMethod',
            'apiUrl',
            'customHeaders',
            'requestBody'
        ];

        fieldsToWatch.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                // Usar diferentes eventos según el tipo de campo
                const events = field.tagName === 'SELECT' ? ['change'] : ['input', 'blur'];

                events.forEach(eventType => {
                    field.addEventListener(eventType, () => {
                        if (!this.isUpdating && this.currentQueryId) {
                            this.updateCurrentQuery();
                            this.highlightFieldChange(field);
                        }
                    });
                });
            }
        });
    }

    /**
     * Resaltar visualmente que un campo ha cambiado
     */
    highlightFieldChange(field) {
        field.classList.add('field-changed');
        setTimeout(() => {
            field.classList.remove('field-changed');
        }, 300);
    }

    /**
     * Obtener todos los queries del localStorage
     */
    getStoredQueries() {
        try {
            const stored = localStorage.getItem(this.storageKey);
            return stored ? JSON.parse(stored) : {};
        } catch (error) {
            console.error('Error al cargar historial:', error);
            return {};
        }
    }

    /**
     * Guardar queries en localStorage
     */
    saveStoredQueries(queries) {
        try {
            localStorage.setItem(this.storageKey, JSON.stringify(queries));
        } catch (error) {
            console.error('Error al guardar historial:', error);
        }
    }

    /**
     * Generar ID único para una consulta
     */
    generateQueryId(name) {
        const timestamp = Date.now();
        const sanitizedName = name.replace(/[^a-zA-Z0-9]/g, '_').toLowerCase();
        return `${sanitizedName}_${timestamp}`;
    }

    /**
     * Obtener datos actuales del formulario
     */
    getCurrentFormData() {
        return {
            name: document.getElementById('queryName').value.trim(),
            method: document.getElementById('httpMethod').value,
            url: document.getElementById('apiUrl').value.trim(),
            headers: document.getElementById('customHeaders').value.trim(),
            body: document.getElementById('requestBody').value.trim(),
            timestamp: Date.now()
        };
    }

    /**
     * Cargar datos en el formulario
     */
    loadFormData(data) {
        this.isUpdating = true;

        document.getElementById('queryName').value = data.name || '';
        document.getElementById('httpMethod').value = data.method || 'GET';
        document.getElementById('apiUrl').value = data.url || '';
        document.getElementById('customHeaders').value = data.headers || '';
        document.getElementById('requestBody').value = data.body || '';

        this.isUpdating = false;
    }

    /**
     * Guardar consulta actual
     */
    saveCurrentQuery() {
        const formData = this.getCurrentFormData();

        if (!formData.name) {
            // Si no tiene nombre, generar uno automático
            const url = new URL(formData.url).pathname;
            formData.name = `${formData.method} ${url}`;
        }

        const queries = this.getStoredQueries();
        const queryId = this.currentQueryId || this.generateQueryId(formData.name);

        queries[queryId] = formData;

        // Limpiar historial si excede el máximo
        this.cleanupHistory(queries);

        this.saveStoredQueries(queries);
        this.currentQueryId = queryId;

        this.loadHistoryList();
        this.selectCurrentQuery();
        this.showSavedIndicator();

        return queryId;
    }

    /**
     * Actualizar consulta actual cuando se modifiquen campos
     */
    updateCurrentQuery() {
        if (!this.currentQueryId) return;

        const formData = this.getCurrentFormData();
        const queries = this.getStoredQueries();

        if (queries[this.currentQueryId]) {
            // Mantener el nombre original si no se ha cambiado
            if (!formData.name) {
                formData.name = queries[this.currentQueryId].name;
            }

            queries[this.currentQueryId] = {
                ...queries[this.currentQueryId],
                ...formData,
                timestamp: Date.now() // Actualizar timestamp
            };

            this.saveStoredQueries(queries);
            this.loadHistoryList();
            this.selectCurrentQuery();
        }
    }

    /**
     * Cargar una consulta específica
     */
    loadQuery(queryId) {
        const queries = this.getStoredQueries();
        const query = queries[queryId];

        if (query) {
            this.currentQueryId = queryId;
            this.loadFormData(query);
            this.updateDeleteButtonState();
        }
    }

    /**
     * Eliminar consulta seleccionada
     */
    deleteSelectedQuery() {
        const historySelect = document.getElementById('queryHistory');
        const selectedId = historySelect.value;

        if (!selectedId) return;

        const queries = this.getStoredQueries();
        const queryName = queries[selectedId]?.name || 'consulta';

        if (confirm(`¿Estás seguro de que quieres eliminar "${queryName}"?`)) {
            delete queries[selectedId];
            this.saveStoredQueries(queries);

            // Si era la consulta actual, limpiar
            if (this.currentQueryId === selectedId) {
                this.currentQueryId = null;
            }

            this.loadHistoryList();
            this.clearForm();
            this.updateDeleteButtonState();
        }
    }

    /**
     * Cargar lista de historial en el select
     */
    loadHistoryList() {
        const historySelect = document.getElementById('queryHistory');
        const queries = this.getStoredQueries();

        // Convertir a array y ordenar por timestamp descendente
        const sortedQueries = Object.entries(queries)
            .sort(([,a], [,b]) => b.timestamp - a.timestamp);

        // Limpiar opciones existentes excepto la primera
        historySelect.innerHTML = '<option value="">Seleccionar consulta previa...</option>';

        // Agregar opciones
        sortedQueries.forEach(([id, query]) => {
            const option = document.createElement('option');
            option.value = id;

            const date = new Date(query.timestamp).toLocaleDateString();
            const time = new Date(query.timestamp).toLocaleTimeString('es-ES', {
                hour: '2-digit',
                minute: '2-digit'
            });

            option.textContent = `${query.name} (${date} ${time})`;
            historySelect.appendChild(option);
        });

        this.updateDeleteButtonState();
    }

    /**
     * Seleccionar consulta actual en el dropdown
     */
    selectCurrentQuery() {
        if (this.currentQueryId) {
            const historySelect = document.getElementById('queryHistory');
            historySelect.value = this.currentQueryId;
        }
    }

    /**
     * Actualizar estado del botón eliminar
     */
    updateDeleteButtonState() {
        const historySelect = document.getElementById('queryHistory');
        const deleteButton = document.getElementById('deleteHistoryButton');

        deleteButton.disabled = !historySelect.value;
    }

    /**
     * Limpiar formulario
     */
    clearForm() {
        this.isUpdating = true;

        document.getElementById('queryName').value = '';
        document.getElementById('httpMethod').value = 'GET';
        document.getElementById('apiUrl').value = '';
        document.getElementById('customHeaders').value = '';
        document.getElementById('requestBody').value = '';
        document.getElementById('queryHistory').value = '';

        this.currentQueryId = null;
        this.isUpdating = false;
    }

    /**
     * Mostrar indicador de guardado
     */
    showSavedIndicator() {
        // Crear indicador si no existe
        let indicator = document.querySelector('.query-saved-indicator');
        if (!indicator) {
            indicator = document.createElement('div');
            indicator.className = 'query-saved-indicator';
            indicator.textContent = '✓ Guardado';

            const nameField = document.getElementById('queryName').parentElement;
            nameField.appendChild(indicator);
        }

        // Mostrar animación
        indicator.classList.add('show');
        setTimeout(() => {
            indicator.classList.remove('show');
        }, 2000);
    }

    /**
     * Limpiar historial antiguo
     */
    cleanupHistory(queries) {
        const entries = Object.entries(queries);

        if (entries.length > this.maxHistoryItems) {
            // Ordenar por timestamp y mantener solo los más recientes
            const sorted = entries.sort(([,a], [,b]) => b.timestamp - a.timestamp);
            const toKeep = sorted.slice(0, this.maxHistoryItems);

            // Crear nuevo objeto con solo las entradas a mantener
            const cleaned = {};
            toKeep.forEach(([id, data]) => {
                cleaned[id] = data;
            });

            // Reemplazar queries con la versión limpia
            Object.keys(queries).forEach(key => delete queries[key]);
            Object.assign(queries, cleaned);
        }
    }

    /**
     * Exportar historial como JSON
     */
    exportHistory() {
        const queries = this.getStoredQueries();
        const dataStr = JSON.stringify(queries, null, 2);
        const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);

        const exportFileDefaultName = 'api_client_history.json';

        const linkElement = document.createElement('a');
        linkElement.setAttribute('href', dataUri);
        linkElement.setAttribute('download', exportFileDefaultName);
        linkElement.click();
    }

    /**
     * Importar historial desde JSON
     */
    importHistory(file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            try {
                const imported = JSON.parse(e.target.result);
                const existing = this.getStoredQueries();

                // Combinar historiales
                const combined = { ...existing, ...imported };
                this.saveStoredQueries(combined);
                this.loadHistoryList();

                alert('Historial importado exitosamente');
            } catch (error) {
                alert('Error al importar historial: archivo inválido');
            }
        };
        reader.readAsText(file);
    }

    /**
     * Obtener estadísticas del historial
     */
    getStats() {
        const queries = this.getStoredQueries();
        const methods = {};
        let totalQueries = 0;

        Object.values(queries).forEach(query => {
            totalQueries++;
            methods[query.method] = (methods[query.method] || 0) + 1;
        });

        return {
            total: totalQueries,
            methods,
            oldestQuery: Math.min(...Object.values(queries).map(q => q.timestamp)),
            newestQuery: Math.max(...Object.values(queries).map(q => q.timestamp))
        };
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    window.apiHistoryManager = new ApiHistoryManager();
});

// Exportar para uso global si es necesario
window.ApiHistoryManager = ApiHistoryManager;
