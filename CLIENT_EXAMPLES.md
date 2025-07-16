# Ejemplo de Cliente JavaScript para la API

## Función Auxiliar para Manejar Respuestas

```javascript
/**
 * Maneja las respuestas de la API con la estructura estandarizada
 * @param {Response} response - Respuesta fetch de la API
 * @returns {Promise<Object>} - Datos de la respuesta o error
 */
async function handleApiResponse(response) {
    const data = await response.json();
    
    if (!data.success) {
        // Manejar errores
        const error = new Error(data.message);
        error.status = response.status;
        error.errors = data.errors;
        throw error;
    }
    
    // Procesar archivos adjuntos si existen
    if (data.data) {
        processAttachments(data.data);
    }
    
    return data;
}

/**
 * Procesa archivos adjuntos en los datos de respuesta
 * @param {Object} dataObj - Objeto de datos que puede contener attachments
 */
function processAttachments(dataObj) {
    // Para elemento único
    if (dataObj.attachments) {
        dataObj.attachments = dataObj.attachments.map(processAttachment);
    }
    
    // Para usuario en respuesta de autenticación
    if (dataObj.user && dataObj.user.attachments) {
        dataObj.user.attachments = dataObj.user.attachments.map(processAttachment);
    }
    
    // Para listas
    if (dataObj.items && Array.isArray(dataObj.items)) {
        dataObj.items.forEach(item => {
            if (item.attachments) {
                item.attachments = item.attachments.map(processAttachment);
            }
        });
    }
}

/**
 * Procesa un archivo adjunto individual
 * @param {Object} attachment - Objeto attachment
 * @returns {Object} - Attachment procesado con URLs completas
 */
function processAttachment(attachment) {
    const API_BASE_URL = window.location.origin;
    
    // Agregar URL completa si es relativa
    if (attachment.url && !attachment.url.startsWith('http')) {
        attachment.url = `${API_BASE_URL}${attachment.url}`;
    }
    
    // Procesar URLs de thumbnail y preview
    if (attachment.metadata) {
        if (attachment.metadata.thumbnail_url && !attachment.metadata.thumbnail_url.startsWith('http')) {
            attachment.metadata.thumbnail_url = `${API_BASE_URL}${attachment.metadata.thumbnail_url}`;
        }
        if (attachment.metadata.preview_url && !attachment.metadata.preview_url.startsWith('http')) {
            attachment.metadata.preview_url = `${API_BASE_URL}${attachment.metadata.preview_url}`;
        }
    }
    
    return attachment;
}

/**
 * Cliente para realizar peticiones a la API
 */
class ApiClient {
    constructor(baseUrl) {
        this.baseUrl = baseUrl;
        this.token = localStorage.getItem('auth_token');
    }

    // Configurar headers con autenticación
    getHeaders() {
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        };

        if (this.token) {
            headers['Authorization'] = `Bearer ${this.token}`;
        }

        return headers;
    }

    // Verificar estado de la API
    async ping() {
        try {
            const response = await fetch(`${this.baseUrl}/api/ping`, {
                method: 'GET',
                headers: this.getHeaders()
            });

            return await handleApiResponse(response);
        } catch (error) {
            console.error('Error en ping:', error.message);
            throw error;
        }
    }

    // Registrar usuario
    async register(userData) {
        try {
            const response = await fetch(`${this.baseUrl}/api/register`, {
                method: 'POST',
                headers: this.getHeaders(),
                body: JSON.stringify(userData)
            });

            const result = await handleApiResponse(response);
            
            // Guardar token automáticamente
            if (result.data.token) {
                this.token = result.data.token;
                localStorage.setItem('auth_token', this.token);
            }

            return result;
        } catch (error) {
            console.error('Error en registro:', error.message);
            if (error.errors) {
                console.error('Errores de validación:', error.errors);
            }
            throw error;
        }
    }

    // Iniciar sesión
    async login(credentials) {
        try {
            const response = await fetch(`${this.baseUrl}/api/login`, {
                method: 'POST',
                headers: this.getHeaders(),
                body: JSON.stringify(credentials)
            });

            const result = await handleApiResponse(response);
            
            // Guardar token automáticamente
            if (result.data.token) {
                this.token = result.data.token;
                localStorage.setItem('auth_token', this.token);
            }

            return result;
        } catch (error) {
            console.error('Error en login:', error.message);
            throw error;
        }
    }

    // Obtener datos del usuario
    async getUser() {
        try {
            const response = await fetch(`${this.baseUrl}/api/user`, {
                method: 'GET',
                headers: this.getHeaders()
            });

            return await handleApiResponse(response);
        } catch (error) {
            console.error('Error obteniendo usuario:', error.message);
            throw error;
        }
    }

    // Cerrar sesión
    async logout() {
        try {
            const response = await fetch(`${this.baseUrl}/api/logout`, {
                method: 'POST',
                headers: this.getHeaders()
            });

            const result = await handleApiResponse(response);
            
            // Limpiar token
            this.token = null;
            localStorage.removeItem('auth_token');

            return result;
        } catch (error) {
            console.error('Error en logout:', error.message);
            throw error;
        }
    }

    // Reenviar email de verificación
    async resendVerificationEmail() {
        try {
            const response = await fetch(`${this.baseUrl}/api/email/verification-notification`, {
                method: 'POST',
                headers: this.getHeaders()
            });

            return await handleApiResponse(response);
        } catch (error) {
            console.error('Error reenviando email:', error.message);
            throw error;
        }
    }

    // Actualizar perfil de usuario
    async updateProfile(profileData) {
        try {
            const response = await fetch(`${this.baseUrl}/api/user/profile`, {
                method: 'PUT',
                headers: this.getHeaders(),
                body: JSON.stringify(profileData)
            });

            return await handleApiResponse(response);
        } catch (error) {
            console.error('Error actualizando perfil:', error.message);
            throw error;
        }
    }

    // Subir avatar de usuario
    async uploadAvatar(file) {
        try {
            const formData = new FormData();
            formData.append('avatar', file);

            // Para FormData no incluir Content-Type en headers
            const headers = {
                'Accept': 'application/json'
            };
            
            if (this.token) {
                headers['Authorization'] = `Bearer ${this.token}`;
            }

            const response = await fetch(`${this.baseUrl}/api/user/avatar`, {
                method: 'POST',
                headers: headers,
                body: formData
            });

            return await handleApiResponse(response);
        } catch (error) {
            console.error('Error subiendo avatar:', error.message);
            throw error;
        }
    }

    // Eliminar avatar de usuario
    async deleteAvatar() {
        try {
            const response = await fetch(`${this.baseUrl}/api/user/avatar`, {
                method: 'DELETE',
                headers: this.getHeaders()
            });

            return await handleApiResponse(response);
        } catch (error) {
            console.error('Error eliminando avatar:', error.message);
            throw error;
        }
    }

    // Obtener lista de usuarios (solo admin)
    async getUsers(page = 1, perPage = 15, search = '') {
        try {
            const params = new URLSearchParams({
                page: page.toString(),
                per_page: perPage.toString()
            });
            
            if (search) {
                params.append('search', search);
            }

            const response = await fetch(`${this.baseUrl}/api/users?${params}`, {
                method: 'GET',
                headers: this.getHeaders()
            });

            return await handleApiResponse(response);
        } catch (error) {
            console.error('Error obteniendo usuarios:', error.message);
            throw error;
        }
    }

    // Obtener archivo por ID
    async getFile(fileId, options = {}) {
        try {
            const params = new URLSearchParams();
            
            if (options.download) {
                params.append('download', 'true');
            }
            
            if (options.thumbnail) {
                params.append('thumbnail', 'true');
            }

            const url = `${this.baseUrl}/api/files/${fileId}${params.toString() ? '?' + params.toString() : ''}`;

            const response = await fetch(url, {
                method: 'GET',
                headers: this.getHeaders()
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            return response; // Retornar la respuesta completa para manejar el blob
        } catch (error) {
            console.error('Error obteniendo archivo:', error.message);
            throw error;
        }
    }
}
}

// Ejemplo de uso
const api = new ApiClient('http://localhost:8000');

// Verificar estado de la API
api.ping()
.then(result => {
    console.log('API funcionando:', result.message);
    console.log('Estado:', result.data.status);
})
.catch(error => {
    console.error('API no disponible:', error.message);
});

// Registro
api.register({
    name: 'Juan Pérez',
    email: 'juan@example.com',
    password: 'secret123',
    password_confirmation: 'secret123'
})
.then(result => {
    console.log('Registro exitoso:', result.message);
    console.log('Usuario:', result.data.user);
    console.log('Token:', result.data.token);
})
.catch(error => {
    console.error('Error en registro:', error.message);
    if (error.errors) {
        // Mostrar errores de validación específicos
        Object.keys(error.errors).forEach(field => {
            console.error(`${field}: ${error.errors[field].join(', ')}`);
        });
    }
});

// Login
api.login({
    email: 'juan@example.com',
    password: 'secret123'
})
.then(result => {
    console.log('Login exitoso:', result.message);
    console.log('Usuario logueado:', result.data.user.name);
})
.catch(error => {
    console.error('Credenciales incorrectas:', error.message);
});

// Actualizar perfil
api.updateProfile({
    name: 'Juan Carlos Pérez',
    email: 'juan.carlos@example.com'
})
.then(result => {
    console.log('Perfil actualizado:', result.message);
    console.log('Usuario actualizado:', result.data);
})
.catch(error => {
    console.error('Error actualizando perfil:', error.message);
});

// Subir avatar (desde un input file)
const avatarInput = document.getElementById('avatar-input');
avatarInput.addEventListener('change', async (event) => {
    const file = event.target.files[0];
    if (file) {
        try {
            const result = await api.uploadAvatar(file);
            console.log('Avatar subido:', result.message);
            console.log('Usuario con nuevo avatar:', result.data.user);
        } catch (error) {
            console.error('Error subiendo avatar:', error.message);
        }
    }
});

// Obtener lista de usuarios (paginada)
api.getUsers(1, 10, 'juan')
.then(result => {
    console.log('Usuarios encontrados:', result.data.count);
    console.log('Total usuarios:', result.data.pagination.total);
    result.data.items.forEach(user => {
        console.log(`Usuario: ${user.name} (${user.email})`);
        if (user.attachments && user.attachments.length > 0) {
            console.log('Avatar:', user.attachments[0].metadata.thumbnail_url);
        }
    });
})
.catch(error => {
    console.error('Error obteniendo usuarios:', error.message);
});

// Descargar un archivo
api.getFile('avatar_123', { download: true })
.then(response => {
    return response.blob();
})
.then(blob => {
    // Crear URL para descargar
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'avatar.jpg';
    document.body.appendChild(a);
    a.click();
    window.URL.revokeObjectURL(url);
    document.body.removeChild(a);
})
.catch(error => {
    console.error('Error descargando archivo:', error.message);
});
```

