<x-layouts.app :title="__('general.bookings')">
    <div class="px-4 pt-6">
        <!-- Success Message -->
        @if(session('success'))
            <div id="alert-success" class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
                <span class="sr-only">Info</span>
                <div class="ml-3 text-sm font-medium">
                    {{ session('success') }}
                </div>
                <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-success" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>
        @endif

        <!-- Header -->
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('general.bookings') }}</h1>
            <a href="{{ route('bookings.create') }}" class="text-white bg-brand-500 hover:bg-brand-600 focus:ring-4 focus:ring-brand-300 font-medium rounded text-sm px-5 py-2.5 dark:bg-brand-600 dark:hover:bg-brand-700 focus:outline-none dark:focus:ring-brand-800">
                <svg class="w-4 h-4 inline-block mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 5v10m-5-5h10"/>
                </svg>
                {{ __('general.add_booking') }}
            </a>
        </div>

        <!-- Table Card -->
        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
            <!-- Search & Filter Bar -->
            <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                <div class="w-full md:w-1/3">
                    <form action="{{ route('bookings.index') }}" method="GET" class="flex items-center gap-2">
                        <input type="hidden" name="court_id" value="{{ request('court_id') }}">
                        <input type="hidden" name="date" value="{{ request('date') }}">
                        <input type="hidden" name="status" value="{{ request('status') }}">
                        <label for="search" class="sr-only">{{ __('general.search') }}</label>
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" id="search" name="search" value="{{ request('search') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500" placeholder="{{ __('general.search_customer') }}">
                        </div>
                        <button type="submit" class="text-white bg-brand-500 hover:bg-brand-600 focus:ring-4 focus:ring-brand-300 font-medium rounded text-sm px-4 py-2 dark:bg-brand-600 dark:hover:bg-brand-700 focus:outline-none dark:focus:ring-brand-800">
                            {{ __('general.search') }}
                        </button>
                    </form>
                </div>
                <div class="w-full md:w-auto flex flex-wrap items-center gap-2">
                    <form action="{{ route('bookings.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <select name="court_id" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500">
                            <option value="">{{ __('general.all_courts') }}</option>
                            @foreach($courts as $court)
                                <option value="{{ $court->id }}" {{ request('court_id') == $court->id ? 'selected' : '' }}>{{ $court->name }} ({{ $court->ground?->name }})</option>
                            @endforeach
                        </select>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                </svg>
                            </div>
                            <input id="filter-datepicker" datepicker datepicker-autohide datepicker-format="yyyy-mm-dd" name="date" value="{{ request('date') }}" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-32 ps-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500" placeholder="{{ __('general.select_date') }}" autocomplete="off">
                        </div>
                        <select name="status" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500">
                            <option value="">{{ __('general.all_statuses') }}</option>
                            <option value="confirmed" {{ request('status') == \App\Enums\BookingStatus::Confirmed ? 'selected' : '' }}>{{ __('general.confirmed') }}</option>
                            <option value="completed" {{ request('status') == \App\Enums\BookingStatus::Completed ? 'selected' : '' }}>{{ __('general.completed') }}</option>
                            <option value="cancelled" {{ request('status') == \App\Enums\BookingStatus::Cancelled ? 'selected' : '' }}>{{ __('general.cancelled') }}</option>
                        </select>
                    </form>
                    @if(request('search') || request('court_id') || request('date') || request('status'))
                        <a href="{{ route('bookings.index') }}" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-300 font-medium rounded text-sm px-4 py-2 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 border border-gray-300">
                            {{ __('general.clear') }}
                        </a>
                    @endif
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-sm text-body bg-neutral-secondary-soft border-b rounded-base border-default">
                        <tr>
                            <th scope="col" class="px-4 py-3">{{ __('general.customer') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('general.court') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('general.date') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('general.time_slot') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('general.amount') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('general.status') }}</th>
                            <th scope="col" class="px-4 py-3">
                                <span class="sr-only">{{ __('general.actions') }}</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $booking->user?->name ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ $booking->user?->contact_no ?? $booking->user?->email }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div>{{ $booking->court?->name ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ $booking->court?->ground?->name ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    {{ formatDate($booking->date) }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ $booking->getFormattedTimeSlot() }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-medium">{{ formatNumber($booking->total_amount) }}</div>
                                    @if($booking->paid_amount > 0)
                                        <div class="text-xs text-green-600">{{ __('general.paid') }}: {{ formatNumber($booking->paid_amount) }}</div>
                                    @endif
                                    @if($booking->getBalanceDue() > 0)
                                        <div class="text-xs text-red-600">{{ __('general.due') }}: {{ formatNumber($booking->getBalanceDue()) }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <span class="{{ $booking->status->badgeClasses() }} text-xs font-medium px-1.5 py-0.5 rounded">{{ $booking->status->label() }}</span>
                                </td>
                                <td class="px-4 py-3 flex items-center justify-end gap-2">
                                    <a href="{{ route('bookings.show', $booking) }}" class="text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 font-medium rounded text-sm px-3 py-2 dark:bg-gray-600 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800">
                                        {{ __('general.view') }}
                                    </a>
                                    <a href="{{ route('bookings.edit', $booking) }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded text-sm px-3 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                        {{ __('general.edit') }}
                                    </a>
                                    <button onclick="confirmDelete({{ $booking->id }}, '{{ addslashes($booking->user?->name ?? 'Booking #' . $booking->id) }}')" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded text-sm px-3 py-2 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-900">
                                        {{ __('general.delete') }}
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-lg font-semibold">{{ __('general.no_bookings_found') }}</p>
                                    <p class="mt-1">{{ __('general.get_started_booking') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-4 py-3 border-t dark:border-gray-700">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <x-crud.delete-modal id="delete-modal" />

    @push('scripts')
    <script>
        window.addEventListener('load', function() {
            ModalManager.setupCloseButtons('delete-modal');

            window.confirmDelete = function(id, name) {
                document.querySelector('#delete-modal .delete-item-name').textContent = name;
                document.querySelector('#delete-modal .delete-form').action = `/bookings/${id}`;
                ModalManager.show('delete-modal');
            };

            // Handle datepicker change to auto-submit filter form
            const filterDatepicker = document.getElementById('filter-datepicker');
            if (filterDatepicker) {
                filterDatepicker.addEventListener('changeDate', function() {
                    this.closest('form').submit();
                });
            }
        });
    </script>
    @endpush
</x-layouts.app>
