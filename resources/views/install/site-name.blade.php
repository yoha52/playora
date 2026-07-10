<x-layouts.install :title="__('install.site_name')" :currentStep="4">
    <div class="p-8">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
            {{ __('install.site_configuration') }}
        </h2>
        <p class="text-gray-600 dark:text-gray-400 mb-6">
            {{ __('install.site_name_description') }}
        </p>

        <form action="{{ route('install.site-name.store') }}" method="POST">
            @csrf

            <div class="space-y-6 mb-8">
                <div>
                    <label for="app_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        {{ __('install.app_name') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="app_name" id="app_name" value="{{ old('app_name', config('app.name')) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500" required>
                    @error('app_name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="app_url" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        {{ __('install.app_url') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="url" name="app_url" id="app_url" value="{{ old('app_url', url('/')) }}" placeholder="https://example.com" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500" required>
                    @error('app_url')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('install.app_url_hint') }}</p>
                </div>
            </div>

            <div class="flex justify-between">
                <a href="{{ route('install.permissions') }}" class="inline-flex items-center px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded text-sm text-center dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                    </svg>
                    {{ __('install.back') }}
                </a>

                <button type="submit" class="inline-flex items-center px-4 py-2 text-white bg-brand-500 hover:bg-brand-600 focus:ring-4 focus:outline-none focus:ring-brand-300 font-medium rounded text-sm text-center dark:bg-brand-600 dark:hover:bg-brand-700 dark:focus:ring-brand-800">
                    {{ __('install.continue') }}
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</x-layouts.install>
