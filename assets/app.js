import './bootstrap.js';
import './bootstrap';

/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';
import './styles/global.scss';
import './handlers/minMaxHandler.js';
import './handlers/modalHandlers.js';
import './handlers/calculateInPlnHandler.js'
window.bootstrap = require('bootstrap/dist/js/bootstrap.bundle.js');
