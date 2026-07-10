<x-layouts.install :title="__('install.smtp')" :currentStep="7">
    <div class="p-8">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
            {{ __('install.smtp_configuration') }}
        </h2>
        <p class="text-gray-600 dark:text-gray-400 mb-6">
            {{ __('install.smtp_description') }}
        </p>

        <form action="{{ route('install.smtp.store') }}" method="POST">
            @csrf

            <div class="space-y-6 mb-8">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="mail_host" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{ __('install.mail_host') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="mail_host" id="mail_host" value="{{ old('mail_host', 'smtp.gmail.com') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500" required>
                        @error('mail_host')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="mail_port" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{ __('install.mail_port') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="mail_port" id="mail_port" value="{{ old('mail_port', '587') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500" required>
                        @error('mail_port')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="mail_username" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        {{ __('install.mail_username') }}
                    </label>
                    <input type="text" name="mail_username" id="mail_username" value="{{ old('mail_username') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500">
                    @error('mail_username')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="mail_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        {{ __('install.mail_password') }}
                    </label>
                    <input type="password" name="mail_password" id="mail_password" value="{{ old('mail_password') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500">
                    @error('mail_password')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="mail_encryption" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                        {{ __('install.mail_encryption') }}
                    </label>
                    <select name="mail_encryption" id="mail_encryption" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500">
                        <option value="tls" {{ old('mail_encryption', 'tls') === 'tls' ? 'selected' : '' }}>TLS</option>
                        <option value="ssl" {{ old('mail_encryption') === 'ssl' ? 'selected' : '' }}>SSL</option>
                        <option value="null" {{ old('mail_encryption') === 'null' ? 'selected' : '' }}>None</option>
                    </select>
                    @error('mail_encryption')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="mail_from_address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{ __('install.mail_from_address') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="mail_from_address" id="mail_from_address" value="{{ old('mail_from_address') }}" placeholder="noreply@example.com" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500" required>
                        @error('mail_from_address')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="mail_from_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            {{ __('install.mail_from_name') }} <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="mail_from_name" id="mail_from_name" value="{{ old('mail_from_name', config('app.name')) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500" required>
                        @error('mail_from_name')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-between">
                <a href="{{ route('install.license') }}" class="inline-flex items-center px-4 py-2 text-gray-700 bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded text-sm text-center dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                    </svg>
                    {{ __('install.back') }}
                </a>

                <button type="submit" class="inline-flex items-center px-4 py-2 text-white bg-brand-500 hover:bg-brand-600 focus:ring-4 focus:outline-none focus:ring-brand-300 font-medium rounded text-sm text-center dark:bg-brand-600 dark:hover:bg-brand-700 dark:focus:ring-brand-800">
                    {{ __('install.test_and_continue') }}
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</x-layouts.install>
