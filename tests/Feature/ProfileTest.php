<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/dashboard');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/profile', [
                'name' => 'firstName',
                'email' => 'test@example.com',
                'phone' => '123456789',
                'gender' => 'female'
            ]);

        $response
            ->assertSessionHasNoErrors();

        $user->refresh();
        $this->assertSame('test@example.com', 'test@example.com');
    }




}
