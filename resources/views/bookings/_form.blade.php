@props(['booking' => null, 'courts', 'selectedCourt' => null, 'selectedDate' => null])

<div class="grid gap-6">
    <!-- User, Date, Court Selection - First Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- User Selection -->
        <div>
            <div class="flex items-center justify-between mb-2">
                <label class="text-sm font-medium text-gray-900 dark:text-white">{{ __('general.user') }} <span class="text-red-500">*</span></label>
                <button type="button" data-modal-target="createUserModal" data-modal-toggle="createUserModal" class="text-sm text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ __('general.add_new_user') }}
                </button>
            </div>
            <div class="relative">
                <input type="text" id="user_search" autocomplete="off" placeholder="{{ __('general.search_user') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500">
                <input type="hidden" name="user_id" id="user_id" value="{{ old('user_id', $booking?->user_id) }}">

                <!-- Search Results Dropdown -->
                <div id="userSearchResults" class="hidden absolute z-20 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg dark:bg-gray-700 dark:border-gray-600 max-h-60 overflow-y-auto">
                    <div id="userResultsList" class="py-1">
                        <!-- Results will be loaded here -->
                    </div>
                    <div id="userSearchLoading" class="hidden px-4 py-3 text-center text-gray-500 dark:text-gray-400">
                        <svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <div id="userSearchEmpty" class="hidden px-4 py-3 text-center text-gray-500 dark:text-gray-400">
                        {{ __('general.no_users_found') }}
                    </div>
                </div>
            </div>

            <!-- Selected User Display -->
            <div id="selectedUserDisplay" class="hidden mt-2 p-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white" id="selectedUserName"></p>
                        <p class="text-sm text-gray-500 dark:text-gray-400" id="selectedUserInfo"></p>
                    </div>
                    <button type="button" id="clearUserSelection" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            @error('user_id')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Date Selection -->
        <div>
            <label for="datepicker" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.date') }} <span class="text-red-500">*</span></label>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                    </svg>
                </div>
                <input id="datepicker" datepicker datepicker-autohide datepicker-format="yyyy-mm-dd" datepicker-min-date="{{ now()->format('Y-m-d') }}" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500" placeholder="{{ __('general.select_date') }}" value="{{ old('date', $booking?->date?->format('Y-m-d') ?? $selectedDate) }}" required autocomplete="off">
            </div>
            <input type="hidden" name="date" id="date" value="{{ old('date', $booking?->date?->format('Y-m-d') ?? $selectedDate) }}">
            @error('date')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Court Selection with Search Dropdown -->
        <div class="relative">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.court') }} <span class="text-red-500">*</span></label>
            <button id="courtDropdownButton" data-dropdown-toggle="courtDropdown" data-dropdown-placement="bottom" class="w-full text-left bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500 flex items-center justify-between" type="button">
                <span id="selectedCourtText">{{ $selectedCourt ? $selectedCourt->name . ' (' . $selectedCourt->ground?->name . ')' : __('general.select_court') }}</span>
                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                </svg>
            </button>
            <input type="hidden" name="court_id" id="court_id" value="{{ old('court_id', $booking?->court_id ?? $selectedCourt?->id) }}">

            <!-- Dropdown menu -->
            <div id="courtDropdown" class="z-10 hidden absolute left-0 right-0 bg-white border border-gray-200 rounded-lg shadow-lg dark:bg-gray-700 dark:border-gray-600">
                <div class="p-3 border-b border-gray-200 dark:border-gray-600">
                    <label for="court-search" class="sr-only">{{ __('general.search') }}</label>
                    <input type="text" id="court-search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-full p-2 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="{{ __('general.search_court') }}">
                </div>
                <ul class="max-h-48 overflow-y-auto p-2 text-sm text-gray-700 dark:text-gray-200" id="courtList">
                    @foreach($courts as $court)
                        <li>
                            <button type="button" data-court-id="{{ $court->id }}" data-court-name="{{ $court->name }} ({{ $court->ground?->name }})" data-court-rate="{{ $court->rate_per_hour }}" class="court-option flex items-center w-full px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600">
                                <img src="{{ $court->getFirstMediaUrl('picture') }}" alt="{{ $court->name }}" class="w-8 h-8 rounded mr-3 object-cover">
                                <div class="text-left">
                                    <div>{{ $court->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $court->ground?->name }} - {{ formatNumber($court->rate_per_hour) }}/hr</div>
                                </div>
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
            @error('court_id')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Time Slots Display -->
    <div id="timeSlotsContainer" class="hidden">
        <h3 class="mb-4 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.available_time_slots') }} <span class="text-red-500">*</span></h3>
        <div id="timeSlotsGrid" class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-2">
            <!-- Slots will be loaded dynamically -->
        </div>
        <div id="timeSlotsLoading" class="text-center py-4 hidden">
            <svg class="animate-spin h-8 w-8 mx-auto text-brand-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="mt-2 text-sm text-gray-500">{{ __('general.loading_slots') }}</p>
        </div>
        <div id="timeSlotsError" class="text-center py-4 hidden">
            <p class="text-sm text-red-600">{{ __('general.error_loading_slots') }}</p>
        </div>
        @error('start_time')
            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <!-- Hidden inputs for start and end time -->
    <input type="hidden" name="start_time" id="start_time" value="{{ old('start_time', $booking ? formatTime($booking->start_time) : '') }}">
    <input type="hidden" name="end_time" id="end_time" value="{{ old('end_time', $booking ? formatTime($booking->end_time) : '') }}">

    <!-- Selected Time Display -->
    <div id="selectedTimeDisplay" class="hidden p-4 bg-brand-50 dark:bg-brand-900/20 border border-brand-200 dark:border-brand-800 rounded-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ __('general.selected_time') }}</p>
                <p class="text-lg font-bold text-brand-600 dark:text-brand-400" id="selectedTimeText">-</p>
            </div>
            <div class="text-right">
                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ __('general.estimated_total') }}</p>
                <p class="text-lg font-bold text-brand-600 dark:text-brand-400" id="estimatedTotalText">-</p>
            </div>
        </div>
    </div>

    <!-- Paid Amount and Status -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Paid Amount -->
        <div>
            <label for="paid_amount" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.paid_amount') }}</label>
            <input type="number" name="paid_amount" id="paid_amount" value="{{ old('paid_amount', $booking?->paid_amount ?? 0) }}" step="0.01" min="0" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500">
            @error('paid_amount')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Status -->
        @if($booking)
        <div>
            <label for="status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.status') }}</label>
            <select name="status" id="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500">
                @foreach(\App\Enums\BookingStatus::cases() as $status)
                    <option value="{{ $status->value }}" {{ old('status', $booking->status->value) === $status->value ? 'selected' : '' }}>{{ $status->label() }}</option>
                @endforeach
            </select>
            @error('status')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>
        @endif
    </div>

    <!-- Notes -->
    <div>
        <label for="notes" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.notes') }}</label>
        <textarea name="notes" id="notes" rows="3" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500">{{ old('notes', $booking?->notes) }}</textarea>
        @error('notes')
            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
        @enderror
    </div>
