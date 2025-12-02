<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Todo::where('user_id', $request->user()->id);

        // Search functionality
        if ($request->has('search') && $request->get('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('category') && $request->get('category')) {
            $query->where('category', $request->get('category'));
        }

        // Filter by completion status
        if ($request->has('completed') && $request->get('completed') !== null) {
            $query->where('completed', filter_var($request->get('completed'), FILTER_VALIDATE_BOOLEAN));
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if ($sortBy === 'due_date') {
            $query->orderByRaw("due_date IS NULL, due_date {$sortOrder}");
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $todos = $query->get();

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
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'due_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $todo = Todo::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'due_date' => $request->due_date,
            'completed' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Todo created successfully',
            'data' => $todo
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Todo $todo): JsonResponse
    {
        if ($todo->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $todo
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Todo $todo): JsonResponse
    {
        if ($todo->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'due_date' => 'nullable|date',
            'completed' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $todo->update($request->only([
            'title',
            'description',
            'category',
            'due_date',
            'completed'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Todo updated successfully',
            'data' => $todo->fresh()
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Todo $todo): JsonResponse
    {
        if ($todo->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $todo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Todo deleted successfully'
        ], 200);
    }

    /**
     * Toggle completion status
     */
    public function toggle(Request $request, Todo $todo): JsonResponse
    {
        if ($todo->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $todo->update([
            'completed' => !$todo->completed
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Todo status updated',
            'data' => $todo->fresh()
        ], 200);
    }
}

