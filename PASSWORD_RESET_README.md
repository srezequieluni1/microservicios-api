# Funcionalidad de Reset de Contraseña

Esta funcionalidad permite a los usuarios resetear su contraseña a través de un enlace enviado por email.

## Configuración

### Variables de Entorno

Asegúrate de tener configuradas las siguientes variables en tu archivo `.env`:

```bash
# URL del frontend donde se manejará el reset de contraseña
APP_FRONTEND_URL=http://localhost:8000

# Configuración de email
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email@example.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

## Endpoints API

### 1. Solicitar Reset de Contraseña

**POST** `/api/password/forgot`

Envía un enlace de reset de contraseña al email del usuario.

**Request Body:**
```json
{
    "email": "usuario@example.com"
}
```

**Respuesta Exitosa (200):**
```json
{
    "success": true,
    "data": null,
    "message": "Password reset link sent to your email address"
}
```

**Respuesta de Error (404):**
```json
{
    "success": false,
    "message": "We can't find a user with that email address.",
    "errors": {
        "email": ["We can't find a user with that email address."]
    }
}
```

### 2. Resetear Contraseña

**POST** `/api/password/reset`

Resetea la contraseña del usuario usando el token recibido por email.

**Request Body:**
```json
{
    "token": "token-recibido-por-email",
    "email": "usuario@example.com",
    "password": "nueva-contraseña",
    "password_confirmation": "nueva-contraseña"
}
```

**Respuesta Exitosa (200):**
```json
{
    "success": true,
    "data": null,
    "message": "Password has been reset successfully"
}
```

**Respuesta de Error (400):**
```json
{
    "success": false,
    "message": "Unable to reset password",
    "errors": {
        "email": ["This password reset token is invalid."]
    }
}
```

## Flujo de Trabajo

1. **Usuario solicita reset**: El usuario envía su email al endpoint `/api/password/forgot`
2. **Email enviado**: El sistema envía un email con un enlace que contiene un token
3. **Usuario hace clic**: El usuario hace clic en el enlace del email
4. **Frontend maneja reset**: El frontend extrae el token y email del URL y muestra un formulario
5. **Usuario resetea**: El usuario ingresa su nueva contraseña y confirma
6. **API resetea**: El frontend envía los datos al endpoint `/api/password/reset`
7. **Confirmación**: El usuario recibe confirmación del reset exitoso

## Estructura del Enlace de Reset

El enlace enviado por email tiene la siguiente estructura:

```
{APP_FRONTEND_URL}/reset-password?token={TOKEN}&email={EMAIL}
```

Ejemplo:
```
http://127.0.0.1:8000/reset-password?token=abc123...&email=usuario%40example.com
```

## Seguridad

- Los tokens expiran en 60 minutos
- Cada token solo puede usarse una vez
- Las contraseñas deben tener mínimo 8 caracteres
- Se requiere confirmación de contraseña
- El email debe estar codificado en la URL

## Testing

Para ejecutar las pruebas de la funcionalidad:

```bash
php artisan test --filter=PasswordResetTest
```

## Personalización del Email

El email de reset se envía usando la clase `App\Notifications\ResetPasswordNotification`. Puedes personalizar el contenido modificando el método `toMail()` en esta clase.

## Ejemplo de Implementación en Frontend

### JavaScript/React Ejemplo

```javascript
// Solicitar reset de contraseña
const forgotPassword = async (email) => {
    try {
        const response = await fetch('/api/password/forgot', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Reset link sent to your email');
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Error:', error);
    }
};

// Resetear contraseña
const resetPassword = async (token, email, password, password_confirmation) => {
    try {
        const response = await fetch('/api/password/reset', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                token,
                email,
                password,
                password_confirmation
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Password reset successfully');
            // Redirigir al login
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Error:', error);
    }
};

// Extraer parámetros del URL
const urlParams = new URLSearchParams(window.location.search);
const token = urlParams.get('token');
const email = urlParams.get('email');
```

## Troubleshooting

### Email no se envía
- Verifica la configuración SMTP en `.env`
- Revisa los logs en `storage/logs/laravel.log`
- Verifica que `MAIL_MAILER` esté configurado correctamente

### Token inválido
- Los tokens expiran en 60 minutos
- Cada token solo puede usarse una vez
- Verifica que el token no haya sido modificado en el URL

### Error de validación
- Verifica que la contraseña tenga mínimo 8 caracteres
- Asegúrate de que `password_confirmation` coincida con `password`
- Verifica que el email sea válido
