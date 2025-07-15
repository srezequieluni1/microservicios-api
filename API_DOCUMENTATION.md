# API Documentation

## Estructura Estandarizada de Respuestas JSON

Todos los endpoints de la API siguen una estructura JSON estandarizada para mantener consistencia y facilitar el manejo de respuestas en el cliente.

### Respuestas Exitosas

```json
{
    "success": true,
    "data": {
        // Datos específicos del endpoint
    },
    "message": "Mensaje descriptivo del éxito"
}
```

### Respuestas de Error

```json
{
    "success": false,
    "message": "Mensaje descriptivo del error",
    "errors": {
        // Objeto con errores de validación específicos (opcional)
    }
}
```

## Endpoints Generales

### GET /api/ping
Verifica que la API esté funcionando correctamente.

**Respuesta Exitosa (200):**
```json
{
    "success": true,
    "data": {
        "status": "ok"
    },
    "message": "API is running correctly"
}
```

## Endpoints de Autenticación

### POST /api/register
Registra un nuevo usuario en el sistema.

**Request Body:**
```json
{
    "name": "string",
    "email": "string",
    "password": "string",
    "password_confirmation": "string"
}
```

**Respuesta Exitosa (201):**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "Juan Pérez",
            "email": "juan@example.com",
            "email_verified_at": null,
            "created_at": "2025-07-15T10:00:00.000000Z",
            "updated_at": "2025-07-15T10:00:00.000000Z"
        },
        "token": "1|abcdef123456...",
        "token_type": "Bearer"
    },
    "message": "User registered successfully. Please check your email to verify your account."
}
```

**Respuesta de Error (422):**
```json
{
    "success": false,
    "message": "Validation errors",
    "errors": {
        "email": ["The email has already been taken."],
        "password": ["The password confirmation does not match."]
    }
}
```

### POST /api/login
Autentica un usuario existente.

**Request Body:**
```json
{
    "email": "string",
    "password": "string"
}
```

**Respuesta Exitosa (200):**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "Juan Pérez",
            "email": "juan@example.com",
            "email_verified_at": "2025-07-15T10:00:00.000000Z",
            "created_at": "2025-07-15T10:00:00.000000Z",
            "updated_at": "2025-07-15T10:00:00.000000Z"
        },
        "token": "1|abcdef123456...",
        "token_type": "Bearer"
    },
    "message": "Login successful"
}
```

**Respuesta de Error (401):**
```json
{
    "success": false,
    "message": "The provided credentials are incorrect.",
    "errors": {
        "email": ["The provided credentials are incorrect."]
    }
}
```

**Respuesta de Error de Validación (422):**
```json
{
    "success": false,
    "message": "Validation errors",
    "errors": {
        "email": ["The email field is required."],
        "password": ["The password field is required."]
    }
}
```

### GET /api/email/verify/{id}/{hash}
Verifica el email de un usuario.

**Respuesta Exitosa (200):**
```json
{
    "success": true,
    "data": null,
    "message": "Email verified successfully"
}
```

**Respuesta si ya está verificado (200):**
```json
{
    "success": true,
    "data": null,
    "message": "Email already verified"
}
```

**Respuesta de Error (404):**
```json
{
    "success": false,
    "message": "User not found",
    "errors": null
}
```

**Respuesta de Error (400):**
```json
{
    "success": false,
    "message": "Invalid verification link",
    "errors": null
}
```

### POST /api/email/verification-notification
Reenvía el email de verificación.

**Headers requeridos:**
```
Authorization: Bearer {token}
```

**Respuesta Exitosa (200):**
```json
{
    "success": true,
    "data": null,
    "message": "Verification email sent"
}
```

**Respuesta si ya está verificado (200):**
```json
{
    "success": true,
    "data": null,
    "message": "Email already verified"
}
```

### POST /api/logout
Cierra la sesión del usuario actual.

**Headers requeridos:**
```
Authorization: Bearer {token}
```

**Respuesta Exitosa (200):**
```json
{
    "success": true,
    "data": null,
    "message": "Successfully logged out"
}
```

### GET /api/user
Obtiene los datos del usuario autenticado.

**Headers requeridos:**
```
Authorization: Bearer {token}
```

**Respuesta Exitosa (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "Juan Pérez",
        "email": "juan@example.com",
        "email_verified_at": "2025-07-15T10:00:00.000000Z",
        "created_at": "2025-07-15T10:00:00.000000Z",
        "updated_at": "2025-07-15T10:00:00.000000Z"
    },
    "message": "User data retrieved successfully"
}
```

## Códigos de Estado HTTP

- **200 OK**: Operación exitosa
- **201 Created**: Recurso creado exitosamente
- **400 Bad Request**: Solicitud inválida
- **401 Unauthorized**: Credenciales incorrectas
- **404 Not Found**: Recurso no encontrado
- **422 Unprocessable Entity**: Errores de validación

## Autenticación

La API utiliza Laravel Sanctum para la autenticación mediante tokens. Después de un login exitoso, incluye el token en el header de Authorization:

```
Authorization: Bearer {tu_token_aquí}
```

## Manejo de Errores

Todos los errores siguen la estructura estandarizada con `success: false`. Los errores de validación incluyen un objeto `errors` con detalles específicos de cada campo.

## Notas Importantes

1. Todos los endpoints devuelven JSON con la estructura estandarizada
2. Los tokens de autenticación no expiran automáticamente (configuración de Laravel Sanctum)
3. Las validaciones de email siguen el estándar RFC 5322
4. Las contraseñas deben tener al menos 8 caracteres
5. El registro de usuarios requiere confirmación de contraseña
