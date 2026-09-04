<?php

namespace App\Http\Requests;

use App\Models\Programme;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^[+0-9 ()\-.]{8,20}$/'],
            'company' => ['nullable', 'string', 'max:255'],
            'role' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'type' => ['required', Rule::in([
                'individual', 'club_des_champions', 'group', 'corporate_wellness', 'teambuilding', 'other',
            ])],
            'programme_id' => ['nullable', 'exists:programmes,id,status,published'],
            'preferred_date' => ['required', 'date', 'after_or_equal:today'],
            'preferred_time' => ['nullable', 'date_format:H:i'],
            'message' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Le numéro de téléphone n\'est pas valide.',
            'preferred_date.after_or_equal' => 'La date de session doit être aujourd\'hui ou plus tard.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $programmeId = $this->input('programme_id');
            if (! $programmeId) {
                return;
            }

            $programme = Programme::find($programmeId);
            if ($programme && $programme->available_seats <= 0) {
                $validator->errors()->add('programme_id', 'Ce programme est complet, merci de choisir une autre session.');
            }
        });
    }
}
