/**
 * JavaScript para la página de pruebas del API Client
 * Incluye funcionalidad de pretty print con syntax highlighting
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 API Client Test página cargada');

    // Inicializar funcionalidades
    initializeUrlCopy();
    initializeAnimations();
    initializeTooltips();
    initializeInteractivePanel();
    initializeEndpointButtons();

    console.log('✅ Funcionalidades de la página de pruebas inicializadas');
});

/**
 * Inicializa el panel interactivo de pruebas
 */
function initializeInteractivePanel() {
    const methodSelect = document.getElementById('testMethod');
    const bodyGroup = document.getElementById('bodyGroup');
    const executeButton = document.getElementById('executeTest');

    // Mostrar/ocultar body según el método HTTP
    if (methodSelect) {
        methodSelect.addEventListener('change', function() {
            const method = this.value;
            if (method === 'POST' || method === 'PUT' || method === 'PATCH') {
                bodyGroup.style.display = 'block';
            } else {
                bodyGroup.style.display = 'none';
            }
        });
    }

    // Ejecutar prueba
    if (executeButton) {
        executeButton.addEventListener('click', executeApiTest);
    }

    // Copiar respuesta JSON
    const copyButton = document.getElementById('copyResponseButton');
    if (copyButton) {
        copyButton.addEventListener('click', copyResponseToClipboard);
    }
}

/**
 * Inicializa los botones de "Usar Endpoint"
 */
function initializeEndpointButtons() {
    const useEndpointButtons = document.querySelectorAll('.use-endpoint-button');

    useEndpointButtons.forEach(button => {
        button.addEventListener('click', function() {
            const method = this.getAttribute('data-method');
            const url = this.getAttribute('data-url');
            const headers = this.getAttribute('data-headers');
            const body = this.getAttribute('data-body');

            // Rellenar el formulario
            document.getElementById('testMethod').value = method;
            document.getElementById('testUrl').value = url;

            if (headers) {
                document.getElementById('testHeaders').value = headers;
            }

            if (body) {
                document.getElementById('testBody').value = body;
                document.getElementById('bodyGroup').style.display = 'block';
            } else {
                document.getElementById('bodyGroup').style.display = 'none';
            }

            // Scroll al panel
            document.querySelector('.interactive-test-panel').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });

            // Feedback visual
            showCopyFeedback(this, '✅ Endpoint configurado!');
        });
    });
}

/**
 * Ejecuta la prueba de API
 */
async function executeApiTest() {
    const button = document.getElementById('executeTest');
    const responsePanel = document.getElementById('responsePanel');
    const method = document.getElementById('testMethod').value;
    const url = document.getElementById('testUrl').value.trim();
    const headersText = document.getElementById('testHeaders').value.trim();
    const bodyText = document.getElementById('testBody').value.trim();

    if (!url) {
        alert('Por favor ingresa una URL');
        return;
    }

    // Estado de carga
    button.classList.add('loading');
    button.disabled = true;

    const startTime = performance.now();

    try {
        // Preparar headers
        let headers = { 'Content-Type': 'application/json' };
        if (headersText) {
            try {
                const customHeaders = JSON.parse(headersText);
                headers = { ...headers, ...customHeaders };
            } catch (e) {
                throw new Error('Headers JSON inválido');
            }
        }

        // Preparar opciones de fetch
        const fetchOptions = {
            method: method,
            headers: headers
        };

        // Agregar body si es necesario
        if ((method === 'POST' || method === 'PUT' || method === 'PATCH') && bodyText) {
            try {
                JSON.parse(bodyText); // Validar JSON
                fetchOptions.body = bodyText;
            } catch (e) {
                throw new Error('Body JSON inválido');
            }
        }

        // Ejecutar petición
        const response = await fetch(url, fetchOptions);
        const endTime = performance.now();
        const responseTime = Math.round(endTime - startTime);

        // Obtener contenido de respuesta
        const responseText = await response.text();
        let responseData;

        try {
            responseData = JSON.parse(responseText);
        } catch (e) {
            responseData = responseText;
        }

        // Mostrar respuesta
        displayResponse(response, responseData, responseTime);

    } catch (error) {
        const endTime = performance.now();
        const responseTime = Math.round(endTime - startTime);
        displayError(error, responseTime);
    } finally {
        // Quitar estado de carga
        button.classList.remove('loading');
        button.disabled = false;
    }
}

/**
 * Muestra la respuesta de la API con pretty print y syntax highlighting
 */
