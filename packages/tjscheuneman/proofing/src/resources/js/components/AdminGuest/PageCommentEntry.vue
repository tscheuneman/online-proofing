<template>
    <div
            v-bind:class="[{ isActive: isActive }, 'userCommpentPage usercomm_' + keyValue]"
            v-bind:data-val="keyValue"
    >

        <revisionPageComment
                :key="i"
                v-for="(z,i) in entryVal"
                :entryVal="z"
                :keyValue="i"
                :pageVal="keyValue"
        >
        </revisionPageComment>

    </div>
</template>

<script>
    import { store } from '../store';
    export default {
        name: "user-comment-entry",
        props: {
            keyValue: Number,
            entryVal: Array,
        },
        data() {
            return {
                isActive: false
            }
        },
        mounted() {
            let self = this;
            if(self.keyValue === store.state.currentElm) {
                self.isActive = true;
            }
        },
        methods: {


        }
    }
</script>