## Funciones Auxiliares para Manejo de Archivos

```javascript
/**
 * Renderiza un preview de archivos adjuntos
 * @param {Array} attachments - Array de attachments
 * @param {string} containerId - ID del contenedor donde renderizar
 */
function renderAttachmentPreviews(attachments, containerId) {
    const container = document.getElementById(containerId);
    if (!container || !attachments || attachments.length === 0) return;

    container.innerHTML = attachments.map(attachment => `
        <div class="attachment-item" data-mime="${attachment.mime_type}">
            <div class="attachment-info">
                <strong>${attachment.name}</strong>
                <span class="mime-type">${attachment.mime_type}</span>
                <span class="file-size">${formatFileSize(attachment.size)}</span>
            </div>
            ${renderAttachmentContent(attachment)}
        </div>
    `).join('');
}

/**
 * Renderiza el contenido de un attachment según su tipo
 * @param {Object} attachment - Objeto attachment
 * @returns {string} - HTML del contenido
 */
function renderAttachmentContent(attachment) {
    const { mime_type, metadata, url } = attachment;
    
    if (mime_type.startsWith('image/')) {
        const imageUrl = metadata.thumbnail_url || url;
        return `
            <div class="image-preview">
                <img src="${imageUrl}" 
                     alt="${metadata.alt_text || attachment.name}"
                     style="max-width: 200px; max-height: 200px; object-fit: cover;">
                <div class="image-actions">
                    <button onclick="viewFullImage('${url}')">Ver completa</button>
                    <button onclick="downloadFile('${attachment.id}')">Descargar</button>
                </div>
            </div>
        `;
    } else if (mime_type === 'application/pdf') {
        return `
            <div class="document-preview">
                <div class="document-icon">📄</div>
                <div class="document-actions">
                    ${metadata.preview_url ? `<button onclick="viewDocument('${metadata.preview_url}')">Vista previa</button>` : ''}
                    <button onclick="downloadFile('${attachment.id}')">Descargar</button>
                </div>
            </div>
        `;
    } else {
        return `
            <div class="generic-file">
                <div class="file-icon">📎</div>
                <button onclick="downloadFile('${attachment.id}')">Descargar</button>
            </div>
        `;
    }
}

/**
 * Formatea el tamaño de un archivo en formato legible
 * @param {number} bytes - Tamaño en bytes
 * @returns {string} - Tamaño formateado
 */
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

/**
 * Muestra una imagen en tamaño completo
 * @param {string} imageUrl - URL de la imagen
 */
function viewFullImage(imageUrl) {
    const modal = document.createElement('div');
    modal.className = 'image-modal';
    modal.innerHTML = `
        <div class="modal-content">
            <span class="close" onclick="this.closest('.image-modal').remove()">&times;</span>
            <img src="${imageUrl}" style="max-width: 90vw; max-height: 90vh;">
        </div>
    `;
    document.body.appendChild(modal);
}

/**
 * Descarga un archivo por su ID
 * @param {string} fileId - ID del archivo
 */
async function downloadFile(fileId) {
    try {
        const response = await api.getFile(fileId, { download: true });
        const blob = await response.blob();
        
        // Obtener nombre del archivo del header Content-Disposition
        const contentDisposition = response.headers.get('Content-Disposition');
        const filename = contentDisposition ? 
            contentDisposition.split('filename=')[1].replace(/"/g, '') : 
            `file_${fileId}`;

        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
    } catch (error) {
        console.error('Error descargando archivo:', error.message);
    }
}
```