function displayResponse(response, data, responseTime) {
    const responsePanel = document.getElementById('responsePanel');
    const statusElement = document.getElementById('responseStatus');
    const timeElement = document.getElementById('responseTime');
    const headersElement = document.getElementById('responseHeadersContent');
    const bodyElement = document.getElementById('responseBodyContent');

    // Mostrar panel
    responsePanel.style.display = 'block';

    // Status badge
    statusElement.textContent = `${response.status} ${response.statusText}`;
    statusElement.className = 'status-badge';

    if (response.status >= 200 && response.status < 300) {
        statusElement.classList.add('success');
    } else if (response.status >= 400 && response.status < 500) {
        statusElement.classList.add('warning');
    } else if (response.status >= 500) {
        statusElement.classList.add('error');
    } else {
        statusElement.classList.add('info');
    }

    // Tiempo de respuesta
    timeElement.textContent = `⏱️ ${responseTime}ms`;

    // Headers de respuesta
    const responseHeaders = {};
    for (let [key, value] of response.headers.entries()) {
        responseHeaders[key] = value;
    }
    headersElement.textContent = JSON.stringify(responseHeaders, null, 2);

    // Body de respuesta con syntax highlighting
    if (typeof data === 'object') {
        bodyElement.innerHTML = formatJsonWithSyntaxHighlighting(data);
    } else {
        bodyElement.textContent = data;
    }

    // Scroll al panel de respuesta
    responsePanel.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
}

/**
 * Muestra errores de la petición
 */
function displayError(error, responseTime) {
    const responsePanel = document.getElementById('responsePanel');
    const statusElement = document.getElementById('responseStatus');
    const timeElement = document.getElementById('responseTime');
    const headersElement = document.getElementById('responseHeadersContent');
    const bodyElement = document.getElementById('responseBodyContent');

    // Mostrar panel
    responsePanel.style.display = 'block';

    // Status de error
    statusElement.textContent = 'Error';
    statusElement.className = 'status-badge error';

    // Tiempo
    timeElement.textContent = `⏱️ ${responseTime}ms`;

    // Headers vacíos
    headersElement.textContent = 'No headers available';

    // Error en el body
    const errorData = {
        error: true,
        message: error.message,
        type: error.name || 'NetworkError'
    };

    bodyElement.innerHTML = formatJsonWithSyntaxHighlighting(errorData);

    // Scroll al panel de respuesta
    responsePanel.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
}

/**
 * Formatea JSON con syntax highlighting
 */
function formatJsonWithSyntaxHighlighting(obj) {
    try {
        // Convertir objeto a JSON string con formato
        const jsonString = JSON.stringify(obj, null, 2);

        // Escapar caracteres HTML básicos
        const escapedJson = jsonString
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');

        // Aplicar syntax highlighting con patrones mejorados
        let highlighted = escapedJson
            // Keys (propiedades del objeto)
            .replace(/("(?:[^"\\]|\\.)*")\s*:/g, '<span class="json-key">$1</span>:')
            // String values
            .replace(/:\s*("(?:[^"\\]|\\.)*")/g, ': <span class="json-string">$1</span>')
            // Numbers (enteros y decimales)
            .replace(/:\s*(-?\d+(?:\.\d+)?(?:[eE][+-]?\d+)?)/g, ': <span class="json-number">$1</span>')
            // Booleans
            .replace(/:\s*(true|false)\b/g, ': <span class="json-boolean">$1</span>')
            // Null values
            .replace(/:\s*(null)\b/g, ': <span class="json-null">$1</span>')
            // Punctuation (brackets, braces, commas)
            .replace(/([{}[\],])/g, '<span class="json-punctuation">$1</span>');

        return highlighted;

    } catch (error) {
        console.error('Error en formatJsonWithSyntaxHighlighting:', error);
        // Fallback: devolver JSON sin highlighting
        return typeof obj === 'string' ? obj : JSON.stringify(obj, null, 2);
    }
}

/**
 * Copia la respuesta JSON al portapapeles
 */
async function copyResponseToClipboard() {
    const bodyElement = document.getElementById('responseBodyContent');
    const button = document.getElementById('copyResponseButton');

    if (!bodyElement) return;

    try {
        // Obtener el texto sin formato HTML
        const textContent = bodyElement.textContent;
        await navigator.clipboard.writeText(textContent);

        const originalText = button.textContent;
        button.textContent = '✅ Copiado!';
        button.style.background = 'var(--success-color)';
        button.style.color = 'white';

        setTimeout(() => {
            button.textContent = originalText;
            button.style.background = '';
            button.style.color = '';
        }, 2000);

    } catch (error) {
        button.textContent = '❌ Error';
        setTimeout(() => {
            button.textContent = '📋 Copiar JSON';
        }, 2000);
    }
}

/**
 * Inicializa tooltips dinámicos
 */
function initializeTooltips() {
    const tooltipElements = document.querySelectorAll('[data-tooltip]');

    tooltipElements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            const tooltipText = this.getAttribute('data-tooltip');
            const tooltip = createTooltip(tooltipText);

            document.body.appendChild(tooltip);
            positionTooltip(this, tooltip);

            this.addEventListener('mouseleave', function() {
                if (tooltip && tooltip.parentNode) {
                    tooltip.parentNode.removeChild(tooltip);
                }
            });
        });
    });
}

/**
 * Crea un elemento tooltip
 */
