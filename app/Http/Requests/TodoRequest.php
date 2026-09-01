<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

abstract class TodoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge($this->todoRules(), $this->additionalRules());
    }

    /**
     * Get the shared todo validation rules.
     *
     * @return array<string, array<int, ValidationRule|string>>
     */
    protected function todoRules(): array
    {
        return [
            'title' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:300'],
            'due_date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:today'],
            'priority' => ['required', 'in:low,medium,high'],
        ];
    }

    /**
     * Get rules specific to a todo request type.
     *
     * @return array<string, array<int, ValidationRule|string>>
     */
    protected function additionalRules(): array
    {
        return [];
    }
}
