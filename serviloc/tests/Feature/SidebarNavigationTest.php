<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SidebarNavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_sidebar_links_point_to_existing_routes(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($user)->get('/customer/dashboard');

        $response->assertOk();
        $response->assertSee('href="/customer/requests"', false);
        $response->assertSee('href="/offers"', false);
        $response->assertSee('href="/customer/reviews"', false);
        $response->assertSee('href="/profile"', false);
    }
}
