<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaginatedRequest extends FormRequest
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
            'pagination' => 'nullable|string|in:cursor,page,simple',
            'per_page' => 'nullable|integer|between:10,100'
        ];
    }

    /**
     * Paginate a given resource using the specified pagination strategy.
     *
     * Reads `pagination` and `per_page` from the current request input.
     * Defaults to cursor-based pagination with 35 items per page if not specified.
     *
     * @param  mixed  $resource  The query builder or Eloquent model to paginate.
     * @return \Illuminate\Contracts\Pagination\Paginator
     *
     * @example
     * // Cursor pagination (default)
     * GET /tracks
     *
     * // Page-based pagination
     * GET /tracks?pagination=page
     *
     * // Simple pagination
     * GET /tracks?pagination=simple
     *
     * // Custom page size
     * GET /tracks?per_page=50
     */
    function paginate(mixed $resource)
    {
        $pagination = $this->input('pagination');
        $perPage = $this->input('per_page') ?? 35;
        $latest = $resource->latest();

        return match ($pagination) {
            default => $latest->cursorPaginate($perPage)->withQueryString(),
            'page' => $latest->paginate($perPage)->withQueryString(),
            'simple' => $latest->simplePaginate($perPage)->withQueryString()
        };
    }
}
