(function () {
    const menuButton = document.querySelector('[data-menu-toggle]');
    const mobileMenu = document.querySelector('[data-mobile-menu]');

    if (menuButton && mobileMenu) {
        menuButton.addEventListener('click', function () {
            const isOpen = mobileMenu.classList.toggle('open');
            menuButton.setAttribute('aria-expanded', String(isOpen));
            document.body.classList.toggle('menu-open', isOpen);
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth > 860) {
                mobileMenu.classList.remove('open');
                document.body.classList.remove('menu-open');
                menuButton.setAttribute('aria-expanded', 'false');
            }
        });
    }

    document.querySelectorAll('[data-quantity]').forEach(function (wrapper) {
        const input = wrapper.querySelector('input[type="number"]');
        const decrease = wrapper.querySelector('[data-decrease]');
        const increase = wrapper.querySelector('[data-increase]');

        if (!input) return;

        const update = function (change) {
            const min = Number(input.min || 1);
            const max = Number(input.max || 9999);
            const current = Number(input.value || min);
            input.value = Math.min(max, Math.max(min, current + change));
        };

        decrease?.addEventListener('click', function () { update(-1); });
        increase?.addEventListener('click', function () { update(1); });
    });

    document.querySelectorAll('[data-confirm-remove]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            if (typeof Swal === 'undefined') {
                if (window.confirm('Remove this product from your cart?')) form.submit();
                return;
            }

            Swal.fire({
                title: 'Remove product?',
                text: 'This item will be removed from your cart.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Remove',
                cancelButtonText: 'Keep item',
                confirmButtonColor: '#111111'
            }).then(function (result) {
                if (result.isConfirmed) form.submit();
            });
        });
    });

    window.showStoreToast = function (type, message) {
        if (!message || typeof Swal === 'undefined') return;

        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: type,
            title: message,
            showConfirmButton: false,
            timer: 2800,
            timerProgressBar: true
        });
    };
})();
