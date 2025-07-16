# Ejemplo de Prueba de Reset de Contraseña

## Usando curl para probar la API

### 1. Primero, crear un usuario para probar

```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### 2. Solicitar reset de contraseña

```bash
curl -X POST http://localhost:8000/api/password/forgot \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com"
  }'
```

**Respuesta esperada:**
```json
{
    "success": true,
    "data": null,
    "message": "Password reset link sent to your email address"
}
```

### 3. Verificar el email en los logs

Como estamos usando `MAIL_MAILER=log`, el email se guardará en `storage/logs/laravel.log`. Busca el token en los logs.

### 4. Resetear la contraseña

```bash
curl -X POST http://localhost:8000/api/password/reset \
  -H "Content-Type: application/json" \
  -d '{
    "token": "TOKEN_AQUI",
    "email": "test@example.com",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
  }'
```

**Respuesta esperada:**
```json
{
    "success": true,
    "data": null,
    "message": "Password has been reset successfully"
}
```

### 5. Probar login con la nueva contraseña

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "newpassword123"
  }'
```

## Usando Postman

1. **Crear usuario**: POST `http://localhost:8000/api/register`
2. **Solicitar reset**: POST `http://localhost:8000/api/password/forgot`
3. **Ver email en logs**: Revisar `storage/logs/laravel.log`
4. **Resetear contraseña**: POST `http://localhost:8000/api/password/reset`
5. **Login con nueva contraseña**: POST `http://localhost:8000/api/login`

## Probando Casos de Error

### Email inexistente
```bash
curl -X POST http://localhost:8000/api/password/forgot \
  -H "Content-Type: application/json" \
  -d '{
    "email": "noexiste@example.com"
  }'
```

### Token inválido
```bash
curl -X POST http://localhost:8000/api/password/reset \
  -H "Content-Type: application/json" \
  -d '{
    "token": "token-invalido",
    "email": "test@example.com",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
  }'
```

### Contraseñas no coinciden
```bash
curl -X POST http://localhost:8000/api/password/reset \
  -H "Content-Type: application/json" \
  -d '{
    "token": "TOKEN_VALIDO",
    "email": "test@example.com",
    "password": "newpassword123",
    "password_confirmation": "diferente123"
  }'
```
