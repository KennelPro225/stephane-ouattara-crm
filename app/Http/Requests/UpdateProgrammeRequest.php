<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProgrammeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAdmin();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'age_min' => ['required', 'integer', 'min:5', 'max:80'],
            'age_max' => ['required', 'integer', 'gte:age_min', 'max:100'],
            'type' => ['required', Rule::in(['individual', 'group', 'corporate', 'adolescents'])],
            'price' => ['required', 'numeric', 'min:0'],
            'max_participants' => ['required', 'integer', 'min:1', 'max:500'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'registration_deadline' => ['nullable', 'date', 'before_or_equal:start_date'],
            'session_time' => ['nullable', 'date_format:H:i'],
            'duration_hours' => ['nullable', 'integer', 'min:1', 'max:12'],
            'location' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'featured' => ['boolean'],
            'status' => ['required', Rule::in(['draft', 'published', 'archived'])],
        ];
    }
}
