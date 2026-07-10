/**
 * Modal Manager - Utility for managing Flowbite modals
 * Uses FlowbiteInstances to get the auto-initialized modal instances
 */
export class ModalManager {
    /**
     * Get a modal instance by ID
     * @param {string} modalId - The modal element ID
     * @returns {object|null} - The modal instance
     */
    static getInstance(modalId) {
        if (typeof FlowbiteInstances === 'undefined') {
            console.warn('FlowbiteInstances not available');
            return null;
        }
        return FlowbiteInstances.getInstance('Modal', modalId);
    }

    /**
     * Show a modal by ID
     * @param {string} modalId - The modal element ID
     */
    static show(modalId) {
        const modal = this.getInstance(modalId);
        if (modal) {
            modal.show();
        } else {
            console.warn(`Modal with ID "${modalId}" not found`);
        }
    }

    /**
     * Hide a modal by ID
     * @param {string} modalId - The modal element ID
     */
    static hide(modalId) {
        const modal = this.getInstance(modalId);
        if (modal) {
            modal.hide();
        } else {
            console.warn(`Modal with ID "${modalId}" not found`);
        }
    }

    /**
     * Toggle a modal by ID
     * @param {string} modalId - The modal element ID
     */
    static toggle(modalId) {
        const modal = this.getInstance(modalId);
        if (modal) {
            modal.toggle();
        } else {
            console.warn(`Modal with ID "${modalId}" not found`);
        }
    }

    /**
     * Setup close buttons for a modal
     * @param {string} modalId - The modal element ID
     */
    static setupCloseButtons(modalId) {
        const modalEl = document.getElementById(modalId);
        if (!modalEl) return;

        // Find all close and cancel buttons within the modal
        const closeButtons = modalEl.querySelectorAll('.modal-close-btn, .modal-cancel-btn');
        closeButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                this.hide(modalId);
            });
        });
    }

    /**
     * Initialize a CRUD modal with form handling
     * @param {object} config - Configuration object
     * @param {string} config.modalId - The modal element ID
     * @param {string} config.formId - The form element ID
     * @param {string} config.titleSelector - Selector for the modal title
     * @param {string} config.createTitle - Title for create mode
     * @param {string} config.editTitle - Title for edit mode
     * @param {string} config.storeUrl - URL for storing new records
     * @param {function} config.onOpen - Callback when modal opens
     * @param {function} config.onReset - Callback to reset form
     */
    static initCrudModal(config) {
        const {
            modalId,
            formId,
            titleSelector = '.modal-title',
            createTitle,
            editTitle,
            storeUrl,
            onOpen,
            onReset
        } = config;

        this.setupCloseButtons(modalId);

        return {
            openForCreate: () => {
                const modalEl = document.getElementById(modalId);
                const form = document.getElementById(formId);
                const titleEl = modalEl?.querySelector(titleSelector);

                if (titleEl) titleEl.textContent = createTitle;
                if (form) {
                    form.action = storeUrl;
                    const methodInput = form.querySelector('input[name="_method"]');
                    if (methodInput) methodInput.value = 'POST';
                    form.reset();
                }

                if (onReset) onReset();
                if (onOpen) onOpen('create');

                this.show(modalId);
            },
            openForEdit: (id, data) => {
                const modalEl = document.getElementById(modalId);
                const form = document.getElementById(formId);
                const titleEl = modalEl?.querySelector(titleSelector);

                if (titleEl) titleEl.textContent = editTitle;
                if (form) {
                    form.action = storeUrl.replace(/\/$/, '') + '/' + id;
                    const methodInput = form.querySelector('input[name="_method"]');
                    if (methodInput) methodInput.value = 'PUT';
                }

                if (onOpen) onOpen('edit', data);

                this.show(modalId);
            },
            close: () => {
                this.hide(modalId);
            }
        };
    }

    /**
     * Initialize a delete confirmation modal
     * @param {object} config - Configuration object
     * @param {string} config.modalId - The modal element ID
     * @param {string} config.baseUrl - Base URL for delete action
     */
    static initDeleteModal(config) {
        const { modalId, baseUrl } = config;

        this.setupCloseButtons(modalId);

        return {
            confirm: (id, name) => {
                const modalEl = document.getElementById(modalId);
                const nameEl = modalEl?.querySelector('.delete-item-name');
                const form = modalEl?.querySelector('.delete-form');

                if (nameEl) nameEl.textContent = name;
                if (form) form.action = baseUrl.replace(/\/$/, '') + '/' + id;

                this.show(modalId);
            },
            close: () => {
                this.hide(modalId);
            }
        };
    }
}

// Make it globally available
window.ModalManager = ModalManager;
