(function (cash) {
    'use strict';

    const dataValueToPercent = ($this) => {
        byIdOptional($this.dataset.valueToPercent).value = percentRound(float($this.value), float(byIdOptional($this.dataset.valueToPercentReference).value));
    };

    const dataPercentToValue = ($this) => {
        let operation = $this.dataset.percentToValueOperation || 'add';

        const first = float(byIdOptional($this.dataset.percentToValueReference).value);
        const second = first * parseFloat(float($this.value)) / 100;

        let value = 0;

        if (operation === 'add') {
            value = first + second;
        } else if (operation === 'substract') {
            value = first - second;
        } else if (operation === 'value') {
            value = second;
        }

        byIdOptional($this.dataset.percentToValue).value = round(value);
    };

    const dataValueToValue = ($this) => {
        const total = float($this.value);
        const reference = float(byIdOptional($this.dataset.valueToValueReference).value);

        byIdOptional($this.dataset.valueToValueTarget).value = round(total / reference);
    };

    const dataTotal = ($this) => {
        ($this.dataset.totalTarget ? byIdOptional($this.dataset.totalTarget) : $this).value = round(float(byIdOptional($this.dataset.totalAmount).value) * float(byIdOptional($this.dataset.totalValue).value));
    };

    cash('[data-value-to-percent]').on('change keyup', function () {
        dataValueToPercent(this);
    });

    cash('[data-percent-to-value]').on('change keyup', function () {
        dataPercentToValue(this);
    });

    cash('[data-value-to-value]').on('change keyup', function () {
        dataValueToValue(this);
    });

    cash('[data-total]').each(function () {
        const $this = this,
            changes = ($this.dataset.totalChange || '').split(',');

        changes.push(this.dataset.totalAmount);
        changes.push(this.dataset.totalValue);

        changes.forEach((each) => byIdCash(each).on('change keyup', () => dataTotal($this)));
    });
})(cash);
