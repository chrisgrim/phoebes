/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */
document.addEventListener('DOMContentLoaded', function() {
    const mobileNav = document.getElementById('mobile-navigation');
    const button = document.querySelector('.mobile-menu-toggle');

    if (!mobileNav || !button) return;

    button.addEventListener('click', function(e) {
        e.preventDefault();
        mobileNav.classList.toggle('toggled');
        button.classList.toggle('toggled');
        const isExpanded = mobileNav.classList.contains('toggled');
        button.setAttribute('aria-expanded', isExpanded);
    });
});


