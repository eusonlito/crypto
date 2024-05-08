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

    document.querySelectorAll('[data-amount-editable]').forEach(element => {
        const $currentExchange = document.getElementById(element.dataset.amountEditableCurrentExchange);
        const $currentValue = document.getElementById(element.dataset.amountEditableCurrentValue);
        const $buyExchange = document.getElementById(element.dataset.amountEditableBuyExchange);
        const $buyValue = document.getElementById(element.dataset.amountEditableBuyValue);
        const $result = document.getElementById(element.dataset.amountEditableResult);

        if (!$currentExchange || !$currentValue || !$buyExchange || !$buyValue || !$result) {
            return;
        }

        element.addEventListener('click', () => {
            element.setAttribute('contenteditable', true);
        }, false);

        element.addEventListener('input', () => {
            const amount = number(element.innerHTML);
            const currentExchange = number($currentExchange.innerHTML);
            const buyExchange = number($buyExchange.innerHTML);
            const currentValue = amount * currentExchange;
            const buyValue = amount * buyExchange;

            $currentValue.innerHTML = format(currentValue);
            $buyValue.innerHTML = format(buyValue);
            $result.innerHTML = format(currentValue - buyValue);
        }, false);
    });
})();