## Validación de Archivos en el Cliente

```javascript
/**
 * Valida un archivo antes de subirlo
 * @param {File} file - Archivo a validar
 * @param {Object} options - Opciones de validación
 * @returns {Object} - Resultado de validación
 */
function validateFile(file, options = {}) {
    const {
        maxSize = 2 * 1024 * 1024, // 2MB por defecto
        allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'],
        maxWidth = null,
        maxHeight = null
    } = options;

    const errors = [];

    // Validar tamaño
    if (file.size > maxSize) {
        errors.push(`El archivo es demasiado grande. Máximo permitido: ${formatFileSize(maxSize)}`);
    }

    // Validar tipo MIME
    if (!allowedTypes.includes(file.type)) {
        errors.push(`Tipo de archivo no permitido. Tipos permitidos: ${allowedTypes.join(', ')}`);
    }

    // Para imágenes, validar dimensiones
    if (file.type.startsWith('image/') && (maxWidth || maxHeight)) {
        return new Promise((resolve) => {
            const img = new Image();
            img.onload = function() {
                if (maxWidth && this.width > maxWidth) {
                    errors.push(`La imagen es demasiado ancha. Máximo: ${maxWidth}px`);
                }
                if (maxHeight && this.height > maxHeight) {
                    errors.push(`La imagen es demasiado alta. Máximo: ${maxHeight}px`);
                }
                resolve({ isValid: errors.length === 0, errors });
            };
            img.src = URL.createObjectURL(file);
        });
    }

    return Promise.resolve({ isValid: errors.length === 0, errors });
}
```

