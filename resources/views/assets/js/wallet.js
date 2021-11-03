(function (cash) {
    'use strict';

    cash('body').on('click', '[data-wallet-order-status]', function (e) {
        e.preventDefault();

        const link = this.dataset.walletOrderStatusLink;
        const target = this.dataset.walletOrderStatusTarget;

        if (!link || !target) {
            return;
        }

        ajax(link, function(response) {
            if (response && response.sell_pending_average) {
                byQueryOptional(target).value = response.sell_pending_average.toFixed(response.product.price_decimal);
            }
        });
    });
})(cash);
