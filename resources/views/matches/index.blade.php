<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{$competition->name}} {{__('Matches')}}
        </h2>
    </x-slot>

    <x-slot name="actions">
        <a href="{{route('competitions.stages.matches.create', [$competition, $stage])}}">
            <x-primary-button>
                {{__('Record')}}
            </x-primary-button>
        </a>
    </x-slot>

    {{--Breadcrumbs--}}
    <x-slot name="breadcrumbs">
        <x-breadcrumbs :crumbs="[
            $competition->name => route('competitions.show', $competition),
            'Matches' => ''
        ]"/>
    </x-slot>

    {{--Match Results--}}
    @foreach($rounds->sortByDesc('starts_on') as $round)
        @if($round->starts_on->isFuture())
            @continue;
        @endif


        <div class="pt-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="w-full bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">

                        <div class="border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-5 sm:px-6">
                            <div class="-ml-4 -mt-4 flex flex-wrap items-center justify-between sm:flex-nowrap">
                                <div class="ml-4 mt-4">
                                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                        {{$round->name}}
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            @switch(true)
                                                @case($round->period->start->year !== $round->period->end->year)
                                                    {{$round->period->start->format('jS F Y') . ' - ' . $round->period->end->format('jS F Y')}}
                                                    @break

                                                @case($round->period->start->month !== $round->period->end->month)
                                                    {{$round->period->start->format('jS F') . ' - ' . $round->period->end->format('jS F Y')}}
                                                    @break

                                                @default
                                                    {{$round->period->start->format('jS') . ' - ' . $round->period->end->format('jS F Y')}}
                                            @endswitch
                                        </p>
                                    </h3>
                                </div>
                            </div>
                        </div>

                        <div class="pb-5 sm:pb-6">
                            <ul class="flex-col space-y-4 divide-y dark:divide-gray-700">
                                @forelse($matches->where('round_id', $round->id)->sortByDesc('shot_at') as $match)
                                    <li class="pt-2">
                                        <x-match-result :match="$match"/>
                                    </li>
                                @empty
                                    <li class="px-6 pt-6 text-center">
                                        No matches have been recorded for this round
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</x-app-layout>
