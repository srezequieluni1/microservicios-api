# Microservicios API - Base para Proyectos Backend

## Descripción

Este proyecto tiene como objetivo ser la **base fundamental para múltiples proyectos de backend de microservicios**. Ha sido diseñado específicamente como una plataforma **didáctica y profesional** para que estudiantes puedan utilizarla como punto de partida para sus proyectos personales y académicos.

La API está construida con **Laravel 12** y PHP 8.2+, siguiendo las mejores prácticas de desarrollo backend moderno. Incluye todas las funcionalidades esenciales que necesita un proyecto profesional, desde autenticación hasta manejo de archivos y notificaciones por email.

## Características y Funcionalidades

### Sistema de Autenticación Completo
- **Registro de usuarios** con validación de datos
- **Autenticación Bearer Token** usando Laravel Sanctum
- **Verificación por email** con enlaces firmados
- **Reset de contraseñas** con tokens seguros
- **Logout seguro** con revocación de tokens

### Sistema de Notificaciones
- **Emails personalizados** con tema corporativo
- **Verificación de cuenta** por email
- **Recuperación de contraseña** por email
- **Configuración para Mailtrap** (ideal para desarrollo)

### Sistema de Archivos
- **Upload de archivos** con validación de tipos y tamaños
- **Descarga de archivos** con control de acceso
- **Gestión de archivos** (listado, eliminación)
- **Almacenamiento local** configurado

### Herramientas de Desarrollo
- **Pruebas automatizadas** con PestPHP
- **Cliente API genérico** para probar endpoints
- **Documentación interactiva** incluida
- **Logs detallados** para debugging

### Características Técnicas
- **API RESTful** con respuestas JSON consistentes
- **Validación robusta** en todas las entradas
- **Manejo de errores** centralizado
- **Middleware de autenticación** configurado
- **Base de datos SQLite** (fácil setup)

## Requisitos del Sistema

Antes de instalar el proyecto, asegúrate de tener instalado en tu computadora:

### Herramientas Necesarias

1. **PHP 8.2 o superior**
   - Descarga desde: https://www.php.net/downloads
   - Verifica la instalación: `php --version`

2. **Composer (Gestor de dependencias de PHP)**
   - Descarga desde: https://getcomposer.org/download/
   - Verifica la instalación: `composer --version`

3. **Git**
   - Descarga desde: https://git-scm.com/downloads
   - Verifica la instalación: `git --version`

4. **Un editor de código** (recomendado: VS Code)

## Instalación en Windows - Guía Detallada

### Paso 1: Instalar PHP en Windows

1. **Descargar PHP**:
   - Ve a https://windows.php.net/download/
   - Descarga la versión "Non Thread Safe" de PHP 8.2 o superior
   - Extrae el archivo ZIP en `C:\php`

2. **Configurar PHP**:
   - Agrega `C:\php` a la variable de entorno PATH de Windows
   - Copia `php.ini-development` y renómbralo a `php.ini`
   - Edita `php.ini` y descomenta las siguientes extensiones:
     ```ini
     extension=openssl
     extension=pdo_sqlite
     extension=sqlite3
     extension=curl
     extension=mbstring
     extension=fileinfo
     ```

3. **Verificar instalación**:
   ```powershell
   php --version
   ```

### Paso 2: Instalar Composer en Windows

1. **Descargar Composer**:
   - Ve a https://getcomposer.org/download/
   - Descarga e instala `Composer-Setup.exe`
   - El instalador configurará automáticamente PHP y las variables de entorno

2. **Verificar instalación**:
   ```powershell
   composer --version
   ```

### Paso 3: Instalar Git en Windows

1. **Descargar Git**:
   - Ve a https://git-scm.com/download/win
   - Descarga e instala la versión más reciente
   - Durante la instalación, selecciona "Use Git from the Windows Command Prompt"

2. **Verificar instalación**:
   ```powershell
   git --version
   ```

### Paso 4: Configurar PowerShell

1. **Abrir PowerShell como Administrador**
2. **Habilitar la ejecución de scripts** (si es necesario):
   ```powershell
   Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
   ```

## Instalación Local - Guía Paso a Paso

### Paso 1: Clonar el Repositorio
```powershell
git clone https://github.com/eormeno-idei/microservicios-api.git
cd microservicios-api
```

### Paso 2: Instalar Dependencias
```powershell
composer install
```

### Paso 3: Configurar Variables de Entorno
```powershell
# Copia el archivo de ejemplo (Windows)
copy .env.example .env

# Genera la clave de aplicación
php artisan key:generate
```

### Paso 4: Configurar Base de Datos
```powershell
# Crear la base de datos SQLite
php artisan migrate

# Opcional: Poblar con datos de prueba
php artisan db:seed
```

### Paso 5: Ejecutar el Servidor
```powershell
php artisan serve
```

Listo! Tu API estará disponible en: http://127.0.0.1:8000

### Comandos Adicionales para Windows

