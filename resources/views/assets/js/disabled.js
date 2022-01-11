(function (cash) {
    'use strict';

    cash('[data-disabled]').each(function (e) {
        cash('input, select, textarea', this).prop('disabled', true);
    });
})(cash);
