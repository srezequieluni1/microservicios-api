<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Client Test - Laravel 12</title>

    <!-- Estilos CSS - Usando los mismos del API Client -->
    <link rel="stylesheet" href="{{ asset('css/api-client-variables.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/api-client-test.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/api-client-test-responsive.css') }}?v={{ time() }}">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-content">
                <div class="header-title">
                    <h1>API Client Test</h1>
                    <span class="header-subtitle">Laravel 12</span>
                </div>
                <div class="header-links">
                    <a href="/api-client" class="header-link">🚀 API Client</a>
                    <a href="/docs/test-page" class="header-link">📚 Documentación</a>
                </div>
            </div>
        </div>

        <!-- Contenido principal -->
        <div class="main-content">
            <div class="content-wrapper">

    <div class="test-section">
        <h2 class="section-title">✅ Cliente API</h2>
        <p class="section-description">Una herramienta completa para probar endpoints de API con interfaz moderna y responsiva.</p>
        <div class="section-actions">
            <a href="/api-client" class="primary-button">Abrir API Client</a>
        </div>
    </div>

    <div class="test-section">
        <h2 class="section-title">🧪 Endpoints de Prueba</h2>
        <p class="section-description">Usa estos endpoints para probar el cliente API:</p>

        <div class="endpoints-grid">
            <div class="endpoint-card">
                <h3 class="endpoint-title">GET - Ejemplo básico</h3>
                <div class="test-url" data-method="GET">{{ url('/api/test/get-example') }}</div>
                <p class="endpoint-description">Petición GET simple para probar conectividad básica.</p>
            </div>

            <div class="endpoint-card">
                <h3 class="endpoint-title">POST - Enviar datos</h3>
                <div class="test-url" data-method="POST">{{ url('/api/test/post-example') }}</div>
                <p class="endpoint-description"><strong>Cuerpo de ejemplo:</strong></p>
                <pre class="code-block">{"nombre": "Juan", "email": "juan@ejemplo.com"}</pre>
            </div>

            <div class="endpoint-card">
                <h3 class="endpoint-title">PUT - Actualizar datos</h3>
                <div class="test-url" data-method="PUT">{{ url('/api/test/put-example/123') }}</div>
                <p class="endpoint-description"><strong>Cuerpo de ejemplo:</strong></p>
                <pre class="code-block">{"nombre": "Juan Actualizado", "email": "juan.nuevo@ejemplo.com"}</pre>
            </div>

            <div class="endpoint-card">
                <h3 class="endpoint-title">DELETE - Eliminar recurso</h3>
                <div class="test-url" data-method="DELETE">{{ url('/api/test/delete-example/123') }}</div>
                <p class="endpoint-description">Elimina un recurso específico por ID.</p>
            </div>

            <div class="endpoint-card">
                <h3 class="endpoint-title">PATCH - Actualización parcial</h3>
                <div class="test-url" data-method="PATCH">{{ url('/api/test/patch-example/123') }}</div>
                <p class="endpoint-description"><strong>Cuerpo de ejemplo:</strong></p>
                <pre class="code-block">{"email": "juan.parcial@ejemplo.com"}</pre>
            </div>

            <div class="endpoint-card">
                <h3 class="endpoint-title">🔒 Endpoint protegido - Autenticación Bearer</h3>
                <div class="test-url" data-method="GET">{{ url('/api/test/protected') }}</div>
                <p class="endpoint-description"><strong>Header requerido:</strong> <code>Authorization: Bearer demo-token-123</code></p>
                <div class="auth-examples">
                    <p><strong>Tokens válidos para pruebas:</strong></p>
                    <ul>
                        <li><code>demo-token-123</code> - Usuario administrador</li>
                        <li><code>test-token-456</code> - Usuario regular</li>
                        <li><code>api-key-789</code> - Usuario API</li>
                    </ul>
                    <p><em>💡 Tip: Copia cualquiera de estos tokens y úsalo como: "Bearer [token]"</em></p>
                </div>
            </div>

            <div class="endpoint-card">
                <h3 class="endpoint-title">Códigos de estado</h3>
                <div class="test-url" data-method="GET">{{ url('/api/test/status?status=200') }}</div>
                <p class="endpoint-description">Cambia el parámetro <code>status</code> por: 200, 201, 400, 404, 500, etc.</p>
            </div>

            <div class="endpoint-card">
                <h3 class="endpoint-title">Prueba de demora</h3>
                <div class="test-url" data-method="GET">{{ url('/api/test/delay?delay=2') }}</div>
                <p class="endpoint-description">Cambia el parámetro <code>delay</code> (0-10 segundos)</p>
            </div>

            <div class="endpoint-card">
                <h3 class="endpoint-title">Headers requeridos</h3>
                <div class="test-url" data-method="GET">{{ url('/api/test/headers') }}</div>
                <p class="endpoint-description"><strong>Header requerido:</strong> <code>X-API-Key: tu-api-key-aqui</code></p>
            </div>

            <div class="endpoint-card">
                <h3 class="endpoint-title">Lista de usuarios</h3>
                <div class="test-url" data-method="GET">{{ url('/api/test/users') }}</div>
                <p class="endpoint-description">Obtiene una lista de usuarios de ejemplo.</p>
            </div>
        </div>
    </div>

    <div class="test-section">
        <h2 class="section-title">📋 Instrucciones Rápidas</h2>
        <div class="instructions-grid">
            <div class="instruction-step">
                <span class="step-number">1</span>
                <p>Haz clic en "Abrir API Client" arriba</p>
            </div>
            <div class="instruction-step">
                <span class="step-number">2</span>
                <p>Selecciona un método HTTP (GET, POST, PUT, DELETE, PATCH)</p>
            </div>
            <div class="instruction-step">
                <span class="step-number">3</span>
                <p>Copia y pega una de las URLs de prueba</p>
            </div>
            <div class="instruction-step">
                <span class="step-number">4</span>
                <p>Agrega headers si es necesario (formato JSON)</p>
            </div>
            <div class="instruction-step">
                <span class="step-number">5</span>
                <p>Para POST/PUT/PATCH, agrega un cuerpo JSON</p>
            </div>
            <div class="instruction-step">
                <span class="step-number">6</span>
                <p>Para endpoints protegidos, agrega el header: <code>{"Authorization": "Bearer demo-token-123"}</code></p>
            </div>
            <div class="instruction-step">
                <span class="step-number">7</span>
                <p>Haz clic en "Ejecutar Request"</p>
            </div>
            <div class="instruction-step">
                <span class="step-number">8</span>
                <p>Ve la respuesta formateada con syntax highlighting</p>
            </div>
        </div>

        <div class="auth-examples">
            <p><strong>🔐 Ejemplo de Headers para Autenticación:</strong></p>
            <pre class="code-block">{
  "Authorization": "Bearer demo-token-123",
  "Content-Type": "application/json"
}</pre>
            <em>💡 Los headers se agregan en formato JSON en el campo "Headers" del cliente API</em>
        </div>
    </div>

    <div class="test-section">
        <h2 class="section-title">🎨 Características</h2>
        <div class="features-grid">
            <div class="feature-item">✅ Interfaz moderna y responsiva</div>
            <div class="feature-item">✅ Soporte para todos los métodos HTTP</div>
            <div class="feature-item">✅ Syntax highlighting para JSON</div>
            <div class="feature-item">✅ Validación en tiempo real</div>
            <div class="feature-item">✅ Estados de carga y feedback visual</div>
            <div class="feature-item">✅ Información detallada de respuesta</div>
            <div class="feature-item">✅ Función de copiado al portapapeles</div>
            <div class="feature-item">✅ Manejo robusto de errores</div>
            <div class="feature-item">✅ Design responsivo para móviles</div>
            <div class="feature-item">🔒 Soporte para autenticación Bearer Token</div>
            <div class="feature-item">🔑 Ejemplos de endpoints protegidos</div>
            <div class="feature-item">📊 Manejo de códigos de estado HTTP</div>
        </div>
    </div>

    <div class="test-section">
        <h2 class="section-title">📚 Recursos</h2>
        <div class="resources-grid">
            <a href="/api-client" class="resource-card primary">
                <span class="resource-icon">🚀</span>
                <span class="resource-title">Abrir API Client</span>
                <span class="resource-description">Interfaz principal para probar endpoints</span>
            </a>
            <a href="/docs/api-client" class="resource-card">
                <span class="resource-icon">📖</span>
                <span class="resource-title">Ver Documentación</span>
                <span class="resource-description">Guía completa del cliente API</span>
            </a>
            <a href="/docs/css-structure" class="resource-card">
                <span class="resource-icon">🎨</span>
                <span class="resource-title">Estructura CSS</span>
                <span class="resource-description">Documentación de estilos</span>
            </a>
            <a href="/docs/test-page" class="resource-card">
                <span class="resource-icon">📋</span>
                <span class="resource-title">Documentación Test Page</span>
                <span class="resource-description">Información sobre esta página</span>
            </a>
            <a href="/" class="resource-card">
                <span class="resource-icon">🏠</span>
                <span class="resource-title">Volver al inicio</span>
                <span class="resource-description">Página principal de Laravel</span>
            </a>
        </div>

        <div class="useful-links">
            <h3 class="links-title">Enlaces útiles:</h3>
            <div class="links-grid">
                <a href="/api/ping" target="_blank" class="link-item">🔍 Ping API (Verificar estado)</a>
                <a href="https://laravel.com/docs" target="_blank" class="link-item">📚 Documentación Laravel</a>
                <a href="https://developer.mozilla.org/es/docs/Web/API/Fetch_API" target="_blank" class="link-item">🌐 Fetch API MDN</a>
                <a href="https://httpstatuses.com/" target="_blank" class="link-item">📊 Códigos de estado HTTP</a>
            </div>
        </div>
    </div>

            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="{{ asset('js/api-client-test.js') }}"></script>
</body>
</html>
