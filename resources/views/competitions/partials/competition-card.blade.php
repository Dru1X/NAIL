<li class="col-span-1 flex flex-col divide-y divide-gray-200 dark:divide-gray-600 rounded-lg bg-white dark:bg-gray-800 text-center shadow">
    <a href="{{route('competitions.show', $competition)}}">
        <div class="flex flex-1 flex-col p-8">
            {{--Picture--}}
            {{--        <img--}}
            {{--            class="mx-auto size-32 shrink-0 rounded-full"--}}
            {{--            src="https://placehold.co/200x200?text={{$person->initials}}"--}}
            {{--            alt="{{$person->initials}}"--}}
            {{--        >--}}

            {{--Icon--}}
            <div class="flex justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="h-16">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 0 1-.982-3.172M9.497 14.25a7.454 7.454 0 0 0 .981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 0 0 7.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 0 0 2.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 0 1 2.916.52 6.003 6.003 0 0 1-5.395 4.972m0 0a6.726 6.726 0 0 1-2.749 1.35m0 0a6.772 6.772 0 0 1-3.044 0" />
                </svg>
            </div>

            {{--Name--}}
            <h3 class="mt-6 text-sm font-semibold text-gray-900 dark:text-gray-200">
                {{$competition->name}}
            </h3>

            {{--Description and Status--}}
            <dl class="mt-1 flex grow flex-col justify-between">
                <dt class="sr-only">Entries</dt>
                <dd class="text-sm text-gray-500">{{$competition->entries_count}} {{Str::plural('entry', $competition->entries_count)}}</dd>

                <dt class="sr-only">Status</dt>
                <dd class="mt-3">

                    @switch($competition->status)
                        @case('planning')
                            <x-label colour="orange">
                                {{__('Planning')}}
                            </x-label>
                        @break

                        @case('ongoing')
                            <x-label colour="green">
                                {{__('Ongoing')}}
                            </x-label>
                            @break

                        @case('ended')
                            <x-label colour="blue">
                                {{__('Ended')}}
                            </x-label>
                            @break

                        @case('unknown')
                            <x-label colour="gray">
                                {{__('Unknown')}}
                            </x-label>
                            @break
                    @endswitch
                </dd>
            </dl>
        </div>
    </a>

    {{--Actions--}}
    <div class="-mt-px flex divide-x divide-gray-200 dark:divide-gray-600">

        {{--Edit--}}
        <div class="flex w-0 flex-1">
            <a href="{{route('competitions.edit', $competition)}}"
               class="relative -mr-px inline-flex w-0 flex-1 items-center justify-center gap-x-1 rounded-bl-lg border border-transparent py-4 text-sm font-semibold text-gray-900 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5 text-gray-500">
                    <path d="m2.695 14.762-1.262 3.155a.5.5 0 0 0 .65.65l3.155-1.262a4 4 0 0 0 1.343-.886L17.5 5.501a2.121 2.121 0 0 0-3-3L3.58 13.419a4 4 0 0 0-.885 1.343Z" />
                </svg>

                Edit
            </a>
        </div>

        {{--Remove--}}
        @include('competitions.partials.remove-competition-modal')
    </div>
</li>
