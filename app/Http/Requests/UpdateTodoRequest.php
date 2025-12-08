<?php

namespace App\Http\Requests;

class UpdateTodoRequest extends TodoRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = parent::rules();
        
        // For update, due_date can be in the past
        $rules['due_date'] = 'nullable|date';
        
        return $rules;
    }
}