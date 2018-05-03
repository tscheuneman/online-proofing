<template>
    <div
            v-bind:class="['commentEntry', 'comm_'+keyEntry]"
            :style="{
            height: itemHeight + 'px',
            width: itemWidth + 'px',
            top: positionTop + 'px',
            left: positionLeft + 'px'
            }"
    >
        <div class="commentInfo">
            {{itemEntry.x.comment}}
        </div>
    </div>
</template>

<script>
    import { store } from './store';

    export default {
        props: {
            itemEntry: Object,
            keyEntry: Number,
            image: Object,
            currentWidth: Number

        },
        data () {
            return {
                linkVal: null,
                positionTop: null,
                positionLeft: null,
                itemWidth: null,
                itemHeight: null
            }

        },
        mounted() {
            let self = this;
            let ratio = self.image.z.width / self.currentWidth;

            self.positionTop = self.itemEntry.x.startY / ratio;
            self.positionLeft = self.itemEntry.x.startX / ratio;

            self.itemWidth = self.itemEntry.x.width / ratio;
            self.itemHeight = self.itemEntry.x.height / ratio;

        },
        methods: {

        },
        name: "comment-image"
    }
</script>

<style scoped>

</style>