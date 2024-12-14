<li class="col-span-1 flex flex-col divide-y divide-gray-200 dark:divide-gray-600 rounded-lg bg-white dark:bg-gray-800 text-center shadow">

    <a href="{{route('people.show', $person)}}">
        <div class="flex flex-1 flex-col p-8">
            {{--Picture--}}
            <img
                class="mx-auto size-32 shrink-0 rounded-full"
                src="https://placehold.co/200x200?text={{$person->initials}}"
                alt="{{$person->initials}}"
            >

            {{--Name--}}
            <h3 class="mt-6 text-sm font-semibold text-gray-900 dark:text-gray-200">
                {{$person->full_name}}
            </h3>

            {{--Description and Role--}}
            {{--        <dl class="mt-1 flex grow flex-col justify-between">--}}
            {{--            <dt class="sr-only">Title</dt>--}}
            {{--            <dd class="text-sm text-gray-500">Paradigm Representative</dd>--}}
            {{--            <dt class="sr-only">Role</dt>--}}
            {{--            <dd class="mt-3">--}}
            {{--                <span--}}
            {{--                    class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Admin</span>--}}
            {{--            </dd>--}}
            {{--        </dl>--}}
        </div>
    </a>

    {{--Actions--}}
    <div class="-mt-px flex divide-x divide-gray-200 dark:divide-gray-600">

        {{--View--}}
{{--        <div class="flex w-0 flex-1">--}}
{{--            <a href="{{route('people.show', $person)}}"--}}
{{--               class="relative -mr-px inline-flex w-0 flex-1 items-center justify-center gap-x-1 rounded-bl-lg border border-transparent py-4 text-sm font-semibold text-gray-900 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">--}}

{{--                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5 text-gray-500">--}}
{{--                    <path d="M10 12.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z" />--}}
{{--                    <path fill-rule="evenodd" d="M.664 10.59a1.651 1.651 0 0 1 0-1.186A10.004 10.004 0 0 1 10 3c4.257 0 7.893 2.66 9.336 6.41.147.381.146.804 0 1.186A10.004 10.004 0 0 1 10 17c-4.257 0-7.893-2.66-9.336-6.41ZM14 10a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z" clip-rule="evenodd" />--}}
{{--                </svg>--}}

{{--                View--}}
{{--            </a>--}}
{{--        </div>--}}

        {{--Edit--}}
        <div class="flex w-0 flex-1">
            <a href="{{route('people.edit', $person)}}"
               class="relative -mr-px inline-flex w-0 flex-1 items-center justify-center gap-x-1 rounded-bl-lg border border-transparent py-4 text-sm font-semibold text-gray-900 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">

                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5 text-gray-500">
                    <path d="m2.695 14.762-1.262 3.155a.5.5 0 0 0 .65.65l3.155-1.262a4 4 0 0 0 1.343-.886L17.5 5.501a2.121 2.121 0 0 0-3-3L3.58 13.419a4 4 0 0 0-.885 1.343Z" />
                </svg>

                Edit
            </a>
        </div>

        {{--Remove--}}
        @include('people.partials.remove-person-modal')
    </div>
</li>
