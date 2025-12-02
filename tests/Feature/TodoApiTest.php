<?php

namespace Tests\Feature;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    protected function authenticatedJson($method, $uri, array $data = [], array $headers = [])
    {
        return $this->json($method, $uri, $data, array_merge($headers, [
            'Authorization' => "Bearer {$this->token}",
        ]));
    }

    public function test_can_list_todos(): void
    {
        Todo::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->authenticatedJson('GET', '/api/todos');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         '*' => ['id', 'title', 'description', 'category', 'due_date', 'completed', 'created_at', 'updated_at']
                     ]
                 ]);
    }

    public function test_can_create_todo(): void
    {
        $todoData = [
            'title' => 'Test Todo',
            'description' => 'Test Description',
            'category' => 'Test',
            'due_date' => '2025-12-31',
        ];

        $response = $this->authenticatedJson('POST', '/api/todos', $todoData);

        $response->assertStatus(201)
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         'title' => 'Test Todo',
                         'description' => 'Test Description',
                         'category' => 'Test',
                     ]
                 ]);

        $this->assertDatabaseHas('todos', [
            'title' => 'Test Todo',
            'description' => 'Test Description',
        ]);
    }

    public function test_can_update_todo(): void
    {
        $todo = Todo::factory()->create(['user_id' => $this->user->id]);

        $updateData = [
            'title' => 'Updated Title',
            'completed' => true,
        ];

        $response = $this->authenticatedJson('PUT', "/api/todos/{$todo->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => [
                         'title' => 'Updated Title',
                         'completed' => true,
                     ]
                 ]);
    }

    public function test_can_delete_todo(): void
    {
        $todo = Todo::factory()->create(['user_id' => $this->user->id]);

        $response = $this->authenticatedJson('DELETE', "/api/todos/{$todo->id}");

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseMissing('todos', ['id' => $todo->id]);
    }

    public function test_can_toggle_todo(): void
    {
        $todo = Todo::factory()->create(['user_id' => $this->user->id, 'completed' => false]);

        $response = $this->authenticatedJson('PATCH', "/api/todos/{$todo->id}/toggle");

        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'data' => ['completed' => true]
                 ]);
    }

    public function test_validation_requires_title(): void
    {
        $response = $this->authenticatedJson('POST', '/api/todos', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['title']);
    }
}

