<template>
    <div
            v-bind:class="['userCommentPageRevision']"
            v-bind:data-classActivator="keyValue"
            v-on="mouseover=showCommentRevision"
            :style="{
            borderColor: entryVal.color,
            }"
    >
    {{entryVal.comment}}
    </div>
</template>

<script>
    import { store } from '../store';
    export default {
        name: "user-comment-entry",
        props: {
            keyValue: Number,
            entryVal: Object,
            pageVal: Number,
        },
        data() {
            return {
                isActive: false
            }
        },
        mounted() {
            let self = this;
            console.log(self.entryVal);
        },
        methods: {
            showCommentRevision: function() {
                alert('test');
            }

        }
    }
</script>
