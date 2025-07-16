# API Documentation

## Estructura Estandarizada de Respuestas JSON

Todos los endpoints de la API siguen una estructura JSON estandarizada para man**Respuesta Exitosa (201):**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "Juan Pérez",
            "email": "juan@example.com",
            "email_verified_at": null,
            "created_at": "2025-07-16T10:00:00.000000Z",
            "updated_at": "2025-07-16T10:00:00.000000Z",
            "attachments": []
        },
        "token": "1|abcdef123456...",
        "token_type": "Bearer",
        "type": "single"
    },
    "message": "User registered successfully"
} y facilitar el manejo de respuestas en el cliente.

### Respuestas Exitosas

#### Elemento Individual
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "Elemento",
        "attachments": [
            {
                "id": "file_123",
                "name": "document.pdf",
                "mime_type": "application/pdf",
                "size": 1048576,
                "url": "/api/files/file_123",
                "protected": true,
                "metadata": {
                    "display_type": "document",
                    "preview_url": "/api/files/file_123/preview",
                    "created_at": "2025-07-16T10:00:00Z"
                }
            }
        ],
        "type": "single"
    },
    "message": "Elemento obtenido exitosamente"
}
```

#### Lista de Elementos
```json
{
    "success": true,
    "data": {
        "items": [
            { /* elemento 1 */ },
            { /* elemento 2 */ }
        ],
        "count": 2,
        "type": "list"
    },
    "message": "Lista obtenida exitosamente"
}
```

#### Lista Paginada
```json
{
    "success": true,
    "data": {
        "items": [
            { /* elementos de la página actual */ }
        ],
        "pagination": {
            "current_page": 1,
            "per_page": 15,
            "total": 150,
            "total_pages": 10,
            "has_next": true,
            "has_previous": false,
            "next_page": 2,
            "previous_page": null,
            "first_page_url": "/api/users?page=1",
            "last_page_url": "/api/users?page=10",
            "next_page_url": "/api/users?page=2",
            "prev_page_url": null
        },
        "count": 15,
        "type": "paginated_list"
    },
    "message": "Página 1 obtenida exitosamente"
}
```

### Archivos Adjuntos (Attachments)

Los elementos pueden contener archivos adjuntos con la siguiente estructura:

```json
{
    "id": "unique_file_id",
    "name": "filename.ext",
    "mime_type": "image/jpeg",
    "size": 245760,
    "url": "/api/files/unique_file_id",
    "protected": true,
    "metadata": {
        "width": 400,
        "height": 400,
        "display_type": "avatar|thumbnail|document|inline_image",
        "thumbnail_url": "/api/files/unique_file_id/thumbnail",
        "preview_url": "/api/files/unique_file_id/preview",
        "alt_text": "Descripción del archivo",
        "created_at": "2025-07-16T10:00:00Z"
    }
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
            "email_verified_at": "2025-07-16T10:00:00.000000Z",
            "created_at": "2025-07-16T10:00:00.000000Z",
            "updated_at": "2025-07-16T10:00:00.000000Z",
            "attachments": [
                {
                    "id": "avatar_123",
                    "name": "profile.jpg",
                    "mime_type": "image/jpeg",
                    "size": 204800,
                    "url": "/api/files/avatar_123",
                    "protected": true,
                    "metadata": {
                        "width": 300,
                        "height": 300,
                        "display_type": "avatar",
                        "thumbnail_url": "/api/files/avatar_123/thumbnail",
                        "alt_text": "Avatar de Juan Pérez",
                        "created_at": "2025-07-16T10:00:00Z"
                    }
                }
            ]
        },
        "token": "1|abcdef123456...",
        "token_type": "Bearer",
        "type": "single"
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

## Endpoints de Usuarios Autenticados

### GET /api/user
Obtiene la información del usuario autenticado.

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
        "email_verified_at": "2025-07-16T10:00:00.000000Z",
        "created_at": "2025-07-16T10:00:00.000000Z",
        "updated_at": "2025-07-16T10:00:00.000000Z",
        "attachments": [
            {
                "id": "avatar_123",
                "name": "profile.jpg",
                "mime_type": "image/jpeg",
                "size": 204800,
                "url": "/api/files/avatar_123",
                "protected": true,
                "metadata": {
                    "width": 300,
                    "height": 300,
                    "display_type": "avatar",
                    "thumbnail_url": "/api/files/avatar_123/thumbnail",
                    "alt_text": "Avatar de Juan Pérez",
                    "created_at": "2025-07-16T10:00:00Z"
                }
            }
        ],
        "type": "single"
    },
    "message": "User data retrieved successfully"
}
```

### PUT /api/user/profile
Actualiza el perfil del usuario autenticado.

**Headers requeridos:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
    "name": "string (opcional)",
    "email": "string (opcional)"
}
```

