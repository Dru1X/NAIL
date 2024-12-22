@props(['match'])

<div>
    <div class="text-center dark:text-gray-400 mb-2">
        {{$match->shot_at->format('l jS F, g:i a')}}
    </div>

    <div class="flex justify-between items-center">
        <div class="w-full flex justify-between items-center gap-x-2">
            <div class="flex gap-x-4 pl-2 sm:pl-8">
                <img
                    class="hidden sm:block size-10 rounded-full"
                    src="https://placehold.co/200x200?text={{$match->leftScore->entry->person->initials}}"
                    alt="{{$match->leftScore->entry->person->initials}}"
                >
                <div class="text-left">
                    <div class="flex gap-x-2">
                        <span>{{$match->leftScore->entry->person->full_name}}</span>
                        <span class="hidden sm:inline">{{$match->winner?->is($match->leftScore->entry)? '👑' : ''}}</span>
                    </div>

                    <span class="block text-sm text-gray-600 dark:text-gray-400">
                        {{$match->leftScore->entry->bow_style->name}}
                    </span>
                </div>
            </div>

            <div class="flex flex-row-reverse items-center gap-x-2">
                <div class="text-2xl {{$match->winner?->is($match->leftScore->entry)? 'font-semibold' : 'font-light text-gray-800 dark:text-gray-200'}}">
                    {{$match->leftScore->match_points_adjusted}}
                </div>

                <div>
                    @switch(true)
                        @case($match->leftScore->handicap_change > 5)
                            <span class="text-green-600 dark:text-green-400">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                    <path fill-rule="evenodd" d="M7.47 12.78a.75.75 0 0 0 1.06 0l3.25-3.25a.75.75 0 0 0-1.06-1.06L8 11.19 5.28 8.47a.75.75 0 0 0-1.06 1.06l3.25 3.25ZM4.22 4.53l3.25 3.25a.75.75 0 0 0 1.06 0l3.25-3.25a.75.75 0 0 0-1.06-1.06L8 6.19 5.28 3.47a.75.75 0 0 0-1.06 1.06Z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            @break

                        @case($match->leftScore->handicap_change > 0)
                            <span class="text-green-400 dark:text-green-600">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                    <path fill-rule="evenodd" d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            @break

                        @default
                            <span class="text-gray-600 dark:text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                    <path d="M3.75 7.25a.75.75 0 0 0 0 1.5h8.5a.75.75 0 0 0 0-1.5h-8.5Z" />
                                </svg>
                            </span>
                    @endswitch
                </div>
            </div>
        </div>

        <div class="mx-2 text-gray-600 dark:text-gray-400">-</div>

        <div class="w-full flex flex-row-reverse justify-between items-center gap-x-2">
            <div class="flex flex-row-reverse gap-x-4 pr-2 sm:pr-8">
                <img
                    class="hidden sm:block size-10 rounded-full"
                    src="https://placehold.co/200x200?text={{$match->rightScore->entry->person->initials}}"
                    alt="{{$match->rightScore->entry->person->initials}}"
                >
                <div class="text-right">
                    <div class="flex flex-row-reverse gap-x-2">
                        <span>{{$match->rightScore->entry->person->full_name}}</span>
                        <span class="hidden sm:inline">{{$match->winner?->is($match->rightScore->entry)? '👑' : ''}}</span>
                    </div>

                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        {{$match->rightScore->entry->bow_style->name}}
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-x-2">
                <div class="text-2xl {{$match->winner?->is($match->rightScore->entry)? 'font-semibold' : 'font-light text-gray-800 dark:text-gray-200'}}">
                    {{$match->rightScore->match_points_adjusted}}
                </div>

                <div>
                    @switch(true)
                        @case($match->rightScore->handicap_change > 5)
                            <span class="text-green-600 dark:text-green-400">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                    <path fill-rule="evenodd" d="M7.47 12.78a.75.75 0 0 0 1.06 0l3.25-3.25a.75.75 0 0 0-1.06-1.06L8 11.19 5.28 8.47a.75.75 0 0 0-1.06 1.06l3.25 3.25ZM4.22 4.53l3.25 3.25a.75.75 0 0 0 1.06 0l3.25-3.25a.75.75 0 0 0-1.06-1.06L8 6.19 5.28 3.47a.75.75 0 0 0-1.06 1.06Z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            @break

                        @case($match->rightScore->handicap_change > 0)
                            <span class="text-green-400 dark:text-green-600">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                    <path fill-rule="evenodd" d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            @break

                        @default
                            <span class="text-gray-600 dark:text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                    <path d="M3.75 7.25a.75.75 0 0 0 0 1.5h8.5a.75.75 0 0 0 0-1.5h-8.5Z" />
                                </svg>
                            </span>
                    @endswitch
                </div>
            </div>
        </div>
    </div>
</div>
