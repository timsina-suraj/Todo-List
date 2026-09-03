<?php

namespace Tests\Feature;

use App\Mail\OtpMail;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ForgotPasswordOtpFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_must_verify_otp_before_setting_new_password(): void
    {
        $this->withoutMiddleware();

        $user = User::factory()->create([
            'email' => 'user@example.com',
        ]);

        Mail::fake();

        $this->post(route('password.email'), [
            'email' => $user->email,
        ])->assertRedirect(route('password.verify.form', ['email' => $user->email]));

        $otp = Otp::where('email', $user->email)->firstOrFail();

        Mail::assertSent(OtpMail::class, function (OtpMail $mail) use ($otp): bool {
            return $mail->otp === $otp->otp
                && $mail->envelope()->from?->name === config('app.name');
        });

        $this->get(route('password.verify.form', ['email' => $user->email]))
            ->assertOk()
            ->assertSee('OTP (6 digits)')
            ->assertDontSee('New Password');

        $this->post(route('password.verify'), [
            'email' => $user->email,
            'otp' => $otp->otp,
        ])->assertRedirect(route('password.reset.form'));

        $this->get(route('password.reset.form'))
            ->assertOk()
            ->assertSee('New Password')
            ->assertDontSee('OTP (6 digits)');

        $this->post(route('password.reset'), [
            'email' => $user->email,
            'password' => 'NewPassword!23',
            'password_confirmation' => 'NewPassword!23',
        ])->assertRedirect(route('login'));

        $user->refresh();

        $this->assertTrue(Hash::check('NewPassword!23', $user->password));
    }
}
