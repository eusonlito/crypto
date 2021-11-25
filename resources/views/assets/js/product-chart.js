import chart from 'chart.js';

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

    const $chart = new chart($canvas.getContext('2d'), productChart.config);

    $canvas.onclick = function (e) {
        e.preventDefault();

        if (insideChart(e) === false) {
            return;
        }

        if ($start_at.value && $end_at.value) {
            $start_at.value = '';
            $end_at.value = '';
        } else if ($start_at.value) {
            $end_at.value = clickLabel(e);
        } else {
            $start_at.value = clickLabel(e);
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

        if (!point) {
            return;
        }

        return $chart.scales['x-axis-default'].getLabelForIndex(point._index, point._datasetIndex);
    }
})();
