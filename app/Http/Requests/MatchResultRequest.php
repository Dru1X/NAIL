<?php

namespace App\Http\Requests;

use App\Data\MatchResultData;
use App\Data\ScoreData;
use App\Enums\Side;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Http\FormRequest;

class MatchResultRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'shot_at'                   => ['required', 'date'],

            // Score data
            'scores'                    => ['required', 'array:left,right'],
            'scores.left'               => ['required', 'array:entry_id,match_points'],
            'scores.left.entry_id'      => ['required', 'exists:entries,id'],
            'scores.left.match_points'  => ['required', 'integer', 'min:0', 'max:150'],
            'scores.right'              => ['required', 'array:entry_id,match_points'],
            'scores.right.entry_id'     => ['required', 'exists:entries,id'],
            'scores.right.match_points' => ['required', 'integer', 'min:0', 'max:150'],
        ];
    }

    public function validatedData(): MatchResultData
    {
        return $this->makeMatchResultDto(
            $this->validated()
        );
    }

    protected function makeMatchResultDto(array $data): MatchResultData
    {
        return new MatchResultData(
            shotAt: CarbonImmutable::make($data['shot_at']),
            leftScore: $this->makeScoreDto('left', data_get($data, 'scores.left')),
            rightScore: $this->makeScoreDto('right', data_get($data, 'scores.right')),
        );
    }

    protected function makeScoreDto(string $side, array $data): ScoreData
    {
        return new ScoreData(
            side: Side::from($side),
            entryId: $data['entry_id'],
            matchPoints: $data['match_points'],
        );
    }
}
