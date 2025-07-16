# 📧 Guía Completa para Personalizar Plantillas de Email

## 🎨 Métodos de Personalización

### **Método 1: Personalización Básica (Recomendado para empezar)**

Modifica directamente el método `toMail()` en `ResetPasswordNotification.php`:

```php
public function toMail(object $notifiable): MailMessage
{
    $resetUrl = url(config('app.frontend_url', config('app.url')) . '/reset-password?token=' . $this->token . '&email=' . urlencode($notifiable->email));

    return (new MailMessage)
        ->subject('🔐 Restablecer Contraseña - ' . config('app.name'))
        ->greeting('¡Hola ' . $notifiable->name . '!')
        ->line('Recibimos una solicitud para restablecer la contraseña de tu cuenta.')
        ->line('Haz clic en el botón de abajo para crear una nueva contraseña:')
        ->action('🔄 Restablecer Contraseña', $resetUrl)
        ->line('**Este enlace expirará en 60 minutos por seguridad.**')
        ->line('Si no solicitaste este restablecimiento, puedes ignorar este email de forma segura.')
        ->salutation('Saludos,<br>El equipo de ' . config('app.name'))
        ->theme('custom'); // Usar tema personalizado
}
```

### **Método 2: Vista Blade Completamente Personalizada**

Para usar la plantilla `emails.reset-password.blade.php`:

```php
public function toMail(object $notifiable): MailMessage
{
    $resetUrl = url(config('app.frontend_url', config('app.url')) . '/reset-password?token=' . $this->token . '&email=' . urlencode($notifiable->email));

    return (new MailMessage)
        ->subject('🔐 Restablecer Contraseña - ' . config('app.name'))
        ->view('emails.reset-password', [
            'user' => $notifiable,
            'resetUrl' => $resetUrl,
            'token' => $this->token
        ]);
}
```

### **Método 3: Usar el Sistema de Temas de Laravel**

Cambiar el tema en la notificación:

```php
->theme('custom') // Usa custom.css
->theme('default') // Usa default.css
```

## 🎯 Opciones de Personalización

### **Elementos del MailMessage que puedes personalizar:**

```php
return (new MailMessage)
    ->subject('Tu asunto personalizado')           // Asunto del email
    ->greeting('¡Hola!')                          // Saludo
    ->line('Primera línea de texto')              // Líneas de contenido
    ->line('**Texto en negrita**')                // Markdown soportado
    ->action('Texto del Botón', $url)            // Botón de acción
    ->line('Texto después del botón')            // Más contenido
    ->salutation('Despedida personalizada')       // Despedida
    ->theme('custom')                             // Tema CSS
    ->from('custom@email.com', 'Nombre Custom')  // Email de origen
    ->replyTo('reply@email.com', 'Reply Name')   // Email de respuesta
    ->priority(1);                               // Prioridad (1-5)
```

### **Markdown soportado en `line()`:**

- `**negrita**` → **negrita**
- `*cursiva*` → *cursiva*
- `[enlace](url)` → enlace clickeable
- `# Título` → Encabezado
- `> Cita` → Texto citado

## 🎨 Personalizar Estilos CSS

### **Estructura de archivos CSS de email:**

```
resources/views/vendor/mail/html/themes/
├── default.css    # Tema original de Laravel
├── custom.css     # Tu tema personalizado
└── company.css    # Otro tema que puedes crear
```

### **Variables CSS importantes para personalizar:**

```css
/* Colores principales */
--primary-color: #667eea;
--secondary-color: #764ba2;
--text-color: #374151;
--background-color: #f8fafc;

/* Tipografía */
--font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
--font-size-base: 16px;
--line-height: 1.6;

/* Espaciado */
--padding-base: 20px;
--margin-base: 20px;
--border-radius: 8px;
```

## 🖼️ Agregar Logo y Imágenes

### **En vista Blade personalizada:**

```html
<div class="header">
    <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" class="logo">
    <h1>{{ config('app.name') }}</h1>
</div>
```

### **En MailMessage:**

```php
->line('<img src="' . asset('images/logo.png') . '" alt="Logo" style="max-width: 200px;">')
```

