(function () {
    'use strict';

    const $element = document.querySelector('[data-wallet-scenario]');

    if (!$element) {
        return;
    }

    const sell_stop_max_percent_min = $element.querySelector('input[name="sell_stop_max_percent_min"]');
    const sell_stop_max_percent_max = $element.querySelector('input[name="sell_stop_max_percent_max"]');

    const sell_stop_min_percent_min = $element.querySelector('input[name="sell_stop_min_percent_min"]');
    const sell_stop_min_percent_max = $element.querySelector('input[name="sell_stop_min_percent_max"]');

    const sell_stop_percent_step = $element.querySelector('input[name="sell_stop_percent_step"]');

    const buy_stop_min_percent_min = $element.querySelector('input[name="buy_stop_min_percent_min"]');
    const buy_stop_min_percent_max = $element.querySelector('input[name="buy_stop_min_percent_max"]');

    const buy_stop_max_percent_min = $element.querySelector('input[name="buy_stop_max_percent_min"]');
    const buy_stop_max_percent_max = $element.querySelector('input[name="buy_stop_max_percent_max"]');

    const buy_stop_percent_step = $element.querySelector('input[name="buy_stop_percent_step"]');

    const sell_stoploss_percent_min = $element.querySelector('input[name="sell_stoploss_percent_min"]');
    const sell_stoploss_percent_max = $element.querySelector('input[name="sell_stoploss_percent_max"]');

    const sell_stoploss_percent_step = $element.querySelector('input[name="sell_stoploss_percent_step"]');

    const submit = $element.querySelector('button[type="submit"]');

    const calculate = function () {
        let steps = 1;

        const sell_stop_percent_step_value = parseFloat(sell_stop_percent_step.value);

        const sell_stop_max_percent_min_value = parseFloat(sell_stop_max_percent_min.value);
        const sell_stop_max_percent_max_value = parseFloat(sell_stop_max_percent_max.value);

        steps *= ((sell_stop_max_percent_max_value - sell_stop_max_percent_min_value) / sell_stop_percent_step_value) + 1;

        const sell_stop_min_percent_min_value = parseFloat(sell_stop_min_percent_min.value);
        const sell_stop_min_percent_max_value = parseFloat(sell_stop_min_percent_max.value);

        steps *= ((sell_stop_min_percent_max_value - sell_stop_min_percent_min_value) / sell_stop_percent_step_value) + 1;

        const buy_stop_percent_step_value = parseFloat(buy_stop_percent_step.value);

        const buy_stop_min_percent_min_value = parseFloat(buy_stop_min_percent_min.value);
        const buy_stop_min_percent_max_value = parseFloat(buy_stop_min_percent_max.value);

        steps *= ((buy_stop_min_percent_max_value - buy_stop_min_percent_min_value) / buy_stop_percent_step_value) + 1;

        const buy_stop_max_percent_min_value = parseFloat(buy_stop_max_percent_min.value);
        const buy_stop_max_percent_max_value = parseFloat(buy_stop_max_percent_max.value);

        steps *= ((buy_stop_max_percent_max_value - buy_stop_max_percent_min_value) / buy_stop_percent_step_value) + 1;

        const sell_stoploss_percent_step_value = parseFloat(sell_stoploss_percent_step.value);

        const sell_stoploss_percent_min_value = parseFloat(sell_stoploss_percent_min.value);
        const sell_stoploss_percent_max_value = parseFloat(sell_stoploss_percent_max.value);

        steps *= ((sell_stoploss_percent_max_value - sell_stoploss_percent_min_value) / sell_stoploss_percent_step_value) + 1;

        submit.innerHTML = submit.innerHTML.replace(/[0-9]+/, Math.abs(parseInt(steps)) || 1);
    };

    calculate();

    sell_stop_max_percent_min.addEventListener('keyup', calculate);
    sell_stop_max_percent_max.addEventListener('keyup', calculate);
    sell_stop_min_percent_min.addEventListener('keyup', calculate);
    sell_stop_min_percent_max.addEventListener('keyup', calculate);
    sell_stop_percent_step.addEventListener('keyup', calculate);

    buy_stop_min_percent_min.addEventListener('keyup', calculate);
    buy_stop_min_percent_max.addEventListener('keyup', calculate);
    buy_stop_max_percent_min.addEventListener('keyup', calculate);
    buy_stop_max_percent_max.addEventListener('keyup', calculate);
    buy_stop_percent_step.addEventListener('keyup', calculate);

    sell_stoploss_percent_min.addEventListener('keyup', calculate);
    sell_stoploss_percent_max.addEventListener('keyup', calculate);
    sell_stop_percent_step.addEventListener('keyup', calculate);
})();
