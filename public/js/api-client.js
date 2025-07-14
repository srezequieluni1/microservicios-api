// Variables globales
let currentResponse = null;

// Manejo de errores global para debugging
window.addEventListener('error', function(event) {
    console.error('🚨 Error JavaScript capturado:', {
        message: event.message,
        filename: event.filename,
        lineno: event.lineno,
        colno: event.colno,
        error: event.error
    });
});

// Manejo de promesas rechazadas
window.addEventListener('unhandledrejection', function(event) {
    console.error('🚨 Promesa rechazada no manejada:', event.reason);
});

// Función de inicialización
function initializeApiClient() {
    console.log('🚀 Inicializando API Client...');

    // Referencias a elementos del DOM
    const apiForm = document.getElementById('apiForm');
    const httpMethodSelect = document.getElementById('httpMethod');
    const apiUrlInput = document.getElementById('apiUrl');
    const customHeadersInput = document.getElementById('customHeaders');
    const requestBodyInput = document.getElementById('requestBody');
    const executeButton = document.getElementById('executeButton');
    const buttonText = document.querySelector('.button-text');
    const loadingSpinner = document.querySelector('.loading');

    const responseContainer = document.getElementById('responseContainer');
    const responseContent = document.getElementById('responseContent');
    const statusBadge = document.getElementById('statusBadge');
    const responseTime = document.getElementById('responseTime');
    const responseHeaders = document.getElementById('responseHeaders');
    const responseBody = document.getElementById('responseBody');
    const copyButton = document.getElementById('copyButton');

    // Verificar elementos críticos
    const criticalElements = {
        apiForm,
        httpMethodSelect,
        apiUrlInput,
        executeButton
    };

    for (const [name, element] of Object.entries(criticalElements)) {
        if (!element) {
            console.error(`❌ Elemento crítico no encontrado: ${name}`);
            return;
        }
    }

    console.log('✅ Todos los elementos DOM encontrados');

    // Hacer las referencias globales para que estén disponibles en otras funciones
    window.apiClientElements = {
        apiForm,
        httpMethodSelect,
        apiUrlInput,
        customHeadersInput,
        requestBodyInput,
        executeButton,
        buttonText,
        loadingSpinner,
        responseContainer,
        responseContent,
        statusBadge,
        responseTime,
        responseHeaders,
        responseBody,
        copyButton
    };

    // Event listeners
    if (apiForm) {
        apiForm.addEventListener('submit', handleFormSubmit);
        console.log('📝 Event listener del formulario agregado');
    }

    if (copyButton) {
        copyButton.addEventListener('click', copyResponseToClipboard);
        console.log('📋 Event listener del botón copiar agregado');
    }

    // Validación en tiempo real
    if (apiUrlInput) {
        apiUrlInput.addEventListener('blur', validateUrl);
    }
    if (customHeadersInput) {
        customHeadersInput.addEventListener('blur', () => validateJSON(customHeadersInput, 'headersError'));
    }
    if (requestBodyInput) {
        requestBodyInput.addEventListener('blur', () => validateJSON(requestBodyInput, 'bodyError'));
    }

    console.log('🎉 API Client inicializado correctamente');
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', initializeApiClient);

/**
 * Maneja el envío del formulario
 */
async function handleFormSubmit(event) {
    event.preventDefault();

    console.log('🔄 Iniciando envío del formulario...');

    // Validar formulario antes de enviar
    if (!validateForm()) {
        console.log('❌ Validación del formulario falló');
        return;
    }

    console.log('✅ Formulario validado correctamente');

    // Guardar en historial antes de enviar
    try {
        if (window.apiHistoryManager) {
            window.apiHistoryManager.saveCurrentQuery();
            console.log('💾 Consulta guardada en historial');
        }
    } catch (historyError) {
        console.warn('Error al guardar en historial (no afecta la ejecución):', historyError);
    }

    // Mostrar estado de carga
    setLoadingState(true);
    console.log('⏳ Estado de carga activado');

    try {
        const startTime = performance.now();

        // Preparar la petición
        const requestOptions = await prepareRequest();
        console.log('📦 Opciones de petición preparadas:', requestOptions);

        // Realizar la petición HTTP
        const apiUrlInput = window.apiClientElements?.apiUrlInput;
        if (!apiUrlInput) {
            throw new Error('Elemento URL no encontrado');
        }

        const url = apiUrlInput.value.trim();
        console.log('🌐 Enviando petición a:', url);

        const response = await fetch(url, requestOptions);

        const endTime = performance.now();
        const duration = Math.round(endTime - startTime);

        console.log('📡 Respuesta recibida en', duration, 'ms');

        // Procesar la respuesta
        await handleResponse(response, duration);

    } catch (error) {
        console.error('❌ Error en la petición:', error);
        handleError(error);
    } finally {
        setLoadingState(false);
        console.log('✅ Estado de carga desactivado');
    }
}

/**
 * Valida todo el formulario
 */
function validateForm() {
    let isValid = true;

    // Validar URL
    if (!validateUrl()) {
        isValid = false;
    }

    // Obtener referencias a los elementos
    const customHeadersInput = window.apiClientElements?.customHeadersInput;
    const requestBodyInput = window.apiClientElements?.requestBodyInput;

    // Validar headers JSON
    if (customHeadersInput && !validateJSON(customHeadersInput, 'headersError')) {
        isValid = false;
    }

    // Validar body JSON
    if (requestBodyInput && !validateJSON(requestBodyInput, 'bodyError')) {
        isValid = false;
    }

    return isValid;
}

/**
 * Valida la URL ingresada
 */
function validateUrl() {
    const apiUrlInput = window.apiClientElements?.apiUrlInput;
    if (!apiUrlInput) return false;

    const url = apiUrlInput.value.trim();
    const urlError = document.getElementById('urlError');

    if (!url) {
        showFieldError(apiUrlInput, urlError, 'La URL es requerida');
        return false;
    }

    try {
        new URL(url);
        hideFieldError(apiUrlInput, urlError);
        return true;
    } catch {
        showFieldError(apiUrlInput, urlError, 'Por favor ingresa una URL válida');
        return false;
    }
}

/**
 * Valida formato JSON en un campo
 */
function validateJSON(input, errorId) {
    const value = input.value.trim();
    const errorElement = document.getElementById(errorId);

    if (!value) {
        hideFieldError(input, errorElement);
        return true;
    }

    try {
        JSON.parse(value);
        hideFieldError(input, errorElement);
        return true;
    } catch {
        showFieldError(input, errorElement, 'Formato JSON inválido');
        return false;
    }
}

/**
 * Muestra error en un campo
 */
function showFieldError(input, errorElement, message) {
    input.classList.add('form-error');
    errorElement.textContent = message;
    errorElement.classList.add('show-error');
}

/**
 * Oculta error en un campo
 */
function hideFieldError(input, errorElement) {
    input.classList.remove('form-error');
    errorElement.classList.remove('show-error');
}

/**
 * Prepara las opciones de la petición HTTP
 */
async function prepareRequest() {
    const httpMethodSelect = window.apiClientElements?.httpMethodSelect;
    const customHeadersInput = window.apiClientElements?.customHeadersInput;
    const requestBodyInput = window.apiClientElements?.requestBodyInput;

    if (!httpMethodSelect) {
        throw new Error('Elementos del formulario no encontrados');
    }

    const method = httpMethodSelect.value;
    const headers = {};

    // Parsear headers personalizados
    const customHeaders = customHeadersInput?.value.trim() || '';
    if (customHeaders) {
        try {
            Object.assign(headers, JSON.parse(customHeaders));
        } catch (error) {
            console.warn('Error parseando headers personalizados:', error);
        }
    }

    // Configurar opciones básicas
    const options = {
        method: method,
        headers: headers,
        mode: 'cors',
        credentials: 'same-origin'
    };

    // Agregar body para métodos que lo permiten
    if (['POST', 'PUT', 'PATCH'].includes(method)) {
        const body = requestBodyInput?.value.trim() || '';
        if (body) {
            options.body = body;
            // Asegurar Content-Type si no está definido
            if (!headers['Content-Type'] && !headers['content-type']) {
                headers['Content-Type'] = 'application/json';
            }
        }
    }

    return options;
}

/**
 * Maneja la respuesta HTTP
 */
async function handleResponse(response, duration) {
    currentResponse = response;

    // Mostrar información básica
    displayResponseInfo(response, duration);

    // Mostrar headers
    displayResponseHeaders(response);

    // Procesar y mostrar el body
    await displayResponseBody(response);

    // Mostrar el panel de respuesta
    showResponsePanel();
}

/**
 * Muestra información básica de la respuesta
 */
function displayResponseInfo(response, duration) {
    const statusBadge = window.apiClientElements?.statusBadge;
    const responseTime = window.apiClientElements?.responseTime;

    if (!statusBadge || !responseTime) {
        console.error('Elementos de respuesta no encontrados');
        return;
    }

    // Status badge
    const status = response.status;
    statusBadge.textContent = `${status} ${response.statusText}`;
    statusBadge.className = 'status-badge ' + getStatusClass(status);

    // Tiempo de respuesta
    responseTime.textContent = `${duration}ms`;
}

/**
 * Determina la clase CSS según el código de estado
 */
function getStatusClass(status) {
    if (status >= 200 && status < 300) return 'status-success';
    if (status >= 400 && status < 500) return 'status-error';
    if (status >= 500) return 'status-error';
    return 'status-warning';
}

/**
 * Muestra los headers de respuesta
 */
function displayResponseHeaders(response) {
    const responseHeaders = window.apiClientElements?.responseHeaders;

    if (!responseHeaders) {
        console.error('Elemento responseHeaders no encontrado');
        return;
    }

    const headersObj = {};
    for (const [key, value] of response.headers.entries()) {
        headersObj[key] = value;
    }

    responseHeaders.textContent = JSON.stringify(headersObj, null, 2);
}

/**
 * Procesa y muestra el body de la respuesta
 */
async function displayResponseBody(response) {
    const responseBody = window.apiClientElements?.responseBody;

    if (!responseBody) {
        console.error('Elemento responseBody no encontrado');
        return;
    }

    try {
        const contentType = response.headers.get('content-type') || '';

        if (contentType.includes('application/json')) {
            const jsonData = await response.json();
            const formattedJson = JSON.stringify(jsonData, null, 2);
            responseBody.innerHTML = syntaxHighlightJSON(formattedJson);
        } else {
            const textData = await response.text();
            responseBody.textContent = textData || '(Respuesta vacía)';
        }
    } catch (error) {
        console.error('Error procesando respuesta:', error);
        responseBody.textContent = 'Error procesando la respuesta: ' + error.message;
    }
}

/**
 * Aplica syntax highlighting a JSON
 */
function syntaxHighlightJSON(json) {
    json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');

    return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
        let cls = 'json-number';
        if (/^"/.test(match)) {
            if (/:$/.test(match)) {
                cls = 'json-key';
            } else {
                cls = 'json-string';
            }
        } else if (/true|false/.test(match)) {
            cls = 'json-boolean';
        } else if (/null/.test(match)) {
            cls = 'json-null';
        }
        return '<span class="' + cls + '">' + match + '</span>';
    });
}

