<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - No encontrado</title>
    <style>
        :root {
            --primary-color: #007bff;
            --danger-color: #dc3545;
            --light-color: #f8f9fa;
            --border-radius: 8px;
            --box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            text-align: center;
        }

        .header {
            background: var(--danger-color);
            color: white;
            padding: 30px;
        }

        .header h1 {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .content {
            padding: 40px 30px;
        }

        .error-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            color: var(--danger-color);
        }

        .error-message {
            font-size: 1.2rem;
            margin-bottom: 15px;
            color: #666;
        }

        .file-name {
            background: var(--light-color);
            padding: 10px;
            border-radius: 4px;
            font-family: monospace;
            color: var(--danger-color);
            margin: 20px 0;
            border: 1px solid #dee2e6;
        }

        .actions {
            margin-top: 30px;
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .button {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .button-primary {
            background: var(--primary-color);
            color: white;
        }

        .button-primary:hover {
            background: #0056b3;
            transform: translateY(-1px);
        }

        .button-secondary {
            background: #6c757d;
            color: white;
        }

        .button-secondary:hover {
            background: #545b62;
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
            .actions {
                flex-direction: column;
                align-items: center;
            }

            .button {
                width: 200px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📚 {{ $title }}</h1>
        </div>
        <div class="content">
            <div class="error-icon">📄❌</div>
            <div class="error-message">
                Archivo de documentación no encontrado
            </div>
            <div class="file-name">{{ $file }}</div>
            <p>El archivo de documentación que intentas acceder no existe o no se pudo leer.</p>

            <div class="actions">
                <a href="/api-client" class="button button-primary">← Volver al API Client</a>
            </div>
        </div>
    </div>
</body>
</html>
