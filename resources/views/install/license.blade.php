<x-layouts.install :title="__('install.license')" :currentStep="6">
    <div class="p-8">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
            {{ __('install.license_verification') }}
        </h2>

        <p class="text-gray-600 dark:text-gray-400 mb-6">
            Please enter your Envato purchase code (purchase code is optional if you don't want to provide it).
            <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code" target="_blank" class="text-brand-500 hover:text-brand-600 underline">
                Where to get purchase code?
            </a>
        </p>

        <form action="{{ route('install.license.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="secure_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('install.requirement_license') }}</label>
                <input id="secure_code" name="secure_code" value="{{ old('secure_code', '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" />
                @error('secure_code')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-between">
                <a href="{{ route('install.database') }}" class="inline-flex items-center px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded text-sm text-center dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500">
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
    </div>
</x-layouts.install>
