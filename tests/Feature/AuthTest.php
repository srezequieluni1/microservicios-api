<?php

use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;

it('permite registrar y loguear un usuario', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'Juan Pérez',
        'email' => 'juan@example.com',
        'password' => 'secret123',
        'password_confirmation' => 'secret123',
    ]);

    $response->assertStatus(201);
    echo "\nRegistro OK";

    $response = $this->postJson('/api/login', [
        'email' => 'juan@example.com',
        'password' => 'secret123',
    ]);

    $response->assertStatus(200);
    expect($response->json())->toHaveKey('token');
    echo "\nLogin OK con token: " . $response->json('token');
});

it('envía email de verificación al registrar usuario', function () {
    Notification::fake();

    $response = $this->postJson('/api/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertStatus(201);
    expect($response->json())->toHaveKey('message')
        ->and($response->json('message'))->toContain('Please check your email');

    $user = User::where('email', 'test@example.com')->first();
    expect($user)->not->toBeNull()
        ->and($user->email_verified_at)->toBeNull();

    Notification::assertSentTo($user, VerifyEmail::class);
    echo "\nEmail de verificación enviado correctamente";
});

it('verifica email con enlace válido', function () {
    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    // Generar URL de verificación válida
    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        [
            'id' => $user->id,
            'hash' => sha1($user->email),
        ]
    );

    // Extraer el path de la URL para la prueba
    $path = parse_url($verificationUrl, PHP_URL_PATH);
    $query = parse_url($verificationUrl, PHP_URL_QUERY);

    $response = $this->get($path . '?' . $query);

    $response->assertStatus(200);
    expect($response->json('message'))->toBe('Email verified successfully');

    $user->refresh();
    expect($user->email_verified_at)->not->toBeNull();
    echo "\nEmail verificado correctamente";
});

it('rechaza verificación con enlace inválido', function () {
    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    // URL de verificación sin firma válida
    $response = $this->get("/api/email/verify/{$user->id}/invalid-hash");

    $response->assertStatus(403); // 403 porque el middleware 'signed' intercepta la petición
    // expect($response->json('message'))->toBe('Invalid verification link');

    $user->refresh();
    expect($user->email_verified_at)->toBeNull();
    echo "\nEnlace inválido rechazado correctamente";
});

it('maneja verificación de usuario inexistente', function () {
    $nonExistentId = 99999;

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        [
            'id' => $nonExistentId,
            'hash' => sha1('test@example.com'),
        ]
    );

    $path = parse_url($verificationUrl, PHP_URL_PATH);
    $query = parse_url($verificationUrl, PHP_URL_QUERY);

    $response = $this->get($path . '?' . $query);

    $response->assertStatus(404);
    expect($response->json('message'))->toBe('User not found');
    echo "\nUsuario inexistente manejado correctamente";
});

it('maneja email ya verificado', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        [
            'id' => $user->id,
            'hash' => sha1($user->email),
        ]
    );

    $path = parse_url($verificationUrl, PHP_URL_PATH);
    $query = parse_url($verificationUrl, PHP_URL_QUERY);

    $response = $this->get($path . '?' . $query);

    $response->assertStatus(200);
    expect($response->json('message'))->toBe('Email already verified');
    echo "\nEmail ya verificado manejado correctamente";
});

it('reenvía email de verificación para usuario autenticado', function () {
    Notification::fake();

    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->postJson('/api/email/resend');

    $response->assertStatus(200);
    expect($response->json('message'))->toBe('Verification email sent');

    Notification::assertSentTo($user, VerifyEmail::class);
    echo "\nEmail de verificación reenviado correctamente";
});

it('no reenvía email si ya está verificado', function () {
    Notification::fake();

    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->postJson('/api/email/resend');

    $response->assertStatus(200);
    expect($response->json('message'))->toBe('Email already verified');

    Notification::assertNotSentTo($user, VerifyEmail::class);
    echo "\nNo se reenvió email ya verificado";
});

it('requiere autenticación para reenviar email', function () {
    $response = $this->postJson('/api/email/resend');

    $response->assertStatus(401);
    echo "\nReenvío requiere autenticación correctamente";
});

it('enlace de verificación expira después del tiempo límite', function () {
    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    // Generar URL de verificación que ya expiró
    $expiredUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->subMinutes(10), // Expiró hace 10 minutos
        [
            'id' => $user->id,
            'hash' => sha1($user->email),
        ]
    );

    $path = parse_url($expiredUrl, PHP_URL_PATH);
    $query = parse_url($expiredUrl, PHP_URL_QUERY);

    $response = $this->get($path . '?' . $query);

    $response->assertStatus(403); // 403 porque el middleware 'signed' intercepta la petición expirada
    // expect($response->json('message'))->toBe('Invalid verification link');

    $user->refresh();
    expect($user->email_verified_at)->toBeNull();
    echo "\nEnlace expirado rechazado correctamente";
});
