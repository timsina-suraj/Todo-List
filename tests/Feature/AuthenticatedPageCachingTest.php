<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticatedPageCachingTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_pages_are_not_cacheable(): void
    {
        $response = $this->actingAs(User::factory()->create())
            ->get(route('todos.index'));

        $response->assertOk()
            ->assertHeader('Cache-Control', 'max-age=0, must-revalidate, no-cache, no-store, private')
            ->assertHeader('Pragma', 'no-cache')
            ->assertHeader('Expires', '0');
    }

    public function test_login_page_is_not_cacheable_after_authentication(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertHeader('Cache-Control', 'max-age=0, must-revalidate, no-cache, no-store, private')
            ->assertHeader('Pragma', 'no-cache')
            ->assertHeader('Expires', '0');
    }

    public function test_authenticated_users_are_redirected_from_login_to_todos(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('login'))
            ->assertRedirect(route('todos.index'));
    }

    public function test_logout_clears_session_and_prevents_cached_redirect(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $response = $this->actingAs(User::factory()->create())
            ->post(route('logout'));

        $response->assertRedirect(route('login'))
            ->assertHeader('Cache-Control', 'max-age=0, must-revalidate, no-cache, no-store, private')
            ->assertHeader('Pragma', 'no-cache')
            ->assertHeader('Expires', '0');

        $this->assertGuest();
    }
}
