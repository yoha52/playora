<x-layouts.app :title="__('general.profile')">
    <div class="px-4 pt-6">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('general.profile') }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('general.profile_description') }}</p>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg">
            <form action="{{ route('profile.update') }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid gap-6">
                    <!-- Profile Picture -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.profile_picture') }}</label>
                        <div class="flex items-center gap-6 mb-4">
                            <img src="{{ $user->getFirstMediaUrl('picture') }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-full object-cover border-4 border-gray-200 dark:border-gray-600">
                            <div class="flex-1">
                                <input type="file" name="picture" id="profile-picture" accept="image/*">
                                <input type="hidden" name="image" id="profile-image-path">
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ __('general.image_upload_info_2mb') }}</p>
                            </div>
                        </div>
                        @error('image')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Name -->
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.name') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500" required>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email (Read Only) -->
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.email') }}</label>
                        <input type="email" id="email" value="{{ $user->email }}" class="bg-gray-100 border border-gray-300 text-gray-500 text-sm rounded block w-full p-2.5 dark:bg-gray-600 dark:border-gray-600 dark:text-gray-400 cursor-not-allowed" readonly disabled>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('general.email_cannot_change') }}</p>
                    </div>

                    <!-- Contact No -->
                    <div>
                        <label for="contact_no" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.contact_no') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="contact_no" id="contact_no" value="{{ old('contact_no', $user->contact_no) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500" required>
                        @error('contact_no')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.password') }}</label>
                        <input type="password" name="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500" placeholder="{{ __('general.leave_blank_password') }}">
                        @error('password')
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
            const inputElement = document.querySelector('#profile-picture');
            const hiddenInput = document.querySelector('#profile-image-path');

            if (inputElement) {
                if (typeof window.FilepondManager !== 'undefined') {
                    window.FilepondManager.init('#profile-picture', {
                        server: {
                            process: {
                                url: '/upload-media',
                                onload: (response) => {
                                    hiddenInput.value = response;
                                    return response;
                                }
                            },
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            }
                        }
                    });
                } else if (typeof window.FilePond !== 'undefined') {
                    window.FilePond.create(inputElement, {
                        server: {
                            process: {
                                url: '/upload-media',
                                onload: (response) => {
                                    hiddenInput.value = response;
                                    return response;
                                }
                            },
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            }
                        },
                        acceptedFileTypes: ['image/png', 'image/jpeg', 'image/jpg', 'image/gif', 'image/webp'],
                        maxFileSize: '2MB',
                        labelIdle: i18n.general.filepond_message,
                        styleButtonRemoveItemPosition: 'right',
                        styleButtonProcessItemPosition: 'right',
                        stylePanelLayout: 'compact',
                        credits: false,
                    });
                }
            }
        });
    </script>
    @endpush
</x-layouts.app>
