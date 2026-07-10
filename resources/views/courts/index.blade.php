<x-layouts.app :title="__('general.courts')">
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
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('general.courts') }}</h1>
            <a href="{{ route('courts.create') }}" class="text-white bg-brand-500 hover:bg-brand-600 focus:ring-4 focus:ring-brand-300 font-medium rounded text-sm px-5 py-2.5 dark:bg-brand-600 dark:hover:bg-brand-700 focus:outline-none dark:focus:ring-brand-800">
                <svg class="w-4 h-4 inline-block mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 5v10m-5-5h10"/>
                </svg>
                {{ __('general.add_court') }}
            </a>
        </div>

        <!-- Table Card -->
        <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
            <!-- Search & Filter Bar -->
            <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                <div class="w-full md:w-1/2">
                    <form action="{{ route('courts.index') }}" method="GET" class="flex items-center gap-2">
                        <input type="hidden" name="ground_id" value="{{ request('ground_id') }}">
                        <label for="search" class="sr-only">{{ __('general.search') }}</label>
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" id="search" name="search" value="{{ request('search') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500" placeholder="Search courts...">
                        </div>
                        <button type="submit" class="text-white bg-brand-500 hover:bg-brand-600 focus:ring-4 focus:ring-brand-300 font-medium rounded text-sm px-4 py-2 dark:bg-brand-600 dark:hover:bg-brand-700 focus:outline-none dark:focus:ring-brand-800">
                            {{ __('general.search') }}
                        </button>
                    </form>
                </div>
                <div class="w-full md:w-auto flex items-center gap-2">
                    <form action="{{ route('courts.index') }}" method="GET" class="flex items-center gap-2">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <select name="ground_id" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block p-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500">
                            <option value="">{{ __('general.all_grounds') }}</option>
                            @foreach($grounds as $ground)
                                <option value="{{ $ground->id }}" {{ request('ground_id') == $ground->id ? 'selected' : '' }}>{{ $ground->name }}</option>
                            @endforeach
                        </select>
                    </form>
                    @if(request('search') || request('ground_id'))
                        <a href="{{ route('courts.index') }}" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-300 font-medium rounded text-sm px-4 py-2 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 border border-gray-300">
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
                            <th scope="col" class="px-4 py-3">{{ __('general.image') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('general.name') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('general.ground') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('general.category') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('general.rate_per_hour') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('general.status') }}</th>
                            <th scope="col" class="px-4 py-3">
                                <span class="sr-only">{{ __('general.actions') }}</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courts as $court)
                            <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-4 py-3">
                                    <img src="{{ $court->getFirstMediaUrl('picture') }}" alt="{{ $court->name }}" class="w-12 h-12 rounded-lg object-cover">
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <div>{{ $court->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $court->opening_time }} - {{ $court->closing_time }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    {{ $court->ground?->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        @if($court->category)
                                            <img src="{{ $court->category->getFirstMediaUrl('picture') }}" alt="{{ $court->category->name }}" class="w-6 h-6 rounded">
                                            <span>{{ $court->category->name }}</span>
                                        @else
                                            -
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    {{ formatNumber($court->rate_per_hour) }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($court->active)
                                        <span class="bg-success-soft border border-success-subtle text-fg-success-strong text-xs font-medium px-1.5 py-0.5 rounded">{{ __('general.active') }}</span>
                                    @else
                                        <span class="bg-danger-soft border border-danger-subtle text-fg-danger-strong text-xs font-medium px-1.5 py-0.5 rounded">{{ __('general.inactive') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 flex items-center justify-end gap-2">
                                    <a href="{{ route('courts.edit', $court) }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded text-sm px-3 py-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                        {{ __('general.edit') }}
                                    </a>
                                    <button onclick="confirmDelete({{ $court->id }}, '{{ addslashes($court->name) }}')" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded text-sm px-3 py-2 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-900">
                                        {{ __('general.delete') }}
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <p class="text-lg font-semibold">No courts found</p>
                                    <p class="mt-1">Get started by creating a new court.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-4 py-3 border-t dark:border-gray-700">
                {{ $courts->links() }}
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
                document.querySelector('#delete-modal .delete-form').action = `/courts/${id}`;
                ModalManager.show('delete-modal');
            };
        });
    </script>
    @endpush
</x-layouts.app>
