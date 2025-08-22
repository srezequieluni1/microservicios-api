<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - Laravel API Client</title>

    <!-- CSS Unificado para tema oscuro de documentación -->
    <link rel="stylesheet" href="css/documentation-dark-theme.css">

    <style>
        .docs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .doc-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 20px;
            transition: all 0.3s ease;
            text-decoration: none;
            color: var(--text-primary);
            display: block;
        }

        .doc-card:hover {
            border-color: var(--primary);
            background: var(--bg-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .doc-title {
            font-size: 1.2em;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--primary);
        }

        .doc-description {
            color: var(--text-secondary);
            line-height: 1.5;
            margin-bottom: 10px;
        }

        .doc-category {
            display: inline-block;
            background: var(--primary);
            color: var(--bg-primary);
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8em;
            font-weight: 500;
        }

        .category-section {
            margin-bottom: 30px;
        }

        .category-title {
            font-size: 1.5em;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--text-primary);
            border-bottom: 2px solid var(--primary);
            padding-bottom: 8px;
        }

        .stats-summary {
            background: var(--bg-secondary);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: var(--success);
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 0.9em;
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
            <!-- Documentación por categorías -->
            @foreach($docs as $category => $categoryDocs)
                <div class="category-section">
                    <h2 class="category-title">
                        @if($category === 'Principal')
                            🎯 Documentación Principal
                        @elseif($category === 'Especializado')
                            📑 Guías Especializadas
                        @else
                            📋 Meta-Documentación
                        @endif
                    </h2>

                    <div class="docs-grid">
                        @foreach($categoryDocs as $doc)
                            <a href="{{ route($doc['route']) }}" class="doc-card">
                                <div class="doc-title">{{ $doc['title'] }}</div>
                                <div class="doc-description">{{ $doc['description'] }}</div>
                                <span class="doc-category">{{ $doc['category'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <!-- Guía de navegación -->
            <div class="stats-summary">
                <h2>🧭 Guía de Navegación Recomendada</h2>
                <div style="margin-top: 15px;">
                    <p><strong>📚 Para desarrolladores nuevos:</strong></p>
                    <p style="margin-left: 20px; color: var(--text-secondary);">
                        README.md → Documentación Completa del API → Resumen de Implementaciones
                    </p>

                    <p style="margin-top: 15px;"><strong>🔧 Para casos específicos:</strong></p>
                    <p style="margin-left: 20px; color: var(--text-secondary);">
                        Personalización de Emails → Ejemplos de Upload de Archivos → Componentes Técnicos
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
