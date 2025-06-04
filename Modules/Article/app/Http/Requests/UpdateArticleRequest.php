<?php

namespace Modules\Article\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title'        => ['sometimes', 'required', 'string', 'max:255'],
            'slug'         => ['sometimes', 'required', 'string', 'unique:articles,slug,' . $this->article->id],
            'body'         => ['sometimes', 'required', 'string'],
            'excerpt'      => ['nullable', 'string'],
            'status'       => ['sometimes', 'required', 'in:draft,published,scheduled'],
            'published_at' => ['nullable', 'date', 'after_or_equal:today'],
            'archived_at'  => ['nullable', 'date', 'after:published_at'],
            'category_id'  => ['sometimes', 'exists:categories,id'],
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
