<template>
    <div v-bind:class="[{ isActive: isActive }, {isAdmin: adminLast}, 'navigation-entry nav_' + proofEntry]">
        <template v-if="!isFileUpload">
            <pageEntry
                    :key="i"
                    v-for="(z,i) in images"
                    :image="{z}"
                    :linkVal = linkVal
                    :keyValue="i"
                    >

            </pageEntry>
        </template>
    </div>
</template>

<script>
    import { store } from './store';
    export default {
        name: "each-navigation",
        props: {
            entry: Object,
            proofEntry: Number
        },
        data() {
            return {
                images: [],
                initalValue: false,
                linkVal: null,
                isActive:false,
                isFileUpload: false,
                adminLast: false
            }
        },
        mounted() {
            let self = this;
            if(self.proofEntry === store.state.currentProof) {
                self.isActive = true;
            }
        },
        created() {
            let self = this;
            let date = moment(store.state.project.created_at);

            if(self.entry.m.path === null ||self.entry.m === 'approved') {
                self.isFileUpload = true;
            }
            else {
                self.images = JSON.parse(this.entry.m.files);
                self.initalValue = true;
                self.linkVal = 'http://localhost:8000/storage/projects/' + date.format('YYYY') + '/' + date.format('MMMM') + '/' + store.state.project.file_path + '/' + this.entry.m.path + '/images';
            }


        }
    }
</script>

<style scoped>

</style>