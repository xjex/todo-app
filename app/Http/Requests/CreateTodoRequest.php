<?php

namespace App\Http\Requests;

class CreateTodoRequest extends TodoRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return parent::rules();
    }
}