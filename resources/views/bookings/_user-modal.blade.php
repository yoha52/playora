<!-- Create User Modal -->
<div id="createUserModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    {{ __('general.add_new_user') }}
                </h3>
                <button type="button" class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="createUserModal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">{{ __('general.close') }}</span>
                </button>
            </div>
            <div class="p-4 md:p-5">
                <form id="createUserForm" class="space-y-4">
                    <div class="mb-2">
                        <label for="new_user_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.name') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="new_user_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                        <p class="mt-1 text-sm text-red-600 dark:text-red-500 hidden" id="new_user_name_error"></p>
                    </div>
                    <div class="mb-2">
                        <label for="new_user_email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.email') }} <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="new_user_email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                        <p class="mt-1 text-sm text-red-600 dark:text-red-500 hidden" id="new_user_email_error"></p>
                    </div>
                    <div class="mb-2">
                        <label for="new_user_contact_no" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('general.contact_no') }}</label>
                        <input type="text" name="contact_no" id="new_user_contact_no" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                        <p class="mt-1 text-sm text-red-600 dark:text-red-500 hidden" id="new_user_contact_no_error"></p>
                    </div>
                    <div class="flex gap-3 mt-2">
                        <button type="button" data-modal-hide="createUserModal" class="flex-1 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                {{ __('general.cancel') }}
                            </button>
                        <button type="submit" id="createUserSubmit" class="flex-1 text-white bg-brand-700 hover:bg-brand-800 focus:ring-4 focus:outline-none focus:ring-brand-300 font-medium rounded text-sm px-5 py-2.5 text-center dark:bg-brand-600 dark:hover:bg-brand-700 dark:focus:ring-brand-800">
                                {{ __('general.create_user') }}
                            </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
