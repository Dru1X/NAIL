<form
    action="{{isset($competition)? route('competitions.update', $competition) : route('competitions.store')}}"
    method="POST"
>
    @csrf
    @isset($competition)
        @method('PUT')
    @endisset

    <div class="space-y-4 sm:space-y-0 sm:grid sm:grid-cols-2 sm:gap-x-4 sm:gap-y-6">

        {{--Name--}}
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input
                class="block mt-1 w-full"
                id="name"
                name="name"
                placeholder="Indoor League 2025/26"
                autocomplete="off"
                required
                :value="old('name', isset($competition)? $competition->name : '')"
            />
            <x-input-error :messages="$errors->get('name')" class="mt-2"/>
        </div>

        {{--Entry Open Date--}}
        <div class="sm:col-start-1">
            <x-input-label for="entriesOpenOn" :value="__('Entry Open Date')" />
            <x-text-input
                type="date"
                class="block mt-1 w-full"
                id="entriesOpenOn"
                name="entries_open_on"
                autocomplete="off"
                required
                :value="old('entries_open_on', isset($competition)? $competition->entries_open_on->toDateString() : '')"
            />
            <x-input-error :messages="$errors->get('entries_open_on')" class="mt-2"/>
        </div>

        {{--Entry Close Date--}}
        <div>
            <x-input-label for="entriesCloseOn" :value="__('Entry Close Date')" />
            <x-text-input
                type="date"
                class="block mt-1 w-full"
                id="entriesCloseOn"
                name="entries_close_on"
                autocomplete="off"
                required
                :value="old('entries_close_on', isset($competition)? $competition->entries_close_on->toDateString() : '')"
            />
            <x-input-error :messages="$errors->get('entries_close_on')" class="mt-2"/>
        </div>

        @isset($competition)
            <input type="hidden" name="stages[0][id]" value="{{$competition->stages[0]?->id}}"/>
        @endisset

        <input type="hidden" name="stages[0][type]" value="league"/>

        {{--League Stage Start Date--}}
        <div>
            <x-input-label for="leagueStartsOn" :value="__('League Start Date')" />
            <x-text-input
                type="date"
                class="block mt-1 w-full"
                id="leagueStartsOn"
                name="stages[0][starts_on]"
                autocomplete="off"
                required
                :value="old('stages.0.starts_on', isset($competition)? $competition->stages[0]?->starts_on->toDateString() : '')"
            />
            <x-input-error :messages="$errors->get('stages.0.starts_on')" class="mt-2"/>
        </div>

        {{--League Stage End Date--}}
        <div>
            <x-input-label for="leagueEndsOn" :value="__('League End Date')" />
            <x-text-input
                type="date"
                class="block mt-1 w-full"
                id="leagueEndsOn"
                name="stages[0][ends_on]"
                autocomplete="off"
                required
                :value="old('stages.0.ends_on', isset($competition)? $competition->stages[0]?->ends_on->toDateString() : '')"
            />
            <x-input-error :messages="$errors->get('stages.0.ends_on')" class="mt-2"/>
        </div>

        @isset($competition)
            <input type="hidden" name="stages[1][id]" value="{{$competition->stages[0]?->id}}"/>
        @endisset

        <input type="hidden" name="stages[1][type]" value="playoff"/>

        {{--Playoff Stage Start Date--}}
        <div>
            <x-input-label for="playoffStartsOn" :value="__('Playoff Start Date')" />
            <x-text-input
                type="date"
                class="block mt-1 w-full"
                id="playoffStartsOn"
                name="stages[1][starts_on]"
                autocomplete="off"
                required
                :value="old('stages.1.starts_on', isset($competition)? $competition->stages[1]?->starts_on->toDateString() : '')"
            />
            <x-input-error :messages="$errors->get('stages.1.starts_on')" class="mt-2"/>
        </div>

        {{--Playoff Stage End Date--}}
        <div>
            <x-input-label for="playoffEndsOn" :value="__('Playoff End Date')" />
            <x-text-input
                type="date"
                class="block mt-1 w-full"
                id="playoffEndsOn"
                name="stages[1][ends_on]"
                autocomplete="off"
                required
                :value="old('stages.1.ends_on', isset($competition)? $competition->stages[1]?->ends_on->toDateString() : '')"
            />
            <x-input-error :messages="$errors->get('stages.1.ends_on')" class="mt-2"/>
        </div>
    </div>

    <div class="flex items-center justify-end mt-4" x-data>
        <x-secondary-button @click="history.back()">
            {{__('Cancel')}}
        </x-secondary-button>

        <x-primary-button class="ms-3">
            {{ __('Save') }}
        </x-primary-button>
    </div>
</form>
