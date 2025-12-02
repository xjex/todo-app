<?php

namespace App\Repositories;

use App\Models\Todo;
use App\Repositories\Interfaces\TodoRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TodoRepository implements TodoRepositoryInterface
{
    /**
     * Get all todos for a user with filters
     */
    public function getAllForUser(int $userId, array $filters = []): Collection
    {
        $query = Todo::where('user_id', $userId);

        // Search functionality
        if (isset($filters['search']) && $filters['search']) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if (isset($filters['category']) && $filters['category']) {
            $query->where('category', $filters['category']);
        }

        // Filter by completion status
        if (isset($filters['completed']) && $filters['completed'] !== null) {
            $query->where('completed', filter_var($filters['completed'], FILTER_VALIDATE_BOOLEAN));
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        
        if ($sortBy === 'due_date') {
            $query->orderByRaw("due_date IS NULL, due_date {$sortOrder}");
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        return $query->get();
    }

    /**
     * Get a todo by ID
     */
    public function findById(int $id): ?Todo
    {
        return Todo::find($id);
    }

    /**
     * Create a new todo
     */
    public function create(array $data): Todo
    {
        return Todo::create($data);
    }

    /**
     * Update a todo
     */
    public function update(Todo $todo, array $data): bool
    {
        return $todo->update($data);
    }

    /**
     * Delete a todo
     */
    public function delete(Todo $todo): bool
    {
        return $todo->delete();
    }

    /**
     * Check if todo belongs to user
     */
    public function belongsToUser(Todo $todo, int $userId): bool
    {
        return $todo->user_id === $userId;
    }
}

