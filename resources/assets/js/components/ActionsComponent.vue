<template>
    <div id="sidebar">
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
            />
        </div>

    </div>
</template>

<script>
    import { mapMutations } from 'vuex';
    import { store } from './store';

    export default {
        data () {
            return {

            }

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
            Vue.bus.on('loadEntries', function() {
                console.log(store.state);
            });
        },
        name: "actions-component"
    }
</script>

<style scoped>

</style>