## 🌍 Configuración de Idioma

### **Crear archivos de traducción:**

```
resources/lang/es/
└── passwords.php    # Mensajes de contraseña en español
```

### **En el archivo `passwords.php`:**

```php
return [
    'reset' => 'Tu contraseña ha sido restablecida!',
    'sent' => 'Te hemos enviado el enlace de restablecimiento!',
    'throttled' => 'Por favor espera antes de intentar de nuevo.',
    'token' => 'Este token de restablecimiento es inválido.',
    'user' => "No encontramos un usuario con esa dirección de email.",
];
```

## 📱 Responsive Design

### **En tu CSS personalizado:**

```css
@media only screen and (max-width: 600px) {
    .container {
        width: 100% !important;
        margin: 10px !important;
    }
    
    .header, .content {
        padding: 20px !important;
    }
    
    .reset-button {
        width: 100% !important;
        display: block !important;
    }
}
```

## 🧪 Probar los Emails

### **1. Método de Log (Desarrollo):**

En `.env`:
```bash
MAIL_MAILER=log
```

Los emails se guardarán en `storage/logs/laravel.log`

### **2. Método Preview (Recomendado):**

Crear una ruta temporal:

```php
// En routes/web.php (solo para desarrollo)
Route::get('/email-preview', function () {
    $user = App\Models\User::first();
    $token = 'sample-token-123';
    
    return (new App\Notifications\ResetPasswordNotification($token))
        ->toMail($user);
});
```

### **3. Envío real:**

```bash
# Configurar SMTP real en .env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-app-password
MAIL_ENCRYPTION=tls
```

## 🎨 Ejemplos de Personalización

### **Tema Corporativo:**

```php
return (new MailMessage)
    ->subject('Restablecimiento de Contraseña - ' . config('app.name'))
    ->greeting('Estimado/a ' . $notifiable->name . ',')
    ->line('Se ha solicitado un restablecimiento de contraseña para su cuenta.')
    ->line('Por favor, haga clic en el siguiente enlace para proceder:')
    ->action('Restablecer Contraseña', $resetUrl)
    ->line('Este enlace expirará en 60 minutos.')
    ->line('Si usted no solicitó este cambio, ignore este mensaje.')
    ->salutation('Atentamente,<br>Equipo de Soporte<br>' . config('app.name'))
    ->theme('corporate');
```

### **Tema Casual:**

```php
return (new MailMessage)
    ->subject('🔑 ¡Oye! Resetea tu contraseña')
    ->greeting('¡Hola ' . $notifiable->name . '! 👋')
    ->line('¡Ups! ¿Olvidaste tu contraseña? ¡No te preocupes, nos pasa a todos! 😅')
    ->line('Haz clic aquí para crear una nueva:')
    ->action('✨ Crear Nueva Contraseña', $resetUrl)
    ->line('⏰ Tienes 60 minutos antes de que este enlace expire.')
    ->line('Si no fuiste tú, simplemente ignora este email. 🤷‍♀️')
    ->salutation('¡Que tengas un día genial! 🌟<br>El equipo de ' . config('app.name'))
    ->theme('fun');
```

## 📋 Checklist de Personalización

- [ ] Definir el tono de comunicación (formal/casual)
- [ ] Personalizar colores de marca
- [ ] Agregar logo de la empresa
- [ ] Configurar idioma apropiado
- [ ] Probar en diferentes clientes de email
- [ ] Verificar responsive design
- [ ] Configurar email de remitente personalizado
- [ ] Probar todos los escenarios (éxito/error)
- [ ] Configurar SMTP en producción
- [ ] Documentar cambios para el equipo

## 🚀 Comandos Útiles

```bash
# Publicar plantillas de email
php artisan vendor:publish --tag=laravel-mail

# Limpiar cache de vistas
php artisan view:clear

# Probar configuración de email
php artisan tinker
>>> Mail::raw('Test email', function($message) { $message->to('test@example.com')->subject('Test'); });

# Ver logs de email
tail -f storage/logs/laravel.log
```

¡Ahora tienes todo lo que necesitas para crear emails hermosos y personalizados! 🎨✨
