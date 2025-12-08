<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexTodoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'search' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:100',
            'completed' => 'nullable|boolean',
            'sort_by' => 'nullable|string|in:created_at,due_date,title',
            'sort_order' => 'nullable|string|in:asc,desc',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'completed' => $this->has('completed') ? filter_var($this->completed, FILTER_VALIDATE_BOOLEAN) : null,
        ]);
    }
}