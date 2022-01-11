(function (cash) {
    'use strict';

    const $checkall = document.querySelector('[data-checkall]');

    if (!$checkall) {
        return;
    }

    const $checkboxes = cash($checkall.dataset.checkall + ' input[type="checkbox"]');

    if ($checkboxes.length) {
        $checkall.indeterminate = true;
    }

    $checkboxes.each(function () {
        this.dataset.checkallPrevious = this.checked;
    })

    let checkallClicks = 0;

    cash($checkall).on('click', function (e) {
        e.stopPropagation();

        if (checkallClicks === 0) {
            this.checked = true;
            this.indeterminate = false;

            $checkboxes.prop('checked', true);
        } else if (checkallClicks === 1) {
            this.checked = false;
            this.indeterminate = false;

            $checkboxes.prop('checked', false);
        } else {
            this.checked = true;
            this.indeterminate = true;

            $checkboxes.each(function () {
                this.checked = this.dataset.checkallPrevious === 'true';
            });
        }

        checkallClicks++;

        if (checkallClicks > 2) {
            checkallClicks = 0;
        }
    });
})(cash);
