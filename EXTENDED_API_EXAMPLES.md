# Ejemplos Prácticos de Uso de la API Extendida

Este documento contiene ejemplos prácticos de cómo usar la nueva estructura extendida de la API con soporte para listas, paginación y archivos adjuntos.

## 1. Registro de Usuario y Subida de Avatar

```javascript
const api = new ApiClient('http://localhost:8000');

// 1. Registrar usuario
try {
    const registrationResult = await api.register({
        name: 'María García',
        email: 'maria@example.com',
        password: 'secreto123',
        password_confirmation: 'secreto123'
    });
    
    console.log('Usuario registrado:', registrationResult.data.user);
    console.log('Token obtenido:', registrationResult.data.token);
    
    // 2. Subir avatar después del registro
    const avatarFile = document.getElementById('avatar-input').files[0];
    if (avatarFile) {
        // Validar archivo antes de subir
        const validation = await validateFile(avatarFile, {
            maxSize: 2 * 1024 * 1024, // 2MB
            allowedTypes: ['image/jpeg', 'image/png', 'image/webp']
        });
        
        if (validation.isValid) {
            const avatarResult = await api.uploadAvatar(avatarFile);
            console.log('Avatar subido:', avatarResult.data.user.attachments[0]);
        } else {
            console.error('Archivo no válido:', validation.errors);
        }
    }
    
} catch (error) {
    console.error('Error en el proceso:', error.message);
    if (error.errors) {
        Object.keys(error.errors).forEach(field => {
            console.error(`${field}: ${error.errors[field].join(', ')}`);
        });
    }
}
```

## 2. Obtener Lista Paginada de Usuarios con Filtrado

```javascript
// Función para cargar y mostrar usuarios con paginación
async function loadUsers(page = 1, search = '') {
    try {
        const result = await api.getUsers(page, 15, search);
        
        console.log(`Página ${page} de ${result.data.pagination.total_pages}`);
        console.log(`Mostrando ${result.data.count} de ${result.data.pagination.total} usuarios`);
        
        // Renderizar usuarios
        const usersContainer = document.getElementById('users-container');
        usersContainer.innerHTML = result.data.items.map(user => `
            <div class="user-card">
                <div class="user-info">
                    <h3>${user.name}</h3>
                    <p>${user.email}</p>
                    <span class="verification-status ${user.email_verified_at ? 'verified' : 'unverified'}">
                        ${user.email_verified_at ? 'Verificado' : 'Sin verificar'}
                    </span>
                </div>
                ${user.attachments && user.attachments.length > 0 ? `
                    <div class="user-avatar">
                        <img src="${user.attachments[0].metadata.thumbnail_url}" 
                             alt="${user.attachments[0].metadata.alt_text}"
                             style="width: 60px; height: 60px; border-radius: 50%; object-fit: cover;">
                    </div>
                ` : `
                    <div class="user-avatar-placeholder">
                        <span>${user.name.charAt(0).toUpperCase()}</span>
                    </div>
                `}
            </div>
        `).join('');
        
        // Renderizar controles de paginación
        renderPaginationControls(result.data.pagination);
        
    } catch (error) {
        console.error('Error cargando usuarios:', error.message);
    }
}

// Función para renderizar controles de paginación
function renderPaginationControls(pagination) {
    const paginationContainer = document.getElementById('pagination-container');
    
    const pages = [];
    const maxVisiblePages = 5;
    const startPage = Math.max(1, pagination.current_page - Math.floor(maxVisiblePages / 2));
    const endPage = Math.min(pagination.total_pages, startPage + maxVisiblePages - 1);
    
    for (let i = startPage; i <= endPage; i++) {
        pages.push(i);
    }
    
    paginationContainer.innerHTML = `
        <div class="pagination">
            <button ${!pagination.has_previous ? 'disabled' : ''} 
                    onclick="loadUsers(${pagination.previous_page || 1})">
                « Anterior
            </button>
            
            ${pages.map(page => `
                <button class="${page === pagination.current_page ? 'active' : ''}"
                        onclick="loadUsers(${page})">
                    ${page}
                </button>
            `).join('')}
            
            <button ${!pagination.has_next ? 'disabled' : ''} 
                    onclick="loadUsers(${pagination.next_page || pagination.total_pages})">
                Siguiente »
            </button>
        </div>
        
        <div class="pagination-info">
            Página ${pagination.current_page} de ${pagination.total_pages} 
            (${pagination.total} usuarios en total)
        </div>
    `;
}

// Búsqueda con debounce
let searchTimeout;
function setupUserSearch() {
    const searchInput = document.getElementById('user-search');
    searchInput.addEventListener('input', (e) => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            loadUsers(1, e.target.value);
        }, 500);
    });
}

// Inicializar
loadUsers();
setupUserSearch();
```

## 3. Manejo de Respuestas con Diferentes Tipos de Archivos

