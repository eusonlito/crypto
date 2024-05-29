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

    const calculator = (element) => {
        let working = false;

        const amount = element.querySelector('input[name="amount"]');

        const buy_exchange = element.querySelector('input[name="buy_exchange"]');
        const buy_value = element.querySelector('input[name="buy_value"]');

        const current_exchange = element.querySelector('input[name="current_exchange"]');
        const current_value = element.querySelector('input[name="current_value"]');

        const buy_stop_reference = element.querySelector('input[name="buy_stop_reference"]');
        const buy_stop_amount = element.querySelector('input[name="buy_stop_amount"]');
        const buy_stop_min_percent = element.querySelector('input[name="buy_stop_min_percent"]');
        const buy_stop_max_percent = element.querySelector('input[name="buy_stop_max_percent"]');
        const buy_stop_min_exchange = element.querySelector('input[name="buy_stop_min_exchange"]');
        const buy_stop_max_exchange = element.querySelector('input[name="buy_stop_max_exchange"]');
        const buy_stop_min_value = element.querySelector('input[name="buy_stop_min_value"]');
        const buy_stop_max_value = element.querySelector('input[name="buy_stop_max_value"]');

        const sell_stop_reference = element.querySelector('input[name="sell_stop_reference"]');
        const sell_stop_percent = element.querySelector('input[name="sell_stop_percent"]');
        const sell_stop_amount = element.querySelector('input[name="sell_stop_amount"]');
        const sell_stop_max_percent = element.querySelector('input[name="sell_stop_max_percent"]');
        const sell_stop_min_percent = element.querySelector('input[name="sell_stop_min_percent"]');
        const sell_stop_max_exchange = element.querySelector('input[name="sell_stop_max_exchange"]');
        const sell_stop_min_exchange = element.querySelector('input[name="sell_stop_min_exchange"]');
        const sell_stop_max_value = element.querySelector('input[name="sell_stop_max_value"]');
        const sell_stop_min_value = element.querySelector('input[name="sell_stop_min_value"]');

        const sell_stoploss_percent = element.querySelector('input[name="sell_stoploss_percent"]');
        const sell_stoploss_exchange = element.querySelector('input[name="sell_stoploss_exchange"]');
        const sell_stoploss_value = element.querySelector('input[name="sell_stoploss_value"]');

        const calculate = (e) => {
            if (working) {
                return;
            }

            working = true;

            let amount_value = amount ? float(amount.value) : null;

            let buy_exchange_value = buy_exchange ? float(buy_exchange.value) : null;
            let buy_value_value = buy_value ? float(buy_value.value) : null;

            let current_exchange_value = current_exchange ? float(current_exchange.value) : null;
            let current_value_value = current_value ? float(current_value.value) : null;

            let buy_stop_reference_value = buy_stop_reference ? float(buy_stop_reference.value) : null;
            let buy_stop_amount_value = buy_stop_amount ? float(buy_stop_amount.value) : null;
            let buy_stop_min_percent_value = buy_stop_min_percent ? float(buy_stop_min_percent.value) : null;
            let buy_stop_max_percent_value = buy_stop_max_percent ? float(buy_stop_max_percent.value) : null;
            let buy_stop_min_exchange_value = buy_stop_min_exchange ? float(buy_stop_min_exchange.value) : null;
            let buy_stop_max_exchange_value = buy_stop_max_exchange ? float(buy_stop_max_exchange.value) : null;
            let buy_stop_min_value_value = buy_stop_min_value ? float(buy_stop_min_value.value) : null;
            let buy_stop_max_value_value = buy_stop_max_value ? float(buy_stop_max_value.value) : null;

            let sell_stop_reference_value = sell_stop_reference ? float(sell_stop_reference.value) : null;
            let sell_stop_percent_value = sell_stop_percent ? float(sell_stop_percent.value) : null;
            let sell_stop_amount_value = sell_stop_amount ? float(sell_stop_amount.value) : null;
            let sell_stop_max_percent_value = sell_stop_max_percent ? float(sell_stop_max_percent.value) : null;
            let sell_stop_min_percent_value = sell_stop_min_percent ? float(sell_stop_min_percent.value) : null;
            let sell_stop_max_exchange_value = sell_stop_max_exchange ? float(sell_stop_max_exchange.value) : null;
            let sell_stop_min_exchange_value = sell_stop_min_exchange ? float(sell_stop_min_exchange.value) : null;
            let sell_stop_max_value_value = sell_stop_max_value ? float(sell_stop_max_value.value) : null;
            let sell_stop_min_value_value = sell_stop_min_value ? float(sell_stop_min_value.value) : null;

            let sell_stoploss_percent_value = sell_stoploss_percent ? float(sell_stoploss_percent.value) : null;
            let sell_stoploss_exchange_value = sell_stoploss_exchange ? float(sell_stoploss_exchange.value) : null;
            let sell_stoploss_value_value = sell_stoploss_value ? float(sell_stoploss_value.value) : null;

            // --- //

            if (buy_value) {
                buy_value_value = amount_value * buy_exchange_value;
                buy_value.value = buy_value_value;
            }

            if (current_value) {
                current_value_value = amount_value * current_exchange_value;
                current_value.value = current_value_value;
            }

            // --- //

            if (buy_stop_min_exchange) {
                buy_stop_min_exchange_value = buy_stop_reference_value * (1 - (buy_stop_min_percent_value / 100));
                buy_stop_min_exchange.value = buy_stop_min_exchange_value;
            }

            if (buy_stop_max_exchange) {
                buy_stop_max_exchange_value = buy_stop_min_exchange_value * (1 + (buy_stop_max_percent_value / 100));
                buy_stop_max_exchange.value = buy_stop_max_exchange_value;
            }

            if (buy_stop_amount) {
                buy_stop_amount_value = buy_stop_max_value_value / buy_stop_max_exchange_value;
                buy_stop_amount.value = buy_stop_amount_value;
            }

            if (buy_stop_min_value) {
                buy_stop_min_value_value = buy_stop_amount_value * buy_stop_min_exchange_value;
                buy_stop_min_value.value = buy_stop_min_value_value;
            }

            // --- //

            if (sell_stop_amount) {
                sell_stop_amount_value = amount_value * sell_stop_percent_value / 100;
                sell_stop_amount.value = sell_stop_amount_value;
            }

            if (sell_stop_max_exchange) {
                sell_stop_max_exchange_value = sell_stop_reference_value * (1 + (sell_stop_max_percent_value / 100));
                sell_stop_max_exchange.value = sell_stop_max_exchange_value;
            }

            if (sell_stop_min_exchange) {
                sell_stop_min_exchange_value = sell_stop_max_exchange_value * (1 - (sell_stop_min_percent_value / 100));
                sell_stop_min_exchange.value = sell_stop_min_exchange_value;
            }

            if (sell_stop_max_value) {
                sell_stop_max_value_value = sell_stop_amount_value * sell_stop_max_exchange_value;
                sell_stop_max_value.value = sell_stop_max_value_value;
            }

            if (sell_stop_min_value) {
                sell_stop_min_value_value = sell_stop_amount_value * sell_stop_min_exchange_value;
                sell_stop_min_value.value = sell_stop_min_value_value;
            }

            // --- //

            if (sell_stoploss_exchange) {
                sell_stoploss_exchange_value = buy_exchange_value * (1 - (sell_stoploss_percent_value / 100));
                sell_stoploss_exchange.value = sell_stoploss_exchange_value;
            }

            if (sell_stoploss_value) {
                sell_stoploss_value_value = amount_value * sell_stoploss_exchange_value;
                sell_stoploss_value.value = sell_stoploss_value_value;
            }

            // --- //

            working = false;
        };

        if (amount) {
            amount.addEventListener('input', calculate);
        }

        if (buy_exchange) {
            buy_exchange.addEventListener('input', calculate);
        }

        if (buy_stop_reference) {
            buy_stop_reference.addEventListener('input', calculate);
        }

        if (buy_stop_max_value) {
            buy_stop_max_value.addEventListener('input', calculate);
        }

        if (buy_stop_min_percent) {
            buy_stop_min_percent.addEventListener('input', calculate);
        }

        if (buy_stop_max_percent) {
            buy_stop_max_percent.addEventListener('input', calculate);
        }

        if (sell_stop_reference) {
            sell_stop_reference.addEventListener('input', calculate);
        }

        if (sell_stop_percent) {
            sell_stop_percent.addEventListener('input', calculate);
        }

        if (sell_stop_max_percent) {
            sell_stop_max_percent.addEventListener('input', calculate);
        }

        if (sell_stop_min_percent) {
            sell_stop_min_percent.addEventListener('input', calculate);
        }

        if (sell_stoploss_percent) {
            sell_stoploss_percent.addEventListener('input', calculate);
        }
    };

    document.querySelectorAll('[data-wallet]').forEach(calculator);
})(cash);
