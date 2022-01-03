import Chart from 'chart.js/auto';

(function () {
    'use strict';

    if (typeof productChart === 'undefined') {
        return;
    }

    const $canvas = document.getElementById('product-chart-canvas');

    if (!$canvas) {
        return;
    }

    const $start_at = document.querySelector('input[name="start_at"]');
    const $end_at = document.querySelector('input[name="end_at"]');

    if (!$start_at || !$end_at) {
        return;
    }

    const $chart = new Chart($canvas.getContext('2d'), productChart.config);

    $canvas.onclick = function (e) {
        e.preventDefault();

        if (insideChart(e) === false) {
            return;
        }

        if ($start_at.value && $end_at.value) {
            $start_at.value = '';
            $end_at.value = '';

            return;
        }

        const value = clickLabel(e);

        if (!value) {
            return
        }

        if ($start_at.value) {
            $end_at.value = value;
        } else {
            $start_at.value = value;
        }
    }

    function insideChart (e) {
        const area = $chart.chartArea;

        return (e.offsetX >= area.left) &&
            (e.offsetX <= area.right) &&
            (e.offsetY >= area.top) &&
            (e.offsetY <= area.bottom);
    }

    function clickLabel (e) {
        const point = $chart.getElementsAtEventForMode(e, 'nearest', { intersect: false, axis: 'x' })[0];

        return point ? $chart.scales.x.getLabelForValue(point.index) : '';
    }
})();
