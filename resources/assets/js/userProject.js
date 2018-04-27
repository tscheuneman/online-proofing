
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 */

require('./bootstrap');
window.Vue = require('vue');
window.VueBus = require('vue-bus');

Vue.use(VueBus);
/**
 * Next, we will create a fresh React component instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


window.axios = require('axios');
window.moment = require('moment');


import {store} from './components/store';

import {LC} from './scripts/literallyCanvas';

Vue.component('actions', require('./components/ActionsComponent.vue'));
Vue.component('proof', require('./components/Proof.vue'));
Vue.component('entry', require('./components/Entry.vue'));
Vue.component('proofEntry', require('./components/ProofEntry.vue'));
Vue.component('imageEntry', require('./components/ImageEntry.vue'));

const app = new Vue({
    el: '#app',
    store
});
