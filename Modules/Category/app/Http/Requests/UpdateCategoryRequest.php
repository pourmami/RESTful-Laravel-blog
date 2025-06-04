<?php

namespace Modules\Category\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $categoryId = $this->route('id');
        return [
            'name'      => ['sometimes', 'required', 'string', 'max:255'],
            'slug'      => ['sometimes', 'required', 'string', 'max:255', Rule::unique('categories')->ignore($categoryId)],
            'parent_id' => ['nullable', 'exists:categories,id']
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
