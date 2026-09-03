<?php

namespace Tests\Feature;

use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationWelcomeEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_users_receive_a_welcome_email(): void
    {
        $this->withoutMiddleware();
        Mail::fake();

        $response = $this->post(route('register'), [
            'name' => 'New User',
            'email' => 'new.user@example.com',
            'password' => 'N3wTodoAccount!2026Xq',
            'password_confirmation' => 'N3wTodoAccount!2026Xq',
        ])->assertSessionHasNoErrors();

        $verificationCode = null;

        Mail::assertSent(WelcomeMail::class, function (WelcomeMail $mail) use (&$verificationCode): bool {
            $verificationCode = $mail->verificationCode;

            return $mail->user->name === 'New User'
                && $mail->user->email === 'new.user@example.com'
                && preg_match('/^\d{6}$/', $mail->verificationCode) === 1
                && $mail->envelope()->from?->name === config('app.name');
        });

        $response->assertRedirect(route('email.verify.form', ['email' => 'new.user@example.com']));

        $this->post(route('email.verify'), [
            'email' => 'new.user@example.com',
            'code' => $verificationCode,
        ])->assertRedirect(route('login'));

        $this->assertNotNull(User::where('email', 'new.user@example.com')->firstOrFail()->email_verified_at);
    }

    public function test_unverified_users_are_sent_to_email_verification_before_signing_in(): void
    {
        $this->withoutMiddleware();
        Mail::fake();

        $user = User::factory()->unverified()->create([
            'email' => 'unverified@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('email.verify.form', ['email' => $user->email]));

        $this->assertGuest();
        Mail::assertSent(WelcomeMail::class);
    }

    public function test_application_uses_nepal_timezone(): void
    {
        $this->assertSame('Asia/Kathmandu', config('app.timezone'));
        $this->assertSame('Asia/Kathmandu', now()->getTimezone()->getName());
    }

    public function test_root_redirects_guests_to_login_and_users_to_todos(): void
    {
        $this->get('/')->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create())
            ->get('/')
            ->assertRedirect(route('todos.index'));
    }
}
