<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ChangePasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_change_their_password_with_valid_current_password(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('OldPassword!23'),
        ]);

        $this->actingAs($user)
            ->from(route('todos.index'))
            ->post(route('password.change'), [
                'current_password' => 'OldPassword!23',
                'password' => 'NewPassword!23',
                'password_confirmation' => 'NewPassword!23',
            ])
            ->assertRedirect(route('todos.index'))
            ->assertSessionHas('status', 'Password changed successfully.');

        $user->refresh();

        $this->assertTrue(Hash::check('NewPassword!23', $user->password));
        $this->assertFalse(Hash::check('OldPassword!23', $user->password));
    }

    public function test_current_password_must_be_valid_when_changing_password(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('OldPassword!23'),
        ]);

        $this->actingAs($user)
            ->from(route('password.change.form'))
            ->post(route('password.change'), [
                'current_password' => 'WrongPassword!23',
                'password' => 'NewPassword!23',
                'password_confirmation' => 'NewPassword!23',
            ])
            ->assertSessionHasErrors('current_password');
    }
}
