<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MatchResultRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'shot_at'                  => ['required', 'date'],
            'stage_id'                 => ['required', 'exists:stages,id'],
            'left_score'               => ['required', 'array:entry_id,match_points'],
            'left_score.entry_id'      => ['required', 'exists:entries,id'],
            'left_score.match_points'  => ['required', 'integer', 'min:0', 'max:150'],
            'right_score'              => ['required', 'array:entry_id,match_points'],
            'right_score.entry_id'     => ['required', 'exists:entries,id', 'different:left_score.entry_id'],
            'right_score.match_points' => ['required', 'integer', 'min:0', 'max:150'],
        ];
    }
}
