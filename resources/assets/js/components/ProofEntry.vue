<template>
    <div class="proof-entry">
        <imageEntry
                :key="i"
                v-for="(z,i) in images"
                :image="{z}"
                :initalVal = initalValue
                :linkVal = linkVal
        >
        </imageEntry>
    </div>
</template>

<script>
    import { store } from './store';
    export default {
        name: "proof-entry",
        props: {
            entry: Object
        },
        data() {
            return {
                images: [],
                initalValue: false,
                linkVal: null
            }
        },
        created() {
            let self = this;
            let date = moment(store.state.project.created_at);

            if(this.entry.m.id === store.state.entries[0].id && this.entry.m.admin) {
                self.images = JSON.parse(this.entry.m.files);
                self.initalValue = true;
                self.linkVal = 'http://localhost:8000/storage/projects/' + date.format('YYYY') + '/' + date.format('MMMM') + '/' + store.state.project.file_path + '/' + this.entry.m.path + '/images';
            }
        }
    }
</script>

<style scoped>

</style>