```javascript
// Ejemplo de respuesta con múltiples tipos de archivos
const exampleResponse = {
    "success": true,
    "data": {
        "id": 1,
        "title": "Documento completo del proyecto",
        "description": "Contiene toda la documentación técnica",
        "attachments": [
            {
                "id": "doc_001",
                "name": "especificaciones.pdf",
                "mime_type": "application/pdf",
                "size": 2048576,
                "url": "/api/files/doc_001",
                "protected": true,
                "metadata": {
                    "pages": 25,
                    "display_type": "document",
                    "preview_url": "/api/files/doc_001/preview",
                    "created_at": "2025-07-16T10:00:00Z"
                }
            },
            {
                "id": "img_002",
                "name": "diagrama.png",
                "mime_type": "image/png",
                "size": 512000,
                "url": "/api/files/img_002",
                "protected": false,
                "metadata": {
                    "width": 1200,
                    "height": 800,
                    "display_type": "inline_image",
                    "thumbnail_url": "/api/files/img_002/thumbnail",
                    "alt_text": "Diagrama de arquitectura"
                }
            }
        ],
        "type": "single"
    },
    "message": "Documento obtenido exitosamente"
};

// Función para procesar y mostrar archivos
function displayProjectFiles(data) {
    const container = document.getElementById('project-files');
    
    if (!data.attachments || data.attachments.length === 0) {
        container.innerHTML = '<p>No hay archivos adjuntos</p>';
        return;
    }
    
    container.innerHTML = `
        <h3>Archivos del proyecto (${data.attachments.length})</h3>
        <div class="files-grid">
            ${data.attachments.map(file => `
                <div class="file-card" data-type="${file.mime_type}">
                    ${renderFilePreview(file)}
                    <div class="file-actions">
                        <button onclick="downloadFile('${file.id}', '${file.name}')" 
                                class="btn-download">
                            📥 Descargar (${formatFileSize(file.size)})
                        </button>
                        ${file.metadata.preview_url ? `
                            <button onclick="previewFile('${file.metadata.preview_url}')"
                                    class="btn-preview">
                                👁️ Vista previa
                            </button>
                        ` : ''}
                    </div>
                </div>
            `).join('')}
        </div>
    `;
}

function renderFilePreview(file) {
    const { mime_type, metadata, name } = file;
    
    if (mime_type.startsWith('image/')) {
        return `
            <div class="image-preview-card">
                <img src="${metadata.thumbnail_url}" alt="${metadata.alt_text || name}">
                <div class="image-info">
                    <strong>${name}</strong>
                    <span>${metadata.width} × ${metadata.height}px</span>
                </div>
            </div>
        `;
    } else if (mime_type === 'application/pdf') {
        return `
            <div class="document-preview-card">
                <div class="doc-icon">📄</div>
                <div class="doc-info">
                    <strong>${name}</strong>
                    <span>${metadata.pages} páginas</span>
                </div>
            </div>
        `;
    } else {
        return `
            <div class="generic-file-card">
                <div class="file-icon">📎</div>
                <div class="file-info">
                    <strong>${name}</strong>
                    <span>${mime_type}</span>
                </div>
            </div>
        `;
    }
}
```

## 4. Validación Avanzada de Archivos

