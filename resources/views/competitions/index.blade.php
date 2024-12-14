<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Competitions') }}
        </h2>
        <span class="inline-flex items-center rounded-md bg-blue-400/10 px-2 py-1 text-xs font-medium text-blue-400 ring-1 ring-inset ring-blue-400/30">
            {{$competitions->count()}}
        </span>
    </x-slot>

    <x-slot name="actions">
        <a href="{{route('competitions.create')}}">
            <x-primary-button>
                Add
            </x-primary-button>
        </a>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                    @each('competitions.partials.competition-card', $competitions, 'competition')
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
