/**
 * JavaScript para la página de pruebas del API Client
 * Mejora la interactividad y experiencia del usuario
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 API Client Test página cargada');

    // Inicializar funcionalidades
    initializeUrlCopy();
    initializeAnimations();
    initializeTooltips();

    console.log('✅ Funcionalidades de la página de pruebas inicializadas');
});

/**
 * Permite copiar URLs al hacer clic en ellas
 */
function initializeUrlCopy() {
    const testUrls = document.querySelectorAll('.test-url');

    testUrls.forEach(url => {
        // Agregar indicador visual de que es clickeable
        url.style.cursor = 'pointer';
        url.title = 'Clic para copiar URL';

        url.addEventListener('click', async function() {
            const urlText = this.textContent.trim();

            try {
                await navigator.clipboard.writeText(urlText);
                showCopyFeedback(this, '✅ URL copiada!');
            } catch (error) {
                console.warn('Error copiando URL:', error);
                // Fallback para navegadores que no soportan clipboard API
                fallbackCopyText(urlText);
                showCopyFeedback(this, '📋 URL copiada!');
            }
        });

        // Agregar hover effect mejorado
        url.addEventListener('mouseenter', function() {
            const method = this.getAttribute('data-method');
            if (method) {
                this.setAttribute('title', `Clic para copiar URL del endpoint ${method}`);
            }
        });
    });
}

/**
 * Muestra feedback visual cuando se copia una URL
 */
function showCopyFeedback(element, message) {
    const originalText = element.textContent;
    const originalBg = element.style.backgroundColor;

    // Cambiar temporalmente el contenido y estilo
    element.textContent = message;
    element.style.backgroundColor = '#d4edda';
    element.style.color = '#155724';
    element.style.fontWeight = 'bold';

    // Restaurar después de 2 segundos
    setTimeout(() => {
        element.textContent = originalText;
        element.style.backgroundColor = originalBg;
        element.style.color = '';
        element.style.fontWeight = '';
    }, 2000);
}

/**
 * Fallback para copiar texto en navegadores antiguos
 */
function fallbackCopyText(text) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.left = '-999999px';
    textArea.style.top = '-999999px';

    document.body.appendChild(textArea);
    textArea.select();

    try {
        document.execCommand('copy');
    } catch (error) {
        console.error('Error en fallback copy:', error);
    }

    document.body.removeChild(textArea);
}

/**
 * Inicializa animaciones mejoradas
 */
function initializeAnimations() {
    // Agregar animación de entrada escalonada si no está presente
    const sections = document.querySelectorAll('.test-section');

    // Observer para animaciones cuando entran en viewport
    if ('IntersectionObserver' in window) {
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

        sections.forEach(section => {
            // Solo aplicar si prefers-reduced-motion no está activo
            if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                section.style.opacity = '0';
                section.style.transform = 'translateY(20px)';
                section.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                observer.observe(section);
            }
        });
    }
}

/**
 * Inicializa tooltips informativos
 */
function initializeTooltips() {
    // Agregar tooltips a elementos código
    const codeElements = document.querySelectorAll('code');
    codeElements.forEach(code => {
        if (code.textContent.includes('status')) {
            code.title = 'Parámetro para cambiar el código de estado HTTP de respuesta';
        } else if (code.textContent.includes('delay')) {
            code.title = 'Parámetro para simular demora en la respuesta (en segundos)';
        } else if (code.textContent.includes('X-API-Key')) {
            code.title = 'Header de autenticación requerido para este endpoint';
        }
    });

    // Agregar información contextual a los métodos HTTP
    const methodElements = document.querySelectorAll('.test-url[data-method]');
    methodElements.forEach(element => {
        const method = element.getAttribute('data-method');
        const methodInfo = {
            'GET': 'Método para obtener datos. No requiere cuerpo de petición.',
            'POST': 'Método para crear nuevos recursos. Requiere cuerpo de petición.',
            'PUT': 'Método para actualizar completamente un recurso. Requiere cuerpo de petición.',
            'DELETE': 'Método para eliminar un recurso. No requiere cuerpo de petición.',
            'PATCH': 'Método para actualizar parcialmente un recurso. Requiere cuerpo de petición.'
        };

        if (methodInfo[method]) {
            element.setAttribute('title', `${method}: ${methodInfo[method]}`);
        }
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
