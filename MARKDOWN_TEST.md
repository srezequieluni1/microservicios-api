# Documentación de Prueba - Nuevo Renderizador Markdown

Esta es una página de prueba para demostrar las capacidades del nuevo renderizador **Marked.js** con **Highlight.js**.

## Características Principales

### 1. Texto Formateado

- **Texto en negrita** para énfasis
- *Texto en cursiva* para destacar
- `código inline` con syntax highlighting
- ~~Texto tachado~~ para correcciones

### 2. Listas

#### Lista no ordenada:
- Elemento 1
- Elemento 2
  - Sub-elemento 2.1
  - Sub-elemento 2.2
- Elemento 3

#### Lista ordenada:
1. Primer paso
2. Segundo paso
3. Tercer paso

#### Lista de tareas:
- [x] Tarea completada
- [ ] Tarea pendiente
- [x] Otra tarea completada

### 3. Bloques de Código

#### JavaScript:
```javascript
// Ejemplo de código JavaScript
function renderMarkdown(content) {
    const html = marked.parse(content);
    return html;
}

// Configuración de Marked.js
marked.setOptions({
    highlight: function(code, lang) {
        return hljs.highlight(code, { language: lang }).value;
    },
    breaks: true,
    gfm: true
});
```

#### PHP:
```php
<?php
// Ejemplo de código PHP
class MarkdownRenderer 
{
    public function render(string $content): string
    {
        return $this->parseMarkdown($content);
    }
    
    private function parseMarkdown(string $markdown): string
    {
        // Lógica de renderizado
        return $processedContent;
    }
}
```

#### JSON:
```json
{
    "name": "microservicios-api",
    "version": "1.0.0",
    "dependencies": {
        "marked": "^11.1.1",
        "highlight.js": "^11.9.0"
    },
    "features": [
        "Dark theme unified",
        "Markdown rendering",
        "Syntax highlighting"
    ]
}
```

### 4. Tablas

| Característica | Antes | Ahora |
|---------------|-------|-------|
| Renderizador | Manual (RegEx) | Marked.js |
| Syntax Highlighting | Ninguno | Highlight.js |
| Soporte GFM | Limitado | Completo |
| Performance | Básica | Optimizada |
| Mantenimiento | Difícil | Fácil |

### 5. Enlaces y Referencias

- [Marked.js Documentation](https://marked.js.org/)
- [Highlight.js](https://highlightjs.org/)
- [GitHub Flavored Markdown](https://github.github.com/gfm/)

### 6. Citas y Definiciones

> Esta es una cita en bloque que demuestra el nuevo estilo del tema oscuro unificado. 
> 
> Las citas pueden tener múltiples párrafos y mantienen el estilo consistente con el resto de la aplicación.

#### Definiciones:

**Marked.js**
: Una librería de JavaScript rápida y ligera para convertir Markdown a HTML

**Highlight.js**
: Una librería de syntax highlighting que soporta más de 190 lenguajes de programación

### 7. Separadores

---

### 8. Elementos Inline

Aquí tienes algunos elementos inline: `código`, **negrita**, *cursiva*, y [enlaces](#).

## Ventajas del Nuevo Sistema

### ✅ Beneficios

1. **Mayor robustez**: Marked.js es una librería probada y mantenida
2. **Mejor performance**: Optimizada para grandes documentos
3. **Syntax highlighting**: Soporte automático para múltiples lenguajes
4. **GitHub Flavored Markdown**: Soporte completo para GFM
5. **Mantenimiento**: Sin necesidad de mantener código de parsing personalizado
6. **Extensibilidad**: Fácil de extender con plugins

### 🔧 Características Técnicas

- **CDN**: Carga desde CDN para mejor performance
- **Tema oscuro**: Integración perfecta con el tema unificado
- **Responsive**: Funciona perfectamente en dispositivos móviles
- **Accesibilidad**: Mejor soporte para lectores de pantalla

---

## Conclusión

El nuevo renderizador Markdown basado en **Marked.js** y **Highlight.js** proporciona una experiencia mucho más robusta y profesional para la documentación del API Client.
