<template>
    <div id="sidebar"
         v-bind:class="[{ dontShow: active }]">
        <div class="sidebarHeadings">
            <h2>{{$store.state.project.project_name}}</h2>
            <hr>
        </div>
        <div class="sidebarContent">
            <entry
                    :key="i"
                    v-for="(m,i) in $store.state.entries"
                    :clickable="true"
                    @click="goToEntry(m,i)"
                    :entry="{m}"
                    :numberKey="i"
            />
        </div>

    </div>
</template>

<script>
    import { mapMutations } from 'vuex';
    import { store } from './store';

    export default {
        props: {
            active: Boolean
        },
        data () {

        },
        mounted() {

        },
        methods: {
            goToEntry: function(m, i) {
                alert('hi');
            }
        },
        created() {
            let self = this;
        },
        name: "actions-component"
    }
</script>

<style scoped>

</style>