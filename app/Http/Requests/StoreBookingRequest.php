<?php

namespace App\Http\Requests;

use App\Models\Programme;
use App\Services\AvailabilityService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'service' => ['required', Rule::in(array_keys(self::SERVICE_MAP))],
            'prenom' => ['required', 'string', 'max:100'],
            'nom' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'tel' => ['nullable', 'string', 'max:30'],
            'societe' => ['nullable', 'string', 'max:255'],
            'poste' => ['nullable', 'string', 'max:255'],
            'programme_id' => ['nullable', 'exists:programmes,id,status,published'],
            'date' => ['required', 'date', 'after_or_equal:today'],
            'heure' => ['nullable', 'date_format:H:i'],
            'message' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'service.required' => 'Choisissez un type de service.',
            'prenom.required' => 'Prénom requis.',
            'nom.required' => 'Nom requis.',
            'email.email' => 'Email invalide.',
            'date.required' => 'Choisissez une date.',
            'date.after_or_equal' => 'La date doit être aujourd’hui ou plus tard.',
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

        $validator->after(function (Validator $validator) {
            $date = $this->input('date');
            if (! $date || $validator->errors()->has('date')) {
                return;
            }

            $slots = app(AvailabilityService::class)->slotsForDate(Carbon::parse($date));

            // Closed day, or a day entirely taken by a full-day programme.
            if ($slots === []) {
                $validator->errors()->add('date', 'Le coach n’est pas disponible ce jour-là, merci de choisir une autre date.');

                return;
            }

            $heure = $this->input('heure');
            if ($heure && ! in_array($heure, $slots, true)) {
                $validator->errors()->add('heure', 'Ce créneau n’est plus disponible, merci d’en choisir un autre.');
            }
        });
    }

    public const SERVICE_MAP = [
        'Coaching individuel' => 'individual',
        'Club des Champions' => 'club_des_champions',
        'Coaching de groupe' => 'group',
        'Bien-être entreprise' => 'corporate_wellness',
        'Teambuilding' => 'teambuilding',
        'Autres' => 'other',
    ];
}
