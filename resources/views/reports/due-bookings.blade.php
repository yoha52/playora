<x-layouts.app :title="__('general.due_bookings_report')">
    <div class="px-4 pt-6">
        <!-- Header -->
        <div class="mb-4 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('general.due_bookings_report') }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('general.due_bookings_report_desc') }}</p>
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
                <form action="{{ route('reports.due-bookings') }}" method="GET" class="flex flex-wrap items-end gap-4">
                    <div>
                        <label for="court_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.court') }}</label>
                        <select name="court_id" id="court_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-48 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">{{ __('general.all_courts') }}</option>
                            @foreach($courts as $court)
                                <option value="{{ $court->id }}" {{ request('court_id') == $court->id ? 'selected' : '' }}>{{ $court->name }} ({{ $court->ground?->name }})</option>
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
                            <input id="from_date" datepicker datepicker-autohide datepicker-format="yyyy-mm-dd" name="from_date" value="{{ request('from_date') }}" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-40 ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="{{ __('general.select_date') }}" required autocomplete="off">
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
                            <input id="to_date" datepicker datepicker-autohide datepicker-format="yyyy-mm-dd" name="to_date" value="{{ request('to_date') }}" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-40 ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="{{ __('general.select_date') }}" required autocomplete="off">
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="text-white bg-brand-500 hover:bg-brand-600 focus:ring-4 focus:ring-brand-300 font-medium rounded text-sm px-5 py-2.5 dark:bg-brand-600 dark:hover:bg-brand-700 focus:outline-none dark:focus:ring-brand-800">
                            {{ __('general.filter') }}
                        </button>
                        @if(request()->hasAny(['court_id', 'from_date', 'to_date']))
                            <a href="{{ route('reports.due-bookings') }}" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-300 font-medium rounded text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 border border-gray-300">
                                {{ __('general.clear') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        @if($hasFilters ?? false)
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('general.total_amount') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ formatNumber($totals['total_amount']) }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('general.paid_amount') }}</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ formatNumber($totals['paid_amount']) }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('general.total_due') }}</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ formatNumber($totals['due_amount']) }}</p>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-sm text-body bg-neutral-secondary-soft border-b rounded-base border-default">
                            <tr>
                                <th scope="col" class="px-4 py-3">{{ __('general.booking_id') }}</th>
                                <th scope="col" class="px-4 py-3">{{ __('general.customer') }}</th>
                                <th scope="col" class="px-4 py-3">{{ __('general.court') }}</th>
                                <th scope="col" class="px-4 py-3">{{ __('general.date') }}</th>
                                <th scope="col" class="px-4 py-3">{{ __('general.total_amount') }}</th>
                                <th scope="col" class="px-4 py-3">{{ __('general.paid_amount') }}</th>
                                <th scope="col" class="px-4 py-3">{{ __('general.balance_due') }}</th>
                                <th scope="col" class="px-4 py-3">
                                    <span class="sr-only">{{ __('general.actions') }}</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">#{{ $booking->id }}</td>
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $booking->user?->name ?? '-' }}</div>
                                        <div class="text-xs text-gray-500">{{ $booking->user?->contact_no ?? $booking->user?->email }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div>{{ $booking->court?->name ?? '-' }}</div>
                                        <div class="text-xs text-gray-500">{{ $booking->court?->ground?->name ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div>{{ formatDate($booking->date) }}</div>
                                        <div class="text-xs text-gray-500">{{ $booking->getFormattedTimeSlot() }}</div>
                                    </td>
                                    <td class="px-4 py-3">{{ formatNumber($booking->total_amount) }}</td>
                                    <td class="px-4 py-3 text-green-600 dark:text-green-400">{{ formatNumber($booking->paid_amount) }}</td>
                                    <td class="px-4 py-3 font-semibold text-red-600 dark:text-red-400">{{ formatNumber($booking->getBalanceDue()) }}</td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('bookings.show', $booking) }}" class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 font-medium rounded text-sm px-3 py-2 dark:bg-gray-600 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800">
                                            {{ __('general.view') }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <p class="text-lg font-semibold">{{ __('general.no_due_bookings') }}</p>
                                        <p class="mt-1">{{ __('general.all_payments_received') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($bookings instanceof \Illuminate\Pagination\LengthAwarePaginator && $bookings->hasPages())
                    <div class="px-4 py-3 border-t dark:border-gray-700">
                        {{ $bookings->links() }}
                    </div>
                @endif
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
