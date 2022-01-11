(function (cash) {
    'use strict';

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
})(cash);
