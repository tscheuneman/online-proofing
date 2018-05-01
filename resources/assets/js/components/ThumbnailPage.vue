<template>
            <img
                  v-bind:class="[{ isActive: isActive }, 'thumbnailImage thumb_'+keyValue]"
                 :src="link"
                  :clickable="true"
                  @click="goToProject(keyValue)"
            />
</template>

<script>
    import { store } from './store';
    export default {
        name: "image-entry",
        props: {
            keyValue: Number,
            image: Object,
            linkVal: String
        },
        data() {
            return {
                elementWidth: null,
                elementHeight: null,
                link: null,
                showModal:false,
                isActive: false
            }
        },
        mounted() {
            let self = this;
            if(self.keyValue === store.state.currentElm) {
                self.isActive = true;
            }
            this.link = this.linkVal + '/' + this.image.z.file;
        },
        methods: {
            goToProject: function(val) {
                Vue.bus.emit('goToEntry', val);
            }
        }
    }
</script>
