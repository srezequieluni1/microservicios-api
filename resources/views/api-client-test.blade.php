<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Client Test</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .test-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .test-url {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            font-family: monospace;
            word-break: break-all;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 5px;
        }
        .button:hover {
            background: #0056b3;
        }
    </style>
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
        <div class="test-url">{{ url('/api/test/get-example') }}</div>

        <h3>POST - Enviar datos</h3>
        <div class="test-url">{{ url('/api/test/post-example') }}</div>
        <p><strong>Cuerpo de ejemplo:</strong></p>
        <pre>{"nombre": "Juan", "email": "juan@ejemplo.com"}</pre>

        <h3>Códigos de estado</h3>
        <div class="test-url">{{ url('/api/test/status?status=200') }}</div>
        <p>Cambia el parámetro <code>status</code> por: 200, 400, 404, 500, etc.</p>

        <h3>Prueba de demora</h3>
        <div class="test-url">{{ url('/api/test/delay?delay=2') }}</div>
        <p>Cambia el parámetro <code>delay</code> (0-10 segundos)</p>

        <h3>Headers requeridos</h3>
        <div class="test-url">{{ url('/api/test/headers') }}</div>
        <p><strong>Header requerido:</strong> <code>X-API-Key: tu-api-key-aqui</code></p>

        <h3>Lista de usuarios</h3>
        <div class="test-url">{{ url('/api/test/users') }}</div>
    </div>

    <div class="test-section">
        <h2>📋 Instrucciones Rápidas</h2>
        <ol>
            <li>Haz clic en "Abrir API Client" arriba</li>
            <li>Selecciona un método HTTP (GET, POST, PUT, DELETE, PATCH)</li>
            <li>Copia y pega una de las URLs de prueba</li>
            <li>Agrega headers si es necesario (formato JSON)</li>
            <li>Para POST/PUT/PATCH, agrega un cuerpo JSON</li>
            <li>Haz clic en "Ejecutar Request"</li>
            <li>Ve la respuesta formateada con syntax highlighting</li>
        </ol>
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
        </ul>
    </div>

    <div class="test-section">
        <h2>📚 Recursos</h2>
        <a href="#" onclick="window.open('/API_CLIENT_README.md')" class="button">Ver Documentación</a>
        <a href="/" class="button">Volver al inicio</a>
    </div>
</body>
</html>
