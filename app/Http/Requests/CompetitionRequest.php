<?php

namespace App\Http\Requests;

use App\Enums\StageType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompetitionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'               => ['required', 'string', 'min:5', 'max:255'],
            'entries_open_on'    => ['required', 'date'],
            'entries_close_on'   => ['required', 'date', 'after:entries_open_on'],
            'stages'             => ['required', 'array'],
            'stages.*'           => ['array:id,type,starts_on,ends_on'],
            'stages.*.id'        => ['filled', 'exists:stages,id'],
            'stages.*.type'      => ['required', Rule::enum(StageType::class)],
            'stages.*.starts_on' => ['required', 'date'],
            'stages.*.ends_on'   => ['required', 'date', 'after_or_equal:stages.*.starts_on'],
        ];
    }
}
