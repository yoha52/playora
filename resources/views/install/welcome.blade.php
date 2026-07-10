<x-layouts.install :title="__('install.welcome')" :currentStep="1">
    <div class="p-8 text-center">
        <div class="mb-6">
            <svg class="mx-auto w-16 h-16 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
            </svg>
        </div>

        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
            {{ __('install.welcome_title') }}
        </h2>

        <p class="text-gray-600 dark:text-gray-400 mb-8">
            {{ __('install.welcome_description') }}
        </p>

        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-8 text-left">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-4">{{ __('install.before_you_begin') }}</h3>
            <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-brand-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ __('install.requirement_database') }}
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-brand-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ __('install.requirement_license') }}
                </li>
                <li class="flex items-start">
                    <svg class="w-5 h-5 text-brand-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ __('install.requirement_smtp') }}
                </li>
            </ul>
        </div>

        <a href="{{ route('install.requirements') }}" class="inline-flex items-center px-6 py-3 text-white bg-brand-500 hover:bg-brand-600 focus:ring-4 focus:outline-none focus:ring-brand-300 font-medium rounded text-sm text-center dark:bg-brand-600 dark:hover:bg-brand-700 dark:focus:ring-brand-800">
            {{ __('install.get_started') }}
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
        </a>
    </div>
</x-layouts.install>
