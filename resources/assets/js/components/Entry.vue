<template>
    <div
            v-bind:class="[{ isActive: isActive }, 'entry entry_'+numberKey]"
            @click="goToRevision(numberKey)"
            >
        {{entry.m.user.first_name}} {{entry.m.user.last_name}}
    </div>
</template>

<script>
    export default {
        name: "entry",
        props: {
            entry: Object,
            numberKey: Number
        },
        data () {
            return {
                isActive: false
            }

        },
        mounted() {
            let self = this;
            if(self.numberKey === 0) {
                self.isActive = true;
            }
        },
        methods: {
            goToRevision: function(val) {
                Vue.bus.emit('goToRevision', val);
            }

        }
    }
</script>

<style scoped>

</style>