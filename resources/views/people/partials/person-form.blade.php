<form
    action="{{isset($person)? route('people.update', $person) : route('people.store')}}"
    method="POST"
>
    @csrf
    @isset($person)
        @method('PUT')
    @endisset

    <div class="sm:grid sm:grid-cols-2 sm:gap-x-4">
        {{--First Name--}}
        <div>
            <x-input-label for="firstName" :value="__('First Name')" />
            <x-text-input
                class="block mt-1 w-full"
                id="firstName"
                name="first_name"
                :value="old('first_name', isset($person)? $person->first_name : '')"
                autocomplete="given-name"
                required
            />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2"/>
        </div>

        {{--Last Name--}}
        <div class="mt-4 sm:mt-0">
            <x-input-label for="lastName" :value="__('Last Name')" />
            <x-text-input
                class="block mt-1 w-full"
                id="lastName"
                name="last_name"
                :value="old('last_name', isset($person)? $person->last_name : '')"
                autocomplete="family-name"
                required
            />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2"/>
        </div>
    </div>

    <div class="flex items-center justify-end mt-4" x-data>
        <x-secondary-button @click="history.back()">
            {{__('Cancel')}}
        </x-secondary-button>

        <x-primary-button class="ms-3">
            {{ __('Save') }}
        </x-primary-button>
    </div>
</form>
