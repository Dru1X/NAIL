<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompetitionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'             => ['required', 'string', 'min:5', 'max:255'],
            'entries_open_at'  => ['required', 'date'],
            'entries_close_at' => ['required', 'date', 'after:entries_open_at'],
            'starts_at'        => ['required', 'date'],
            'ends_at'          => ['required', 'date', 'after:starts_at'],
        ];
    }
}
