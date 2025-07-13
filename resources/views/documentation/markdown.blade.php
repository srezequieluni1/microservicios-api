<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - Laravel API Client</title>
    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
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
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
        }

        .header {
            background: var(--primary-color);
            color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .header h1 {
            font-size: 1.8rem;
            font-weight: 600;
        }

        .back-button {
            background: rgba(255,255,255,0.2);
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 4px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .back-button:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-1px);
        }

        .content {
            padding: 30px;
            max-height: 80vh;
            overflow-y: auto;
        }

        /* Estilos para Markdown */
        .markdown-content h1,
        .markdown-content h2,
        .markdown-content h3,
        .markdown-content h4,
        .markdown-content h5,
        .markdown-content h6 {
            margin: 25px 0 15px 0;
            color: var(--dark-color);
            font-weight: 600;
        }

        .markdown-content h1 {
            font-size: 2rem;
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 10px;
        }

        .markdown-content h2 {
            font-size: 1.6rem;
            color: var(--primary-color);
        }

        .markdown-content h3 {
            font-size: 1.3rem;
            color: var(--secondary-color);
        }

        .markdown-content p {
            margin: 15px 0;
            text-align: justify;
        }

        .markdown-content ul,
        .markdown-content ol {
            margin: 15px 0;
            padding-left: 30px;
        }

        .markdown-content li {
            margin: 8px 0;
        }

        .markdown-content code {
            background: #f1f3f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 0.9em;
            color: #c7254e;
            border: 1px solid #e1e1e8;
        }

        .markdown-content pre {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 20px;
            border-radius: var(--border-radius);
            overflow-x: auto;
            margin: 20px 0;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 0.9rem;
            line-height: 1.4;
            border: 1px solid #34495e;
        }

        .markdown-content blockquote {
            border-left: 4px solid var(--primary-color);
            background: var(--light-color);
            padding: 15px 20px;
            margin: 20px 0;
            font-style: italic;
        }

        .markdown-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .markdown-content th,
        .markdown-content td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .markdown-content th {
            background: var(--primary-color);
            color: white;
            font-weight: 600;
        }

        .markdown-content tr:hover {
            background: #f5f5f5;
        }

        .markdown-content a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .markdown-content a:hover {
            text-decoration: underline;
        }

        .markdown-content strong {
            font-weight: 600;
            color: var(--dark-color);
        }

        .markdown-content em {
            font-style: italic;
            color: var(--secondary-color);
        }

        /* Scrollbar personalizado */
        .content::-webkit-scrollbar {
            width: 8px;
        }

        .content::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .content::-webkit-scrollbar-thumb {
            background: var(--secondary-color);
            border-radius: 4px;
        }

        .content::-webkit-scrollbar-thumb:hover {
            background: var(--primary-color);
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .header {
                padding: 15px 20px;
                flex-direction: column;
                text-align: center;
            }

            .header h1 {
                font-size: 1.5rem;
            }

            .content {
                padding: 20px;
            }

            .markdown-content h1 {
                font-size: 1.6rem;
            }

            .markdown-content h2 {
                font-size: 1.3rem;
            }

            .markdown-content pre {
                padding: 15px;
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📚 {{ $title }}</h1>
            <a href="{{ $backUrl }}" class="back-button">← Volver</a>
        </div>
        <div class="content">
            <div class="markdown-content" id="markdown-content">
                {{ $content }}
            </div>
        </div>
    </div>

    <script>
        // Procesador de Markdown mejorado
        document.addEventListener('DOMContentLoaded', function() {
            const content = document.getElementById('markdown-content');
            let html = content.textContent || content.innerText;

            // Escapar caracteres HTML primero
            html = html.replace(/&/g, '&amp;')
                      .replace(/</g, '&lt;')
                      .replace(/>/g, '&gt;')
                      .replace(/"/g, '&quot;')
                      .replace(/'/g, '&#39;');

            // Convertir bloques de código primero (para evitar conflictos)
            html = html.replace(/```(\w+)?\n([\s\S]*?)\n```/gim, '<pre><code>$2</code></pre>');
            html = html.replace(/```([\s\S]*?)```/gim, '<pre><code>$1</code></pre>');

            // Convertir headers
            html = html.replace(/^#### (.*$)/gim, '<h4>$1</h4>');
            html = html.replace(/^### (.*$)/gim, '<h3>$1</h3>');
            html = html.replace(/^## (.*$)/gim, '<h2>$1</h2>');
            html = html.replace(/^# (.*$)/gim, '<h1>$1</h1>');

            // Convertir texto en negrita
            html = html.replace(/\*\*(.*?)\*\*/gim, '<strong>$1</strong>');

            // Convertir texto en cursiva
            html = html.replace(/(?<!\*)\*([^*\n]+?)\*(?!\*)/gim, '<em>$1</em>');

            // Convertir código inline (evitar conflictos con bloques)
            html = html.replace(/(?<!`)`([^`\n]+?)`(?!`)/gim, '<code>$1</code>');

            // Convertir enlaces
            html = html.replace(/\[([^\]]+)\]\(([^)]+)\)/gim, '<a href="$2" target="_blank">$1</a>');

            // Convertir listas
            html = html.replace(/^\* (.*$)/gim, '___LI___$1___/LI___');
            html = html.replace(/^\- (.*$)/gim, '___LI___$1___/LI___');
            html = html.replace(/^\d+\. (.*$)/gim, '___OLI___$1___/OLI___');

            // Convertir saltos de línea dobles en párrafos
            html = html.replace(/\n\n/g, '___PARAGRAPH___');
            html = html.replace(/\n/g, ' ');
            html = html.replace(/___PARAGRAPH___/g, '</p><p>');

            // Envolver el contenido en párrafos
            if (!html.startsWith('<')) {
                html = '<p>' + html + '</p>';
            }

            // Procesar listas
            html = html.replace(/___LI___(.*?)___\/LI___/g, '<li>$1</li>');
            html = html.replace(/___OLI___(.*?)___\/OLI___/g, '<li>$1</li>');

            // Envolver elementos li consecutivos
            html = html.replace(/(<li>.*?<\/li>\s*)+/gim, function(match) {
                if (match.includes('___OLI___')) {
                    return '<ol>' + match + '</ol>';
                }
                return '<ul>' + match + '</ul>';
            });

            // Limpiar párrafos vacíos
            html = html.replace(/<p><\/p>/g, '');
            html = html.replace(/<p>\s*<\/p>/g, '');

            // Ajustar elementos de bloque dentro de párrafos
            html = html.replace(/<p>(<h[1-6]>.*?<\/h[1-6]>)<\/p>/g, '$1');
            html = html.replace(/<p>(<pre>.*?<\/pre>)<\/p>/g, '$1');
            html = html.replace(/<p>(<ul>.*?<\/ul>)<\/p>/g, '$1');
            html = html.replace(/<p>(<ol>.*?<\/ol>)<\/p>/g, '$1');

            content.innerHTML = html;
        });
    </script>
</body>
</html>
