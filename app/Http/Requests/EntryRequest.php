<?php

namespace App\Http\Requests;

use App\Enums\BowStyle;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EntryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'person_id'        => ['required', 'exists:people'],
            'bow_style'        => ['required', Rule::enum(BowStyle::class)],
            'initial_handicap' => ['nullable', 'integer', 'min:0', 'max:150'],
            'current_handicap' => ['nullable', 'integer', 'min:0', 'max:150', 'lte:initial_handicap'],
        ];
    }
}
