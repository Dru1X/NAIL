<form
    action="{{isset($match)? route('competitions.stages.matches.update', [$competition, $stage, $match]) : route('competitions.stages.matches.store', [$competition, $stage])}}"
    method="POST"
>
    @csrf
    @isset($match)
        @method('PUT')
    @endisset

    <div class="space-y-4 sm:space-y-0 sm:grid sm:grid-cols-2 sm:gap-x-4 sm:gap-y-6">

        {{--Date/Time--}}
        <div>
            <x-input-label for="shotAt" :value="__('Match Date & Time')"/>
            <x-text-input
                type="datetime-local"
                class="block mt-1 w-full"
                id="shotAt"
                name="shot_at"
                autocomplete="off"
                required
                :value="old('shot_at', isset($match)? $match->shot_at->toDateTimeString() : '')"
            />
            <x-input-error :messages="$errors->get('shot_at')" class="mt-2"/>
        </div>

        <h3 class="col-span-2 hidden sm:block">Scores</h3>

        {{--Left Side--}}
        <div class="space-y-4 sm:space-y-0 sm:grid sm:grid-cols-2 sm:gap-x-4 sm:gap-y-6">

            <h4 class="sm:hidden mt-8 text-sm">{{__('Left Side')}}</h4>

            {{--Entry--}}
            <div>
                <x-input-label for="leftEntryId" :value="__('Name')"/>
                <div class="mt-1 grid grid-cols-1">
                    <select
                        id="leftEntryId"
                        name="scores[left][entry_id]"
                        autocomplete="off"
                        required
                        class="col-start-1 row-start-1 w-full h-[42px] appearance-none bg-none rounded-md bg-white dark:bg-gray-900 py-1.5 pl-3 pr-8 text-base text-gray-900 dark:text-gray-200 outline outline-1 -outline-offset-1 outline-gray-300 dark:outline-gray-700 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                    >
                        <option value="" disabled {{ (!isset($match) || !$match->leftScore->entry_id)? 'selected' : '' }}>
                            Select person...
                        </option>

                        @foreach($entries as $entry)
                            <option
                                value="{{$entry->id}}"
                                {{isset($match) && $match->leftScore->entry->is($entry)? 'selected' : ''}}
                            >
                                {{$entry->person->full_name}}
                            </option>
                        @endforeach
                    </select>
                    <svg
                        class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4"
                        viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon">
                        <path fill-rule="evenodd"
                              d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z"
                              clip-rule="evenodd"
                        />
                    </svg>
                </div>
                <x-input-error :messages="$errors->get('scores.left.entry_id')" class="mt-2"/>
            </div>

            {{--Match Points--}}
            <div class="sm:pr-10">
                <x-input-label for="leftMatchPoints" :value="__('Match Points')"/>
                <x-text-input
                    type="number"
                    class="block mt-1 w-full"
                    id="leftMatchPoints"
                    name="scores[left][match_points]"
                    min="0"
                    max="150"
                    autocomplete="off"
                    required
                    :value="old('scores.left.match_points', isset($match)? $match->leftScore->match_points : '')"
                />
                <x-input-error :messages="$errors->get('scores.left.match_points')" class="mt-2"/>
            </div>
        </div>


        {{--Right Side--}}
        <div class="space-y-4 sm:space-y-0 sm:grid sm:grid-cols-2 sm:gap-x-4 sm:gap-y-6">

            <h4 class="sm:hidden mt-8 text-sm">{{__('Right Side')}}</h4>

            {{--Entry--}}
            <div class="sm:col-start-2 sm:row-start-1">
                <x-input-label for="rightEntryId" :value="__('Name')"/>
                <div class="mt-1 grid grid-cols-1">
                    <select
                        id="rightEntryId"
                        name="scores[right][entry_id]"
                        autocomplete="off"
                        required
                        class="col-start-1 row-start-1 w-full h-[42px] appearance-none bg-none rounded-md bg-white dark:bg-gray-900 py-1.5 pl-3 pr-8 text-base text-gray-900 dark:text-gray-200 outline outline-1 -outline-offset-1 outline-gray-300 dark:outline-gray-700 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                    >
                        <option value="" disabled {{ (!isset($match) || !$match->rightScore->entry_id)? 'selected' : '' }}>
                            Select person...
                        </option>

                        @foreach($entries as $entry)
                            <option
                                value="{{$entry->id}}"
                                {{isset($match) && $match->rightScore->entry->is($entry)? 'selected' : ''}}
                            >
                                {{$entry->person->full_name}}
                            </option>
                        @endforeach
                    </select>
                    <svg
                        class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4"
                        viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon">
                        <path fill-rule="evenodd"
                              d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z"
                              clip-rule="evenodd"
                        />
                    </svg>
                </div>
                <x-input-error :messages="$errors->get('scores.right.entry_id')" class="mt-2"/>
            </div>

            {{--Match Points--}}
            <div class="sm:col-start-1 sm:row-start-1 sm:pl-10">
                <x-input-label for="rightMatchPoints" :value="__('Match Points')"/>
                <x-text-input
                    type="number"
                    class="block mt-1 w-full"
                    id="rightMatchPoints"
                    name="scores[right][match_points]"
                    min="0"
                    max="150"
                    autocomplete="off"
                    required
                    :value="old('scores.right.match_points', isset($match)? $match->rightScore->match_points : '')"
                />
                <x-input-error :messages="$errors->get('scores.right.match_points')" class="mt-2"/>
            </div>
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
