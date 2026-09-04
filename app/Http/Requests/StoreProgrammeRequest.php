<?php

namespace App\Http\Requests;

use App\Models\Programme;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProgrammeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'audience' => ['required', Rule::in(Programme::AUDIENCES)],
            'type' => ['required', Rule::in(Programme::TYPES)],
            'level' => ['nullable', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:5000'],
            'price_amount' => ['nullable', 'numeric', 'min:0'],
            'price_label' => ['required', 'string', 'max:100'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'registration_deadline' => ['nullable', 'date', 'before_or_equal:start_date'],
            'duration_label' => ['required', 'string', 'max:100'],
            'ages_label' => ['required', 'string', 'max:100'],
            'max_participants' => ['required', 'integer', 'min:1'],
            'featured' => ['boolean'],
            'status' => ['required', Rule::in(Programme::STATUSES)],
        ];
    }
}
