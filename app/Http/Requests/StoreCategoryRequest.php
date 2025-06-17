<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'category_ids' => 'sometimes|required|array|min:1',
            'category_ids.*' => 'integer|exists:categories,id',
        ];
    }
}
