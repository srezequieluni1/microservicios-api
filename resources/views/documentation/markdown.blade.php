<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - Laravel API Client</title>

    <!-- CSS Unificado para tema oscuro de documentación -->
    <link rel="stylesheet" href="{{ asset('css/documentation-dark-theme.css') }}">
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
