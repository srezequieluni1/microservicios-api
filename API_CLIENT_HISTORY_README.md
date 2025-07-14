# 📚 Funcionalidad de Historial de Consultas API

## 🎯 **Descripción General**

La funcionalidad de historial permite a los usuarios guardar, cargar y gestionar consultas API previas, mejorando significativamente la productividad y la reutilización de peticiones.

## ✨ **Características Principales**

### 🏷️ **Nombres de Consulta**
- **Auto-generación**: Si no se proporciona un nombre, se genera automáticamente basado en el método HTTP y la URL
- **Formato automático**: `GET /api/users`, `POST /api/login (username)`, etc.
- **Personalización**: Los usuarios pueden sobrescribir el nombre auto-generado
- **Límite de caracteres**: Máximo 100 caracteres para evitar nombres excesivamente largos

### 📋 **Lista de Historial**
- **Ordenamiento**: Las consultas se ordenan por fecha, mostrando la más reciente primero
- **Formato de visualización**: `Nombre (DD/MM/AAAA HH:MM)`
- **Selección fácil**: Dropdown que muestra todas las consultas guardadas
- **Carga automática**: Al seleccionar una consulta, todos los campos se rellenan automáticamente

### 💾 **Persistencia en LocalStorage**
- **Almacenamiento local**: Los datos se guardan en el navegador del usuario
- **Estructura JSON**: Cada consulta incluye:
  ```json
  {
    "name": "Nombre de la consulta",
    "method": "GET|POST|PUT|DELETE|PATCH",
    "url": "https://api.ejemplo.com/endpoint",
    "headers": "JSON string de headers",
    "body": "JSON string del cuerpo",
    "timestamp": 1234567890123
  }
  ```

### 🔄 **Auto-guardado**
- **Guardado en envío**: Cada vez que se ejecuta una consulta, se guarda automáticamente
- **Actualización en tiempo real**: Los cambios en campos se reflejan inmediatamente en el historial
- **Sincronización**: Los campos modificados se actualizan en la consulta activa

### 🗑️ **Gestión de Consultas**
- **Eliminación individual**: Botón de eliminar junto al dropdown
- **Confirmación**: Diálogo de confirmación antes de eliminar
- **Limpieza automática**: Se mantienen máximo 50 consultas (las más recientes)

## 🛠️ **Implementación Técnica**

### 📁 **Archivos Involucrados**

#### **Vista Blade**
```blade
<!-- resources/views/api-client.blade.php -->
- Campo de nombre de consulta
- Dropdown de historial
- Botón de eliminar
```

#### **CSS Modular**
```css
/* public/css/api-client.css */
- Estilos para campos de historial
- Animaciones de guardado
- Estados visuales

/* public/css/api-client-responsive.css */
- Adaptaciones móviles
- Grids responsivos
```

#### **JavaScript**
```javascript
/* public/js/api-client-history.js */
- Clase ApiHistoryManager
- Gestión de localStorage
- Auto-guardado y carga

/* public/js/api-client.js */
- Integración con historial
- Auto-generación de nombres
```

### 🔧 **Clase ApiHistoryManager**

#### **Métodos Principales**
- `saveCurrentQuery()`: Guarda la consulta actual
- `loadQuery(id)`: Carga una consulta específica
- `deleteSelectedQuery()`: Elimina la consulta seleccionada
- `updateCurrentQuery()`: Actualiza consulta en tiempo real
- `loadHistoryList()`: Refresca el dropdown

#### **Funciones de Utilidad**
- `generateQueryId()`: Crea IDs únicos
- `getCurrentFormData()`: Extrae datos del formulario
- `loadFormData()`: Llena el formulario
- `cleanupHistory()`: Mantiene límite de consultas

## 🎨 **Experiencia de Usuario**

### 🚀 **Flujo de Uso**

1. **Nueva Consulta**:
   - Usuario ingresa URL y método
   - Nombre se genera automáticamente
   - Usuario puede personalizar el nombre
   - Se ejecuta la consulta → Se guarda automáticamente

2. **Consulta Existente**:
   - Usuario selecciona del dropdown
   - Todos los campos se rellenan
   - Cualquier cambio actualiza la consulta guardada

3. **Gestión**:
   - Consultas se ordenan por fecha
   - Eliminar consultas no deseadas
   - Límite automático de 50 consultas

### 🎯 **Indicadores Visuales**

- **✓ Guardado**: Aparece brevemente cuando se guarda
- **🔄 Cambio**: Animación sutil cuando se modifican campos
- **🗑️ Eliminar**: Botón de eliminar con confirmación
- **📝 Auto-nombre**: Nombres generados automáticamente

## 🔧 **Configuración**

### ⚙️ **Parámetros Configurables**

```javascript
// En ApiHistoryManager constructor
this.storageKey = 'api_client_history';      // Clave localStorage
this.maxHistoryItems = 50;                   // Máximo de consultas
this.currentQueryId = null;                  // ID consulta activa
```

### 🎛️ **Personalización**

#### **Cambiar límite de historial**:
```javascript
// Modificar en api-client-history.js
this.maxHistoryItems = 100; // Aumentar a 100 consultas
```

#### **Personalizar formato de nombres**:
```javascript
// En generateAutoName() de api-client.js
let name = `${method} ${path} - ${new Date().toLocaleDateString()}`;
```

## 📊 **Características Avanzadas**

### 📤 **Exportar/Importar** (Funcionalidad adicional disponible)
```javascript
// Exportar historial
apiHistoryManager.exportHistory();

// Importar desde archivo
apiHistoryManager.importHistory(file);
```

### 📈 **Estadísticas** (Funcionalidad adicional disponible)
```javascript
// Obtener estadísticas de uso
const stats = apiHistoryManager.getStats();
console.log(stats);
// { total: 25, methods: { GET: 10, POST: 8, ... }, oldestQuery: ..., newestQuery: ... }
```

## 🔒 **Seguridad y Privacidad**

- **Local únicamente**: Los datos nunca salen del navegador
- **Sin información sensible**: Se recomienda no guardar tokens en headers
- **Limpieza automática**: Historial se mantiene dentro de límites razonables
- **Control del usuario**: Usuarios pueden eliminar consultas en cualquier momento

## 🚀 **Beneficios**

### 👥 **Para Desarrolladores**
- ✅ Reutilización rápida de consultas
- ✅ Menos tiempo configurando peticiones
- ✅ Historial ordenado y accesible
- ✅ Nombres descriptivos automáticos

### 🏢 **Para Equipos**
- ✅ Consultas comunes guardadas localmente
- ✅ Debugging más eficiente
- ✅ Pruebas repetibles
- ✅ Documentación implícita de endpoints

## 🔄 **Mantenimiento**

### 🧹 **Limpieza Automática**
- Se ejecuta cada vez que se guarda una consulta
- Mantiene solo las 50 consultas más recientes
- Elimina consultas más antiguas automáticamente

### 🛠️ **Debugging**
```javascript
// Verificar contenido del historial
console.log(localStorage.getItem('api_client_history'));

// Limpiar historial manualmente
localStorage.removeItem('api_client_history');

// Verificar instancia del manager
console.log(window.apiHistoryManager);
```

---

## 📞 **Soporte**

Esta funcionalidad está integrada con el sistema principal del API Client y sigue las mismas convenciones de código y estilo. Para modificaciones o extensiones, revisar los archivos JavaScript mencionados.
