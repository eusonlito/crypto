(function () {
    'use strict';

    const number = (value) => {
        return parseFloat((value || '0').replace('.', '').replace(',', '.'));
    };

    const round = (value) => {
        return Math.round(parseFloat(value) * 100) / 100;
    };

    const format = (value) => {
        return value.toLocaleString('es-ES', {
            minimumFractionDigits: 2
        });
    };

    document.querySelectorAll('[data-amount-editable]').forEach(element => {
        if (!element.dataset.amountEditableValue || !element.dataset.amountEditableTotal) {
            return;
        }

        const value = document.getElementById(element.dataset.amountEditableValue);
        const total = document.getElementById(element.dataset.amountEditableTotal);

        if (!value || !total) {
            return;
        }

        element.addEventListener('click', () => {
            element.setAttribute('contenteditable', true);
        }, false);

        element.addEventListener('input', () => {
            total.innerHTML = format(round(number(element.innerHTML) * number(value.innerHTML)));
        }, false);
    });
})();
