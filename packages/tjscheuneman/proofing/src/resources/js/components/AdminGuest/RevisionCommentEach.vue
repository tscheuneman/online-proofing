<template>
    <div v-bind:class="[{ isActive: isActive }, {isAdmin: adminLast}, 'revision-comment revcom_' + numberKey]">
        <template v-if="adminLast">
            <div v-html="notes" class="adminUploadComment">
                {{notes}}
            </div>
        </template>
        <template v-else-if="isFileUpload">
            <div class="adminUploadComment">
                {{notes}}
            </div>
        </template>
        <template v-else-if="entry.m === 'approved'">
            <div class="adminUploadComment">
                Approved
            </div>
        </template>

        <template v-else>
            <pageCommentEntry
                    :key="i"
                    v-for="(z,i) in numPages"
                    :entryVal="entryVal[i]"
                    :keyValue="i"
            >
            </pageCommentEntry>
        </template>
    </div>
</template>

<script>
    import { store } from '../store';
    export default {
        name: "comment-each",
        props: {
            entry: Object,
            numberKey: Number
        },
        data() {
            return {
                isActive:false,
                adminLast: false,
                isFileUpload: false,
                notes: '',
                entryVal: null,
                numPages: 0,
                userData:false
            }
        },
        mounted() {
            let self = this;
            if(self.numberKey === store.state.currentProof) {
                self.isActive = true;
            }

        },
        created() {
            let self = this;
            if(self.entry.m !== 'approved') {
                if(this.entry.m.admin) {
                    self.adminLast = true;

                    self.notes = markdown.toHTML(this.entry.m.notes);

                }
                if(self.entry.m.path === null) {
                    self.isFileUpload = true;
                    self.notes = this.entry.m.notes;
                }
                if(self.entry.m.path === null) {
                    self.isFileUpload = true;
                    self.notes = this.entry.m.notes;
                }
                if(this.entry.m.user_notes !== null) {
                    self.entryVal = JSON.parse(this.entry.m.user_notes);
                    self.numPages = self.entryVal.length;
                }
            }


        }
    }
</script>

<style scoped>

</style>