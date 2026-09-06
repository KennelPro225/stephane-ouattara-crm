<?php

namespace App\Http\Requests;

use App\Models\AvailabilityRule;
use App\Models\Programme;
use App\Services\AvailabilityService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreProgrammeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** A programme that says nothing about scheduling simply doesn't occupy the agenda. */
    protected function prepareForValidation(): void
    {
        if (! $this->filled('schedule_mode')) {
            $this->merge(['schedule_mode' => 'none']);
        }
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
            'schedule_mode' => ['required', Rule::in(Programme::SCHEDULE_MODES)],
            'session_weekday' => ['nullable', 'integer', 'between:0,6'],
            'session_start_time' => ['nullable', 'date_format:H:i'],
            'session_duration_minutes' => ['nullable', 'integer', 'min:15', 'max:480'],
            'featured' => ['boolean'],
            'status' => ['required', Rule::in(Programme::STATUSES)],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ];
    }

    /**
     * A programme may only be scheduled inside the coach's own availability,
     * and may never land on top of another already-scheduled programme.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $mode = $this->input('schedule_mode');

            if ($mode === 'none') {
                return;
            }

            if (in_array($mode, Programme::WEEKLY_MODES, true) && ! $this->checkWeeklyFitsAvailability($validator, $mode)) {
                return;
            }

            $this->checkNoProgrammeOverlap($validator);
        });
    }

    /** True when the weekly session sits on an open day, inside its service hours. */
    private function checkWeeklyFitsAvailability(Validator $validator, string $mode): bool
    {
        $weekday = $this->input('session_weekday');

        if ($weekday === null || $weekday === '') {
            $validator->errors()->add('session_weekday', 'Choisissez le jour de la séance.');

            return false;
        }

        $rule = AvailabilityRule::where('weekday', (int) $weekday)->first();
        $label = AvailabilityRule::WEEKDAY_LABELS[(int) $weekday] ?? 'Ce jour';

        if (! $rule || ! $rule->is_open || ! $rule->start_time || ! $rule->end_time) {
            $validator->errors()->add('session_weekday', "Le coach est fermé le {$label}. Ouvrez ce jour dans Disponibilités ou choisissez-en un autre.");

            return false;
        }

        if ($mode !== 'weekly') {
            return true;
        }

        $start = $this->input('session_start_time');
        $duration = $this->input('session_duration_minutes');

        if (! $start) {
            $validator->errors()->add('session_start_time', 'Indiquez l’heure de début de la séance.');

            return false;
        }

        if (! $duration) {
            $validator->errors()->add('session_duration_minutes', 'Indiquez la durée de la séance.');

            return false;
        }

        $opens = Carbon::parse(substr($rule->start_time, 0, 5));
        $closes = Carbon::parse(substr($rule->end_time, 0, 5));
        $sessionStart = Carbon::parse($start);
        $sessionEnd = $sessionStart->copy()->addMinutes((int) $duration);

        if ($sessionStart->lt($opens) || $sessionEnd->gt($closes)) {
            $hours = $opens->format('H:i').' – '.$closes->format('H:i');
            $validator->errors()->add(
                'session_start_time',
                "La séance ({$sessionStart->format('H:i')} – {$sessionEnd->format('H:i')}) sort des heures de prestation du {$label} ({$hours})."
            );

            return false;
        }

        return true;
    }

    private function checkNoProgrammeOverlap(Validator $validator): void
    {
        $candidate = new Programme([
            'schedule_mode' => $this->input('schedule_mode'),
            'session_weekday' => $this->input('session_weekday'),
            'session_start_time' => $this->input('session_start_time'),
            'session_duration_minutes' => $this->input('session_duration_minutes'),
            'start_date' => $this->input('start_date'),
            'end_date' => $this->input('end_date'),
        ]);

        $conflict = app(AvailabilityService::class)
            ->findSchedulingConflict($candidate, $this->route('programme')?->id);

        if ($conflict) {
            $validator->errors()->add(
                'schedule_mode',
                "Ce créneau chevauche déjà « {$conflict->title} ». Choisissez un autre jour, une autre heure ou une autre période."
            );
        }
    }
}
