/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import 'bootstrap';
import '@fortawesome/fontawesome-free/css/all.css';
import './styles/style.css';
import './styles/my.css';
import './styles/bootstrap-dataTables.css';

import 'datatables';
import './js/layout.js';
import './js/confirmModal.js';
import './js/club.js';
import './js/delegate.js';
import './js/deleteImage.js';
import './js/gameType.js';
import './js/matchGame.js';
import './js/matchGameForm.js';
import './js/person.js';
import './js/referee.js';
import './js/refereeObserver.js';
import './js/team.js';
import './js/user.js';
import './js/bootstrap-dataTables.js';

import 'bootstrap-select/dist/css/bootstrap-select.min.css';

const bootstrap = window.bootstrap = require('bootstrap'); // without this bootstrap-select crashes with `undefined bootstrap`
require('bootstrap-select/js/bootstrap-select'); // we have to manually require the working js file

$(function() {
    $('select[data-live-search=true]').selectpicker();
});
