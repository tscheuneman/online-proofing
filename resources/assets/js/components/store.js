import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

export const store = new Vuex.Store({
    state: {
        project: [],
        entries: [],
        canvasItems: [],
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
    }
});