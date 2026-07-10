<x-layouts.install :title="__('install.permissions')" :currentStep="3">
    <div class="p-8">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
            {{ __('install.folder_permissions') }}
        </h2>
        <p class="text-gray-600 dark:text-gray-400 mb-6">
            {{ __('install.permissions_description') }}
        </p>

        <div class="space-y-3 mb-8">
            @foreach($permissions as $permission)
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $permission['name'] }}</span>
                        <span class="text-sm text-gray-500 dark:text-gray-400 ml-2">({{ __('install.writable') }})</span>
                    </div>
                    <div class="flex items-center">
                        @if($permission['writable'])
                            <span class="text-sm text-green-600 dark:text-green-400 mr-3">{{ __('install.writable') }}</span>
                            <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        @else
                            <span class="text-sm text-red-600 dark:text-red-400 mr-3">{{ __('install.not_writable') }}</span>
                            <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex justify-between">
            <a href="{{ route('install.requirements') }}" class="inline-flex items-center px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded text-sm text-center dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                </svg>
                {{ __('install.back') }}
            </a>

            @if($allPassed)
                <a href="{{ route('install.site-name') }}" class="inline-flex items-center px-4 py-2 text-white bg-brand-500 hover:bg-brand-600 focus:ring-4 focus:outline-none focus:ring-brand-300 font-medium rounded text-sm text-center dark:bg-brand-600 dark:hover:bg-brand-700 dark:focus:ring-brand-800">
                    {{ __('install.continue') }}
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            @else
                <button disabled class="inline-flex items-center px-4 py-2 text-white bg-gray-400 cursor-not-allowed font-medium rounded text-sm text-center">
                    {{ __('install.continue') }}
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </button>
            @endif
        </div>
    </div>
</x-layouts.install>
