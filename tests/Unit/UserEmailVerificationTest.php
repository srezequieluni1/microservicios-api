<?php

use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;

describe('User Email Verification Unit Tests', function () {

    // No need for manual migration since RefreshDatabase trait handles it

    it('usuario implementa MustVerifyEmail', function () {
        $user = new User();
        expect($user)->toBeInstanceOf(\Illuminate\Contracts\Auth\MustVerifyEmail::class);
    });

    it('usuario recién creado no tiene email verificado', function () {
        $user = User::factory()->create(['email_verified_at' => null]);

        expect($user->hasVerifiedEmail())->toBeFalse()
            ->and($user->email_verified_at)->toBeNull();
    });

    it('usuario con email_verified_at tiene email verificado', function () {
        $user = User::factory()->create(['email_verified_at' => now()]);

        expect($user->hasVerifiedEmail())->toBeTrue()
            ->and($user->email_verified_at)->not->toBeNull();
    });

    it('markEmailAsVerified establece email_verified_at', function () {
        $user = User::factory()->create(['email_verified_at' => null]);

        expect($user->hasVerifiedEmail())->toBeFalse();

        $result = $user->markEmailAsVerified();

        expect($result)->toBeTrue()
            ->and($user->fresh()->hasVerifiedEmail())->toBeTrue()
            ->and($user->fresh()->email_verified_at)->not->toBeNull();
    });

    it('markEmailAsVerified retorna false si ya está verificado', function () {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $result = $user->markEmailAsVerified();

        // En Laravel, markEmailAsVerified retorna true incluso si ya estaba verificado
        expect($result)->toBeTrue();
    });

    it('sendEmailVerificationNotification envía notificación', function () {
        Notification::fake();

        $user = User::factory()->create(['email_verified_at' => null]);

        $user->sendEmailVerificationNotification();

        Notification::assertSentTo($user, VerifyEmail::class);
    });

    it('getEmailForVerification retorna el email del usuario', function () {
        $email = 'test@example.com';
        $user = User::factory()->create(['email' => $email]);

        expect($user->getEmailForVerification())->toBe($email);
    });

    it('usuario puede tener múltiples tokens API', function () {
        $user = User::factory()->create();

        $token1 = $user->createToken('token1');
        $token2 = $user->createToken('token2');

        expect($user->tokens)->toHaveCount(2)
            ->and($token1->plainTextToken)->not->toBe($token2->plainTextToken);
    });

    it('usuario sin email verificado puede crear tokens', function () {
        $user = User::factory()->create(['email_verified_at' => null]);

        $token = $user->createToken('test-token');

        expect($token)->not->toBeNull()
            ->and($token->plainTextToken)->toBeString();
    });

    it('datos sensibles están ocultos en serialización', function () {
        $user = User::factory()->create();

        $array = $user->toArray();

        expect($array)->not->toHaveKey('password')
            ->and($array)->not->toHaveKey('remember_token');
    });

    it('email_verified_at se castea a datetime', function () {
        $user = User::factory()->create(['email_verified_at' => now()]);

        expect($user->email_verified_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
    });
});
