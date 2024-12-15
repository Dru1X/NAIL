<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{$competition->name}}
        </h2>
    </x-slot>

    <x-slot name="actions">
        <a href="{{route('competitions.edit', $competition)}}">
            <x-primary-button>
                Edit
            </x-primary-button>
        </a>
    </x-slot>

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

    <div class="pt-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="w-full px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">

                    {{--Tabs--}}
                    <nav class="border-b border-gray-200 dark:border-gray-700 pb-5 sm:pb-0">

                        <!-- Tabs at small breakpoint and up -->
                        <nav class="hidden space-x-8 sm:-my-px sm:flex sm:h-12">
                            <x-nav-link :href="route('competitions.show', $competition)" :active="true">
                                {{__('Table')}}
                            </x-nav-link>

                            <x-nav-link :href="route('competitions.show', $competition)" :active="false">
                                {{__('Stats')}}
                            </x-nav-link>

                            <x-nav-link :href="route('competitions.show', $competition)" :active="false">
                                {{__('Matches')}}
                            </x-nav-link>
                        </nav>

                        <!-- Responsive menu -->
                        <div class="grid grid-cols-1 sm:hidden">

                            <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
                            <select
                                aria-label="Select a tab"
                                class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-2 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600">
                                <option selected>Table</option>
                                <option>Stats</option>
                                <option>Matches</option>
                            </select>
                            <svg
                                class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end fill-gray-500"
                                viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon">
                                <path
                                    fill-rule="evenodd"
                                    d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                    </nav>

                    <div class="mt-6">
                        Content to be added
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
