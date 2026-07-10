// Import FilePond
import * as FilePond from 'filepond';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';

// Register FilePond plugins
FilePond.registerPlugin(FilePondPluginImagePreview, FilePondPluginFileValidateType);

// Make FilePond available globally as a mutable object (ES modules are read-only)
window.FilePond = { ...FilePond };

// Import utilities
import { ModalManager } from './utils/modal-manager.js';
import { FilepondManager } from './utils/filepond-manager.js';

// Make utilities globally available
window.ModalManager = ModalManager;
window.FilepondManager = FilepondManager;

// Dark mode functionality
function applyThemeFromStorage() {
    if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
}

function updateThemeIcons() {
    const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
    const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

    if (themeToggleDarkIcon && themeToggleLightIcon) {
        if (document.documentElement.classList.contains('dark')) {
            themeToggleLightIcon.classList.remove('hidden');
            themeToggleDarkIcon.classList.add('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
            themeToggleLightIcon.classList.add('hidden');
        }
    }
}

function initThemeToggle() {
    const themeToggleBtn = document.getElementById('theme-toggle');

    updateThemeIcons();

    if (themeToggleBtn && !themeToggleBtn.hasAttribute('data-theme-initialized')) {
        themeToggleBtn.setAttribute('data-theme-initialized', 'true');
        themeToggleBtn.addEventListener('click', function() {
            // Toggle dark class on html element
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            }
            updateThemeIcons();
        });
    }
}

// Apply theme on initial load
document.addEventListener('DOMContentLoaded', function() {
    applyThemeFromStorage();
    initThemeToggle();
});

// Re-apply theme after Livewire soft navigation
document.addEventListener('livewire:navigated', function() {
    applyThemeFromStorage();
    initThemeToggle();
    initMobileSidebar();
});

// Mobile sidebar functionality
function initMobileSidebar() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggleSidebarMobile');
    const hamburgerIcon = document.getElementById('toggleSidebarMobileHamburger');
    const closeIcon = document.getElementById('toggleSidebarMobileClose');

    if (!sidebar || !toggleBtn) return;

    // Remove existing backdrop if any
    let backdrop = document.getElementById('sidebarBackdrop');

    // Create backdrop element if it doesn't exist
    if (!backdrop) {
        backdrop = document.createElement('div');
        backdrop.id = 'sidebarBackdrop';
        backdrop.className = 'fixed inset-0 z-10 bg-gray-900/50 hidden lg:hidden';
        document.body.appendChild(backdrop);
    }

    function openSidebar() {
        sidebar.classList.remove('-translate-x-full');
        sidebar.classList.add('translate-x-0');
        backdrop.classList.remove('hidden');
        toggleBtn.setAttribute('aria-expanded', 'true');
        if (hamburgerIcon) hamburgerIcon.classList.add('hidden');
        if (closeIcon) closeIcon.classList.remove('hidden');
    }

    function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        sidebar.classList.remove('translate-x-0');
        backdrop.classList.add('hidden');
        toggleBtn.setAttribute('aria-expanded', 'false');
        if (hamburgerIcon) hamburgerIcon.classList.remove('hidden');
        if (closeIcon) closeIcon.classList.add('hidden');
    }

    // Remove old event listeners by cloning the elements
    const newToggleBtn = toggleBtn.cloneNode(true);
    toggleBtn.parentNode.replaceChild(newToggleBtn, toggleBtn);

    // Toggle sidebar on button click
    newToggleBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        const isExpanded = newToggleBtn.getAttribute('aria-expanded') === 'true';
        if (isExpanded) {
            closeSidebar();
        } else {
            openSidebar();
        }
    });

    // Close sidebar when clicking backdrop
    backdrop.addEventListener('click', closeSidebar);

    // Close sidebar on navigation (when clicking sidebar links)
    sidebar.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth < 1024) {
                closeSidebar();
            }
        });
    });
}

// Initialize mobile sidebar on DOMContentLoaded
document.addEventListener('DOMContentLoaded', function() {
    initMobileSidebar();
});