**Respuesta Exitosa (200):**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "Juan Pérez Actualizado",
        "email": "juan.nuevo@example.com",
        "email_verified_at": null,
        "created_at": "2025-07-16T10:00:00.000000Z",
        "updated_at": "2025-07-16T10:30:00.000000Z",
        "attachments": [],
        "type": "single"
    },
    "message": "Profile updated successfully"
}
```

### POST /api/user/avatar
Sube o actualiza la imagen de perfil del usuario.

**Headers requeridos:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Request Body (Form Data):**
```
avatar: File (imagen JPG, PNG, WebP máximo 2MB)
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
            "attachments": [
                {
                    "id": "avatar_456",
                    "name": "new_profile.jpg",
                    "mime_type": "image/jpeg",
                    "size": 187392,
                    "url": "/api/files/avatar_456",
                    "protected": true,
                    "metadata": {
                        "width": 400,
                        "height": 400,
                        "display_type": "avatar",
                        "thumbnail_url": "/api/files/avatar_456/thumbnail",
                        "alt_text": "Avatar de Juan Pérez",
                        "created_at": "2025-07-16T11:00:00Z"
                    }
                }
            ]
        },
        "type": "single"
    },
    "message": "Avatar uploaded successfully"
}
```

### DELETE /api/user/avatar
Elimina la imagen de perfil del usuario.

**Headers requeridos:**
```
Authorization: Bearer {token}
```

**Respuesta Exitosa (200):**
```json
{
    "success": true,
    "data": {
        "message": "Avatar deleted successfully",
        "type": "single"
    },
    "message": "Avatar deleted successfully"
}
```

## Endpoints de Archivos

### GET /api/files/{id}
Descarga o muestra un archivo por su ID.

**Parámetros de Query (opcionales):**
- `download=true`: Fuerza descarga del archivo
- `thumbnail=true`: Obtiene versión thumbnail (si está disponible)

**Headers requeridos (para archivos protegidos):**
```
Authorization: Bearer {token}
```

**Respuesta Exitosa (200):**
- Content-Type: Según el tipo de archivo
- Content-Disposition: attachment; filename="nombre_archivo.ext" (si download=true)

### GET /api/users
Obtiene una lista paginada de usuarios (solo administradores).

**Headers requeridos:**
```
Authorization: Bearer {token}
```

**Parámetros de Query (opcionales):**
- `page`: Número de página (default: 1)
- `per_page`: Elementos por página (default: 15, máximo: 100)
- `search`: Término de búsqueda en nombre o email

**Respuesta Exitosa (200):**
```json
{
    "success": true,
    "data": {
        "items": [
            {
                "id": 1,
                "name": "Juan Pérez",
                "email": "juan@example.com",
                "email_verified_at": "2025-07-16T10:00:00.000000Z",
                "created_at": "2025-07-16T10:00:00.000000Z",
                "attachments": [
                    {
                        "id": "avatar_123",
                        "name": "profile.jpg",
                        "mime_type": "image/jpeg",
                        "size": 204800,
                        "url": "/api/files/avatar_123",
                        "protected": true,
                        "metadata": {
                            "display_type": "avatar",
                            "thumbnail_url": "/api/files/avatar_123/thumbnail"
                        }
                    }
                ]
            }
        ],
        "pagination": {
            "current_page": 1,
            "per_page": 15,
            "total": 150,
            "total_pages": 10,
            "has_next": true,
            "has_previous": false,
            "next_page": 2,
            "previous_page": null,
            "first_page_url": "/api/users?page=1",
            "last_page_url": "/api/users?page=10",
            "next_page_url": "/api/users?page=2",
            "prev_page_url": null
        },
        "count": 15,
        "type": "paginated_list"
    },
    "message": "Users retrieved successfully"
}
```

## Códigos de Estado HTTP

- **200 OK**: Operación exitosa
- **201 Created**: Recurso creado exitosamente
- **400 Bad Request**: Solicitud inválida
- **401 Unauthorized**: Credenciales incorrectas
- **403 Forbidden**: Sin permisos para acceder al recurso
- **404 Not Found**: Recurso no encontrado
- **413 Payload Too Large**: Archivo demasiado grande
- **422 Unprocessable Entity**: Errores de validación

## Autenticación

La API utiliza Laravel Sanctum para la autenticación mediante tokens. Después de un login exitoso, incluye el token en el header de Authorization:

```
Authorization: Bearer {tu_token_aquí}
```

## Manejo de Errores

Todos los errores siguen la estructura estandarizada con `success: false`. Los errores de validación incluyen un objeto `errors` con detalles específicos de cada campo.

## Manejo de Archivos

- **Tipos permitidos para avatares**: JPG, JPEG, PNG, WebP
- **Tamaño máximo**: 2MB por archivo
- **Archivos protegidos**: Requieren autenticación para acceder
- **Thumbnails**: Se generan automáticamente para imágenes
- **URLs de archivos**: Son relativas al dominio de la API

## Notas Importantes

1. Todos los endpoints devuelven JSON con la estructura estandarizada
2. Los tokens de autenticación no expiran automáticamente (configuración de Laravel Sanctum)
3. Las validaciones de email siguen el estándar RFC 5322
4. Las contraseñas deben tener al menos 8 caracteres
5. El registro de usuarios requiere confirmación de contraseña
6. Los archivos se almacenan de forma segura y se acceden mediante IDs únicos
7. Las imágenes de perfil se redimensionan automáticamente
8. Los archivos eliminados se marcan como eliminados pero no se borran físicamente inmediatamente
