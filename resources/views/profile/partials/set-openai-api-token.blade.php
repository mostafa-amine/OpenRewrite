<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('OpenAi Token') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Insert your OpenAi API Token") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.setToken') }}" class="mt-6 space-y-6">
        @csrf

        <div>
            <x-input-label for="token" :value="__('Token')" />
            <x-text-input id="token" name="token" type="text" :value="old('token' , env('OPENAI_API_KEY'))" class="mt-1 block w-full"  required autofocus />
            <x-input-error class="mt-2" :messages="$errors->get('token')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'token-valid')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
