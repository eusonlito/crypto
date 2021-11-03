(function (cash) {
    'use strict';

    cash('body').on('click', '[data-product-favorite]', function (e) {
        e.preventDefault();

        const link = this;

        ajax(link.href, function(response) {
            const icon = link.querySelector('.feather');

            if (response.id) {
                icon.classList.add('is-favorite');
            } else {
                icon.classList.remove('is-favorite');
            }
        });
    });
})(cash);
