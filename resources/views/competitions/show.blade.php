<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{$competition->name}}
        </h2>
    </x-slot>

    <x-slot name="actions">
        <a href="{{route('competitions.edit', $competition)}}">
            <x-secondary-button type="button">
                Edit
            </x-secondary-button>
        </a>
    </x-slot>

    {{--Key Dates and Values--}}
    <div class="pt-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="w-full px-6 py-2 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">

                    <dl class="grid grid-cols-1 sm:grid-cols-4">
                        <div class="border-b border-gray-100 dark:border-gray-700 px-4 py-6 sm:col-span-1 sm:px-0">
                            <dt class="text-sm/6 font-medium text-gray-900 dark:text-gray-200">
                                Entries Open
                            </dt>
                            <dd class="mt-1 text-sm/6 text-gray-700 dark:text-gray-400 sm:mt-2">
                                {{$competition->entries_open_on->toFormattedDateString()}}
                            </dd>
                        </div>

                        <div class="border-b border-gray-100 dark:border-gray-700 px-4 py-6 sm:col-span-1 sm:px-0">
                            <dt class="text-sm/6 font-medium text-gray-900 dark:text-gray-200">
                                Entries Close
                            </dt>
                            <dd class="mt-1 text-sm/6 text-gray-700 dark:text-gray-400 sm:mt-2">
                                {{$competition->entries_close_on->toFormattedDateString()}}
                            </dd>
                        </div>

                        <div class="border-b border-gray-100 dark:border-gray-700 px-4 py-6 sm:col-span-1 sm:px-0">
                            <dt class="text-sm/6 font-medium text-gray-900 dark:text-gray-200">
                                League Starts
                            </dt>
                            <dd class="mt-1 text-sm/6 text-gray-700 dark:text-gray-400 sm:mt-2">
                                {{$competition->stages[0]->starts_on->toFormattedDateString()}}
                            </dd>
                        </div>

                        <div class="border-b border-gray-100 dark:border-gray-700 px-4 py-6 sm:col-span-1 sm:px-0">
                            <dt class="text-sm/6 font-medium text-gray-900 dark:text-gray-200">
                                Playoff Date
                            </dt>
                            <dd class="mt-1 text-sm/6 text-gray-700 dark:text-gray-400 sm:mt-2">
                                {{$competition->stages[1]->starts_on->toFormattedDateString()}}
                            </dd>
                        </div>

                        <div class="border-b sm:border-0 border-gray-100 dark:border-gray-700 px-4 py-6 sm:col-span-1 sm:px-0">
                            <dt class="text-sm/6 font-medium text-gray-900 dark:text-gray-200">
                                League Spaces
                            </dt>
                            <dd class="mt-1 text-sm/6 text-gray-700 dark:text-gray-400 sm:mt-2">
                                {{$competition->stages[0]->capacity}}
                            </dd>
                        </div>

                        <div class="border-b sm:border-0 border-gray-100 dark:border-gray-700 px-4 py-6 sm:col-span-1 sm:px-0">
                            <dt class="text-sm/6 font-medium text-gray-900 dark:text-gray-200">
                                League Entries
                            </dt>
                            <dd class="mt-1 text-sm/6 text-gray-700 dark:text-gray-400 sm:mt-2">
                                {{$competition->entries_count}}
                            </dd>
                        </div>

                        <div class="border-b sm:border-0 border-gray-100 dark:border-gray-700 px-4 py-6 sm:col-span-1 sm:px-0">
                            <dt class="text-sm/6 font-medium text-gray-900 dark:text-gray-200">
                                Playoff Spaces
                            </dt>
                            <dd class="mt-1 text-sm/6 text-gray-700 dark:text-gray-400 sm:mt-2">
                                {{$competition->stages[1]->capacity}}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    {{--Entries--}}
    <div class="pt-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="w-full bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">

                    <div class="border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-5 sm:px-6">
                        <div class="-ml-4 -mt-4 flex flex-wrap items-center justify-between sm:flex-nowrap">
                            <div class="ml-4 mt-4">
                                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                    All Entries
                                </h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Entries are currently {{$competition->entry_period->isInProgress()? 'open' : 'closed'}}
                                </p>
                            </div>
                            <div class="ml-4 mt-4 shrink-0">
                                <a href="{{route('competitions.entries.create', $competition)}}">
                                    <x-primary-button
                                        type="button"
                                        :disabled="!$competition->entry_period->isInProgress() || $competition->entries_count >= $competition->stages[0]->capacity"
                                    >
                                        Enter
                                    </x-primary-button>
                                </a>
                            </div>
                        </div>
                    </div>

                    @if($entries->isEmpty())
                        <div class="p-6 text-center">
                            No entries yet.
                        </div>
                    @endif

                    <ul role="list" class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($entries as $entry)
                            <li class="flex justify-between gap-x-6 px-6 py-5">
                                <div class="flex min-w-0 gap-x-4">
                                    <img
                                        class="size-12 flex-none rounded-full bg-gray-50 dark:bg-gray-800"
                                        src="https://placehold.co/200x200?text={{$entry->person->initials}}"
                                        alt="{{$entry->person->initials}}"
                                    >
                                    <div class="min-w-0 flex-auto">
                                        <p class="text-sm/6 font-semibold text-gray-900 dark:text-gray-100">
                                            <a href="{{route('people.show', $entry->person)}}" class="hover:underline">
                                                {{$entry->person->full_name}}
                                            </a>
                                        </p>
                                        <p class="mt-1 flex text-xs/5 text-gray-500 dark:text-gray-400">
                                            {{$entry->bow_style->name}}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex shrink-0 items-center gap-x-6">
                                    <div class="hidden sm:flex sm:flex-col sm:items-end">
                                        <p class="text-sm/6 text-gray-900 dark:text-gray-100">Current Handicap: {{$entry->current_handicap}}</p>
                                        <p class="mt-1 text-xs/5 text-gray-500 dark:text-gray-400">Initial Handicap: {{$entry->initial_handicap}}</p>
                                    </div>
                                    <a href="{{route('competitions.entries.edit', [$competition, $entry])}}">
                                        <x-secondary-button>
                                            Edit
                                        </x-secondary-button>
                                    </a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{--League Table--}}
    @if($standings->isNotEmpty())
        <div class="pt-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="w-full bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">

                        <div class="border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-5 sm:px-6">
                            <div class="-ml-4 -mt-4 flex flex-wrap items-center justify-between sm:flex-nowrap">
                                <div class="ml-4 mt-4">
                                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                        League Table
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Last updated {{$standings->max('updated_at')->toFormattedDateString()}}
                                    </p>
                                </div>
                                <div class="ml-4 mt-4 shrink-0">
                                    <x-primary-button>
                                      Match
                                    </x-primary-button>
                                </div>
                            </div>
                        </div>

                        @include('competitions.partials.league-table')
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