function createTooltip(text) {
    const tooltip = document.createElement('div');
    tooltip.className = 'tooltip';
    tooltip.textContent = text;
    tooltip.style.cssText = `
        position: absolute;
        background: var(--dark-2);
        color: var(--text-light);
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 12px;
        white-space: nowrap;
        z-index: 1000;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        border: 1px solid var(--primary-color);
        opacity: 0;
        transition: opacity 0.2s ease-in-out;
        pointer-events: none;
    `;

    // Animación de aparición
    setTimeout(() => {
        tooltip.style.opacity = '1';
    }, 10);

    return tooltip;
}

/**
 * Posiciona el tooltip relativo al elemento
 */
function positionTooltip(element, tooltip) {
    const rect = element.getBoundingClientRect();
    const tooltipRect = tooltip.getBoundingClientRect();

    const left = rect.left + (rect.width / 2) - (tooltipRect.width / 2);
    const top = rect.top - tooltipRect.height - 8;

    tooltip.style.left = Math.max(10, left) + 'px';
    tooltip.style.top = Math.max(10, top) + 'px';
}

/**
 * Inicializa animaciones CSS de entrada
 */
function initializeAnimations() {
    const animatedElements = document.querySelectorAll('.endpoint-card, .interactive-test-panel');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    animatedElements.forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        element.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
        observer.observe(element);
    });
}

/**
 * Función de utilidad para abrir el API Client con una URL pre-cargada
 */
function openApiClientWithUrl(url) {
    // Guardar la URL en localStorage para que el API Client la use
    localStorage.setItem('preloadedUrl', url);

    // Abrir el API Client
    window.open('/api-client', '_blank');
}

/**
 * Detectar preferencias de usuario
 */
function detectUserPreferences() {
    // Detectar tema preferido
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        document.body.classList.add('dark-theme');
    }

    // Detectar preferencia de movimiento reducido
    if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        document.body.classList.add('reduced-motion');
    }

    // Detectar alta densidad de píxeles
    if (window.devicePixelRatio > 1.5) {
        document.body.classList.add('high-dpi');
    }
}

/**
 * Copia tokens de autenticación al portapapeles
 */
function copyAuthToken(token) {
    const fullToken = `Bearer ${token}`;

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(fullToken).then(() => {
            showCopyFeedback(`Token copiado: ${token}`);
        }).catch(error => {
            console.error('Error al copiar token:', error);
            fallbackCopyText(fullToken);
            showCopyFeedback(`Token copiado: ${token}`);
        });
    } else {
        fallbackCopyText(fullToken);
        showCopyFeedback(`Token copiado: ${token}`);
    }
}

/**
 * Inicializa interactividad para tokens de autenticación
 */
function initializeAuthTokens() {
    // Agregar click handlers a los tokens en las listas
    const authExamples = document.querySelectorAll('.auth-examples li code');

    authExamples.forEach(tokenElement => {
        // Solo agregar interactividad a elementos que parecen tokens
        const tokenText = tokenElement.textContent.trim();
        if (tokenText.includes('token') || tokenText.includes('key')) {
            tokenElement.style.cursor = 'pointer';
            tokenElement.style.userSelect = 'none';
            tokenElement.title = 'Clic para copiar como Bearer token';

            // Agregar efectos hover
            tokenElement.addEventListener('mouseenter', () => {
                tokenElement.style.transform = 'scale(1.05)';
                tokenElement.style.transition = 'transform 0.2s ease';
            });

            tokenElement.addEventListener('mouseleave', () => {
                tokenElement.style.transform = 'scale(1)';
            });

            // Agregar click handler
            tokenElement.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                copyAuthToken(tokenText);

                // Efecto visual de clic
                tokenElement.style.background = '#28a745';
                tokenElement.style.color = 'white';
                setTimeout(() => {
                    tokenElement.style.background = '';
                    tokenElement.style.color = '';
                }, 1000);
            });
        }
    });
}

// Ejecutar detección de preferencias
detectUserPreferences();

// Inicializar funciones de autenticación
initializeAuthTokens();


// Utilidad para debounce de eventos
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Utilidad para formatear tiempo
 */
function formatTime(ms) {
    if (ms < 1000) {
        return `${ms}ms`;
    } else if (ms < 60000) {
        return `${(ms / 1000).toFixed(1)}s`;
    } else {
        return `${Math.floor(ms / 60000)}m ${Math.floor((ms % 60000) / 1000)}s`;
    }
}

/**
 * Función de debug para probar el syntax highlighting
 */
function testSyntaxHighlighting() {
    const testObject = {
        "message": "Test successful",
        "data": {
            "id": 123,
            "name": "John Doe",
            "active": true,
            "score": 95.5,
            "tags": ["user", "admin"],
            "metadata": null
        },
        "success": true,
        "timestamp": "2025-07-14T12:00:00Z"
    };

    console.log('🧪 Testing JSON syntax highlighting...');
    console.log('Original object:', testObject);

    const formatted = formatJsonWithSyntaxHighlighting(testObject);
    console.log('Formatted HTML:', formatted);

    return formatted;
}

// Hacer la función disponible globalmente para debugging
window.testSyntaxHighlighting = testSyntaxHighlighting;

console.log('🔧 Debug function available: window.testSyntaxHighlighting()');
