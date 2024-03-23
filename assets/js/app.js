require('../css/app.scss');

import { Modal, Tooltip, Popover } from "bootstrap";
import { parse } from '@vanillaes/csv';
const axios = require('axios');
require('../../vendor/schulit/common-bundle/Resources/assets/js/polyfill');
require('../../vendor/schulit/common-bundle/Resources/assets/js/menu');

const batchSize = 1000;

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('[title]').forEach(function(el) {
        new Tooltip(el, {
            placement: 'bottom'
        });
    });

    document.querySelector('.form-import-codes').closest('form').addEventListener('submit', function(event) {
        event.preventDefault();

        let $submit = this.querySelector('button[type=submit]');

        $submit.disabled = 'disabled';

        let url = this.querySelector('.form-import-codes').getAttribute('data-url');
        let $duration = this.querySelector('input[type=number]');
        let $input = this.querySelector('input[type=file]');
        let $progress = this.querySelector('.progress');
        if($input.files.length !== 1) {
            console.error('Keine oder mehr als eine Datei ausgewählt. Fehler.');
            return;
        }

        let reader = new FileReader();
        reader.onload = async function() {
            // Remove comment fields
            let data = reader.result.replace(/^#.*$/gm, '');

            let parsed = parse(data);
            let count = parsed.length;

            for(let batch = 0; batch < Math.ceil(count / batchSize); batch++) {
                let offsetStart = batch*batchSize;
                let offsetEnd = Math.min(parsed.length, (batch+1)*batchSize);

                let codes = [ ];
                for(let idx = offsetStart; idx < offsetEnd; idx++) {
                    let code = parsed[idx][0].trim();

                    if(code !== '') {
                        codes.push(code);
                    }
                }

                let importObject = {
                    duration: parseInt($duration.value),
                    codes: codes
                };

                let response = await axios.post(url, JSON.stringify(importObject), {
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                // update progress bar
                let width = Math.round(offsetEnd / count * 100);
                $progress.querySelector('.progress-bar').style.width = width + '%';
                $progress.querySelector('.progress-bar').innerHTML = offsetEnd + "/" + count;
            }

            $submit.disabled = '';
            window.location.reload();
        }

        reader.readAsText($input.files[0]);
    });
});