<template>
    <div
            v-bind:class="'message_thread thread_' + messageID"
            @click="populateMessages(message.m.id)"
    >
        {{message.m.subject}} ({{message.m.msg_cnt_count}})
    </div>
</template>

<script>
    import { store } from '../store';
    export default {
        name: "messaging",
        props: {
            messageID: Number,
            message: Object
        },
        data() {
            return {
                isActive:false,
            }
        },
        mounted() {

        },
        created() {
            let self = this;
        },
        methods: {
            populateMessages: function(elm) {
                let self = this;
                axios.get('/admin/message/' + elm, {
                }).then(function (response) {
                    let returnData = response.data;
                    if(returnData.status === "Success") {
                        let threadData = returnData.message;
                        store.commit('changeActiveMessages', threadData);
                        store.commit('setActiveThread', self.message.m);
                    }
                    else {
                        alert(returnData.message);
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            }
        }
    }
</script>

<style scoped>

</style>