/**
 * Muestra el panel de respuesta con animación
 */
function showResponsePanel() {
    const responseContainer = window.apiClientElements?.responseContainer;
    const responseContent = window.apiClientElements?.responseContent;

    if (!responseContainer || !responseContent) {
        console.error('Elementos del panel de respuesta no encontrados');
        return;
    }

    responseContainer.style.display = 'none';
    responseContent.style.display = 'block';
    responseContent.classList.add('fade-in');
}

/**
 * Maneja errores de red o de petición
 */
function handleError(error) {
    console.error('Error en la petición:', error);

    const responseContainer = window.apiClientElements?.responseContainer;
    const responseContent = window.apiClientElements?.responseContent;
    const statusBadge = window.apiClientElements?.statusBadge;
    const responseTime = window.apiClientElements?.responseTime;
    const responseHeaders = window.apiClientElements?.responseHeaders;
    const responseBody = window.apiClientElements?.responseBody;

    if (!responseContainer || !responseContent || !statusBadge ||
        !responseTime || !responseHeaders || !responseBody) {
        console.error('Elementos para mostrar error no encontrados');
        return;
    }

    // Mostrar error en el panel de respuesta
    responseContainer.style.display = 'none';
    responseContent.style.display = 'block';

    statusBadge.textContent = 'Error de Red';
    statusBadge.className = 'status-badge status-error';

    responseTime.textContent = 'N/A';
    responseHeaders.textContent = 'No disponible';
    responseBody.innerHTML = `<span style="color: #e74c3c;">Error: ${error.message}</span>`;

    responseContent.classList.add('fade-in');
}

