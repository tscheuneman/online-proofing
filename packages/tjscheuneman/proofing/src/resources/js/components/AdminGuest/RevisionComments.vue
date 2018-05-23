<template>
    <div id="comments"
         >
        <div class="sidebarHeadings">
            <h3>Comments</h3>
            <hr>
        </div>
        <div class="sidebarContent">
            <revisionsComments
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
        name: "comment-admin"
    }
</script>

<style scoped>

</style>