<section class="bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto min-h-screen">
        <x-auth-logo />
        <div class="w-full p-6 bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md dark:bg-gray-800 dark:border-gray-700 sm:p-8">
            <x-auth-session-status :status="session('status')" />
            <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                {{ __('general.reset_password') }}
            </h1>
            <form wire:submit="resetPassword" class="mt-4 space-y-4 lg:mt-5 md:space-y-5">
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.emal') }}</label>
                    <input type="email" wire:model="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 rounded focus:ring-brand-600 focus:border-brand-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500" placeholder="email@example.com" autocomplete="email" required>
                    @error('email')
                    <p class="mt-2.5 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.password') }}</label>
                    <input type="password" wire:model="password" id="password" placeholder="{{ __('Password') }}" class="bg-gray-50 border border-gray-300 text-gray-900 rounded focus:ring-brand-600 focus:border-brand-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500" autocomplete="new-password" required autofocus>
                    @error('password')
                    <p class="mt-2.5 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.confirm_password') }}</label>
                    <input type="password" wire:model="password_confirmation" id="password_confirmation" placeholder="{{ __('Confirm password') }}" class="bg-gray-50 border border-gray-300 text-gray-900 rounded focus:ring-brand-600 focus:border-brand-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500" autocomplete="new-password" required>
                    @error('password_confirmation')
                    <p class="mt-2.5 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-center">
                    {{ __('general.or_return_to') }}&nbsp;<a wire:navigate href="{{ route('login') }}" class="text-sm font-medium text-brand-600 hover:underline dark:text-brand-500">{{ __('general.log_in') }}</a>
                </div>
                <button type="submit" class="w-full text-white bg-brand-600 hover:bg-brand-700 focus:ring-4 focus:outline-none focus:ring-brand-300 font-medium rounded text-sm px-5 py-2.5 text-center dark:bg-brand-600 dark:hover:bg-brand-700 dark:focus:ring-brand-800">{{ __('general.reset_password') }}</button>
            </form>
        </div>
    </div>
</section>
