<template>
    <div id="revisions">
        <div class="sidebarHeadings">
            <h3>Revisions</h3>
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
    import { store } from '../store';

    export default {
        props: {
            active: Boolean
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
        name: "revisions-admin"
    }
</script>

<style scoped>

</style>