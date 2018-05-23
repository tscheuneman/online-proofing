<template>
    <drag-resize
            :w="350"
            :h="550"
            :drag-handle="'.threadHead'"
            id="logsContainer"
    >
        <div id="logs">
            <div class="threadHead">
                <div
                        class="closeItem"
                        @click="closeLogs"
                >
                    <i class="fa fa-times" aria-hidden="true"></i>
                </div>
                <h3>Logs</h3>
                <hr>
            </div>
            <ol id="logItems" reversed>
                <li v-for="log in $store.state.logs">
                    <strong>{{log.user.first_name}}</strong> {{ log.action }}
                </li>
            </ol>
        </div>
    </drag-resize>
</template>

<script>
    import { store } from '../store';
    export default {
        name: "messaging",
        props: {

        },
        data() {
            return {

            }
        },
        mounted() {

        },
        created() {
            let self = this;


        },
        methods: {
            closeLogs: function() {
                $('#logsContainer').fadeOut(500);
            },
        }
    }
</script>

<style scoped>

</style>