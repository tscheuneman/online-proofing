<template>
    <div id="proof">
        <proofEntry
                :key="i"
                v-for="(m,i) in $store.state.entries"
                :clickable="true"
                :entry="{m}"
        />
    </div>
</template>

<script>
    import { store } from './store';
    export default {
        props: {
            project: String,
            user: String
        },
        data () {
            return {

            }

        },
        mounted() {
            let project_id = this.project;
            let self = this;
            axios.get('/info/project/'+project_id)
                .then(function (response) {
                    let returnData = response.data;
                    store.commit('addProject', returnData);
                    store.commit('addEntries', returnData.entries);
                    self.loadInEntries();
                })
                .catch(function (error) {
                    alert("Failed to initialize cart");
                    console.log(error);
                });
        },
        methods: {
            loadInEntries: function() {
                Vue.bus.emit('loadEntries');
            },
            loadInFirstEntry: function() {

            },
        },
        name: "proof"
    }
</script>

<style scoped>

</style>