<x-layouts.app :title="__('general.add_ground')">
    <div class="px-4 pt-6">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('general.add_ground') }}</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Create a new ground with its facilities</p>
            </div>
            <a href="{{ route('grounds.index') }}" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-300 font-medium rounded text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 border border-gray-300">
                <svg class="w-4 h-4 inline-block mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4"/>
                </svg>
                {{ __('general.back_to_list') }}
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
            <form action="{{ route('grounds.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf

                @include('grounds._form')

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('grounds.index') }}" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded border border-gray-300 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                        {{ __('general.cancel') }}
                    </a>
                    <button type="submit" class="text-white bg-brand-500 hover:bg-brand-600 focus:ring-4 focus:outline-none focus:ring-brand-300 font-medium rounded text-sm px-5 py-2.5 text-center dark:bg-brand-600 dark:hover:bg-brand-700 dark:focus:ring-brand-800">
                        {{ __('general.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        window.addEventListener('load', function() {
            if (typeof window.FilepondManager !== 'undefined') {
                window.FilepondManager.init('#ground-image');
            } else if (typeof window.FilePond !== 'undefined') {
                const inputElement = document.querySelector('#ground-image');
                if (inputElement) {
                    window.FilePond.create(inputElement, {
                        server: {
                            process: '/upload-media',
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