</div>

@push('modals')
<!-- Create User Modal -->
<div id="createUserModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ __('general.add_new_user') }}
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="createUserModal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5">
                <form id="createUserForm">
                    <div class="grid gap-4 mb-4">
                        <div>
                            <label for="new_user_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.name') }} <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="new_user_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-600 focus:border-brand-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500" placeholder="{{ __('general.enter_name') }}" required>
                            <p id="new_user_name_error" class="mt-2 text-sm text-red-600 dark:text-red-500 hidden"></p>
                        </div>
                        <div>
                            <label for="new_user_email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.email') }} <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="new_user_email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-600 focus:border-brand-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500" placeholder="{{ __('general.enter_email') }}" required>
                            <p id="new_user_email_error" class="mt-2 text-sm text-red-600 dark:text-red-500 hidden"></p>
                        </div>
                        <div>
                            <label for="new_user_contact_no" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.contact_no') }}</label>
                            <input type="text" name="contact_no" id="new_user_contact_no" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-600 focus:border-brand-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500" placeholder="{{ __('general.enter_contact_no') }}">
                            <p id="new_user_contact_no_error" class="mt-2 text-sm text-red-600 dark:text-red-500 hidden"></p>
                        </div>
                    </div>
                    <button type="submit" id="createUserSubmit" class="w-full text-white bg-brand-500 hover:bg-brand-600 focus:ring-4 focus:outline-none focus:ring-brand-300 font-medium rounded text-sm px-5 py-2.5 text-center dark:bg-brand-600 dark:hover:bg-brand-700 dark:focus:ring-brand-800">
                        {{ __('general.create_user') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const courtIdInput = document.getElementById('court_id');
        const dateInput = document.getElementById('date');
        const datepickerInput = document.getElementById('datepicker');
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');
        const timeSlotsContainer = document.getElementById('timeSlotsContainer');
        const timeSlotsGrid = document.getElementById('timeSlotsGrid');
        const timeSlotsLoading = document.getElementById('timeSlotsLoading');
        const timeSlotsError = document.getElementById('timeSlotsError');
        const selectedTimeDisplay = document.getElementById('selectedTimeDisplay');
        const selectedTimeText = document.getElementById('selectedTimeText');
        const estimatedTotalText = document.getElementById('estimatedTotalText');
        const selectedCourtText = document.getElementById('selectedCourtText');
        const courtSearch = document.getElementById('court-search');
        const courtList = document.getElementById('courtList');
        const courtOptions = courtList?.querySelectorAll('.court-option');

        let selectedSlots = [];
        let courtRate = {{ $selectedCourt?->rate_per_hour ?? 0 }};
        let allSlots = [];

        // Initialize selected court text
        const initialCourtId = courtIdInput?.value;
        if (initialCourtId && courtOptions) {
            courtOptions.forEach(function(option) {
                if (option.dataset.courtId === initialCourtId) {
                    selectedCourtText.textContent = option.dataset.courtName;
                    courtRate = parseFloat(option.dataset.courtRate);
                }
            });
        }

        // Court search functionality
        if (courtSearch) {
            courtSearch.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                courtOptions.forEach(function(option) {
                    const courtName = option.dataset.courtName.toLowerCase();
                    const listItem = option.closest('li');
                    listItem.style.display = courtName.includes(searchTerm) ? '' : 'none';
                });
            });
        }

        // Court selection
        if (courtOptions) {
            courtOptions.forEach(function(option) {
                option.addEventListener('click', function() {
                    courtIdInput.value = this.dataset.courtId;
                    selectedCourtText.textContent = this.dataset.courtName;
                    courtRate = parseFloat(this.dataset.courtRate);

                    // Close dropdown
                    const dropdown = FlowbiteInstances.getInstance('Dropdown', 'courtDropdown');
                    if (dropdown) {
                        dropdown.hide();
                    }

                    // Reload time slots
                    loadTimeSlots();
                });
            });
        }

        // Flowbite datepicker change handler
        if (datepickerInput) {
            datepickerInput.addEventListener('changeDate', function(e) {
                dateInput.value = this.value;
                loadTimeSlots();
            });

            // Also handle manual input change
            datepickerInput.addEventListener('change', function() {
                dateInput.value = this.value;
                loadTimeSlots();
            });
        }

        // Load time slots on page load if court and date are selected
        if (courtIdInput?.value && dateInput?.value) {
            loadTimeSlots();
        }

        function loadTimeSlots() {
            const courtId = courtIdInput?.value;
            const date = dateInput?.value;

            if (!courtId || !date) {
                timeSlotsContainer.classList.add('hidden');
                return;
            }

            // Show loading
            timeSlotsContainer.classList.remove('hidden');
            timeSlotsGrid.classList.add('hidden');
            timeSlotsLoading.classList.remove('hidden');
            timeSlotsError.classList.add('hidden');

            fetch(`{{ route('bookings.available-slots') }}?court_id=${courtId}&date=${date}`)
                .then(response => response.json())
                .then(data => {
                    timeSlotsLoading.classList.add('hidden');
                    timeSlotsGrid.classList.remove('hidden');
                    allSlots = data.slots;
                    renderTimeSlots(data.slots);

                    // Restore selection if editing
                    if (startTimeInput.value && endTimeInput.value) {
                        restoreSelection();
                    }
                })
                .catch(error => {
                    timeSlotsLoading.classList.add('hidden');
                    timeSlotsError.classList.remove('hidden');
                    console.error('Error loading slots:', error);
                });
        }

        function renderTimeSlots(slots) {
            timeSlotsGrid.innerHTML = '';

            slots.forEach((slot, index) => {
                const slotEl = document.createElement('button');
                slotEl.type = 'button';
                slotEl.dataset.index = index;
                slotEl.dataset.startTime = slot.start_time;
                slotEl.dataset.endTime = slot.end_time;

                const baseClasses = 'p-2 text-xs font-medium rounded border text-center transition-colors';

                if (slot.available) {
                    slotEl.className = `${baseClasses} bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-300 dark:border-gray-600 hover:bg-brand-50 dark:hover:bg-brand-900/20 cursor-pointer slot-available`;
                    slotEl.addEventListener('click', () => toggleSlotSelection(slotEl, index));
                } else {
                    slotEl.className = `${baseClasses} bg-gray-100 dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-400 cursor-not-allowed`;
                    slotEl.disabled = true;
                }

                slotEl.textContent = slot.display_time;
                timeSlotsGrid.appendChild(slotEl);
            });
        }

        function toggleSlotSelection(slotEl, index) {
            const slotIndex = selectedSlots.indexOf(index);

            if (slotIndex > -1) {
                // Deselect
                selectedSlots.splice(slotIndex, 1);
            } else {
                // Select - check if contiguous
                if (selectedSlots.length === 0) {
                    selectedSlots.push(index);
                } else {
                    const min = Math.min(...selectedSlots);
                    const max = Math.max(...selectedSlots);

                    if (index === min - 1 || index === max + 1) {
                        selectedSlots.push(index);
                    } else if (index >= min && index <= max) {
                        // Already in range
                    } else {
                        // Start new selection
                        selectedSlots = [index];
                    }
                }
            }

            selectedSlots.sort((a, b) => a - b);
            updateSlotStyles();
            updateSelectedTime();
        }

        function updateSlotStyles() {
            const slotButtons = timeSlotsGrid.querySelectorAll('button.slot-available');
            slotButtons.forEach((btn, idx) => {
                const index = parseInt(btn.dataset.index);
                if (selectedSlots.includes(index)) {
                    btn.classList.remove('bg-white', 'dark:bg-gray-700', 'border-gray-300', 'dark:border-gray-600');
                    btn.classList.add('bg-brand-500', 'text-white', 'border-brand-500');
                } else {
                    btn.classList.add('bg-white', 'dark:bg-gray-700', 'border-gray-300', 'dark:border-gray-600');
                    btn.classList.remove('bg-brand-500', 'text-white', 'border-brand-500');
                }
            });
        }

        function updateSelectedTime() {
            if (selectedSlots.length === 0) {
                startTimeInput.value = '';
                endTimeInput.value = '';
                selectedTimeDisplay.classList.add('hidden');
                return;
            }

            const minIndex = Math.min(...selectedSlots);
            const maxIndex = Math.max(...selectedSlots);

            const startTime = allSlots[minIndex].start_time;
            const endTime = allSlots[maxIndex].end_time;
            const displayStartTime = allSlots[minIndex].display_time;
            const displayEndTime = formatTo12Hour(endTime);

            startTimeInput.value = startTime;
            endTimeInput.value = endTime;

            const hours = selectedSlots.length;
            const total = hours * courtRate;

            selectedTimeText.textContent = `${displayStartTime} - ${displayEndTime} (${hours} ${hours === 1 ? i18n.general.hour : i18n.general.hours})`;
            estimatedTotalText.textContent = total.toFixed(2);
            selectedTimeDisplay.classList.remove('hidden');
        }

        function formatTo12Hour(time24) {
            const [hours, minutes] = time24.split(':');
            const hour = parseInt(hours);
            const ampm = hour >= 12 ? 'PM' : 'AM';
            const hour12 = hour % 12 || 12;
            return `${hour12}:${minutes} ${ampm}`;
        }

        function restoreSelection() {
            const startTime = startTimeInput.value;
            const endTime = endTimeInput.value;

            if (!startTime || !endTime) return;

            selectedSlots = [];
            allSlots.forEach((slot, index) => {
                if (slot.start_time >= startTime && slot.end_time <= endTime) {
                    selectedSlots.push(index);
                }
            });

            updateSlotStyles();
            updateSelectedTime();
        }

        // User Search Functionality
        const userSearchInput = document.getElementById('user_search');
        const userIdInput = document.getElementById('user_id');
        const userSearchResults = document.getElementById('userSearchResults');
        const userResultsList = document.getElementById('userResultsList');
        const userSearchLoading = document.getElementById('userSearchLoading');
        const userSearchEmpty = document.getElementById('userSearchEmpty');
        const selectedUserDisplay = document.getElementById('selectedUserDisplay');
        const selectedUserName = document.getElementById('selectedUserName');
        const selectedUserInfo = document.getElementById('selectedUserInfo');
        const clearUserSelection = document.getElementById('clearUserSelection');

        let userSearchTimeout;

        // Initialize if editing existing booking
        @if($booking && $booking->user)
        selectUser({
            id: {{ $booking->user->id }},
            name: "{{ $booking->user->name }}",
            email: "{{ $booking->user->email }}",
            contact_no: "{{ $booking->user->contact_no ?? '' }}"
        });
        @endif

        if (userSearchInput) {
            userSearchInput.addEventListener('input', function() {
                clearTimeout(userSearchTimeout);
                const search = this.value.trim();

                if (search.length < 2) {
                    userSearchResults.classList.add('hidden');
                    return;
                }

                userSearchResults.classList.remove('hidden');
                userResultsList.classList.add('hidden');
                userSearchLoading.classList.remove('hidden');
                userSearchEmpty.classList.add('hidden');

                userSearchTimeout = setTimeout(() => {
                    fetch(`{{ route('users.search') }}?search=${encodeURIComponent(search)}`)
                        .then(response => response.json())
                        .then(users => {
                            userSearchLoading.classList.add('hidden');

                            if (users.length === 0) {
                                userSearchEmpty.classList.remove('hidden');
                                return;
                            }

                            userResultsList.innerHTML = '';
                            users.forEach(user => {
                                const item = document.createElement('button');
                                item.type = 'button';
                                item.className = 'w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-600';
                                item.innerHTML = `
                                    <div class="font-medium text-gray-900 dark:text-white">${user.name}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">${user.email}${user.contact_no ? ' - ' + user.contact_no : ''}</div>
                                `;
                                item.addEventListener('click', () => selectUser(user));
                                userResultsList.appendChild(item);
                            });

                            userResultsList.classList.remove('hidden');
                        })
                        .catch(error => {
                            console.error('Error searching users:', error);
                            userSearchLoading.classList.add('hidden');
                        });
                }, 300);
            });

            userSearchInput.addEventListener('focus', function() {
                if (this.value.trim().length >= 2) {
                    userSearchResults.classList.remove('hidden');
                }
            });

            // Close search results when clicking outside
            document.addEventListener('click', function(e) {
                if (!userSearchInput.contains(e.target) && !userSearchResults.contains(e.target)) {
                    userSearchResults.classList.add('hidden');
                }
            });
        }

        if (clearUserSelection) {
            clearUserSelection.addEventListener('click', function() {
                userIdInput.value = '';
                userSearchInput.value = '';
                selectedUserDisplay.classList.add('hidden');
                userSearchInput.classList.remove('hidden');
            });
        }

        function selectUser(user) {
            userIdInput.value = user.id;
            selectedUserName.textContent = user.name;
            selectedUserInfo.textContent = `${user.email}${user.contact_no ? ' - ' + user.contact_no : ''}`;
            selectedUserDisplay.classList.remove('hidden');
            userSearchInput.classList.add('hidden');
            userSearchResults.classList.add('hidden');
            userSearchInput.value = '';
        }

        // Make selectUser available globally for the modal
        window.selectUser = selectUser;

        // Create User Form Handling
        const createUserForm = document.getElementById('createUserForm');
        if (createUserForm) {
            createUserForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Clear previous errors
                document.querySelectorAll('[id$="_error"]').forEach(el => {
                    el.classList.add('hidden');
                    el.textContent = '';
                });

                const formData = new FormData(createUserForm);
                const submitBtn = document.getElementById('createUserSubmit');
                const originalText = submitBtn.textContent;
                submitBtn.disabled = true;
                submitBtn.textContent = '...';

                fetch('{{ route('users.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(Object.fromEntries(formData)),
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw data;
                        });
                    }
                    return response.json();
                })
                .then(user => {
                    // Select the newly created user
                    selectUser(user);

                    // Close the modal
                    const modal = FlowbiteInstances.getInstance('Modal', 'createUserModal');
                    if (modal) {
                        modal.hide();
                    }

                    // Reset the form
                    createUserForm.reset();
                })
                .catch(error => {
                    if (error.errors) {
                        Object.keys(error.errors).forEach(field => {
                            const errorEl = document.getElementById(`new_user_${field}_error`);
                            if (errorEl) {
                                errorEl.textContent = error.errors[field][0];
                                errorEl.classList.remove('hidden');
                            }
                        });
                    }
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                });
            });
        }
    });
</script>
@endpush
