(function () {
    'use strict';

    const body = document.body;
    const sidebarToggle = document.querySelector('[data-sidebar-toggle]');
    const sidebarClose = document.querySelector('[data-sidebar-close]');
    const sidebarOverlay = document.querySelector('[data-sidebar-overlay]');

    function setSidebar(open) {
        body.classList.toggle('sidebar-open', open);
        if (sidebarToggle) {
            sidebarToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        }
    }

    sidebarToggle?.addEventListener('click', function () {
        setSidebar(!body.classList.contains('sidebar-open'));
    });

    sidebarClose?.addEventListener('click', function () {
        setSidebar(false);
    });

    sidebarOverlay?.addEventListener('click', function () {
        setSidebar(false);
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth > 920) {
            setSidebar(false);
        }
    });

    document.querySelectorAll('[data-alert-close]').forEach(function (button) {
        button.addEventListener('click', function () {
            button.closest('.admin-alert')?.remove();
        });
    });

    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (form.dataset.confirmed === 'true') {
                return;
            }

            event.preventDefault();

            const title = form.dataset.confirmTitle || 'Are you sure?';
            const text = form.dataset.confirmText || 'This action cannot be undone.';
            const confirmText = form.dataset.confirmButton || 'Yes, continue';

            if (window.Swal) {
                window.Swal.fire({
                    title: title,
                    text: text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: confirmText,
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#171716',
                    cancelButtonColor: '#8a8a84',
                    reverseButtons: true
                }).then(function (result) {
                    if (result.isConfirmed) {
                        form.dataset.confirmed = 'true';
                        form.submit();
                    }
                });
                return;
            }

            if (window.confirm(title + '\n\n' + text)) {
                form.dataset.confirmed = 'true';
                form.submit();
            }
        });
    });

    document.querySelectorAll('[data-image-input]').forEach(function (input) {
        input.addEventListener('change', function () {
            const file = input.files && input.files[0];
            const targetId = input.dataset.imageInput;
            const preview = document.getElementById(targetId);
            const upload = input.closest('.file-upload');

            if (!file || !preview) {
                return;
            }

            const reader = new FileReader();
            reader.onload = function (event) {
                preview.src = event.target.result;
                preview.classList.add('visible');
                upload?.classList.add('has-preview');
            };
            reader.readAsDataURL(file);
        });
    });

    document.addEventListener('click', function (event) {
        document.querySelectorAll('.admin-account[open]').forEach(function (details) {
            if (!details.contains(event.target)) {
                details.removeAttribute('open');
            }
        });
    });
})();
