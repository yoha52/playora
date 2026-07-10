<x-layouts.app :title="__('general.court_stats_report')">
    <div class="px-4 pt-6">
        <!-- Header -->
        <div class="mb-4 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('general.court_stats_report') }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('general.court_stats_report_desc') }}</p>
            </div>
        </div>

        <!-- Filters Card -->
        <div x-data="{ open: true }" class="bg-white dark:bg-gray-800 shadow-md rounded-lg mb-6 border-t-2 border-brand-500">
            <!-- Filter Header -->
            <button @click="open = !open" type="button" class="w-full p-4 flex items-center justify-between text-left">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-brand-100 dark:bg-brand-900 rounded-lg">
                        <svg class="w-5 h-5 text-brand-600 dark:text-brand-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('general.filters') }}</h2>
                </div>
                <svg class="w-5 h-5 text-gray-500 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <!-- Filter Content -->
            <div x-show="open" x-collapse class="px-4 pb-4">
                <form action="{{ route('reports.court-stats') }}" method="GET" class="flex flex-wrap items-end gap-4">
                    <div>
                        <label for="ground_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.ground') }}</label>
                        <select name="ground_id" id="ground_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-48 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">{{ __('general.all_grounds') }}</option>
                            @foreach($grounds as $ground)
                                <option value="{{ $ground->id }}" {{ $groundId == $ground->id ? 'selected' : '' }}>{{ $ground->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="from_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.from_date') }} <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                </svg>
                            </div>
                            <input id="from_date" datepicker datepicker-autohide datepicker-format="yyyy-mm-dd" name="from_date" value="{{ $fromDate }}" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-40 ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="{{ __('general.select_date') }}" required autocomplete="off">
                        </div>
                    </div>
                    <div>
                        <label for="to_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.to_date') }} <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                </svg>
                            </div>
                            <input id="to_date" datepicker datepicker-autohide datepicker-format="yyyy-mm-dd" name="to_date" value="{{ $toDate }}" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-40 ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="{{ __('general.select_date') }}" required autocomplete="off">
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="text-white bg-brand-500 hover:bg-brand-600 focus:ring-4 focus:ring-brand-300 font-medium rounded text-sm px-5 py-2.5 dark:bg-brand-600 dark:hover:bg-brand-700 focus:outline-none dark:focus:ring-brand-800">
                            {{ __('general.filter') }}
                        </button>
                        @if(request()->hasAny(['ground_id', 'from_date', 'to_date']))
                            <a href="{{ route('reports.court-stats') }}" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-300 font-medium rounded text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 border border-gray-300">
                                {{ __('general.clear') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        @if($hasFilters ?? false)
            <!-- Stats Table -->
            <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-sm text-body bg-neutral-secondary-soft border-b rounded-base border-default">
                            <tr>
                                <th scope="col" class="px-4 py-3">{{ __('general.court') }}</th>
                                <th scope="col" class="px-4 py-3">{{ __('general.ground') }}</th>
                                <th scope="col" class="px-4 py-3">{{ __('general.category') }}</th>
                                <th scope="col" class="px-4 py-3 text-center">{{ __('general.total_bookings') }}</th>
                                <th scope="col" class="px-4 py-3 text-center">{{ __('general.confirmed') }}</th>
                                <th scope="col" class="px-4 py-3 text-center">{{ __('general.completed') }}</th>
                                <th scope="col" class="px-4 py-3 text-center">{{ __('general.cancelled') }}</th>
                                <th scope="col" class="px-4 py-3 text-right">{{ __('general.total_revenue') }}</th>
                                <th scope="col" class="px-4 py-3 text-right">{{ __('general.collected') }}</th>
                                <th scope="col" class="px-4 py-3 text-right">{{ __('general.pending') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stats as $court)
                                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $court->getFirstMediaUrl('picture') }}" alt="{{ $court->name }}" class="w-10 h-10 rounded object-cover">
                                            <span class="font-medium text-gray-900 dark:text-white">{{ $court->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">{{ $court->ground?->name ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $court->category?->name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-center font-medium text-gray-900 dark:text-white">{{ $court->total_bookings }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">{{ $court->confirmed_bookings }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-0.5 rounded dark:bg-green-900 dark:text-green-300">{{ $court->completed_bookings }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2 py-0.5 rounded dark:bg-red-900 dark:text-red-300">{{ $court->cancelled_bookings }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-right font-medium text-gray-900 dark:text-white">{{ formatNumber($court->total_revenue ?? 0) }}</td>
                                    <td class="px-4 py-3 text-right text-green-600 dark:text-green-400">{{ formatNumber($court->collected_amount ?? 0) }}</td>
                                    <td class="px-4 py-3 text-right text-red-600 dark:text-red-400">{{ formatNumber(($court->total_revenue ?? 0) - ($court->collected_amount ?? 0)) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                        <p class="text-lg font-semibold">{{ __('general.no_data_available') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($stats->count() > 0)
                            <tfoot class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <td class="px-4 py-3 font-bold text-gray-900 dark:text-white" colspan="3">{{ __('general.total') }}</td>
                                    <td class="px-4 py-3 text-center font-bold">{{ $stats->sum('total_bookings') }}</td>
                                    <td class="px-4 py-3 text-center font-bold">{{ $stats->sum('confirmed_bookings') }}</td>
                                    <td class="px-4 py-3 text-center font-bold">{{ $stats->sum('completed_bookings') }}</td>
                                    <td class="px-4 py-3 text-center font-bold">{{ $stats->sum('cancelled_bookings') }}</td>
                                    <td class="px-4 py-3 text-right font-bold text-gray-900 dark:text-white">{{ formatNumber($stats->sum('total_revenue')) }}</td>
                                    <td class="px-4 py-3 text-right font-bold text-green-600 dark:text-green-400">{{ formatNumber($stats->sum('collected_amount')) }}</td>
                                    <td class="px-4 py-3 text-right font-bold text-red-600 dark:text-red-400">{{ formatNumber($stats->sum('total_revenue') - $stats->sum('collected_amount')) }}</td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        @else
            <!-- No Filters Selected Message -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 text-center">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('general.select_date_range') }}</p>
            </div>
        @endif
    </div>
</x-layouts.app>
