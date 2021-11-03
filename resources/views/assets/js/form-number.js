(function (cash) {
    'use strict';

    function dataValueToPercent ($this) {
        byIdOptional($this.dataset.valueToPercent).value = percentRound(float($this.value), float(byIdOptional($this.dataset.valueToPercentReference).value));
    }

    function dataPercentToValue ($this) {
        const operation = ($this.dataset.percentToValueOperation === 'substract') ? 'substract' : 'add';

        const first = float(byIdOptional($this.dataset.percentToValueReference).value);
        const second = first * parseFloat(float($this.value)) / 100;

        byIdOptional($this.dataset.percentToValue).value = round((operation === 'add') ? (first + second) : (first - second));
    }

    function dataTotal ($this) {
        ($this.dataset.totalTarget ? byIdOptional($this.dataset.totalTarget) : $this).value = round(float(byIdOptional($this.dataset.totalAmount).value) * float(byIdOptional($this.dataset.totalValue).value));
    }

    cash('[data-value-to-percent]').on('change keyup', function () {
        dataValueToPercent(this);
    });

    cash('[data-percent-to-value]').on('change keyup', function () {
        dataPercentToValue(this);
    });

    cash('[data-total]').each(function () {
        const $this = this,
            changes = ($this.dataset.totalChange || '').split(',');

        changes.push(this.dataset.totalAmount);
        changes.push(this.dataset.totalValue);

        changes.forEach((each) => byIdCash(each).on('change keyup', () => dataTotal($this)));
    });
})(cash);
