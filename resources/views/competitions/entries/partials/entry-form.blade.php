<form
    action="{{isset($entry)? route('competitions.entries.update', [$competition, $entry]) : route('competitions.entries.store', $competition)}}"
    method="POST"
>
    @csrf
    @isset($entry)
        @method('PUT')
    @endisset

    <div class="space-y-4 sm:space-y-0 sm:grid sm:grid-cols-2 sm:gap-x-4 sm:gap-y-6">

        {{--Person--}}
        <div>
            <x-input-label for="personId" :value="__('Person')"/>
            <div class="mt-2 grid grid-cols-1">
                <select
                    id="personId"
                    name="person_id"
                    autocomplete="off"
                    required
                    class="col-start-1 row-start-1 w-full appearance-none bg-none rounded-md bg-white dark:bg-gray-900 py-1.5 pl-3 pr-8 text-base text-gray-900 dark:text-gray-200 outline outline-1 -outline-offset-1 outline-gray-300 dark:outline-gray-700 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                >
                    <option
                        value=""
                        disabled
                        {{ (!isset($entry) || !$entry->person_id)? 'selected' : '' }}
                    >
                        Select person...
                    </option>
                    @foreach($people as $person)
                        <option
                            value="{{$person->id}}"
                            {{$entries->pluck('person_id')->contains($person->id) && (!isset($entry) || $entry->person->isNot($person))? 'disabled' : '' }}
                            {{isset($entry) && $entry->person->is($person)? 'selected' : ''}}
                        >
                            {{$person->full_name}}
                        </option>
                    @endforeach
                </select>
                <svg
                    class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4"
                    viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon">
                    <path fill-rule="evenodd"
                          d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z"
                          clip-rule="evenodd"/>
                </svg>
            </div>
            <x-input-error :messages="$errors->get('person_id')" class="mt-2"/>
        </div>

        {{--Bow Style--}}
        <div>
            <x-input-label for="bowStyle" :value="__('Bow Style')"/>
            <div class="mt-2 grid grid-cols-1">
                <select
                    id="bowStyle"
                    name="bow_style"
                    autocomplete="off"
                    required
                    class="col-start-1 row-start-1 w-full appearance-none bg-none rounded-md bg-white dark:bg-gray-900 py-1.5 pl-3 pr-8 text-base text-gray-900 dark:text-gray-200 outline outline-1 -outline-offset-1 outline-gray-300 dark:outline-gray-700 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                >
                    <option value="" disabled {{ (!isset($entry) || !$entry->bow_style)? 'selected' : '' }}>Select bow
                        style...
                    </option>
                    @foreach($bowStyles as $bowStyle)
                        <option
                            value="{{$bowStyle->value}}" {{isset($entry) && $entry->bow_style == $bowStyle? 'selected' : ''}}>
                            {{$bowStyle->name}}
                        </option>
                    @endforeach
                </select>
                <svg
                    class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4"
                    viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon">
                    <path fill-rule="evenodd"
                          d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z"
                          clip-rule="evenodd"/>
                </svg>
            </div>
            <x-input-error :messages="$errors->get('bow_style')" class="mt-2"/>
        </div>

        <div class="sm:grid sm:grid-cols-2 sm:gap-x-4">
            {{--Initial Handicap--}}
            <div>
                <x-input-label for="initialHandicap" :value="__('Initial Handicap')"/>
                <x-text-input
                    type="number"
                    class="block mt-1 w-full"
                    id="initialHandicap"
                    name="initial_handicap"
                    min="0"
                    max="150"
                    autocomplete="off"
                    required
                    :value="old('initial_handicap', isset($entry)? $entry->initial_handicap : '')"
                />
                <x-input-error :messages="$errors->get('initial_handicap')" class="mt-2"/>
            </div>

            {{--Current Handicap--}}
            @isset($entry)
                <div>
                    <x-input-label for="currentHandicap" :value="__('Current Handicap')"/>
                    <x-text-input
                        type="number"
                        class="block mt-1 w-full"
                        id="currentHandicap"
                        name="current_handicap"
                        min="0"
                        max="150"
                        autocomplete="off"
                        required
                        :value="old('current_handicap', isset($entry)? $entry->current_handicap : '')"
                    />
                    <x-input-error :messages="$errors->get('current_handicap')" class="mt-2"/>
                </div>
            @endisset
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
