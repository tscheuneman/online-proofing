<template>
    <div
            v-bind:class="[{ isActive: isActive }, { isAdmin: isAdmin }, { approval: approval }, 'entry entry_'+numberKey]"
            @click="goToRevision(numberKey)"
            >

        <template v-if="entry.m !== 'approved'">
            <template v-if="hasImage">
                <div class="userEntry"
                     :style="{background: ' url(/storage/'+entry.m.user.picture+') center center no-repeat'}"
                >

                </div>
            </template>
            <template v-else>
                <div class="userEntry">
                    {{entry.m.user.first_name.charAt(0) + entry.m.user.last_name.charAt(0)}}
                </div>
            </template>
            {{entry.m.user.first_name}} {{entry.m.user.last_name}}
            <span class="dateTime">{{dateString}}</span>
        </template>

        <template v-else>
            <h4 style="text-align:center;">Approved</h4>
            <span class="dateTime">{{dateString}}</span>
        </template>

    </div>
</template>

<script>
    import { store } from './store';
    export default {
        name: "entry",
        props: {
            entry: Object,
            numberKey: Number
        },
        data () {
            return {
                isActive: false,
                hasImage: false,
                dateString: '',
                isAdmin: false,
                approval:false
            }

        },
        mounted() {
            let self = this;
            if(self.entry.m !== 'approved') {
                if(self.numberKey === 0) {
                    self.isActive = true;
                }
                if(self.entry.m.user.picture !== null) {
                    self.hasImage = true;
                }
                if(self.entry.m.admin) {
                    self.isAdmin = true;
                }
                let theData = moment(self.entry.m.created_at);

                self.dateString = theData.format('MMMM Do YYYY, h:mm:ss a');
            }
            else {
                let theData = moment(store.state.project.approval.created_at);

                self.dateString = theData.format('MMMM Do YYYY, h:mm:ss a');
                self.approval = true;
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