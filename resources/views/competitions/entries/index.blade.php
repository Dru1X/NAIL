<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{$competition->name}} {{__('Entries')}}
        </h2>
    </x-slot>

    <x-slot name="actions">
        <a href="{{route('competitions.entries.create', $competition)}}">
            <x-primary-button type="button" :disabled="!$competition->entry_period->isInProgress() || $competition->is_full">
                Enter
            </x-primary-button>
        </a>
    </x-slot>

    {{--Breadcrumbs--}}
    <x-slot name="breadcrumbs">
        <x-breadcrumbs :crumbs="[
            $competition->name => route('competitions.show', $competition),
            'Entries' => ''
        ]"/>
    </x-slot>

    {{--Entries--}}
    <div>
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
</x-app-layout>
