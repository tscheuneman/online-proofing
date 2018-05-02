<template>
    <div
            v-bind:class="['commentEntry', 'comm_'+keyEntry]"
            :style="{
            height: itemEntry.x.height + 'px',
            width: itemEntry.x.width + 'px',
            top: itemEntry.x.startY + 'px',
            left: itemEntry.x.startX + 'px',
            background: '#fff url('+link+') no-repeat',
            backgroundSize: 'initial',
            backgroundPositionX: '-' + itemEntry.x.startX + 'px',
            backgroundPositionY: '-' + itemEntry.x.startY + 'px',
            }"
    >
        <div class="commentInfo">
            {{itemEntry.x.comment}}
        </div>
    </div>
</template>

<script>
    import { store } from '../store';

    export default {
        props: {
            itemEntry: Object,
            keyEntry: Number,
            link: String
        },
        data () {
            return {
                linkVal: null,
            }

        },
        mounted() {
            let self = this;
        },
        methods: {

        },
        name: "comment-image"
    }
</script>

<style scoped>

</style>