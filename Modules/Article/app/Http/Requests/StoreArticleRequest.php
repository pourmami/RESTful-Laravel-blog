<?php

namespace Modules\Article\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'max:255'],
            'slug'         => ['required', 'string', 'max:255', 'unique:articles,slug'],
            'body'         => ['required', 'string'],
            'excerpt'      => ['nullable', 'string'],
            'status'       => ['required', 'in:draft,published,scheduled'],
            'published_at' => ['nullable', 'date', 'after_or_equal:today'],
            'archived_at'  => ['nullable', 'date', 'after:published_at'],
            'category_id'  => ['required', 'exists:categories,id'],
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
