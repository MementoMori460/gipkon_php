document.addEventListener('DOMContentLoaded', function () {
    // Mobile Menu Toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileMenuCloseBtn = document.getElementById('mobile-menu-close');

    function toggleMenu() {
        if (mobileMenu.classList.contains('hidden')) {
            mobileMenu.classList.remove('hidden');
            // Simple slide down animation logic could go here
        } else {
            mobileMenu.classList.add('hidden');
        }
    }

    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', toggleMenu);
    }

    if (mobileMenuCloseBtn) {
        mobileMenuCloseBtn.addEventListener('click', toggleMenu);
    }

    // Mobile Dropdown Toggles
    const mobileDropdownBtns = document.querySelectorAll('.mobile-dropdown-btn');
    mobileDropdownBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const content = this.nextElementSibling;
            const icon = this.querySelector('.chevron-icon');
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                if (icon) icon.style.transform = 'rotate(180deg)';
            } else {
                content.classList.add('hidden');
                if (icon) icon.style.transform = 'rotate(0deg)';
            }
        });
    });
});
