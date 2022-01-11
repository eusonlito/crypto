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
})(cash);
