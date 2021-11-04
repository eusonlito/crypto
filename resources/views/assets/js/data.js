(function (cash) {
    'use strict';

    cash('[data-change-submit]').on('change', function (e) {
        const form = this.closest('form');

        if (!form) {
            return;
        }

        e.preventDefault();

        form.submit();
    });

    cash('[data-click-one]').on('click', function (e) {
        const form = this.closest('form');

        if (!form) {
            return;
        }

        e.preventDefault();

        this.disabled = true;

        const input = document.createElement('input');

        input.type = 'hidden';
        input.name = this.name;
        input.value = this.value;

        form.appendChild(input);
        form.submit();
    });

    cash('[data-link-boolean]').on('click', function (e) {
        e.preventDefault();

        const link = this;

        ajax(link.href, function(response) {
            const value = response[link.dataset.linkBoolean];

            link.innerHTML = '<span class="hidden">' + (value ? '1' : '0') + '</span>'
                + '<span class="flex items-center justify-center ' + (value ? 'text-theme-10' : 'text-theme-24') + '">'
                + '<svg class="feather w-4 h-4 mr-2"><use xlink:href="' + WWW + '/build/images/feather-sprite.svg#' + (value ? 'check-square' : 'square') + '" /></svg>'
                + '</span>';
        });
    });

    cash('[data-link-form]').on('change', function (e) {
        e.preventDefault();

        ajax(this.dataset.linkForm, { [this.name]: this.value });
    });

    cash('[data-disabled]').each(function (e) {
        cash('input, select, textarea', this).prop('disabled', true);
    });

    cash('[data-password-show]').on('click', function (e) {
        e.preventDefault();

        const field = byQuery(this.dataset.passwordShow);

        if (!field) {
            return;
        }

        let hidden;

        if (field.tagName.toLowerCase() === 'textarea') {
            hidden = field.classList.contains('textarea-password');
            field.classList.toggle('textarea-password');
        } else {
            hidden = field.type === 'password';
            field.type = hidden ? 'text' : 'password';
        }

        this.querySelector('svg:first-child').innerHTML = '<use xlink:href="' + WWW + '/build/images/feather-sprite.svg#' + (hidden ? 'eye-off' : 'eye') + '" />';
    });
})(cash);