/**
 * Controla el estado de carga del botón
 */
function setLoadingState(isLoading) {
    const executeButton = window.apiClientElements?.executeButton;
    const buttonText = window.apiClientElements?.buttonText;
    const loadingSpinner = window.apiClientElements?.loadingSpinner;

    if (!executeButton) return;

    if (isLoading) {
        executeButton.disabled = true;
        if (buttonText) buttonText.style.display = 'none';
        if (loadingSpinner) loadingSpinner.style.display = 'block';
    } else {
        executeButton.disabled = false;
        if (buttonText) buttonText.style.display = 'block';
        if (loadingSpinner) loadingSpinner.style.display = 'none';
    }
}

/**
 * Copia la respuesta JSON al portapapeles
 */
async function copyResponseToClipboard() {
    const responseBody = window.apiClientElements?.responseBody;
    const copyButton = window.apiClientElements?.copyButton;

    if (!responseBody || !copyButton) return;

    try {
        const text = responseBody.textContent || responseBody.innerText;
        await navigator.clipboard.writeText(text);

        // Feedback visual
        const originalText = copyButton.textContent;
        copyButton.textContent = 'Copiado!';
        copyButton.style.background = '#27ae60';

        setTimeout(() => {
            copyButton.textContent = originalText;
            copyButton.style.background = 'transparent';
        }, 2000);

    } catch (error) {
        console.error('Error copiando al portapapeles:', error);
        alert('Error al copiar al portapapeles');
    }
}

