(function (cash) {
    'use strict';

    cash('[data-grid-12]').on('click', function () {
        var $this = cash(this);

        if ($this.hasClass('lg:col-span-4')) {
            $this.removeClass('lg:col-span-4');
            $this.addClass('lg:col-span-12');
        } else {
            $this.removeClass('lg:col-span-12');
            $this.addClass('lg:col-span-4')
        }
    });
})(cash);