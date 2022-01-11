(function (cash) {
    'use strict';

    cash('[data-link-form]').on('change', function (e) {
        e.preventDefault();

        ajax(this.dataset.linkForm, { [this.name]: this.value });
    });
})(cash);
