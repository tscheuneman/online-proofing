
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.VueBus = require('vue-bus');
window.VToolTip = require('v-tooltip');
window.markdown = require('markdown').markdown;

Vue.use(VueBus);
Vue.use(VToolTip);

/**
 * Next, we will create a fresh React component instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


window.axios = require('axios');
window.moment = require('moment');


import {store} from './components/store';

import {LC} from './scripts/literallyCanvas';

import { Photoshop } from 'vue-color'


Vue.component('projectNavigation', require('./components/ProjectNavigation.vue'));

Vue.component('actions', require('./components/ActionsComponent.vue'));
Vue.component('pages-left', require('./components/Allpages.vue'));
Vue.component('proof', require('./components/Proof.vue'));
Vue.component('entry', require('./components/Entry.vue'));
Vue.component('proofEntry', require('./components/ProofEntry.vue'));
Vue.component('imageEntry', require('./components/ImageEntry.vue'));
Vue.component('color-picker', require('vue-color/src/components/Photoshop.vue'));

Vue.component('pageEntry', require('./components/ThumbnailPage.vue'));
Vue.component('eachEntry', require('./components/EachNavigation.vue'));

Vue.component('commentEntry', require('./components/CommentEntry.vue'));

//Guest / Admin
Vue.component('project-navigation-guest', require('./components/AdminGuest/ProjectNavigationGuest.vue'));
Vue.component('proof-guest', require('./components/AdminGuest/ProofGuest.vue'));
Vue.component('proofEntryGuest', require('./components/AdminGuest/ProofEntryGuest.vue'));
Vue.component('imageEntryGuest', require('./components/AdminGuest/imageEntryGuest.vue'));

const app = new Vue({
    el: '#app',
    store
});


