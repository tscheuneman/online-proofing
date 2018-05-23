<template>
    <div
            v-bind:class="[{isActive:isActive}, 'userCommentPageRevision']"
            v-bind:data-classactivator="keyValue"
            :style="{
            borderColor: entryVal.color,
            }"
            @click="showCommentRevision"
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
        },
        methods: {
            showCommentRevision: function() {

                let self = this;

                if(self.isActive && $('.proof-entry.isActive .elem.isActive .comm_'+ self.keyValue).hasClass('active')) {
                    $('.userCommentPageRevision').removeClass('isActive');
                    $('.commentEntry ').removeClass('active');
                    $('#commentOverlay').remove();
                    self.isActive = false;
                }
                else {

                    $('.userCommentPageRevision').removeClass('isActive');
                    $('.commentEntry ').removeClass('active');
                    $('#commentOverlay').remove();

                    self.isActive = true;

                    let elm = $('.proof-entry.isActive .elem.isActive');
                    let width = $('.imageElm', elm).width();
                    let height = $('.imageElm', elm).height();

                    let overlay = '<div style="width: '+width+'px; height: '+height+'px;" id="commentOverlay"> </div>';

                    $('.proof-entry.isActive .elem.isActive .imageElm').prepend(overlay);

                    let currentElm = self.keyValue;

                    $('.proof-entry.isActive .elem.isActive .comm_'+currentElm).addClass('active');

                }

            }

        }
    }
</script>
