# Cliente API Laravel 12

Una herramienta visual completa para probar endpoints de API directamente desde tu aplicación Laravel 12.

## 🚀 Características

- **Interfaz moderna y responsiva** con diseño gradiente
- **Soporte completo para métodos HTTP**: GET, POST, PUT, DELETE, PATCH
- **Editor de headers personalizados** con validación JSON
- **Editor de cuerpo de petición** con syntax highlighting
- **Respuesta formateada** con syntax highlighting JSON
- **Información detallada de respuesta**: status code, headers, tiempo de respuesta
- **Validación en tiempo real** de formularios
- **Estados de carga** y feedback visual
- **Función de copiado** al portapapeles
- **Manejo robusto de errores**

## 📋 Cómo usar

### Acceso
Visita: `http://127.0.0.1:8000/api-client`

### Campos disponibles

1. **Método HTTP**: Selecciona entre GET, POST, PUT, DELETE, PATCH
2. **URL**: Ingresa la URL completa del endpoint a probar
3. **Headers**: JSON con headers personalizados (opcional)
4. **Request Body**: JSON con el cuerpo de la petición (para POST, PUT, PATCH)

### Ejemplos de uso

#### Ejemplo básico GET
```
Método: GET
URL: http://tu-dominio/api/test/get-example
Headers: {"Accept": "application/json"}
Body: (vacío)
```

#### Ejemplo POST con datos
```
Método: POST
URL: http://tu-dominio/api/test/post-example
Headers: {
  "Content-Type": "application/json",
  "Accept": "application/json"
}
Body: {
  "nombre": "Juan",
  "email": "juan@ejemplo.com",
  "edad": 30
}
```

#### Ejemplo con headers personalizados
```
Método: GET
URL: http://tu-dominio/api/test/headers
Headers: {
  "Accept": "application/json",
  "X-API-Key": "mi-api-key-secreta"
}
```

## 🧪 Endpoints de prueba incluidos

El sistema incluye varios endpoints de ejemplo para probar:

### GET `/api/test/get-example`
- Respuesta simple con datos de ejemplo
- Acepta parámetros query

### POST `/api/test/post-example`
- Acepta cualquier datos JSON
- Devuelve los datos recibidos

### PUT `/api/test/put-example/{id?}`
- Simula actualización de recurso
- ID opcional en la URL

### DELETE `/api/test/delete-example/{id?}`
- Simula eliminación de recurso
- ID opcional en la URL

### PATCH `/api/test/patch-example/{id?}`
- Simula actualización parcial
- ID opcional en la URL

### GET `/api/test/status?status=200`
- Simula diferentes códigos de estado HTTP
- Parámetro: `status` (100-599)

### GET `/api/test/delay?delay=2`
- Simula respuesta con demora
- Parámetro: `delay` (0-10 segundos)

### GET `/api/test/users`
- Devuelve lista de usuarios de ejemplo
- Datos ficticios para pruebas

### GET `/api/test/headers`
- Requiere header `X-API-Key`
- Devuelve información de headers

## 🎨 Características visuales

- **Diseño responsivo** que se adapta a móviles y tablets
- **Syntax highlighting** para JSON en respuestas
- **Estados visuales** para diferentes códigos de estado HTTP
- **Animaciones suaves** y transiciones
- **Loading states** durante las peticiones
- **Feedback visual** para acciones del usuario

## 🔧 Configuración

### Requisitos
- Laravel 12
- PHP 8.4+
- Navegador moderno con soporte para fetch API

### Instalación
1. Copia el archivo `api-client.blade.php` a `resources/views/`
2. Agrega la ruta en `routes/web.php`:
   ```php
   Route::get('/api-client', fn() => view('api-client'))->name('api-client');
   ```
3. (Opcional) Copia `ApiTestController.php` para endpoints de prueba
4. (Opcional) Agrega las rutas de prueba en `routes/api.php`

### Personalización

#### Cambiar estilos
Modifica las variables CSS en la sección `<style>` del archivo:
```css
/* Colores principales */
:root {
    --primary-color: #3498db;
    --success-color: #27ae60;
    --error-color: #e74c3c;
    --warning-color: #f39c12;
}
```

#### Agregar nuevos métodos HTTP
Modifica el select de métodos:
```html
<select id="httpMethod" class="method-select">
    <option value="GET">GET</option>
    <option value="POST">POST</option>
    <!-- Agregar nuevos métodos aquí -->
</select>
```

#### Personalizar headers por defecto
Modifica la función de inicialización en JavaScript:
```javascript
customHeadersInput.value = JSON.stringify({
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": "Bearer tu-token"
}, null, 2);
```

## 🛡️ Seguridad

- **Validación CSRF**: Incluye token CSRF de Laravel
- **Validación de entrada**: Validación de URLs y JSON
- **Sanitización**: Escaping automático de contenido
- **Headers seguros**: Configuración de CORS y headers de seguridad

## 📱 Compatibilidad

- ✅ Chrome 80+
- ✅ Firefox 75+
- ✅ Safari 13+
- ✅ Edge 80+
- ✅ Dispositivos móviles

## 🐛 Solución de problemas

### Error de CORS
Si encuentras errores de CORS, asegúrate de:
1. Configurar correctamente las rutas API
2. Verificar headers de CORS en Laravel
3. Usar URLs completas (con protocolo)

### Respuestas vacías
- Verifica que el endpoint exista
- Confirma que el método HTTP sea correcto
- Revisa los logs de Laravel para errores

### JSON inválido
- Usa comillas dobles para keys y strings
- Verifica la sintaxis con un validador JSON
- No olvides las comas entre elementos

## 📚 Recursos adicionales

- [Documentación de Laravel](https://laravel.com/docs)
- [Fetch API MDN](https://developer.mozilla.org/es/docs/Web/API/Fetch_API)
- [JSON.org](https://www.json.org/) para sintaxis JSON
- [HTTP Status Codes](https://httpstatuses.com/) para códigos de estado

---

**Desarrollado para Laravel 12** - Una herramienta completa para desarrollo y testing de APIs.