## Manejo de Errores con React/Vue

### React Hook
```javascript
import { useState, useCallback } from 'react';

export function useApi() {
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(null);

    const callApi = useCallback(async (apiFunction) => {
        setLoading(true);
        setError(null);

        try {
            const result = await apiFunction();
            return result;
        } catch (error) {
            setError({
                message: error.message,
                errors: error.errors || null,
                status: error.status
            });
            throw error;
        } finally {
            setLoading(false);
        }
    }, []);

    return { callApi, loading, error };
}

// Uso en componente
function LoginForm() {
    const { callApi, loading, error } = useApi();
    const api = new ApiClient('http://localhost:8000');

    const handleLogin = async (credentials) => {
        try {
            const result = await callApi(() => api.login(credentials));
            console.log('Login exitoso:', result.data.user);
        } catch (error) {
            // El error ya está en el estado
        }
    };

    return (
        <form onSubmit={handleSubmit}>
            {error && (
                <div className="error">
                    <p>{error.message}</p>
                    {error.errors && Object.entries(error.errors).map(([field, messages]) => (
                        <ul key={field}>
                            {messages.map((message, index) => (
                                <li key={index}>{field}: {message}</li>
                            ))}
                        </ul>
                    ))}
                </div>
            )}
            {/* Campos del formulario */}
        </form>
    );
}
```

### Vue Composable
```javascript
import { ref } from 'vue';

export function useApi() {
    const loading = ref(false);
    const error = ref(null);

    const callApi = async (apiFunction) => {
        loading.value = true;
        error.value = null;

        try {
            const result = await apiFunction();
            return result;
        } catch (err) {
            error.value = {
                message: err.message,
                errors: err.errors || null,
                status: err.status
            };
            throw err;
        } finally {
            loading.value = false;
        }
    };

    return { callApi, loading, error };
}
```
