<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\TodoService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TodoController extends Controller
{
    public function __construct(
        private TodoService $todoService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = [
            'search' => $request->get('search'),
            'category' => $request->get('category'),
            'completed' => $request->get('completed'),
            'sort_by' => $request->get('sort_by', 'created_at'),
            'sort_order' => $request->get('sort_order', 'desc'),
        ];

        $todos = $this->todoService->getAllTodos($request->user()->id, $filters);

        return response()->json([
            'success' => true,
            'data' => $todos
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $todo = $this->todoService->createTodo($request->all(), $request->user()->id);

        return response()->json([
            'success' => true,
            'message' => 'Todo created successfully',
            'data' => $todo
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $todo): JsonResponse
    {
        $todo = $this->todoService->getTodoById($todo, $request->user()->id);

        return response()->json([
            'success' => true,
            'data' => $todo
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $todo): JsonResponse
    {
        $todo = $this->todoService->updateTodo($todo, $request->all(), $request->user()->id);

        return response()->json([
            'success' => true,
            'message' => 'Todo updated successfully',
            'data' => $todo
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $todo): JsonResponse
    {
        $this->todoService->deleteTodo($todo, $request->user()->id);

        return response()->json([
            'success' => true,
            'message' => 'Todo deleted successfully'
        ], 200);
    }

    /**
     * Toggle completion status
     */
    public function toggle(Request $request, int $todo): JsonResponse
    {
        $todo = $this->todoService->toggleTodo($todo, $request->user()->id);

        return response()->json([
            'success' => true,
            'message' => 'Todo status updated',
            'data' => $todo
        ], 200);
    }
}

