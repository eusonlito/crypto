(function (cash) {
    'use strict';

    const search = function (e) {
        if (event.code === 'Enter') {
            return e.preventDefault();
        }

        if (event.type !== 'input') {
            return;
        }

        const value = this.value.toLowerCase().trim();

        cash(this.dataset.tableSearch).find('tbody > tr').each(function () {
            this.style.display = ((this.textContent || this.innerText).toLowerCase().indexOf(value) > -1) ? '' : 'none';
        });
    };

    cash('[data-table-search]').on('input', search);
    cash('[data-table-search]').on('keydown', search);

    const cellValue = function (td) {
        return td.dataset.tableSortValue || (td.innerText || td.textContent).trim();
    };

    const sort = function (table, col, reverse) {
        let tb = table.tBodies[0],
            tr = Array.prototype.slice.call(tb.rows, 0);

        reverse = -((+reverse) || -1);

        tr = tr.sort(function (a, b) {
            const aValue = cellValue(a.cells[col]);
            const bValue = cellValue(b.cells[col]);

            if (!isNaN(aValue) && !isNaN(bValue)) {
                return reverse * (aValue - bValue);
            }

            return reverse * aValue.localeCompare(bValue);
        });

        for (let i = 0; i < tr.length; ++i) {
            tb.appendChild(tr[i]);
        }
    };

    cash('[data-table-sort]').each(function (e) {
        const table = this;
        const th = this.tHead.rows[0].cells;
        let i = th.length;

        while (--i >= 0) (function (i) {
            let dir = 1;

            th[i].addEventListener('click', function () {
                sort(table, i, (dir = 1 - dir));
            });
        }(i));
    });
})(cash);