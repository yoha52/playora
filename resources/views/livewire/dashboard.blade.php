<div class="px-4 pt-6">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('general.dashboard') }}</h1>
    </div>

    <!-- Row 1: Stats Cards (Categories, Grounds, Courts) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <!-- Total Categories -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-brand-100 dark:bg-brand-900 rounded-lg p-3">
                    <svg class="w-6 h-6 text-brand-600 dark:text-brand-400" fill="currentColor" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <g>
                            <path d="M24,2a2.1,2.1,0,0,0-1.7,1L13.2,17a2.3,2.3,0,0,0,0,2,1.9,1.9,0,0,0,1.7,1H33a2.1,2.1,0,0,0,1.7-1,1.8,1.8,0,0,0,0-2l-9-14A1.9,1.9,0,0,0,24,2Z"/>
                            <path d="M43,43H29a2,2,0,0,1-2-2V27a2,2,0,0,1,2-2H43a2,2,0,0,1,2,2V41A2,2,0,0,1,43,43Z"/>
                            <path d="M13,24A10,10,0,1,0,23,34,10,10,0,0,0,13,24Z"/>
                        </g>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('general.total_categories') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->totalCategories }}</p>
                </div>
            </div>
        </div>

        <!-- Total Grounds -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-100 dark:bg-green-900 rounded-lg p-3">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 511.957 511.957" xmlns="http://www.w3.org/2000/svg">
                        <path d="M507.691,110.215l-42.667-32c-3.221-2.411-7.552-2.816-11.157-1.003c-3.605,1.813-5.888,5.504-5.888,9.536v95.637 c-36.373-10.987-89.365-16.235-132.117-18.709c-1.088-0.832-2.347-1.493-3.733-1.877c-2.923-0.811-5.888-0.235-8.32,1.237 c-20.373-1.003-37.419-1.387-47.829-1.515V134.77l38.4-28.8c2.688-2.005,4.267-5.184,4.267-8.533c0-3.349-1.579-6.528-4.267-8.533 l-42.667-32c-3.2-2.432-7.552-2.795-11.157-1.003c-3.627,1.813-5.909,5.504-5.909,9.536v96.085 c-10.411,0.149-27.456,0.533-47.829,1.515c-2.432-1.472-5.397-2.027-8.32-1.237c-1.408,0.384-2.667,1.045-3.733,1.877 c-52.16,3.029-119.616,10.155-153.451,26.816v-34.411l38.4-28.8c2.709-2.005,4.267-5.184,4.267-8.533 c0-3.349-1.579-6.528-4.267-8.533l-42.667-32c-3.221-2.411-7.509-2.816-11.157-1.003C2.283,79.026,0,82.717,0,86.749v128 c0,0.256,0.021,0.683,0.064,1.173l21.205,190.827c0,33.003,84.117,45.568,149.333,50.368v-71.701c0-17.643,14.357-32,32-32h85.333 c17.643,0,32,14.357,32,32v71.765c65.216-4.693,149.312-17.003,149.269-49.259l21.141-190.229c0-0.085,0.021-0.192,0.021-0.277 c0.043-0.491,0.107-1.003,0.149-1.472c0.043-0.491,0.064-0.917,0.064-1.173c0-9.771-8.107-17.749-21.333-24.256v-34.411 l38.443-28.821c2.688-2.005,4.267-5.184,4.267-8.533C511.957,115.399,510.379,112.221,507.691,110.215z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('general.total_grounds') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->totalGrounds }}</p>
                </div>
            </div>
        </div>

        <!-- Total Courts -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-purple-100 dark:bg-purple-900 rounded-lg p-3">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                        <path d="M403.478,256.004c0,16.549,12.028,30.838,28.18,33.735V222.26 C415.506,225.166,403.478,239.446,403.478,256.004z"/>
                        <path d="M108.522,256.004c0-16.558-12.028-30.838-28.179-33.744v67.478 C96.494,286.842,108.522,272.553,108.522,256.004z"/>
                        <path d="M415.586,164.74c-23.363,23.378-37.795,55.602-37.803,91.264c0.008,35.654,14.44,67.884,37.803,91.256 c23.371,23.371,55.602,37.795,91.264,37.803H512V126.937h-5.15C471.188,126.937,438.958,141.377,415.586,164.74z"/>
                        <path d="M96.414,347.26c23.364-23.371,37.796-55.602,37.803-91.256c-0.007-35.662-14.439-67.885-37.803-91.264 c-23.371-23.363-55.602-37.795-91.263-37.803H0v258.126h5.151C40.812,385.054,73.043,370.631,96.414,347.26z"/>
                        <path d="M9.688,91.068C3.694,97.086,0.008,105.302,0,114.455v0.645h5.151c38.893,0,74.157,15.786,99.638,41.274 c25.488,25.472,41.266,60.736,41.266,99.63c0,38.886-15.778,74.15-41.266,99.63c-25.481,25.489-60.745,41.266-99.638,41.266H0 v0.645c0.008,9.163,3.694,17.369,9.688,23.379c6.01,6.002,14.224,9.68,23.379,9.688h217.01V322.312 c-16.015-1.417-30.44-8.494-41.162-19.224c-12.044-12.035-19.511-28.72-19.503-47.084c-0.008-18.372,7.459-35.057,19.503-47.093 c10.722-10.73,25.147-17.815,41.162-19.224V81.389H33.067C23.912,81.389,15.698,85.074,9.688,91.068z"/>
                        <path d="M502.312,91.068c-6.01-5.994-14.225-9.68-23.379-9.68H261.922v108.298c16.016,1.409,30.44,8.494,41.162,19.224 c12.044,12.036,19.511,28.721,19.503,47.093c0.008,18.364-7.458,35.049-19.503,47.084c-10.722,10.73-25.146,17.807-41.162,19.224 v108.299h217.011c9.154-0.008,17.369-3.686,23.379-9.688c5.994-6.01,9.68-14.216,9.688-23.379v-0.645h-5.15 c-38.894,0-74.158-15.777-99.638-41.266c-25.488-25.48-41.266-60.744-41.266-99.63c0-38.894,15.778-74.158,41.266-99.63 c25.481-25.488,60.745-41.274,99.638-41.274H512v-0.645C511.992,105.302,508.307,97.086,502.312,91.068z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('general.total_courts') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->totalCourts }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 2: Booking Stats with Filter -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="p-5 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('general.bookings') }}</h2>
                <select wire:model.live="dateFilter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded focus:ring-brand-500 focus:border-brand-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-brand-500 dark:focus:border-brand-500 block w-auto">
                    @foreach($filterOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="p-5">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Total Bookings -->
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.75 2.5C7.75 2.08579 7.41421 1.75 7 1.75C6.58579 1.75 6.25 2.08579 6.25 2.5V4.07926C4.81067 4.19451 3.86577 4.47737 3.17157 5.17157C2.47737 5.86577 2.19451 6.81067 2.07926 8.25H21.9207C21.8055 6.81067 21.5226 5.86577 20.8284 5.17157C20.1342 4.47737 19.1893 4.19451 17.75 4.07926V2.5C17.75 2.08579 17.4142 1.75 17 1.75C16.5858 1.75 16.25 2.08579 16.25 2.5V4.0129C15.5847 4 14.839 4 14 4H10C9.16097 4 8.41527 4 7.75 4.0129V2.5Z" />
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M2 12C2 11.161 2 10.4153 2.0129 9.75H21.9871C22 10.4153 22 11.161 22 12V14C22 17.7712 22 19.6569 20.8284 20.8284C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.8284C2 19.6569 2 17.7712 2 14V12ZM17 14C17.5523 14 18 13.5523 18 13C18 12.4477 17.5523 12 17 12C16.4477 12 16 12.4477 16 13C16 13.5523 16.4477 14 17 14ZM17 18C17.5523 18 18 17.5523 18 17C18 16.4477 17.5523 16 17 16C16.4477 16 16 16.4477 16 17C16 17.5523 16.4477 18 17 18ZM13 13C13 13.5523 12.5523 14 12 14C11.4477 14 11 13.5523 11 13C11 12.4477 11.4477 12 12 12C12.5523 12 13 12.4477 13 13ZM13 17C13 17.5523 12.5523 18 12 18C11.4477 18 11 17.5523 11 17C11 16.4477 11.4477 16 12 16C12.5523 16 13 16.4477 13 17ZM7 14C7.55228 14 8 13.5523 8 13C8 12.4477 7.55228 12 7 12C6.44772 12 6 12.4477 6 13C6 13.5523 6.44772 14 7 14ZM7 18C7.55228 18 8 17.5523 8 17C8 16.4477 7.55228 16 7 16C6.44772 16 6 16.4477 6 17C6 17.5523 6.44772 18 7 18Z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-blue-600 dark:text-blue-400">{{ __('general.total_bookings') }}</p>
                            <p class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ $this->bookingStats['total_bookings'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Received -->
                <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 border border-green-200 dark:border-green-800">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2.75C6.89137 2.75 2.75 6.89137 2.75 12C2.75 17.1086 6.89137 21.25 12 21.25C17.1086 21.25 21.25 17.1086 21.25 12C21.25 6.89137 17.1086 2.75 12 2.75ZM12 7.25C12.4142 7.25 12.75 7.58579 12.75 8V8.25H13C14.5188 8.25 15.75 9.48122 15.75 11C15.75 11.4142 15.4142 11.75 15 11.75C14.5858 11.75 14.25 11.4142 14.25 11C14.25 10.3096 13.6904 9.75 13 9.75H12.75V11.75H13C14.5188 11.75 15.75 12.9812 15.75 14.5C15.75 16.0188 14.5188 17.25 13 17.25H12.75V17.5C12.75 17.9142 12.4142 18.25 12 18.25C11.5858 18.25 11.25 17.9142 11.25 17.5V17.25H11C9.48122 17.25 8.25 16.0188 8.25 14.5C8.25 14.0858 8.58579 13.75 9 13.75C9.41421 13.75 9.75 14.0858 9.75 14.5C9.75 15.1904 10.3096 15.75 11 15.75H11.25V13.25H11C9.48122 13.25 8.25 12.0188 8.25 10.5C8.25 8.98122 9.48122 7.75 11 7.75H11.25V7.5C11.25 7.08579 11.5858 6.75 12 6.75C12.4142 6.75 12.75 7.08579 12.75 7.5V7.75H13C14.5188 7.75 15.75 8.98122 15.75 10.5C15.75 10.9142 15.4142 11.25 15 11.25C14.5858 11.25 14.25 10.9142 14.25 10.5C14.25 9.80964 13.6904 9.25 13 9.25H12.75V11.25H13C14.5188 11.25 15.75 12.4812 15.75 14C15.75 15.5188 14.5188 16.75 13 16.75H12.75V17C12.75 17.4142 12.4142 17.75 12 17.75C11.5858 17.75 11.25 17.4142 11.25 17V16.75H11C9.48122 16.75 8.25 15.5188 8.25 14C8.25 13.5858 8.58579 13.25 9 13.25C9.41421 13.25 9.75 13.5858 9.75 14C9.75 14.6904 10.3096 15.25 11 15.25H11.25V13.25H11C9.48122 13.25 8.25 12.0188 8.25 10.5C8.25 8.98122 9.48122 7.75 11 7.75H11.25V7.5C11.25 7.08579 11.5858 6.75 12 6.75ZM11.25 9.25H11C10.3096 9.25 9.75 9.80964 9.75 10.5C9.75 11.1904 10.3096 11.75 11 11.75H11.25V9.25ZM12.75 13.25V15.75H13C13.6904 15.75 14.25 15.1904 14.25 14.5C14.25 13.8096 13.6904 13.25 13 13.25H12.75Z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-green-600 dark:text-green-400">{{ __('general.total_received') }}</p>
                            <p class="text-2xl font-bold text-green-700 dark:text-green-300">{{ formatNumber($this->bookingStats['total_received']) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Due -->
                <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4 border border-red-200 dark:border-red-800">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2.75C6.89137 2.75 2.75 6.89137 2.75 12C2.75 17.1086 6.89137 21.25 12 21.25C17.1086 21.25 21.25 17.1086 21.25 12C21.25 6.89137 17.1086 2.75 12 2.75ZM12 7C12.4142 7 12.75 7.33579 12.75 7.75V12.25C12.75 12.6642 12.4142 13 12 13C11.5858 13 11.25 12.6642 11.25 12.25V7.75C11.25 7.33579 11.5858 7 12 7ZM12 17C12.5523 17 13 16.5523 13 16C13 15.4477 12.5523 15 12 15C11.4477 15 11 15.4477 11 16C11 16.5523 11.4477 17 12 17Z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-red-600 dark:text-red-400">{{ __('general.total_due') }}</p>
                            <p class="text-2xl font-bold text-red-700 dark:text-red-300">{{ formatNumber($this->bookingStats['total_due']) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 3: Chart and Upcoming Bookings -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Category Wise Bookings Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('general.category_wise_bookings') }}</h2>
            </div>
            <div class="p-5">
                <div
                    x-data="{
                        chart: null,
                        chartData: @js($this->categoryBookingsData),
                        hasData() {
                            return this.chartData.reduce((sum, item) => sum + item.bookings_count, 0) > 0;
                        },
                        init() {
                            this.$nextTick(() => this.renderChart());
                        },
                        renderChart() {
                            if (!this.hasData()) {
                                if (this.chart) {
                                    this.chart.destroy();
                                    this.chart = null;
                                }
                                return;
                            }

                            const labels = this.chartData.map(item => item.name);
                            const series = this.chartData.map(item => item.bookings_count);
                            const isDark = document.documentElement.classList.contains('dark');

                            const options = {
                                series: series,
                                labels: labels,
                                chart: {
                                    type: 'donut',
                                    height: 320,
                                    background: 'transparent',
                                },
                                colors: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', '#EC4899', '#06B6D4', '#84CC16'],
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        colors: isDark ? '#9CA3AF' : '#374151',
                                    },
                                },
                                dataLabels: {
                                    enabled: true,
                                    formatter: function(val, opts) {
                                        return opts.w.config.series[opts.seriesIndex];
                                    },
                                },
                                plotOptions: {
                                    pie: {
                                        donut: {
                                            size: '65%',
                                            labels: {
                                                show: true,
                                                name: {
                                                    show: true,
                                                    color: isDark ? '#E5E7EB' : '#374151',
                                                },
                                                value: {
                                                    show: true,
                                                    color: isDark ? '#E5E7EB' : '#374151',
                                                },
                                                total: {
                                                    show: true,
                                                    label: '{{ __('general.total') }}',
                                                    color: isDark ? '#9CA3AF' : '#6B7280',
                                                    formatter: function(w) {
                                                        return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                                    },
                                                },
                                            },
                                        },
                                    },
                                },
                                stroke: { show: false },
                                responsive: [{
                                    breakpoint: 480,
                                    options: {
                                        chart: { height: 280 },
                                        legend: { position: 'bottom' },
                                    },
                                }],
                            };

                            if (this.chart) {
                                this.chart.destroy();
                            }

                            this.chart = new ApexCharts(this.$refs.chartEl, options);
                            this.chart.render();
                        }
                    }"
                    x-effect="chartData = @js($this->categoryBookingsData); $nextTick(() => renderChart())"
                    class="h-80"
                >
                    <div x-show="hasData()" x-ref="chartEl" class="h-full"></div>
                    <div x-show="!hasData()" class="flex items-center justify-center h-full text-gray-500 dark:text-gray-400">
                        <div class="text-center">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <p>{{ __('general.no_data_available') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Bookings -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-5 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('general.upcoming_bookings') }}</h2>
                <a href="{{ route('bookings.index') }}" class="text-sm text-brand-600 hover:text-brand-700 dark:text-brand-400 dark:hover:text-brand-300">
                    {{ __('general.view_all') }} &rarr;
                </a>
            </div>
            <div class="p-5">
                @if($this->upcomingBookings->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-4 py-3">{{ __('general.customer') }}</th>
                                    <th scope="col" class="px-4 py-3">{{ __('general.court') }}</th>
                                    <th scope="col" class="px-4 py-3">{{ __('general.date') }}</th>
                                    <th scope="col" class="px-4 py-3">{{ __('general.time') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($this->upcomingBookings as $booking)
                                    <tr wire:key="booking-{{ $booking->id }}" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-gray-900 dark:text-white">{{ $booking->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $booking->user->contact_no }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="font-medium text-gray-900 dark:text-white">{{ $booking->court->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $booking->court->ground->name }}</div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            {{ formatDate($booking->date) }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            {{ $booking->getFormattedTimeSlot() }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="flex items-center justify-center h-64 text-gray-500 dark:text-gray-400">
                        <div class="text-center">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p>{{ __('general.no_upcoming_bookings') }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @endpush
</div>
