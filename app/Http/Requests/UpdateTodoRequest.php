<?php

namespace App\Http\Requests;

class UpdateTodoRequest extends TodoRequest
{
    protected function additionalRules(): array
    {
        return [
            'completed' => ['sometimes', 'boolean'],
        ];
    }
}
