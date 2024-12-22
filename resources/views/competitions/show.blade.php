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
                                {{$stages[0]->starts_on->toFormattedDateString()}}
                            </dd>
                        </div>

                        <div class="border-b border-gray-100 dark:border-gray-700 px-4 py-6 sm:col-span-1 sm:px-0">
                            <dt class="text-sm/6 font-medium text-gray-900 dark:text-gray-200">
                                Playoff Date
                            </dt>
                            <dd class="mt-1 text-sm/6 text-gray-700 dark:text-gray-400 sm:mt-2">
                                {{$stages[1]->starts_on->toFormattedDateString()}}
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
                                <a href="{{route('competitions.entries.index', $competition)}}" class="flex items-center space-x-1">
                                    <span>League Entries</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                        <path fill-rule="evenodd" d="M8.914 6.025a.75.75 0 0 1 1.06 0 3.5 3.5 0 0 1 0 4.95l-2 2a3.5 3.5 0 0 1-5.396-4.402.75.75 0 0 1 1.251.827 2 2 0 0 0 3.085 2.514l2-2a2 2 0 0 0 0-2.828.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                        <path fill-rule="evenodd" d="M7.086 9.975a.75.75 0 0 1-1.06 0 3.5 3.5 0 0 1 0-4.95l2-2a3.5 3.5 0 0 1 5.396 4.402.75.75 0 0 1-1.251-.827 2 2 0 0 0-3.085-2.514l-2 2a2 2 0 0 0 0 2.828.75.75 0 0 1 0 1.06Z" clip-rule="evenodd" />
                                    </svg>
                                </a>
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
                                {{$stages[1]->capacity}}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    {{--League Table--}}
    @if($standings->isNotEmpty() && $competition->status !== 'planning')
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
                            </div>
                        </div>

                        @include('competitions.partials.league-table')
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{--Recent Matches--}}
    <div class="pt-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="w-full bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">

                    <div class="border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-5 sm:px-6">
                        <div class="-ml-4 -mt-4 flex flex-wrap items-center justify-between sm:flex-nowrap">
                            <div class="ml-4 mt-4">
                                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                    Recent Matches
                                </h3>
                            </div>
                            <div class="ml-4 mt-4 shrink-0">
                                <a href="{{route('competitions.stages.matches.create', [$competition, $stages[0]])}}">
                                    <x-primary-button>
                                        Record
                                    </x-primary-button>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="pb-5 sm:pb-6">
                        <ul class="flex-col space-y-4 divide-y dark:divide-gray-700">
                            @foreach($matches as $match)
                                <li class="pt-2">
                                    <x-match-result :match="$match"/>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
