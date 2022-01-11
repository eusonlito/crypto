(function (cash) {
    'use strict';

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
