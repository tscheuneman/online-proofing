<template>
    <div v-bind:class="[{ isActive: isActive }, {isAdmin: adminLast}, 'proof-entry proof_' + proofEntry]">

        <template v-if="isFileUpload">
            <div class="fileUpload">
                <h5 class="title">{{entry.m.user.first_name + ' ' + entry.m.user.last_name}} uploaded {{numberOfFiles}} file(s)</h5>
                <small>Upload On {{dateString}}</small>
                <br />
                <br />
                <button
                        :key="i"
                        v-for="(z,i) in fileData"
                        v-bind:class="['getLink btn btn-primary', 'btn_'+i]"
                        @click="GenerateLink(z.path, $store.state.project.file_path)"
                >
                    {{z.name}}
                </button>
            </div>
        </template>

        <template v-else-if="entry.m === 'approved'">
            <div class="fileUpload">
                <h5 class="title">Approved!</h5>
                <small>Approved by {{$store.state.project.approval.user.first_name + ' ' + $store.state.project.approval.user.last_name}} on {{dateString}}</small>
                <br />
                <br />
                <button
                        v-bind:class="['getLink btn btn-primary']"
                        @click="GenerateLink(finalFilePath, $store.state.project.file_path)"
                >
                    Download Final PDF Proof
                </button>
            </div>
        </template>

        <template v-else>
            <imageEntryGuest
                    :key="i"
                    v-for="(z,i) in images"
                    :image="{z}"
                    :initalVal = initalValue
                    :linkVal = linkVal
                    :keyValue="i"
                    :entry="entry"
                    :proofVal="proofEntry"
            >
            </imageEntryGuest>
        </template>
    </div>
</template>

<script>
    import { store } from '../store';
    export default {
        name: "proof-entry-admin",
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
                dateString: null,
                fileData: null,
                finalFilePath: null
            }
        },
        mounted() {
            let self = this;
            if(self.proofEntry === store.state.currentProof) {
                self.isActive = true;
            }

            if(self.entry.m === 'approved') {
                self.finalFilePath = store.state.project.entries[1].pdf_path;
            }

        },
        methods: {
            GenerateLink: function(val, proj) {
                axios.post('/proof/api/link', {
                    val: val,
                    project_id: proj
                })
                    .then(function (response) {
                        let returnData = response.data;
                        if(returnData.status === "Success") {
                            location.assign(returnData.message);
                        }
                        else {
                            alert(returnData.message);
                            $('#loader').fadeOut(500);
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
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

                self.isFileUpload = true;
                self.fileData = JSON.parse(this.entry.m.files);

                self.numberOfFiles = JSON.parse(self.entry.m.files).length;
            }
            else {
                if(this.entry.m !== "approved") {
                    self.images = JSON.parse(this.entry.m.files);
                    self.initalValue = true;
                    self.linkVal = store.state.currentURL + '/storage/projects/' + date.format('YYYY') + '/' + date.format('MMMM') + '/' + store.state.project.file_path + '/' + this.entry.m.path + '/images';
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