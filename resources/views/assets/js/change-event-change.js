(function (cash) {
    'use strict';

    cash('[data-change-event-change] input').on('change', function (e) {
        if (this.dataset.changeEventChangeApply) {
            return;
        }

        this.closest('form').querySelectorAll('input').forEach(element => {
            element.dataset.changeEventChangeApply = 'true';
            element.dispatchEvent(new Event('change'));
            element.dataset.changeEventChangeApply = '';
        });
    });
})(cash);
