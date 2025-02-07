<?php

namespace App\Http\Requests;

use App\Data\CompetitionData;
use App\Data\StageData;
use App\Enums\StageType;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Http\FormRequest;

class CompetitionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'                     => ['required', 'string', 'min:5', 'max:255'],
            'entries_open_on'          => ['required', 'date'],
            'entries_close_on'         => ['required', 'date', 'after:entries_open_on'],

            // Stage Data
            'stages'                   => ['required', 'array:league,playoff'],
            'stages.league'            => ['array:starts_on,ends_on'],
            'stages.league.starts_on'  => ['required', 'date'],
            'stages.league.ends_on'    => ['required', 'date', 'after_or_equal:stages.league.starts_on'],
            'stages.playoff'           => ['array:starts_on,ends_on'],
            'stages.playoff.starts_on' => ['required', 'date'],
            'stages.playoff.ends_on'   => ['required', 'date', 'after_or_equal:stages.playoff.starts_on'],
        ];
    }

    public function validatedData(): CompetitionData
    {
        return $this->makeCompetitionDto(
            $this->validated()
        );
    }

    protected function makeCompetitionDto(array $data): CompetitionData
    {
        return new CompetitionData(
            name: $data['name'],
            entriesOpenOn: CarbonImmutable::make($data['entries_open_on']),
            entriesCloseOn: CarbonImmutable::make($data['entries_close_on']),
            leagueStage: $this->makeStageDto('league', data_get($data,'stages.league')),
            playoffStage: $this->makeStageDto('playoff', data_get($data,'stages.playoff')),
        );
    }

    protected function makeStageDto(string $type, array $data): StageData
    {
        return new StageData(
            type: StageType::from($type),
            startsOn: CarbonImmutable::make($data['starts_on']),
            endsOn: CarbonImmutable::make($data['ends_on']),
        );
    }
}
