require('../css/app.scss');

global.bsn = require('bootstrap.native');
let bsCustomFileInput = require('bs-custom-file-input');
require('../../vendor/schulit/common-bundle/Resources/assets/js/polyfill');
require('../../vendor/schulit/common-bundle/Resources/assets/js/menu');

document.addEventListener('DOMContentLoaded', function() {
    bsCustomFileInput.init();

    document.querySelectorAll('[title]').forEach(function (el) {
        new bsn.Tooltip(el, {
            placement: 'bottom'
        });
    });
});