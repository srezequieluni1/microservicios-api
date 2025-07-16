# API Client - Soporte para Archivos Adjuntos

## Funcionalidad Implementada

La aplicación API Client ahora incluye soporte completo para el envío de archivos adjuntos mediante formularios multipart/form-data.

## Características

### 📁 **Carga de Archivos**
- Soporte para múltiples archivos (máximo 10)
- Tamaño máximo por archivo: 10MB
- Drag & Drop habilitado
- Validación de archivos duplicados
- Vista previa de archivos seleccionados

### 🎯 **Validaciones**
- Límite de cantidad de archivos
- Límite de tamaño por archivo
- Prevención de archivos duplicados
- Notificaciones de error en tiempo real

### 🔧 **Integración**
- Compatible con peticiones POST, PUT, PATCH
- Combina archivos con datos JSON
- Headers automáticos para multipart/form-data
- Limpieza automática del formulario

## Endpoints Disponibles

### Subir Archivos
```
POST /api/files/upload
```

**Parámetros:**
- `files[]` (required): Array de archivos
- `description` (optional): Descripción de los archivos
- `folder` (optional): Carpeta de destino (default: uploads)

**Respuesta:**
```json
{
    "success": true,
    "message": "Archivos subidos exitosamente",
    "data": {
        "files": [
            {
                "original_name": "documento.pdf",
                "stored_name": "1734368400_abc123.pdf",
                "path": "uploads/1734368400_abc123.pdf",
                "url": "/storage/uploads/1734368400_abc123.pdf",
                "size": 1024000,
                "mime_type": "application/pdf",
                "extension": "pdf"
            }
        ],
        "description": "Documentos importantes",
        "folder": "uploads",
        "total_files": 1
    }
}
```

### Listar Archivos
```
GET /api/files
```

### Descargar Archivo
```
GET /api/files/download/{filename}
```

### Eliminar Archivo
```
DELETE /api/files/{filename}
```

## Cómo Usar

### 1. **En el Cliente Web**
1. Selecciona el método POST, PUT o PATCH
2. Ingresa la URL del endpoint
3. Haz clic en "Seleccionar Archivos" o arrastra archivos al área
4. Opcionalmente agrega una descripción
5. Completa otros campos del formulario si es necesario
6. Haz clic en "Ejecutar Request"

### 2. **Ejemplo de Uso**
Para probar la funcionalidad:

**URL:** `http://localhost:8000/api/test-files`
**Método:** POST
**Archivos:** Selecciona uno o más archivos
**Descripción:** "Archivos de prueba"

### 3. **Con Datos Adicionales**
Puedes combinar archivos con datos JSON en el campo "Request Body":

```json
{
    "category": "documents",
    "priority": "high",
    "tags": ["important", "urgent"]
}
```

## Características Técnicas

### **Frontend (JavaScript)**
- `api-client-files.js`: Manejo de archivos
- Drag & Drop nativo
- Validaciones en tiempo real
- FormData automático
- Vista previa con iconos

### **Backend (Laravel)**
- `FileController`: Manejo de archivos
- Validaciones de servidor
- Storage en `storage/app/public`
- URLs públicas automáticas
- Respuestas JSON estructuradas

### **Validaciones**
- Máximo 10 archivos por petición
- 10MB máximo por archivo
- Prevención de duplicados
- Tipos MIME permitidos (todos)

## Ejemplos de Peticiones

### Ejemplo con cURL
```bash
curl -X POST http://localhost:8000/api/test-files \
  -F "files[]=@archivo1.pdf" \
  -F "files[]=@imagen.jpg" \
  -F "description=Documentos de prueba" \
  -F "category=test"
```

### Ejemplo con JavaScript
```javascript
const formData = new FormData();
formData.append('files[0]', file1);
formData.append('files[1]', file2);
formData.append('description', 'Mi descripción');

fetch('/api/test-files', {
    method: 'POST',
    body: formData
});
```

## Archivos Modificados

1. **`app/Http/Controllers/Api/FileController.php`** - Nuevo controlador
2. **`routes/api.php`** - Nuevas rutas API
3. **`resources/views/api-client.blade.php`** - UI de archivos
4. **`public/css/api-client.css`** - Estilos para archivos
5. **`public/js/api-client-files.js`** - Nuevo manejo de archivos
6. **`public/js/api-client.js`** - Integración con FormData
7. **`public/js/api-client-history.js`** - Limpieza de archivos

## Configuración

### Storage
```bash
php artisan storage:link
```

### Permisos
Asegúrate de que `storage/app/public` tenga permisos de escritura.

## Troubleshooting

### Error: "Archivo demasiado grande"
- Verifica que el archivo sea menor a 10MB
- Ajusta `maxFileSize` en `api-client-files.js` si es necesario

### Error: "Carpeta no encontrada" 
- Ejecuta `php artisan storage:link`
- Verifica permisos en `storage/app/public`

### Archivos no se muestran
- Verifica que el servidor esté sirviendo archivos desde `/storage`
- Revisa los logs en `storage/logs/laravel.log`

## Próximas Mejoras

- [ ] Progress bar para carga de archivos
- [ ] Vista previa de imágenes
- [ ] Compresión automática
- [ ] Validaciones por tipo MIME específico
- [ ] Integración con servicios cloud (S3, etc.)
