<template>
    <div v-bind:class="[{ isActive: isActive }, {isAdmin: adminLast}, 'proof-entry proof_' + proofEntry]">

        <template v-if="isFileUpload">
            <div class="fileUpload">
                <h5 class="title">{{entry.m.user.first_name + ' ' + entry.m.user.last_name}} uploaded {{numberOfFiles}} file(s)</h5>
                <small>Upload On {{dateString}}</small>
            </div>
        </template>

        <template v-else-if="entry.m === 'approved'">
            <div class="fileUpload">
                <h5 class="title">Approved!</h5>
                <small>Approved by {{$store.state.project.approval.user.first_name + ' ' + $store.state.project.approval.user.last_name}} on {{dateString}}</small>
            </div>
        </template>

        <template v-else>
            <imageEntry
                    :key="i"
                    v-for="(z,i) in images"
                    :image="{z}"
                    :initalVal = initalValue
                    :linkVal = linkVal
                    :keyValue="i"
                    :entry="entry"
                    :proofVal="proofEntry"
            >
            </imageEntry>
        </template>
    </div>
</template>

<script>
    import { store } from './store';
    export default {
        name: "proof-entry",
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
                adminLast: false,
                isFileUpload: false,
                numberOfFiles: 0,
                dateString: null
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

            if(this.entry.m.id === store.state.entries[0].id && this.entry.m.admin) {
                self.adminLast = true;
                store.state.needResponse = true;
            }
            if(self.entry.m.path === null) {
                let theData = moment(self.entry.m.created_at);
                self.dateString = theData.format('MMMM Do YYYY, h:mm:ss a');
                self.numberOfFiles = JSON.parse(self.entry.m.files).length;
                self.isFileUpload = true;
            }
            else {
                if(this.entry.m !== "approved") {
                    self.images = JSON.parse(this.entry.m.files);
                    self.initalValue = true;
                    self.linkVal = 'http://localhost:8000/storage/projects/' + date.format('YYYY') + '/' + date.format('MMMM') + '/' + store.state.project.file_path + '/' + this.entry.m.path + '/images';
                }

            }

            if(this.entry.m === "approved") {
                let theData = moment(store.state.project.approval.created_at);
                self.dateString = theData.format('MMMM Do YYYY, h:mm:ss a');

            }

        }
    }
</script>

<style scoped>

</style>