<x-layouts.app :title="__('general.settings')">
    <div class="px-4 pt-6">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('general.settings') }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('general.settings_description') }}</p>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg">
            <form action="{{ route('settings.update') }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid gap-6">
                    <!-- Date Format -->
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <label for="date_format" class="text-sm font-medium text-gray-900 dark:text-white">{{ __('general.date_format') }}</label>
                            <button type="button" data-tooltip-target="tooltip-date-format" data-tooltip-placement="bottom" class="text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div id="tooltip-date-format" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700 max-w-sm">
                                <div class="text-xs">
                                    <p class="font-bold mb-1">{{ __('general.date_format_options') }}:</p>
                                    <p><strong>d</strong> - {{ __('general.day_two_digits') }} (01-31)</p>
                                    <p><strong>j</strong> - {{ __('general.day_no_zero') }} (1-31)</p>
                                    <p><strong>m</strong> - {{ __('general.month_two_digits') }} (01-12)</p>
                                    <p><strong>n</strong> - {{ __('general.month_no_zero') }} (1-12)</p>
                                    <p><strong>M</strong> - {{ __('general.month_short') }} (Jan-Dec)</p>
                                    <p><strong>F</strong> - {{ __('general.month_full') }} (January-December)</p>
                                    <p><strong>Y</strong> - {{ __('general.year_four_digits') }} (2024)</p>
                                    <p><strong>y</strong> - {{ __('general.year_two_digits') }} (24)</p>
                                    <p class="mt-1 text-gray-300">{{ __('general.example') }}: d-M-Y → {{ today()->format('d-M-Y') }}</p>
                                </div>
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        </div>
                        <input type="text" name="date_format" id="date_format" value="{{ old('date_format', $setting->date_format) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500" placeholder="d/m/Y">
                        @error('date_format')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Time Format -->
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <label for="time_format" class="text-sm font-medium text-gray-900 dark:text-white">{{ __('general.time_format') }}</label>
                            <button type="button" data-tooltip-target="tooltip-time-format" class="text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            <div id="tooltip-time-format" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700 max-w-sm">
                                <div class="text-xs">
                                    <p class="font-bold mb-1">{{ __('general.time_format_options') }}:</p>
                                    <p><strong>H</strong> - {{ __('general.hour_24_two_digits') }} (00-23)</p>
                                    <p><strong>G</strong> - {{ __('general.hour_24_no_zero') }} (0-23)</p>
                                    <p><strong>h</strong> - {{ __('general.hour_12_two_digits') }} (01-12)</p>
                                    <p><strong>g</strong> - {{ __('general.hour_12_no_zero') }} (1-12)</p>
                                    <p><strong>i</strong> - {{ __('general.minutes_two_digits') }} (00-59)</p>
                                    <p><strong>s</strong> - {{ __('general.seconds_two_digits') }} (00-59)</p>
                                    <p><strong>A</strong> - {{ __('general.am_pm_upper') }} (AM/PM)</p>
                                    <p><strong>a</strong> - {{ __('general.am_pm_lower') }} (am/pm)</p>
                                    <p class="mt-1 text-gray-300">{{ __('general.example') }}: h:i A → {{ now()->format('h:i A') }}</p>
                                </div>
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        </div>
                        <input type="text" name="time_format" id="time_format" value="{{ old('time_format', $setting->time_format) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500" placeholder="h:i A">
                        @error('time_format')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Currency -->
                    <div class="relative">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.currency') }}</label>
                        <button id="currencyDropdownButton" data-dropdown-toggle="currencyDropdown" data-dropdown-placement="bottom" class="w-full text-left bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500 flex items-center justify-between" type="button">
                            <span id="selectedCurrencyText">{{ $currencies[$setting->currency] ?? __('general.select_currency') }}</span>
                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                            </svg>
                        </button>
                        <input type="hidden" name="currency" id="currency" value="{{ old('currency', $setting->currency) }}">

                        <!-- Dropdown menu -->
                        <div id="currencyDropdown" class="z-10 hidden absolute left-0 right-0 bg-white border border-gray-200 rounded-lg shadow-lg dark:bg-gray-700 dark:border-gray-600">
                            <div class="p-3 border-b border-gray-200 dark:border-gray-600">
                                <label for="currency-search" class="sr-only">{{ __('general.search') }}</label>
                                <input type="text" id="currency-search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-full p-2 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="{{ __('general.search_currency') }}">
                            </div>
                            <ul class="max-h-48 overflow-y-auto p-2 text-sm text-gray-700 dark:text-gray-200" id="currencyList">
                                @foreach($currencies as $code => $name)
                                    <li>
                                        <button type="button" data-currency-code="{{ $code }}" data-currency-name="{{ $name }}" class="currency-option flex items-center w-full px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600 text-left">
                                            {{ $name }}
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        @error('currency')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="text-white bg-brand-500 hover:bg-brand-600 focus:ring-4 focus:outline-none focus:ring-brand-300 font-medium rounded text-sm px-5 py-2.5 text-center dark:bg-brand-600 dark:hover:bg-brand-700 dark:focus:ring-brand-800">
                        {{ __('general.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const currencyInput = document.getElementById('currency');
            const selectedCurrencyText = document.getElementById('selectedCurrencyText');
            const currencySearch = document.getElementById('currency-search');
            const currencyList = document.getElementById('currencyList');
            const currencyOptions = currencyList?.querySelectorAll('.currency-option');

            // Currency search functionality
            if (currencySearch) {
                currencySearch.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    currencyOptions.forEach(function(option) {
                        const currencyName = option.dataset.currencyName.toLowerCase();
                        const listItem = option.closest('li');
                        listItem.style.display = currencyName.includes(searchTerm) ? '' : 'none';
                    });
                });
            }

            // Currency selection
            if (currencyOptions) {
                currencyOptions.forEach(function(option) {
                    option.addEventListener('click', function() {
                        currencyInput.value = this.dataset.currencyCode;
                        selectedCurrencyText.textContent = this.dataset.currencyName;

                        // Close dropdown
                        const dropdown = FlowbiteInstances.getInstance('Dropdown', 'currencyDropdown');
                        if (dropdown) {
                            dropdown.hide();
                        }
                    });
                });
            }
        });
    </script>
    @endpush
</x-layouts.app>
