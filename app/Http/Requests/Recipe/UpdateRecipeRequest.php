<?php

namespace App\Http\Requests\Recipe;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRecipeRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'user_id' => ['sometimes', 'exists:users,id'],
            'category_id' => ['sometimes', 'exists:categories,id'],
            'preparation_time' => ['sometimes', 'integer', 'min:1'],
            'portion' => ['sometimes', 'integer', 'min:1'],
            'preparation_mode' => ['sometimes', 'string'],
            'ingredients' => ['sometimes', 'string'],
        ];

    }
}
