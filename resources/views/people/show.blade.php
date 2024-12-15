<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{$person->full_name}}
        </h2>
    </x-slot>

    <x-slot name="actions">
        <a href="{{route('people.edit', $person)}}">
            <x-secondary-button>
                Edit
            </x-secondary-button>
        </a>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="w-full px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6">
                        <div class="-ml-4 -mt-4 flex flex-wrap items-center justify-between sm:flex-nowrap">
                            <div class="ml-4 mt-4">
                                <div class="flex items-center">
                                    <div class="shrink-0">
                                        <img
                                            class="size-12 rounded-full"
                                            src="https://placehold.co/200x200?text={{$person->initials}}"
                                            alt="{{$person->initials}}"
                                        >
                                    </div>

                                    <div class="ml-4">
                                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                            {{$person->full_name}}
                                        </h3>
                                        <p class="text-sm text-gray-500">Person</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
