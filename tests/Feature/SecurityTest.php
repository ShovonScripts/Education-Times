<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_security_headers_are_present(): void
    {
        $this->get('/this-page-does-not-exist')
            ->assertNotFound()
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->assertHeader('Cross-Origin-Opener-Policy', 'same-origin')
            ->assertHeader('X-Permitted-Cross-Domain-Policies', 'none')
            ->assertHeader('Content-Security-Policy', "frame-ancestors 'self'; base-uri 'self'; form-action 'self'");
    }

    public function test_guest_is_redirected_from_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_guest_is_redirected_from_admin_dashboard(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/login');
    }

    public function test_non_admin_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get('/admin/dashboard')
            ->assertForbidden();
    }

    public function test_active_user_can_login(): void
    {
        $user = User::factory()->create(['password' => 'password']);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($user);
    }

    public function test_inactive_user_cannot_login(): void
    {
        $user = User::factory()->create([
            'password' => 'password',
            'is_active' => false,
        ]);

        $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect('/login')->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_non_admin_cannot_login_to_admin_area(): void
    {
        $user = User::factory()->create([
            'password' => 'password',
            'is_admin' => false,
        ]);

        $this->from('/admin/login')->post('/admin/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect('/admin/login')->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_inactive_admin_cannot_login_to_admin_area(): void
    {
        $user = User::factory()->create([
            'password' => 'password',
            'is_admin' => true,
            'is_active' => false,
        ]);

        $this->from('/admin/login')->post('/admin/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect('/admin/login')->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_article_body_is_sanitized_on_assignment(): void
    {
        $article = new Article();
        $article->body_bn = '<p>Hello</p><script>alert(1)</script><a href="javascript:alert(1)">x</a>';

        $this->assertStringNotContainsString('<script', $article->body_bn);
        $this->assertStringNotContainsString('javascript:', $article->body_bn);
        $this->assertStringContainsString('<p>Hello</p>', $article->body_bn);
    }
}
