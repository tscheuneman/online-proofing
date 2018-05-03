import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

export const store = new Vuex.Store({
    state: {
        project: [],
        entries: [],
        canvasItems: [],
        revisionEntries: [],
        color: '#000000',
        currentElm: 0,
        currentProof: 0,
        needResponse: false,
        messages: [],
        activeThread: null,
        activeMessages: null,
        moveElement: false,
        userID: null
    },
    getters: {

    },
    mutations: {
        addProject (state, value) {
            state.project = value;
        },
        addEntries (state, value) {
            state.entries = value;
        },
        addCanvas (state, value) {
            state.canvasItems.push(value);
        },
        changeColor(state, value) {
            state.color = value;
        },
        changeActiveElm(state, value) {
            state.currentElm = value;
        },
        changeCurrentProof(state, value) {
            state.currentProof = value;
        },
        addRevision (state, value) {
            state.revisionEntries.push(value);
        },
        popMessages (state, value) {
            state.messages = value;
        },
        changeActiveMessages (state, value) {
            state.activeMessages = value;
        },
        setActiveThread (state, value) {
            state.activeThread = value;
        },
    }
});