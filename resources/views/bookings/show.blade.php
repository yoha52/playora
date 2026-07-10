<x-layouts.app :title="__('general.booking_details')">
    <div class="px-4 pt-6">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('general.booking_details') }}</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('general.booking_id') }}: #{{ $booking->id }}</p>
            </div>
            <div class="flex gap-2">
                @if($booking->status !== \App\Enums\BookingStatus::Cancelled)
                    <a href="{{ route('bookings.edit', $booking) }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        <svg class="w-4 h-4 inline-block mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                        </svg>
                        {{ __('general.edit') }}
                    </a>
                @endif
                <a href="{{ route('bookings.index') }}" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-300 font-medium rounded text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 border border-gray-300">
                    <svg class="w-4 h-4 inline-block mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4"/>
                    </svg>
                    {{ __('general.back_to_list') }}
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Info Card -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('general.booking_information') }}</h2>

                    <!-- Status Badge -->
                    <div class="mb-6">
                        <span class="{{ $booking->status->badgeClasses() }} text-sm font-medium px-3 py-1 rounded">{{ $booking->status->label() }}</span>
                    </div>

                    <!-- Court Info -->
                    <div class="flex items-start gap-4 mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <img src="{{ $booking->court?->getFirstMediaUrl('picture') }}" alt="{{ $booking->court?->name }}" class="w-20 h-20 rounded-lg object-cover">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $booking->court?->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $booking->court?->ground?->name }}</p>
                            @if($booking->court?->category)
                                <div class="flex items-center gap-2 mt-2">
                                    <img src="{{ $booking->court->category->getFirstMediaUrl('picture') }}" alt="{{ $booking->court->category->name }}" class="w-5 h-5 rounded">
                                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ $booking->court->category->name }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Date & Time -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('general.date') }}</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ formatDate($booking->date) }} ({{ $booking->date->dayName }})</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('general.time_slot') }}</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $booking->getFormattedTimeSlot() }}
                                <span class="text-sm font-normal text-gray-500">({{ $booking->getDurationInHours() }} {{ $booking->getDurationInHours() === 1 ? __('general.hour') : __('general.hours') }})</span>
                            </p>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($booking->notes)
                        <div class="border-t dark:border-gray-700 pt-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">{{ __('general.notes') }}</p>
                            <p class="text-gray-900 dark:text-white">{{ $booking->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Customer Card -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('general.customer_information') }}</h2>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('general.name') }}</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $booking->user?->name ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('general.email') }}</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $booking->user?->email ?? '-' }}</p>
                            </div>
                            @if($booking->user?->contact_no)
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('general.contact_no') }}</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $booking->user->contact_no }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Payment Card -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('general.payment_information') }}</h2>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">{{ __('general.rate_per_hour') }}</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ formatNumber($booking->court?->rate_per_hour) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">{{ __('general.duration') }}</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $booking->getDurationInHours() }} {{ $booking->getDurationInHours() === 1 ? __('general.hour') : __('general.hours') }}</span>
                            </div>
                            <div class="border-t dark:border-gray-700 pt-3 flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">{{ __('general.total_amount') }}</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ formatNumber($booking->total_amount) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">{{ __('general.paid_amount') }}</span>
                                <span class="font-medium text-brand-600 dark:text-brand-400">{{ formatNumber($booking->paid_amount) }}</span>
                            </div>
                            <div class="border-t dark:border-gray-700 pt-3 flex justify-between">
                                <span class="font-medium text-gray-700 dark:text-gray-300">{{ __('general.balance_due') }}</span>
                                <span class="font-bold text-lg {{ $booking->isFullyPaid() ? 'text-green-600 dark:text-green-600' : 'text-red-600 dark:text-red-600' }}">{{ formatNumber($booking->getBalanceDue()) }}</span>
                            </div>
                            @if($booking->isFullyPaid())
                                <div class="pt-2">
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">{{ __('general.fully_paid') }}</span>
                                </div>
                            @endif
                        </div>

                        @if($booking->canReceivePayment())
                            <div class="mt-4 pt-4 border-t dark:border-gray-700">
                                <button type="button" data-modal-target="receive-payment-modal" data-modal-toggle="receive-payment-modal" class="w-full text-white bg-brand-700 hover:bg-brand-800 focus:ring-4 focus:ring-brand-300 font-medium rounded text-sm px-5 py-2.5 dark:bg-brand-600 dark:hover:bg-brand-700 focus:outline-none dark:focus:ring-brand-800">
                                    <svg class="w-4 h-4 inline-block mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6"/>
                                    </svg>
                                    {{ __('general.receive_payment') }}
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions Card -->
                @if($booking->canCancel())
                    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('general.actions') }}</h2>
                            <button type="button" data-modal-target="cancel-booking-modal" data-modal-toggle="cancel-booking-modal" class="w-full text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded text-sm px-5 py-2.5 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800">
                                <svg class="w-4 h-4 inline-block mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6m0 12L6 6"/>
                                </svg>
                                {{ __('general.cancel_booking') }}
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Timestamps -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                    <div class="p-6">
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">{{ __('general.created_at') }}</span>
                                <span class="text-gray-900 dark:text-white">{{ formatDateTime($booking->created_at) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">{{ __('general.updated_at') }}</span>
                                <span class="text-gray-900 dark:text-white">{{ formatDateTime($booking->updated_at) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Receive Payment Modal -->
    @if($booking->canReceivePayment())
        <div id="receive-payment-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                            {{ __('general.receive_payment') }}
                        </h3>
                        <button type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="receive-payment-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="p-4 md:p-5">
                        <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-600 rounded-lg">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500 dark:text-gray-400">{{ __('general.balance_due') }}</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ formatNumber($booking->getBalanceDue()) }}</span>
                            </div>
                        </div>
                        <form action="{{ route('bookings.receive-payment', $booking) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="amount" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.amount') }}</label>
                                <input type="number" name="amount" id="amount" step="0.01" min="0.01" max="{{ $booking->getBalanceDue() }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="{{ __('general.enter_amount') }}" required>
                                @error('amount')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex gap-3">
                                <button type="button" data-modal-hide="receive-payment-modal" class="flex-1 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                    {{ __('general.cancel') }}
                                </button>
                                <button type="submit" class="flex-1 text-white bg-brand-700 hover:bg-brand-800 focus:ring-4 focus:outline-none focus:ring-brand-300 font-medium rounded text-sm px-5 py-2.5 text-center dark:bg-brand-600 dark:hover:bg-brand-700 dark:focus:ring-brand-800">
                                    {{ __('general.receive') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Cancel Booking Modal -->
    @if($booking->canCancel())
        <div id="cancel-booking-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="cancel-booking-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <div class="p-4 md:p-5 text-center">
                        <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">{{ __('general.cancel_booking_confirmation') }}</h3>
                        <div class="flex justify-center gap-3">
                            <button data-modal-hide="cancel-booking-modal" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                {{ __('general.no_keep_it') }}
                            </button>
                            <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded text-sm inline-flex items-center px-5 py-2.5 text-center">
                                    {{ __('general.yes_cancel') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-layouts.app>
