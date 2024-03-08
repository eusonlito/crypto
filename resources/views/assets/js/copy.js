(function () {
    'use strict';

    const copyValue = (element) => {
        if (element.dataset.copyValue) {
            return element.dataset.copyValue;
        }

        const target = document.querySelector(element.dataset.copy);

        if (!target) {
            return;
        }

        if (['input', 'textarea'].includes(target.tagName.toLowerCase())) {
            return target.value;
        }

        return target.innerHTML;
    };

    function clipboard (text) {
        const element = document.createElement('textarea');

        element.style = 'position: absolute; width: 1px; height: 1px; left: -10000px; top: -10000px';
        element.value = text;

        document.body.appendChild(element);

        element.select();
        element.setSelectionRange(0, Number.MAX_SAFE_INTEGER);

        document.execCommand('copy');

        document.body.removeChild(element);
    }

    document.querySelectorAll('[data-copy], [data-copy-value]').forEach(element => {
        element.addEventListener('click', (e) => {
            e.preventDefault();

            clipboard(copyValue(element));

            const color = element.style.color;

            element.style.color = 'green';

            setTimeout(() => element.style.color = color, 1000);
        }, false);
    });
})();