#### Limpiar caché y configuración
```powershell
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

#### Verificar el estado del proyecto
```powershell
php artisan about
```

#### Crear enlace simbólico para storage (si es necesario)
```powershell
php artisan storage:link
```

## Configuración de Mailtrap (Recomendado para Estudiantes)

Para probar las funcionalidades de email (verificación, reset de contraseña), te recomendamos usar **Mailtrap**:

### Paso 1: Crear Cuenta en Mailtrap
1. Ve a https://mailtrap.io/
2. Crea una cuenta gratuita
3. Inicia sesión en tu dashboard

### Paso 2: Obtener Credenciales
1. Ve a **Email Testing** > **Inboxes**
2. Crea un nuevo inbox o selecciona uno existente
3. Ve a la pestaña **SMTP Settings**
4. Selecciona **Laravel 9+** en el dropdown
5. Copia las credenciales mostradas

### Paso 3: Configurar en .env
Abre tu archivo `.env` y actualiza estas variables:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu_usuario_mailtrap
MAIL_PASSWORD=tu_password_mailtrap
MAIL_FROM_ADDRESS="noreply@tuproyecto.com"
MAIL_FROM_NAME="Tu Proyecto"
```

> **Nota**: Reemplaza `tu_usuario_mailtrap` y `tu_password_mailtrap` con las credenciales reales de Mailtrap.

## Configuración del Usuario Administrador

Para tener acceso completo a la API, necesitas configurar un usuario administrador:

### Opción 1: Configurar en .env
Agrega estas variables a tu archivo `.env`:

```env
ADMIN_NAME="Administrador"
ADMIN_EMAIL="admin@tuproyecto.com"
ADMIN_PASSWORD="tu_password_seguro"
```

### Opción 2: Crear Manualmente
```powershell
# Ejecuta el seeder para crear el usuario admin
php artisan db:seed --class=AdminUserSeeder
```

## Probando la API

### Cliente Web Incluido
El proyecto incluye un cliente web para probar todos los endpoints:

1. Ve a: http://127.0.0.1:8000
2. Usa el cliente interactivo para probar las funcionalidades

### Endpoints Principales
- **POST** `/api/register` - Registrar usuario
- **POST** `/api/login` - Iniciar sesión
- **POST** `/api/logout` - Cerrar sesión
- **GET** `/api/user` - Obtener usuario autenticado
- **POST** `/api/password/forgot` - Solicitar reset de contraseña
- **POST** `/api/password/reset` - Resetear contraseña
- **GET** `/api/email/verify/{id}/{hash}` - Verificar email
- **POST** `/api/files/upload` - Subir archivo
- **GET** `/api/files` - Listar archivos

### Ejecutar Pruebas
```powershell
# Ejecutar todas las pruebas
php artisan test

# Ejecutar pruebas específicas
php artisan test --filter AuthTest

# Ejecutar pruebas con cobertura (requiere Xdebug)
php artisan test --coverage
```

## Documentación Adicional

El proyecto incluye documentación detallada:

- `API_COMPLETE_DOCUMENTATION.md` - Documentación completa de la API
- `IMPLEMENTATION_COMPLETE_SUMMARY.md` - Resumen de implementaciones
- `EMAIL_CUSTOMIZATION_GUIDE.md` - Guía de personalización de emails
- `FILE_UPLOAD_EXAMPLES.md` - Ejemplos de subida de archivos
- `TECHNICAL_COMPONENTS_README.md` - Componentes técnicos

## Para Estudiantes

Este proyecto está diseñado específicamente para:

- **Aprender desarrollo backend** con tecnologías modernas
- **Entender arquitecturas de microservicios**
- **Practicar APIs RESTful**
- **Implementar autenticación y autorización**
- **Trabajar con bases de datos y migraciones**
- **Manejar testing automatizado**

### Sugerencias para Proyectos Personales
1. **Modifica las entidades** según tu dominio de negocio
2. **Agrega nuevos endpoints** para tus funcionalidades
3. **Personaliza los emails** con tu marca
4. **Implementa nuevas validaciones**
5. **Extiende el sistema de archivos**

## Solución de Problemas

### Problemas Comunes en Windows

#### Error: "php: command not found"
```powershell
# Verifica que PHP esté en el PATH
echo $env:PATH
# Agrega PHP al PATH si no está presente
$env:PATH += ";C:\php"
```

#### Error: "composer: command not found"
```powershell
# Reinstala Composer desde https://getcomposer.org/download/
# O verifica que esté en el PATH
composer --version
```

#### Error: "Extension not found"
```powershell
# Verifica que las extensiones estén habilitadas en php.ini
php -m | Select-String "openssl|pdo_sqlite|sqlite3"
```

#### Error: "Permission denied" en Windows
```powershell
# Ejecuta PowerShell como Administrador
# Verifica permisos de carpetas storage y bootstrap/cache
icacls storage /grant Everyone:(OI)(CI)F
icacls bootstrap\cache /grant Everyone:(OI)(CI)F
```

### Errores Generales

#### Error: "Class not found"
```bash
composer dump-autoload
```

#### Error: "Key not found"
```bash
php artisan key:generate
```

#### Error: "Database not found"
```bash
php artisan migrate:fresh
```

#### Error: "Port already in use"
```powershell
# Cambiar puerto del servidor
php artisan serve --port=8001
```

#### Error: "SQLite database locked"
```powershell
# Cierra todas las conexiones y reinicia
php artisan migrate:fresh --seed
```

## Soporte

Si tienes preguntas o encuentras problemas:

1. Revisa la documentación en el repositorio
2. Consulta los logs en `storage/logs/`
3. Verifica tu configuración en `.env`
4. Ejecuta las pruebas para verificar el funcionamiento

## Licencia

Este proyecto está bajo la licencia MIT. Puedes usarlo libremente para tus proyectos personales y académicos.

---

**¡Feliz codificación!**

> Proyecto diseñado para estudiantes por estudiantes. Úsalo como base para crear proyectos increíbles.
