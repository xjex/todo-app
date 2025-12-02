<?php

namespace App\Repositories\Interfaces;

use App\Models\Todo;
use Illuminate\Database\Eloquent\Collection;

interface TodoRepositoryInterface
{
    /**
     * Get all todos for a user with filters
     */
    public function getAllForUser(int $userId, array $filters = []): Collection;

    /**
     * Get a todo by ID
     */
    public function findById(int $id): ?Todo;

    /**
     * Create a new todo
     */
    public function create(array $data): Todo;

    /**
     * Update a todo
     */
    public function update(Todo $todo, array $data): bool;

    /**
     * Delete a todo
     */
    public function delete(Todo $todo): bool;

    /**
     * Check if todo belongs to user
     */
    public function belongsToUser(Todo $todo, int $userId): bool;
}

