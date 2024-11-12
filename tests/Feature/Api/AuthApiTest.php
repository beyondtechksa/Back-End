<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Address;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Hash;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $response = $this->post('/api/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'phone' => '123456789',
            'gender' => 'female'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'phone',
                    'name'
                ],
            ]);

        // Additional assertions to check the exact values
        $response->assertJson([
            'message' => 'user created successfully',
            'data' => [
                'phone' => '123456789',
                'name' => 'John Doe',
            ],
        ]);
    }


    public function test_user_registration_validation_errors()
    {
        // Assume phone '123456789' already exists in the database
        User::factory()->create(['phone' => '123456789']);

        $response = $this->post('/api/register', [
            'name' => '',
            'email' => '',
            'password' => 'password',
            'password_confirmation' => 'password',
            'phone' => '123456789',
            'gender' => 'female'
        ]);

        $response->assertStatus(400)
            ->assertJsonStructure([
                'errors' => [
                    'name',
                    'email',
                    'phone',
                ],
            ])
            ->assertJson([
                'errors' => [
                    'name' => ['The name field is required.'],
                    'email' => ['The email field is required.'],
                    'phone' => ['The phone has already been taken.'],
                ],
            ]);
    }

    public function test_user_can_login()
    {
        $user = \App\Models\User::factory()->create([
            'password' => bcrypt('password') // Ensure the password matches the login attempt
        ]);

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'name',
                    'token',
                ],
            ])
            ->assertJson([
                'message' => 'login successfully',
                'data' => [
                    'name' => $user->name,
                ],
            ]);

        $this->assertIsString($response->json('data.token'));
        $this->assertNotEmpty($response->json('data.token'));
    }

    public function test_user_cannot_login_with_wrong_password()
    {
        $user = \App\Models\User::factory()->create([
            'password' => bcrypt('correct-password') // Ensure the user has a known correct password
        ]);

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'wrong-password', // Incorrect password
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'error' => 'The provided credentials are incorrect.',
            ]);
    }


    public function test_successful_registration()
    {
        $response = $this->post('/api/register2Auth', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'uuid' => Str::uuid()->toString(),
            'phone' => '1234567890',
            'gender' => 'male',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'name',
                    'token',
                ],
            ])
            ->assertJson([
                'message' => 'login successfully',
                'data' => [
                    'name' => 'John Doe',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'gender' => 'male',
        ]);
    }

    public function test_validation_errors()
    {
        $response = $this->post('/api/register2Auth', [
            'name' => '',
            'email' => '',
            'uuid' => '',
            'phone' => '',
            'gender' => '',
        ]);

        $response->assertStatus(400)
            ->assertJsonStructure([
                'errors' => [
                    'name',
                    'email',
                    'uuid',
                    'phone',
                    'gender',
                ],
            ]);
    }

    public function test_incorrect_uuid()
    {
        $existingUser = User::factory()->create([
            'phone' => '1234567890',
            'uuid' => 'existing-uuid',
        ]);

        $response = $this->post('/api/register2Auth', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'uuid' => 'different-uuid',
            'phone' => '1234567890',
            'gender' => 'male',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'uuid' => ['Invalid uuid for your phone!'],
                ],
            ]);
    }


    public function test_user_can_view_profile()
    {
        $user = User::factory()->create([
            'avatar' => 'path/to/avatar.png',
        ]);

        // Authenticate the user
        Sanctum::actingAs($user, ['*']);

        // Send a request to get the profile
        $response = $this->get('/api/profile');

        // Check the response status and structure
        $response->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'name',
                'email',
                'phone',
                'gender',
                'email_verified_at',
                'avatar',
                'uuid',
                'created_at',
                'updated_at',
            ],
        ])->assertJson([
            'message' => 'success',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'gender' => $user->gender,
                'email_verified_at' => $user->email_verified_at->toISOString(), // Ensure it's in ISO format
                'avatar' => $user->avatar,
                'uuid' => $user->uuid,
                'created_at' => $user->created_at->toISOString(), // Ensure it's in ISO format
                'updated_at' => $user->updated_at->toISOString(), // Ensure it's in ISO format
            ],
        ]);
    }

    public function test_user_profile_with_default_avatar()
    {
        $user = User::factory()->create([
            'avatar' => null,
        ]);

        // Authenticate the user
        Sanctum::actingAs($user, ['*']);

        $response = $this->get('/api/profile');

        $response->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'name',
                'email',
                'phone',
                'gender',
                'email_verified_at',
                'avatar',
                'uuid',
                'created_at',
                'updated_at',
            ],
        ])->assertJson([
            'message' => 'success',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'gender' => $user->gender,
                'email_verified_at' => $user->email_verified_at->toISOString(), // Ensure it's in ISO format
                'avatar' => $user->avatar,
                'uuid' => $user->uuid,
                'created_at' => $user->created_at->toISOString(), // Ensure it's in ISO format
                'updated_at' => $user->updated_at->toISOString(), // Ensure it's in ISO format
            ],
        ]);
    }

    public function test_user_can_not_update_password_if_he_not_unauthenticated()
    {
        $user = User::factory()->create([
            'password' => Hash::make('old_password'),
        ]);

        $response = $this->actingAs($user, 'api')
            ->postJson(route('api.update.password'), [
                'old_password' => 'wrong_old_password',
                'password' => 'new_password',
                'password_confirmation' => 'new_password',
            ]);

        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Unauthenticated.'
            ]);
    }

    public function test_update_password_invalid_old_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('old_password'),
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson(route('api.update.password'), [
                'old_password' => 'wrong_old_password',
                'password' => 'new_password',
                'password_confirmation' => 'new_password',
            ]);

        $response->assertStatus(401)
            ->assertJson([
                'error' => 'Invalid old password',
            ]);
    }


    public function test_update_password_success()
    {
        // Create a user with a known password
        $user = User::factory()->create([
            'password' => Hash::make('old_password'),
        ]);

        // Authenticate the user using Sanctum
        Sanctum::actingAs($user, ['*']);

        // Make a POST request to update the password
        $response = $this->postJson(route('api.update.password'), [
            'old_password' => 'old_password',
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ]);

        // Assert HTTP status 200 and the JSON structure and content
        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'phone',
                    'gender',
                    'email_verified_at',
                    'avatar',
                    'uuid',
                    'created_at',
                    'updated_at',
                ],
            ])->assertJson([
                'message' => 'success',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'gender' => $user->gender,
                    'email_verified_at' => $user->email_verified_at ? $user->email_verified_at->toISOString() : null,
                    'avatar' => $user->avatar,
                    'uuid' => $user->uuid,
                    'created_at' => $user->created_at->toISOString(),
                    'updated_at' => $user->fresh()->updated_at->toISOString(), // Fetch fresh updated_at value
                ],
            ]);
    }
/*
    public function test_authenticated_user_can_add_to_cart()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->post('/api/add-to-cart', [
                'product_id' => $product->id,
                'quantity' => 2,
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
            ]);
    }

    public function test_authenticated_user_can_add_address()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->post('/api/addresses', [
                'address_line_1' => '123 Main St',
                'city' => 'Anytown',
                'state' => 'CA',
                'zip' => '12345',
                'country' => 'USA',
            ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'address_line_1',
                'city',
                'state',
                'zip',
                'country',
                'is_default',
            ]);
    }

    public function test_authenticated_user_can_place_order()
    {
        $user = User::factory()->create();
        $address = Address::factory()->create(['user_id' => $user->id]);
        $product = Product::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->post('/api/add-to-cart', [
                'product_id' => $product->id,
                'quantity' => 1,
            ]);

        $response = $this->actingAs($user, 'sanctum')
            ->post('/api/store-order', [
                'address_id' => $address->id,
                'payment_method' => 'credit_card',
            ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'user_id',
                'address_id',
                'payment_method',
                'total',
                'status',
            ]);
    }*/
}
