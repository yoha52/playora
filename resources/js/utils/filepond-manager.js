/**
 * FilePond Manager - Utility for initializing and managing FilePond instances
 */
export class FilepondManager {
    static instances = new Map();
    static isGlobalWrapperApplied = false;

    /**
     * Setup form submit button control for a FilePond instance
     * Disables submit button during file upload, enables when complete
     * @param {object} pond - FilePond instance
     */
    static setupFormSubmitControl(pond) {
        // FilePond replaces the input element, so we need to find form from pond's element
        const pondElement = pond.element;
        const form = pondElement ? pondElement.closest('form') : null;

        if (!form) return;

        const getSubmitButtons = () => form.querySelectorAll('button[type="submit"], input[type="submit"]');

        const disableSubmit = () => {
            getSubmitButtons().forEach(btn => {
                btn.disabled = true;
                btn.dataset.filepondDisabled = 'true';
                btn.classList.add('opacity-50', 'cursor-not-allowed');
            });
        };

        const enableSubmit = () => {
            // FilePond status codes: PROCESSING = 3, LOADING = 2
            const isProcessing = pond.getFiles().some(file => file.status === 3 || file.status === 2);
            if (!isProcessing) {
                getSubmitButtons().forEach(btn => {
                    if (btn.dataset.filepondDisabled === 'true') {
                        btn.disabled = false;
                        delete btn.dataset.filepondDisabled;
                        btn.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                });
            }
        };

        pond.on('addfilestart', disableSubmit);
        pond.on('processfilestart', disableSubmit);
        pond.on('processfile', enableSubmit);
        pond.on('processfileabort', enableSubmit);
        pond.on('processfilerevert', enableSubmit);
        pond.on('removefile', enableSubmit);
        pond.on('error', enableSubmit);
    }

    /**
     * Wrap the global FilePond.create method to auto-apply form submit control
     * This ensures ALL FilePond instances get the feature, not just those created via FilepondManager
     */
    static applyGlobalWrapper() {
        if (this.isGlobalWrapperApplied || typeof window.FilePond === 'undefined') return;

        const originalCreate = window.FilePond.create.bind(window.FilePond);

        window.FilePond.create = (element, options) => {
            const pond = originalCreate(element, options);
            this.setupFormSubmitControl(pond);
            return pond;
        };

        this.isGlobalWrapperApplied = true;
    }

    /**
     * Initialize FilePond on an input element
     * @param {string|HTMLElement} selector - Input element or selector
     * @param {object} options - FilePond options
     * @returns {object|null} - FilePond instance
     */
    static init(selector, options = {}) {
        if (typeof window.FilePond === 'undefined') {
            console.warn('FilePond not available');
            return null;
        }

        // Ensure global wrapper is applied
        this.applyGlobalWrapper();

        const inputElement = typeof selector === 'string'
            ? document.querySelector(selector)
            : selector;

        if (!inputElement) {
            console.warn('FilePond input element not found:', selector);
            return null;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content
            || document.querySelector('input[name="_token"]')?.value
            || '';

        const defaultOptions = {
            server: {
                process: '/upload-media',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                }
            },
            acceptedFileTypes: ['image/png', 'image/jpeg', 'image/jpg', 'image/gif', 'image/webp'],
            maxFileSize: '2MB',
            labelIdle: window.i18n?.general?.filepond_message || 'Drag & Drop your image or <span class="filepond--label-action">Browse</span>',
            styleButtonRemoveItemPosition: 'right',
            styleButtonProcessItemPosition: 'right',
            stylePanelLayout: 'compact',
            credits: false,
        };

        const mergedOptions = { ...defaultOptions, ...options };

        // Merge server options if provided
        if (options.server) {
            mergedOptions.server = {
                ...defaultOptions.server,
                ...options.server,
                headers: {
                    ...defaultOptions.server.headers,
                    ...(options.server?.headers || {})
                }
            };
        }

        // This will use the wrapped create method which auto-applies form submit control
        const pond = window.FilePond.create(inputElement, mergedOptions);

        // Store instance for later access
        const id = inputElement.id || `filepond-${Date.now()}`;
        this.instances.set(id, pond);

        return pond;
    }

    /**
     * Get a FilePond instance by ID
     * @param {string} id - Input element ID
     * @returns {object|null} - FilePond instance
     */
    static getInstance(id) {
        return this.instances.get(id) || null;
    }

    /**
     * Clear files from a FilePond instance
     * @param {string} id - Input element ID
     */
    static clear(id) {
        const pond = this.getInstance(id);
        if (pond) {
            pond.removeFiles();
        }
    }

    /**
     * Destroy a FilePond instance
     * @param {string} id - Input element ID
     */
    static destroy(id) {
        const pond = this.getInstance(id);
        if (pond) {
            pond.destroy();
            this.instances.delete(id);
        }
    }

    /**
     * Clear all FilePond instances
     */
    static clearAll() {
        this.instances.forEach((pond, id) => {
            pond.removeFiles();
        });
    }
}

// Apply global wrapper immediately when this module loads
// This ensures FilePond.create is wrapped before any code uses it
if (typeof window !== 'undefined') {
    // Wait for FilePond to be available, then apply wrapper
    const checkAndApply = () => {
        if (typeof window.FilePond !== 'undefined') {
            FilepondManager.applyGlobalWrapper();
        }
    };

    // Try immediately
    checkAndApply();

    // Also try after DOM is ready (in case FilePond is loaded later)
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', checkAndApply);
    }
}

// Make it globally available
window.FilepondManager = FilepondManager;
