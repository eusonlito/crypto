(function (cash) {
    'use strict';

    cash('[data-link-boolean]').on('click', function (e) {
        e.preventDefault();

        const link = this;

        ajax(link.href, function(response) {
            const value = response[link.dataset.linkBoolean];

            link.innerHTML = '<span class="hidden">' + (value ? '1' : '0') + '</span>'
                + '<span class="flex items-center justify-center ' + (value ? 'text-theme-10' : 'text-theme-24') + '">'
                + '<svg class="feather w-4 h-4 mr-2"><use xlink:href="' + WWW + '/build/images/feather-sprite.svg#' + (value ? 'check-square' : 'square') + '" /></svg>'
                + '</span>';
        });
    });
})(cash);
