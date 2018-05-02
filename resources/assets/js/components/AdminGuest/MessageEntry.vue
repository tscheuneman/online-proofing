<template>
    <div class="message_entry">
        <div
                v-bind:class="[{isYou: isYou}, 'message msg_' + messageID]"
        >
            {{message.m.message}}
        </div>
        <p
                v-bind:class="[{isYou: isYou}, 'messageUser']"
                >
            {{message.m.user.first_name}} - {{dateString}}
        </p>
        <br class="clear" />
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
                isYou: false,
                dateString: '',
            }
        },
        mounted() {
            let self = this;
            if(store.state.userID === self.message.m.user_id) {
                self.isYou = true;
            }

            console.log('test');
            console.log(self.message);

            let theData = moment(self.message.m.created_at);

            self.dateString = theData.format('MMMM Do, h:mm a');
        },
        created() {
            let self = this;

        },
        methods: {

        }
    }
</script>

<style scoped>

</style>