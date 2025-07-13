<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Client Test - Laravel 12</title>

    <!-- Estilos CSS -->
    <link rel="stylesheet" href="{{ asset('css/api-client-test.css') }}">
    <link rel="stylesheet" href="{{ asset('css/api-client-test-responsive.css') }}">
</head>
<body>
    <h1>🚀 API Client para Laravel 12</h1>

    <div class="test-section">
        <h2>✅ Cliente API</h2>
        <p>Una herramienta completa para probar endpoints de API con interfaz moderna y responsiva.</p>
        <a href="/api-client" class="button">Abrir API Client</a>
    </div>

    <div class="test-section">
        <h2>🧪 Endpoints de Prueba</h2>
        <p>Usa estos endpoints para probar el cliente API:</p>

        <h3>GET - Ejemplo básico</h3>
        <div class="test-url" data-method="GET">{{ url('/api/test/get-example') }}</div>

        <h3>POST - Enviar datos</h3>
        <div class="test-url" data-method="POST">{{ url('/api/test/post-example') }}</div>
        <p><strong>Cuerpo de ejemplo:</strong></p>
        <pre>{"nombre": "Juan", "email": "juan@ejemplo.com"}</pre>

        <h3>PUT - Actualizar datos</h3>
        <div class="test-url" data-method="PUT">{{ url('/api/test/put-example/123') }}</div>
        <p><strong>Cuerpo de ejemplo:</strong></p>
        <pre>{"nombre": "Juan Actualizado", "email": "juan.nuevo@ejemplo.com"}</pre>

        <h3>DELETE - Eliminar recurso</h3>
        <div class="test-url" data-method="DELETE">{{ url('/api/test/delete-example/123') }}</div>

        <h3>PATCH - Actualización parcial</h3>
        <div class="test-url" data-method="PATCH">{{ url('/api/test/patch-example/123') }}</div>
        <p><strong>Cuerpo de ejemplo:</strong></p>
        <pre>{"email": "juan.parcial@ejemplo.com"}</pre>

        <h3>Códigos de estado</h3>
        <div class="test-url" data-method="GET">{{ url('/api/test/status?status=200') }}</div>
        <p>Cambia el parámetro <code>status</code> por: 200, 201, 400, 404, 500, etc.</p>

        <h3>Prueba de demora</h3>
        <div class="test-url" data-method="GET">{{ url('/api/test/delay?delay=2') }}</div>
        <p>Cambia el parámetro <code>delay</code> (0-10 segundos)</p>

        <h3>Headers requeridos</h3>
        <div class="test-url" data-method="GET">{{ url('/api/test/headers') }}</div>
        <p><strong>Header requerido:</strong> <code>X-API-Key: tu-api-key-aqui</code></p>

        <h3>Lista de usuarios</h3>
        <div class="test-url" data-method="GET">{{ url('/api/test/users') }}</div>

        <h3>🔒 Endpoint protegido - Autenticación Bearer</h3>
        <div class="test-url" data-method="GET">{{ url('/api/test/protected') }}</div>
        <p><strong>Header requerido:</strong> <code>Authorization: Bearer demo-token-123</code></p>
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

    <div class="test-section">
        <h2>📋 Instrucciones Rápidas</h2>
        <ol>
            <li>Haz clic en "Abrir API Client" arriba</li>
            <li>Selecciona un método HTTP (GET, POST, PUT, DELETE, PATCH)</li>
            <li>Copia y pega una de las URLs de prueba</li>
            <li>Agrega headers si es necesario (formato JSON)</li>
            <li>Para POST/PUT/PATCH, agrega un cuerpo JSON</li>
            <li>Para endpoints protegidos, agrega el header: <code>{"Authorization": "Bearer demo-token-123"}</code></li>
            <li>Haz clic en "Ejecutar Request"</li>
            <li>Ve la respuesta formateada con syntax highlighting</li>
        </ol>

        <div class="auth-examples">
            <p><strong>🔐 Ejemplo de Headers para Autenticación:</strong></p>
            <pre>{
  "Authorization": "Bearer demo-token-123",
  "Content-Type": "application/json"
}</pre>
            <em>💡 Los headers se agregan en formato JSON en el campo "Headers" del cliente API</em>
        </div>
    </div>

    <div class="test-section">
        <h2>🎨 Características</h2>
        <ul>
            <li>✅ Interfaz moderna y responsiva</li>
            <li>✅ Soporte para todos los métodos HTTP</li>
            <li>✅ Syntax highlighting para JSON</li>
            <li>✅ Validación en tiempo real</li>
            <li>✅ Estados de carga y feedback visual</li>
            <li>✅ Información detallada de respuesta</li>
            <li>✅ Función de copiado al portapapeles</li>
            <li>✅ Manejo robusto de errores</li>
            <li>✅ Design responsivo para móviles</li>
            <li>🔒 Soporte para autenticación Bearer Token</li>
            <li>🔑 Ejemplos de endpoints protegidos</li>
            <li>📊 Manejo de códigos de estado HTTP</li>
        </ul>
    </div>

    <div class="test-section">
        <h2>📚 Recursos</h2>
        <div class="button-group">
            <a href="/api-client" class="button">🚀 Abrir API Client</a>
            <a href="#" onclick="window.open('/API_CLIENT_README.md')" class="button">📖 Ver Documentación</a>
            <a href="#" onclick="window.open('/CSS_STRUCTURE_README.md')" class="button">🎨 Estructura CSS</a>
            <a href="/" class="button">🏠 Volver al inicio</a>
        </div>

        <h3>Enlaces útiles:</h3>
        <ul>
            <li><a href="/api/ping" target="_blank">🔍 Ping API (Verificar estado)</a></li>
            <li><a href="https://laravel.com/docs" target="_blank">📚 Documentación Laravel</a></li>
            <li><a href="https://developer.mozilla.org/es/docs/Web/API/Fetch_API" target="_blank">🌐 Fetch API MDN</a></li>
            <li><a href="https://httpstatuses.com/" target="_blank">📊 Códigos de estado HTTP</a></li>
        </ul>
    </div>

    <!-- JavaScript -->
    <script src="{{ asset('js/api-client-test.js') }}"></script>
</body>
</html>
