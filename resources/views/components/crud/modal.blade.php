@props([
    'id',
    'title' => '',
    'size' => 'md', // sm, md, lg, xl, 2xl
])

@php
    $sizeClasses = match($size) {
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        default => 'max-w-md',
    };
@endphp

{{-- Hidden trigger for Flowbite auto-initialization --}}
<button data-modal-target="{{ $id }}" data-modal-toggle="{{ $id }}" class="hidden" aria-hidden="true"></button>

<div id="{{ $id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full {{ $sizeClasses }} max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal Header -->
            @if(isset($header))
                {{ $header }}
            @else
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="modal-title text-lg font-semibold text-gray-900 dark:text-white">
                        {{ $title }}
                    </h3>
                    <button type="button" class="modal-close-btn text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">{{ __('general.close') }}</span>
                    </button>
                </div>
            @endif

            <!-- Modal Body -->
            <div class="modal-body p-4 md:p-5">
                {{ $slot }}
            </div>

            <!-- Modal Footer -->
            @if(isset($footer))
                <div class="modal-footer flex items-center justify-end p-4 md:p-5 border-t dark:border-gray-600 gap-2">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>