```javascript
// Configuración de validación por tipo de contenido
const FILE_VALIDATION_RULES = {
    avatar: {
        maxSize: 2 * 1024 * 1024, // 2MB
        allowedTypes: ['image/jpeg', 'image/png', 'image/webp'],
        maxWidth: 1000,
        maxHeight: 1000,
        required: false
    },
    document: {
        maxSize: 10 * 1024 * 1024, // 10MB
        allowedTypes: ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
        required: true
    },
    image: {
        maxSize: 5 * 1024 * 1024, // 5MB
        allowedTypes: ['image/jpeg', 'image/png', 'image/webp', 'image/gif'],
        maxWidth: 2000,
        maxHeight: 2000,
        required: false
    }
};

// Función de validación mejorada
async function validateFileAdvanced(file, type = 'general') {
    const rules = FILE_VALIDATION_RULES[type] || FILE_VALIDATION_RULES.image;
    const errors = [];
    
    // Validar tamaño
    if (file.size > rules.maxSize) {
        errors.push(`El archivo es demasiado grande. Máximo: ${formatFileSize(rules.maxSize)}`);
    }
    
    // Validar tipo MIME
    if (!rules.allowedTypes.includes(file.type)) {
        errors.push(`Tipo de archivo no permitido. Permitidos: ${rules.allowedTypes.join(', ')}`);
    }
    
    // Validar dimensiones para imágenes
    if (file.type.startsWith('image/') && (rules.maxWidth || rules.maxHeight)) {
        return new Promise((resolve) => {
            const img = new Image();
            img.onload = function() {
                if (rules.maxWidth && this.width > rules.maxWidth) {
                    errors.push(`Imagen demasiado ancha. Máximo: ${rules.maxWidth}px`);
                }
                if (rules.maxHeight && this.height > rules.maxHeight) {
                    errors.push(`Imagen demasiado alta. Máximo: ${rules.maxHeight}px`);
                }
                resolve({
                    isValid: errors.length === 0,
                    errors,
                    metadata: {
                        width: this.width,
                        height: this.height,
                        aspectRatio: this.width / this.height
                    }
                });
            };
            img.onerror = () => {
                errors.push('No se pudo cargar la imagen para validación');
                resolve({ isValid: false, errors });
            };
            img.src = URL.createObjectURL(file);
        });
    }
    
    return { isValid: errors.length === 0, errors };
}

// Ejemplo de uso con preview en tiempo real
async function setupFileUpload(inputId, type) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(`${inputId}-preview`);
    const errors = document.getElementById(`${inputId}-errors`);
    
    input.addEventListener('change', async (e) => {
        const file = e.target.files[0];
        if (!file) return;
        
        // Validar archivo
        const validation = await validateFileAdvanced(file, type);
        
        // Mostrar errores
        if (!validation.isValid) {
            errors.innerHTML = validation.errors.map(error => 
                `<div class="error">${error}</div>`
            ).join('');
            preview.innerHTML = '';
            return;
        }
        
        errors.innerHTML = '';
        
        // Mostrar preview
        if (file.type.startsWith('image/')) {
            const imageUrl = URL.createObjectURL(file);
            preview.innerHTML = `
                <div class="file-preview-success">
                    <img src="${imageUrl}" style="max-width: 200px; max-height: 200px;">
                    <div class="file-info">
                        <strong>${file.name}</strong>
                        <div>Tamaño: ${formatFileSize(file.size)}</div>
                        ${validation.metadata ? `
                            <div>Dimensiones: ${validation.metadata.width} × ${validation.metadata.height}px</div>
                        ` : ''}
                    </div>
                </div>
            `;
        } else {
            preview.innerHTML = `
                <div class="file-preview-success">
                    <div class="file-icon">📄</div>
                    <div class="file-info">
                        <strong>${file.name}</strong>
                        <div>Tamaño: ${formatFileSize(file.size)}</div>
                        <div>Tipo: ${file.type}</div>
                    </div>
                </div>
            `;
        }
    });
}

// Inicializar uploads
setupFileUpload('avatar-input', 'avatar');
setupFileUpload('document-input', 'document');
```

## 5. Interface Completa de Usuario con Archivos

```html
<!DOCTYPE html>
<html>
<head>
    <title>Gestión de Archivos - API Client</title>
    <link rel="stylesheet" href="/css/api-client.css">
</head>
<body>
    <div class="container">
        <!-- Sección de perfil de usuario -->
        <div class="profile-section">
            <h2>Mi Perfil</h2>
            <div class="profile-form">
                <div class="avatar-upload">
                    <input type="file" id="avatar-input" accept="image/*">
                    <div id="avatar-input-preview"></div>
                    <div id="avatar-input-errors"></div>
                </div>
                
                <div class="profile-fields">
                    <input type="text" id="profile-name" placeholder="Nombre">
                    <input type="email" id="profile-email" placeholder="Email">
                    <button onclick="updateProfile()">Actualizar Perfil</button>
                </div>
            </div>
        </div>
        
        <!-- Sección de usuarios (admin) -->
        <div class="users-section">
            <h2>Gestión de Usuarios</h2>
            <div class="users-controls">
                <input type="text" id="user-search" placeholder="Buscar usuarios...">
                <button onclick="loadUsers(1)">Recargar</button>
            </div>
            <div id="users-container"></div>
            <div id="pagination-container"></div>
        </div>
        
        <!-- Sección de archivos del proyecto -->
        <div class="project-files-section">
            <h2>Archivos del Proyecto</h2>
            <div id="project-files"></div>
        </div>
    </div>
    
    <script src="/js/api-client.js"></script>
    <script>
        // Inicializar la aplicación
        document.addEventListener('DOMContentLoaded', () => {
            setupFileUpload('avatar-input', 'avatar');
            loadUsers();
            setupUserSearch();
        });
        
        // Función para actualizar perfil
        async function updateProfile() {
            const name = document.getElementById('profile-name').value;
            const email = document.getElementById('profile-email').value;
            const avatarFile = document.getElementById('avatar-input').files[0];
            
            try {
                // Actualizar datos básicos
                if (name || email) {
                    const profileData = {};
                    if (name) profileData.name = name;
                    if (email) profileData.email = email;
                    
                    await api.updateProfile(profileData);
                }
                
                // Subir avatar si hay archivo
                if (avatarFile) {
                    await api.uploadAvatar(avatarFile);
                }
                
                alert('Perfil actualizado exitosamente');
            } catch (error) {
                console.error('Error actualizando perfil:', error);
                alert('Error actualizando perfil: ' + error.message);
            }
        }
    </script>
</body>
</html>
```

Esta implementación proporciona una base sólida para manejar la nueva estructura extendida de la API con soporte completo para archivos adjuntos, listas paginadas y diferentes tipos de contenido multimedia.
