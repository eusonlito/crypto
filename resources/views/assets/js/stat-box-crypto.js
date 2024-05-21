(function () {
    'use strict';

    const number = (value) => {
        return parseFloat((value || '0').replace('.', '').replace(',', '.'));
    };

    const round = (value) => {
        return Math.round(parseFloat(value) * 100) / 100;
    };

    const format = (value) => {
        return round(value).toLocaleString('es-ES', {
            minimumFractionDigits: 2,
            useGrouping: true
        });
    };

    document.querySelectorAll('[data-stat-box-crypto]').forEach(element => {
        const $current_exchange = element.querySelector('[data-stat-box-crypto-current_exchange]');
        const $current_value = element.querySelector('[data-stat-box-crypto-current_value]');
        const $amount = element.querySelector('[data-stat-box-crypto-amount]');
        const $buy_exchange = element.querySelector('[data-stat-box-crypto-buy_exchange]');
        const $buy_value = element.querySelector('[data-stat-box-crypto-buy_value]');
        const $result = element.querySelector('[data-stat-box-crypto-result]');

        if (!$current_exchange || !$current_value || !$amount || !$buy_exchange || !$buy_value || !$result) {
            return;
        }

        const $sell_stop_amount = element.querySelector('[data-stat-box-crypto-sell_stop_amount]');
        const $sell_stop_max_exchange = element.querySelector('[data-stat-box-crypto-sell_stop_max_exchange]');
        const $sell_stop_min_exchange = element.querySelector('[data-stat-box-crypto-sell_stop_min_exchange]');
        const $sell_stop_max_value = element.querySelector('[data-stat-box-crypto-sell_stop_max_value]');
        const $sell_stop_max_value_difference = element.querySelector('[data-stat-box-crypto-sell_stop_max_value_difference]');
        const $sell_stop_min_value = element.querySelector('[data-stat-box-crypto-sell_stop_min_value]');
        const $sell_stop_min_value_difference = element.querySelector('[data-stat-box-crypto-sell_stop_min_value_difference]');

        const $buy_stop_amount = element.querySelector('[data-stat-box-crypto-buy_stop_amount]');
        const $buy_stop_min_exchange = element.querySelector('[data-stat-box-crypto-buy_stop_min_exchange]');
        const $buy_stop_max_exchange = element.querySelector('[data-stat-box-crypto-buy_stop_max_exchange]');
        const $buy_stop_min_value = element.querySelector('[data-stat-box-crypto-buy_stop_min_value]');
        const $buy_stop_min_value_difference = element.querySelector('[data-stat-box-crypto-buy_stop_min_value_difference]');
        const $buy_stop_max_value = element.querySelector('[data-stat-box-crypto-buy_stop_max_value]');
        const $buy_stop_max_value_difference = element.querySelector('[data-stat-box-crypto-buy_stop_max_value_difference]');

        const $sell_stoploss_amount = element.querySelector('[data-stat-box-crypto-sell_stoploss_amount]');
        const $sell_stoploss_exchange = element.querySelector('[data-stat-box-crypto-sell_stoploss_exchange]');
        const $sell_stoploss_value = element.querySelector('[data-stat-box-crypto-sell_stoploss_value]');
        const $sell_stoploss_value_difference = element.querySelector('[data-stat-box-crypto-sell_stoploss_value_difference]');

        $amount.addEventListener('click', () => {
            $amount.setAttribute('contenteditable', true);
        }, false);

        $amount.addEventListener('input', () => {
            const amount = number($amount.innerHTML);
            const current_exchange = number($current_exchange.innerHTML);
            const buy_exchange = number($buy_exchange.innerHTML);
            const current_value = amount * current_exchange;
            const buy_value = amount * buy_exchange;

            $current_value.innerHTML = format(current_value);
            $buy_value.innerHTML = format(buy_value);
            $result.innerHTML = format(current_value - buy_value);

            if ($sell_stop_amount) {
                $sell_stop_amount.innerHTML = format(amount);

                const sell_stop_max_exchange = number($sell_stop_max_exchange.innerHTML);
                const sell_stop_min_exchange = number($sell_stop_min_exchange.innerHTML);
                const sell_stop_max_value = amount * sell_stop_max_exchange;
                const sell_stop_max_value_difference = sell_stop_max_value - buy_value;
                const sell_stop_min_value = amount * sell_stop_min_exchange;
                const sell_stop_min_value_difference = sell_stop_min_value - buy_value;

                $sell_stop_max_value.innerHTML = format(sell_stop_max_value);
                $sell_stop_max_value_difference.innerHTML = format(sell_stop_max_value_difference);
                $sell_stop_min_value.innerHTML = format(sell_stop_min_value);
                $sell_stop_min_value_difference.innerHTML = format(sell_stop_min_value_difference);
            }

            if ($buy_stop_amount) {
                $buy_stop_amount.innerHTML = format(amount);

                const buy_stop_min_exchange = number($buy_stop_min_exchange.innerHTML);
                const buy_stop_max_exchange = number($buy_stop_max_exchange.innerHTML);
                const buy_stop_min_value = amount * buy_stop_min_exchange;
                const buy_stop_min_value_difference = buy_stop_min_value - buy_value;
                const buy_stop_max_value = amount * buy_stop_max_exchange;
                const buy_stop_max_value_difference = buy_stop_max_value - buy_value;

                $buy_stop_min_value.innerHTML = format(buy_stop_min_value);
                $buy_stop_min_value_difference.innerHTML = format(buy_stop_min_value_difference);
                $buy_stop_max_value.innerHTML = format(buy_stop_max_value);
                $buy_stop_max_value_difference.innerHTML = format(buy_stop_max_value_difference);
            }

            if ($sell_stoploss_amount) {
                $sell_stoploss_amount.innerHTML = format(amount);

                const sell_stoploss_exchange = number($sell_stoploss_exchange.innerHTML);
                const sell_stoploss_value = amount * sell_stoploss_exchange;
                const sell_stoploss_value_difference = sell_stoploss_value - buy_value;

                $sell_stoploss_value.innerHTML = format(sell_stoploss_value);
                $sell_stoploss_value_difference.innerHTML = format(sell_stoploss_value_difference);
            }
        }, false);
    });
})();
