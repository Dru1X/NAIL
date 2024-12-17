<?php

namespace App\Services;

use App\Enums\BowStyle;
use App\Models\Competition;
use App\Models\Entry;
use App\Models\Person;
use DomainException;
use Illuminate\Support\Collection;

class EntryService
{
    public function __construct(protected StandingService $standingService) {}

    // Lookup ----

    /**
     * Get all entries to a competition
     *
     * @return Collection<int, Entry>
     */
    public function getCompetitionEntries(Competition $competition): Collection
    {
        return $competition
            ->entries()
            ->with('person')
            ->get()
            ->sortBy([
                ['person.full_name', 'asc'],
                ['id', 'asc'],
            ]);
    }

    /**
     * Find a single competition entry
     */
    public function findCompetitionEntry(Competition $competition, int $id): ?Entry
    {
        return $competition
            ->entries()
            ->with(['competition', 'person'])
            ->find($id);
    }

    // Management ----

    /**
     * Enter a person into a competition
     */
    public function enterCompetition(Competition $competition, array $data): Entry
    {
        $person = Person::findOrFail($data['person_id']);

        if ($competition->entries()->wherePersonId($person->id)->exists()) {
            throw new DomainException("A person cannot enter the same competition multiple times.");
        }

        $entry = $competition->entries()->make([
            'bow_style'        => BowStyle::from($data['bow_style']),
            'initial_handicap' => $data['initial_handicap'],
            'current_handicap' => $data['current_handicap'] ?? $data['initial_handicap'],
        ]);

        $entry->person()->associate($person);

        $entry->save();

        $this->standingService->addStandingForStage(
            $competition->stages()->first(),
            $entry
        );

        return $entry;
    }

    /**
     * Update an existing competition entry
     */
    public function updateEntry(Entry $entry, array $data): Entry
    {
        $person = Person::findOrFail($data['person_id']);

        $entry->fill([
            'bow_style'        => BowStyle::from($data['bow_style']),
            'initial_handicap' => $data['initial_handicap'],
            'current_handicap' => $data['current_handicap'] ?? $entry->current_handicap,
        ]);

        if ($person->isNot($entry->person)) {

            if ($entry->competition->entries()->wherePersonId($person->id)->exists()) {
                throw new DomainException("A person cannot enter the same competition multiple times.");
            }

            $entry->person()->associate($person);
        }

        $entry->save();

        return $entry;
    }

    /**
     * Improve the current handicap held for an entry
     */
    public function improveEntryHandicap(Entry $entry, int $newHandicap): Entry
    {
        if($newHandicap >= $entry->current_handicap) {
            throw new DomainException("An improved handicap is always a lower number");
        }

        $entry->update(['current_handicap' => $newHandicap]);

        return $entry;
    }

    /**
     * Withdraw from a competition by removing an entry
     */
    public function withdrawFromCompetition(Entry $entry): bool
    {
        // TODO: Should this mark the entry as withdrawn instead of deleting?
        return $entry->delete();
    }
}
