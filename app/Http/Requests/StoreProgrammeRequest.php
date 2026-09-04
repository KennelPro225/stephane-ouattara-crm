<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class StoreProgrammeRequest extends UpdateProgrammeRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('programmes', 'slug')],
        ]);
    }
}