/**
 * Inicialización de la aplicación
 */
function initializeApp() {
    const apiUrlInput = window.apiClientElements?.apiUrlInput;
    const customHeadersInput = window.apiClientElements?.customHeadersInput;

    if (!apiUrlInput) {
        console.error('apiUrlInput no encontrado durante la inicialización');
        return;
    }

    // Configurar valores por defecto
    apiUrlInput.value = window.location.origin + '/api/';

    if (customHeadersInput) {
        customHeadersInput.value = JSON.stringify({
            "Content-Type": "application/json",
            "Accept": "application/json"
        }, null, 2);
    }

    // Configurar auto-generación de nombres de consulta
    setupAutoQueryNaming();

    console.log('API Client inicializado correctamente');
}

/**
 * Configurar auto-generación de nombres de consulta
 */
function setupAutoQueryNaming() {
    const queryNameInput = document.getElementById('queryName');
    const urlInput = document.getElementById('apiUrl');
    const methodSelect = document.getElementById('httpMethod');

    if (!queryNameInput || !urlInput || !methodSelect) {
        console.warn('Elementos para auto-generación de nombres no encontrados:', {
            queryNameInput: !!queryNameInput,
            urlInput: !!urlInput,
            methodSelect: !!methodSelect
        });
        return;
    }

    console.log('✅ Auto-generación de nombres configurada correctamente');

    // Generar nombre automático cuando cambien URL o método
    function generateAutoName() {
        // Solo generar si el campo está vacío o tiene un nombre auto-generado previo
        const currentName = queryNameInput.value.trim();
        const isAutoGenerated = currentName === '' || currentName.match(/^(GET|POST|PUT|DELETE|PATCH)\s+/);

        if (isAutoGenerated && urlInput.value.trim()) {
            try {
                const url = new URL(urlInput.value.trim());
                const method = methodSelect.value;
                const path = url.pathname;

                // Crear nombre descriptivo
                let name = `${method} ${path}`;

                // Agregar parámetros de consulta si existen
                if (url.search) {
                    const params = new URLSearchParams(url.search);
                    const firstParam = Array.from(params.keys())[0];
                    if (firstParam) {
                        name += ` (${firstParam})`;
                    }
                }

                queryNameInput.value = name;

                // Disparar evento para que el historial se actualice
                queryNameInput.dispatchEvent(new Event('input', { bubbles: true }));
            } catch (error) {
                // Si la URL no es válida, usar un nombre simple
                queryNameInput.value = `${methodSelect.value} Request`;
            }
        }
    }

    // Escuchar cambios
    urlInput.addEventListener('blur', generateAutoName);
    methodSelect.addEventListener('change', generateAutoName);
}

// Inicialización cuando el DOM está cargado
document.addEventListener('DOMContentLoaded', function() {
    // Esperar un poco para asegurar que initializeApiClient se ejecute primero
    setTimeout(() => {
        if (window.apiClientElements) {
            initializeApp();
        } else {
            console.warn('apiClientElements no está disponible, reintentando...');
            setTimeout(() => {
                if (window.apiClientElements) {
                    initializeApp();
                } else {
                    console.error('No se pudo inicializar la aplicación: elementos no encontrados');
                }
            }, 500);
        }
    }, 100);
});
