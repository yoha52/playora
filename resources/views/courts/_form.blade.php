@props(['court' => null, 'grounds', 'categories'])

<div class="grid gap-6">
    <!-- Name and Ground Fields in One Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Name Field -->
        <div>
            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.name') }} <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name', $court?->name) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500" required>
            @error('name')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Ground Selection with Search Dropdown -->
        <div class="relative">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.ground') }} <span class="text-red-500">*</span></label>
            <button id="groundDropdownButton" data-dropdown-toggle="groundDropdown" data-dropdown-placement="bottom" class="w-full text-left bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500 flex items-center justify-between" type="button">
                <span id="selectedGroundText">{{ __('general.select_ground') }}</span>
                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                </svg>
            </button>
            <input type="hidden" name="ground_id" id="ground_id" value="{{ old('ground_id', $court?->ground_id) }}">

            <!-- Dropdown menu -->
            <div id="groundDropdown" class="z-10 hidden absolute left-0 right-0 bg-white border border-gray-200 rounded-lg shadow-lg dark:bg-gray-700 dark:border-gray-600">
                <div class="p-3 border-b border-gray-200 dark:border-gray-600">
                    <label for="ground-search" class="sr-only">{{ __('general.search') }}</label>
                    <input type="text" id="ground-search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-full p-2 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="{{ __('general.search_ground') }}">
                </div>
                <ul class="max-h-48 overflow-y-auto p-2 text-sm text-gray-700 dark:text-gray-200" id="groundList">
                    @foreach($grounds as $ground)
                        <li>
                            <button type="button" data-ground-id="{{ $ground->id }}" data-ground-name="{{ $ground->name }}" class="ground-option flex items-center w-full px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600">
                                <img src="{{ $ground->getFirstMediaUrl('picture') }}" alt="{{ $ground->name }}" class="w-8 h-8 rounded mr-3 object-cover">
                                <span>{{ $ground->name }}</span>
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
            @error('ground_id')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Category Selection with Radio Cards -->
    <div>
        <h3 class="mb-4 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.category') }} <span class="text-red-500">*</span></h3>
        <ul class="select-none grid w-full gap-4 md:grid-cols-3 lg:grid-cols-4">
            @foreach($categories as $category)
                <li>
                    <input type="radio" id="category-{{ $category->id }}" name="category_id" value="{{ $category->id }}" class="hidden peer" {{ old('category_id', $court?->category_id) == $category->id ? 'checked' : '' }} required>
                    <label for="category-{{ $category->id }}" class="inline-flex items-center justify-center w-full p-4 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 peer-checked:border-brand-500 peer-checked:bg-brand-50 hover:text-gray-600 dark:peer-checked:bg-brand-900/20 hover:bg-gray-50 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700 peer-checked:text-brand-600 dark:peer-checked:text-brand-400">
                        <div class="block text-center">
                            <img src="{{ $category->getFirstMediaUrl('picture') }}" alt="{{ $category->name }}" class="w-12 h-12 mx-auto mb-2 rounded-lg object-cover">
                            <div class="w-full font-semibold">{{ $category->name }}</div>
                        </div>
                    </label>
                </li>
            @endforeach
        </ul>
        @error('category_id')
            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <!-- Time and Rate Fields -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Opening Time -->
        <div>
            <label for="opening_time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.opening_time') }} <span class="text-red-500">*</span></label>
            <div class="relative">
                <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                </div>
                <input type="time" name="opening_time" id="opening_time" value="{{ old('opening_time', $court?->opening_time ? \Carbon\Carbon::parse($court->opening_time)->format('H:i') : '06:00') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500" required>
            </div>
            @error('opening_time')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Closing Time -->
        <div>
            <label for="closing_time" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.closing_time') }} <span class="text-red-500">*</span></label>
            <div class="relative">
                <div class="absolute inset-y-0 end-0 top-0 flex items-center pe-3.5 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                </div>
                <input type="time" name="closing_time" id="closing_time" value="{{ old('closing_time', $court?->closing_time ? \Carbon\Carbon::parse($court->closing_time)->format('H:i') : '22:00') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500" required>
            </div>
            @error('closing_time')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Rate per Hour -->
        <div>
            <label for="rate_per_hour" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.rate_per_hour') }} <span class="text-red-500">*</span></label>
            <input type="number" name="rate_per_hour" id="rate_per_hour" value="{{ old('rate_per_hour', $court?->rate_per_hour ?? 0) }}" step="0.01" min="0" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500" required>
            @error('rate_per_hour')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Image Upload -->
    <div>
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.image') }} @if(!$court)<span class="text-red-500">*</span>@endif</label>
        <input type="file" name="image" id="court-image" accept="image/*">
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('general.image_upload_info_2mb') }}</p>
        @error('image')
            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
        @enderror

        @if($court && $court->getFirstMediaUrl('picture'))
            <div class="mt-3">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Current image:</p>
                <img src="{{ $court->getFirstMediaUrl('picture') }}" alt="{{ $court->name }}" class="w-32 h-32 object-cover rounded-lg">
            </div>
        @endif
    </div>

    <!-- Active Status -->
    <div class="flex items-center">
        <input type="checkbox" id="active" name="active" value="1" {{ old('active', $court?->active ?? true) ? 'checked' : '' }} class="w-4 h-4 text-brand-600 bg-gray-100 border-gray-300 rounded focus:ring-brand-500 dark:focus:ring-brand-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
        <label for="active" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __('general.active') }}</label>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ground dropdown search functionality
        const groundSearch = document.getElementById('ground-search');
        const groundList = document.getElementById('groundList');
        const groundOptions = groundList?.querySelectorAll('.ground-option');
        const groundIdInput = document.getElementById('ground_id');
        const selectedGroundText = document.getElementById('selectedGroundText');

        // Set initial selected ground text
        const initialGroundId = groundIdInput?.value;
        if (initialGroundId && groundOptions) {
            groundOptions.forEach(function(option) {
                if (option.dataset.groundId === initialGroundId) {
                    selectedGroundText.textContent = option.dataset.groundName;
                }
            });
        }

        // Search functionality
        if (groundSearch) {
            groundSearch.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                groundOptions.forEach(function(option) {
                    const groundName = option.dataset.groundName.toLowerCase();
                    const listItem = option.closest('li');
                    if (groundName.includes(searchTerm)) {
                        listItem.style.display = '';
                    } else {
                        listItem.style.display = 'none';
                    }
                });
            });
        }

        // Ground selection
        if (groundOptions) {
            groundOptions.forEach(function(option) {
                option.addEventListener('click', function() {
                    const groundId = this.dataset.groundId;
                    const groundName = this.dataset.groundName;

                    groundIdInput.value = groundId;
                    selectedGroundText.textContent = groundName;

                    // Close dropdown
                    const dropdown = FlowbiteInstances.getInstance('Dropdown', 'groundDropdown');
                    if (dropdown) {
                        dropdown.hide();
                    }
                });
            });
        }
    });
</script>
@endpush
