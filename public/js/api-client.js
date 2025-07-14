// Variables globales
let currentResponse = null;

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

// Event listeners
apiForm.addEventListener('submit', handleFormSubmit);
copyButton.addEventListener('click', copyResponseToClipboard);

// Validación en tiempo real
apiUrlInput.addEventListener('blur', validateUrl);
customHeadersInput.addEventListener('blur', () => validateJSON(customHeadersInput, 'headersError'));
requestBodyInput.addEventListener('blur', () => validateJSON(requestBodyInput, 'bodyError'));

/**
 * Maneja el envío del formulario
 */
async function handleFormSubmit(event) {
    event.preventDefault();

    // Validar formulario antes de enviar
    if (!validateForm()) {
        return;
    }

    // Guardar en historial antes de enviar
    if (window.apiHistoryManager) {
        window.apiHistoryManager.saveCurrentQuery();
    }

    // Mostrar estado de carga
    setLoadingState(true);

    try {
        const startTime = performance.now();

        // Preparar la petición
        const requestOptions = await prepareRequest();

        // Realizar la petición HTTP
        const response = await fetch(apiUrlInput.value.trim(), requestOptions);

        const endTime = performance.now();
        const duration = Math.round(endTime - startTime);

        // Procesar la respuesta
        await handleResponse(response, duration);

    } catch (error) {
        console.error('Error en la petición:', error);
        handleError(error);
    } finally {
        setLoadingState(false);
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

    // Validar headers JSON
    if (!validateJSON(customHeadersInput, 'headersError')) {
        isValid = false;
    }

    // Validar body JSON
    if (!validateJSON(requestBodyInput, 'bodyError')) {
        isValid = false;
    }

    return isValid;
}

/**
 * Valida la URL ingresada
 */
function validateUrl() {
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
    const method = httpMethodSelect.value;
    const headers = {};

    // Parsear headers personalizados
    const customHeaders = customHeadersInput.value.trim();
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
        const body = requestBodyInput.value.trim();
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
    responseContainer.style.display = 'none';
    responseContent.style.display = 'block';
    responseContent.classList.add('fade-in');
}

/**
 * Maneja errores de red o de petición
 */
function handleError(error) {
    console.error('Error en la petición:', error);

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
    if (isLoading) {
        executeButton.disabled = true;
        buttonText.style.display = 'none';
        loadingSpinner.style.display = 'block';
    } else {
        executeButton.disabled = false;
        buttonText.style.display = 'block';
        loadingSpinner.style.display = 'none';
    }
}

/**
 * Copia la respuesta JSON al portapapeles
 */
async function copyResponseToClipboard() {
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
    // Configurar valores por defecto
    apiUrlInput.value = window.location.origin + '/api/';
    customHeadersInput.value = JSON.stringify({
        "Content-Type": "application/json",
        "Accept": "application/json"
    }, null, 2);

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

    if (!queryNameInput || !urlInput || !methodSelect) return;

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
document.addEventListener('DOMContentLoaded', initializeApp);
