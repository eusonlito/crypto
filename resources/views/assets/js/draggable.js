(function (cash) {
    'use strict';

    const vw = Math.max(document.documentElement.clientWidth || 0, window.innerWidth || 0);

    if (vw < 1440) {
        return;
    }

    document.querySelectorAll('[data-draggable]').forEach(function (element) {
        const wrapper = document.querySelector(element.dataset.draggable);

        if (!wrapper) {
            return;
        }

        wrapper.style.position = 'absolute';
        wrapper.style.top = '50%';
        wrapper.style.left = '50%';
        wrapper.style.transform = 'translate(-50%, -50%)';

        function onDrag ({ movementX, movementY }) {
            const style = window.getComputedStyle(wrapper);

            const left = parseInt(style.left);
            const top = parseInt(style.top);

            wrapper.style.left = `${left + movementX}px`;
            wrapper.style.top = `${top + movementY}px`;
            wrapper.style.position = 'absolute';
        }

        element.addEventListener('mousedown', () => {
            element.addEventListener('mousemove', onDrag);
        });

        document.addEventListener('mouseup', () => {
            element.removeEventListener('mousemove', onDrag);
        });
    });
})(cash);
