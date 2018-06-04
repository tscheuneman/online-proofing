
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 */
require('bootstrap');

window.Vue = require('vue');
window.VueBus = require('vue-bus');
window.VToolTip = require('v-tooltip');


import VueDraggableResizable from 'vue-draggable-resizable';


Vue.use(VueBus);
Vue.use(VToolTip);

/**
 * Next, we will create a fresh React component instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


window.axios = require('axios');
window.moment = require('moment');
window.markdown = require('markdown').markdown;

import {store} from './components/store';

import { Photoshop } from 'vue-color'


Vue.component('actions', require('./components/ActionsComponent.vue'));
Vue.component('pages-left', require('./components/Allpages.vue'));

Vue.component('entry', require('./components/Entry.vue'));

Vue.component('pageEntry', require('./components/ThumbnailPage.vue'));
Vue.component('eachEntry', require('./components/EachNavigation.vue'));

Vue.component('commentEntry', require('./components/AdminGuest/CommentEntryAdmin.vue'));

//Guest / Admin
Vue.component('project-navigation-admin', require('./components/AdminGuest/ProjectNavigationAdmin.vue'));
Vue.component('proof-guest', require('./components/AdminGuest/ProofAdmin.vue'));
Vue.component('proofEntryGuest', require('./components/AdminGuest/ProofEntryAdmin.vue'));
Vue.component('imageEntryGuest', require('./components/AdminGuest/ImageEntryAdmin.vue'));

Vue.component('revisions-admin', require('./components/AdminGuest/RevisionsAdmin.vue'));
Vue.component('comments-admin', require('./components/AdminGuest/RevisionComments.vue'));

Vue.component('revisionsComments', require('./components/AdminGuest/RevisionCommentEach.vue'));
Vue.component('pageCommentEntry', require('./components/AdminGuest/PageCommentEntry.vue'));
Vue.component('revisionPageComment', require('./components/AdminGuest/RevisionPageComment.vue'));


Vue.component('messaging', require('./components/AdminGuest/Messaging.vue'));
Vue.component('messageThread', require('./components/AdminGuest/MessageThread.vue'));
Vue.component('messageEntry', require('./components/AdminGuest/MessageEntry.vue'));

Vue.component('logs', require('./components/AdminGuest/Logs.vue'));

Vue.component('drag-resize', VueDraggableResizable);

const app = new Vue({
    el: '#app',
    store
});


