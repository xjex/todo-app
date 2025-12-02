<?php

namespace App\Services;

use App\Models\Todo;
use App\Repositories\Interfaces\TodoRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;

class TodoService
{
    public function __construct(
        private TodoRepositoryInterface $todoRepository
    ) {}

    /**
     * Get all todos for authenticated user
     */
    public function getAllTodos(int $userId, array $filters = []): Collection
    {
        return $this->todoRepository->getAllForUser($userId, $filters);
    }

    /**
     * Get a todo by ID
     */
    public function getTodoById(int $id, int $userId): ?Todo
    {
        $todo = $this->todoRepository->findById($id);

        if (!$todo) {
            return null;
        }

        if (!$this->todoRepository->belongsToUser($todo, $userId)) {
            throw new HttpResponseException(
                response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403)
            );
        }

        return $todo;
    }

    /**
     * Create a new todo
     */
    public function createTodo(array $data, int $userId): Todo
    {
        $validator = Validator::make($data, [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'due_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            throw new HttpResponseException(
                response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], 422)
            );
        }

        $data['user_id'] = $userId;
        $data['completed'] = $data['completed'] ?? false;

        return $this->todoRepository->create($data);
    }

    /**
     * Update a todo
     */
    public function updateTodo(int $id, array $data, int $userId): Todo
    {
        $todo = $this->getTodoById($id, $userId);

        $validator = Validator::make($data, [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'due_date' => 'nullable|date',
            'completed' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            throw new HttpResponseException(
                response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], 422)
            );
        }

        $this->todoRepository->update($todo, $data);

        return $todo->fresh();
    }

    /**
     * Delete a todo
     */
    public function deleteTodo(int $id, int $userId): bool
    {
        $todo = $this->getTodoById($id, $userId);
        return $this->todoRepository->delete($todo);
    }

    /**
     * Toggle todo completion status
     */
    public function toggleTodo(int $id, int $userId): Todo
    {
        $todo = $this->getTodoById($id, $userId);
        
        $this->todoRepository->update($todo, [
            'completed' => !$todo->completed
        ]);

        return $todo->fresh();
    }
}

