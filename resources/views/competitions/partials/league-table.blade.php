<div class="mt-1 flow-root">
    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
            <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                <thead>
                <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-center text-sm font-semibold text-gray-900 dark:text-gray-200">Position</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-200">Name</th>
                    <th scope="col" class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900 dark:text-gray-200">Handicap</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-200">Bow Style</th>
                    <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900 dark:text-gray-200">Played</th>
                    <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900 dark:text-gray-200">Won</th>
                    <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900 dark:text-gray-200">Drawn</th>
                    <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900 dark:text-gray-200">Lost</th>
                    <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900 dark:text-gray-200">Scored</th>
                    <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900 dark:text-gray-200">Adjusted</th>
                    <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900 dark:text-gray-200">Bonus</th>
                    <th scope="col" class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900 dark:text-gray-200">Points</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-dashed divide-gray-200 dark:divide-gray-700">
                @foreach($standings as $index => $standing)
                    <tr class="{{$index == 8 ? 'border-t !border-solid !border-gray-300 dark:!border-gray-500' : ''}}">
                        <td class="whitespace-nowrap py-4 pl-5 pr-3 text-center text-sm font-medium text-gray-700 dark:text-gray-200">
                            @if($index == 0)
                                <span class="inline-flex items-center rounded-md bg-yellow-400/10 px-2 py-1 text-xs font-medium text-yellow-400 ring-1 ring-inset ring-yellow-400/30">
                                                        1
                                                    </span>
                            @elseif($index == 1)
                                <span class="inline-flex items-center rounded-md bg-gray-400/10 px-2 py-1 text-xs font-medium text-gray-400 ring-1 ring-inset ring-gray-400/30">
                                                        2
                                                    </span>
                            @elseif($index == 2)
                                <span class="inline-flex items-center rounded-md bg-orange-400/10 px-2 py-1 text-xs font-medium text-orange-400 ring-1 ring-inset ring-orange-400/30">
                                                        3
                                                    </span>
                            @elseif($index <= 7)
                                <span class="inline-flex items-center rounded-md bg-green-400/10 px-2 py-1 text-xs font-medium text-green-400 ring-1 ring-inset ring-green-400/30">
                                                        {{$index + 1}}
                                                    </span>
                            @else
                                {{$index + 1}}
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-3 py-5 text-left text-sm text-gray-700 dark:text-gray-200">{{$standing->entry->person->full_name}}</td>
                        <td class="whitespace-nowrap px-3 py-5 text-center text-sm text-gray-500 dark:text-gray-400">{{$standing->entry->current_handicap ?? '??'}}</td>
                        <td class="whitespace-nowrap px-3 py-5 text-left text-sm text-gray-500 dark:text-gray-400">{{$standing->entry->bow_style->name}}</td>
                        <td class="whitespace-nowrap px-3 py-5 text-right text-sm text-gray-500 dark:text-gray-400">{{$standing->matches_played}}</td>
                        <td class="whitespace-nowrap px-3 py-5 text-right text-sm text-gray-500 dark:text-gray-400">{{$standing->matches_won}}</td>
                        <td class="whitespace-nowrap px-3 py-5 text-right text-sm text-gray-500 dark:text-gray-400">{{$standing->matches_drawn}}</td>
                        <td class="whitespace-nowrap px-3 py-5 text-right text-sm text-gray-500 dark:text-gray-400">{{$standing->matches_lost}}</td>
                        <td class="whitespace-nowrap px-3 py-5 text-right text-sm text-gray-500 dark:text-gray-400">{{number_format($standing->match_points)}}</td>
                        <td class="whitespace-nowrap px-3 py-5 text-right text-sm text-gray-500 dark:text-gray-400">{{number_format($standing->match_points_adjusted)}}</td>
                        <td class="whitespace-nowrap px-3 py-5 text-right text-sm text-gray-500 dark:text-gray-400">{{$standing->bonus_points}}</td>
                        <td class="whitespace-nowrap px-3 py-5 text-center text-lg text-gray-700 dark:text-gray-200">{{$standing->league_points}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
