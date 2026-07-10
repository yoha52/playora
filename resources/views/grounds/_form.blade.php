@props(['ground' => null])

<div class="grid gap-6">
    <!-- Name Field -->
    <div>
        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.name') }} <span class="text-red-500">*</span></label>
        <input type="text" name="name" id="name" value="{{ old('name', $ground?->name) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500" required>
        @error('name')
            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <!-- Description Field -->
    <div>
        <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.description') }}</label>
        <textarea name="description" id="description" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500">{{ old('description', $ground?->description) }}</textarea>
        @error('description')
            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <!-- Address Field -->
    <div>
        <label for="address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.address') }} <span class="text-red-500">*</span></label>
        <textarea name="address" id="address" rows="2" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500" required>{{ old('address', $ground?->address) }}</textarea>
        @error('address')
            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <!-- Location Coordinates -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="latitude" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.latitude') }} <span class="text-red-500">*</span></label>
            <input type="number" step="any" name="latitude" id="latitude" value="{{ old('latitude', $ground?->latitude) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500" placeholder="e.g., 25.276987" required>
            @error('latitude')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="longitude" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.longitude') }} <span class="text-red-500">*</span></label>
            <input type="number" step="any" name="longitude" id="longitude" value="{{ old('longitude', $ground?->longitude) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500" placeholder="e.g., 55.296249" required>
            @error('longitude')
                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Facilities Section -->
    <div>
        <h3 class="mb-4 text-lg font-medium text-gray-900 dark:text-white">{{ __('general.facilities') }}</h3>
        <ul class="select-none grid w-full gap-4 md:grid-cols-2 lg:grid-cols-3">
            <!-- Parking Available -->
            <li>
                <input type="checkbox" id="parking_available" name="parking_available" value="1" class="hidden peer" {{ old('parking_available', $ground?->parking_available) ? 'checked' : '' }}>
                <label for="parking_available" class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 peer-checked:border-brand-500 peer-checked:bg-brand-50 hover:text-gray-600 dark:peer-checked:bg-brand-900/20 hover:bg-gray-50 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700 peer-checked:text-brand-600 dark:peer-checked:text-brand-400">
                    <div class="block">
                        <svg class="mb-2 w-7 h-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                        </svg>
                        <div class="w-full font-semibold">{{ __('general.parking_available') }}</div>
                        <div class="w-full text-sm">{{ __('general.parking_available_desc') }}</div>
                    </div>
                </label>
            </li>

            <!-- Camera Allowed -->
            <li>
                <input type="checkbox" id="camera_allowed" name="camera_allowed" value="1" class="hidden peer" {{ old('camera_allowed', $ground?->camera_allowed) ? 'checked' : '' }}>
                <label for="camera_allowed" class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 peer-checked:border-brand-500 peer-checked:bg-brand-50 hover:text-gray-600 dark:peer-checked:bg-brand-900/20 hover:bg-gray-50 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700 peer-checked:text-brand-600 dark:peer-checked:text-brand-400">
                    <div class="block">
                        <svg class="mb-2 w-7 h-7 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <div class="w-full font-semibold">{{ __('general.camera_allowed') }}</div>
                        <div class="w-full text-sm">{{ __('general.camera_allowed_desc') }}</div>
                    </div>
                </label>
            </li>

            <!-- Waiting Area -->
            <li>
                <input type="checkbox" id="waiting_area" name="waiting_area" value="1" class="hidden peer" {{ old('waiting_area', $ground?->waiting_area) ? 'checked' : '' }}>
                <label for="waiting_area" class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 peer-checked:border-brand-500 peer-checked:bg-brand-50 hover:text-gray-600 dark:peer-checked:bg-brand-900/20 hover:bg-gray-50 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700 peer-checked:text-brand-600 dark:peer-checked:text-brand-400">
                    <div class="block">
                        <svg class="mb-2 w-7 h-7 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                        <div class="w-full font-semibold">{{ __('general.waiting_area') }}</div>
                        <div class="w-full text-sm">{{ __('general.waiting_area_desc') }}</div>
                    </div>
                </label>
            </li>

            <!-- Changing Room -->
            <li>
                <input type="checkbox" id="changing_room" name="changing_room" value="1" class="hidden peer" {{ old('changing_room', $ground?->changing_room) ? 'checked' : '' }}>
                <label for="changing_room" class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 peer-checked:border-brand-500 peer-checked:bg-brand-50 hover:text-gray-600 dark:peer-checked:bg-brand-900/20 hover:bg-gray-50 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700 peer-checked:text-brand-600 dark:peer-checked:text-brand-400">
                    <div class="block">
                        <svg class="mb-2 w-7 h-7 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <div class="w-full font-semibold">{{ __('general.changing_room') }}</div>
                        <div class="w-full text-sm">{{ __('general.changing_room_desc') }}</div>
                    </div>
                </label>
            </li>

            <!-- Security Locker -->
            <li>
                <input type="checkbox" id="security_locker" name="security_locker" value="1" class="hidden peer" {{ old('security_locker', $ground?->security_locker) ? 'checked' : '' }}>
                <label for="security_locker" class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 peer-checked:border-brand-500 peer-checked:bg-brand-50 hover:text-gray-600 dark:peer-checked:bg-brand-900/20 hover:bg-gray-50 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700 peer-checked:text-brand-600 dark:peer-checked:text-brand-400">
                    <div class="block">
                        <svg class="mb-2 w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <div class="w-full font-semibold">{{ __('general.security_locker') }}</div>
                        <div class="w-full text-sm">{{ __('general.security_locker_desc') }}</div>
                    </div>
                </label>
            </li>
        </ul>
    </div>

    <!-- Image Upload -->
    <div>
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.image') }} @if(!$ground)<span class="text-red-500">*</span>@endif</label>
        <input type="file" name="image" id="ground-image" accept="image/*">
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('general.image_upload_info_2mb') }}</p>
        @error('image')
            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
        @enderror

        @if($ground && $ground->getFirstMediaUrl('picture'))
            <div class="mt-3">
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Current image:</p>
                <img src="{{ $ground->getFirstMediaUrl('picture') }}" alt="{{ $ground->name }}" class="w-32 h-32 object-cover rounded-lg">
            </div>
        @endif
    </div>

    <!-- Active Status -->
    <div class="flex items-center">
        <input type="checkbox" id="active" name="active" value="1" {{ old('active', $ground?->active ?? true) ? 'checked' : '' }} class="w-4 h-4 text-brand-600 bg-gray-100 border-gray-300 rounded focus:ring-brand-500 dark:focus:ring-brand-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
        <label for="active" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __('general.active') }}</label>
    </div>
</div>
