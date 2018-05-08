<template>
    <div id="projectNavigation">
        <ul class="navContainer">
            <li v-tooltip="'See all pages'" v-on:click="showSidePictureNavigation"><i class="fa fa-picture-o" aria-hidden="true"></i></li>
            <li v-tooltip="'Go Home'" v-on:click="goHome"><i class="fa fa-home" aria-hidden="true"></i></li>
            <li v-if="!$store.state.needResponse && !$store.state.project.completed" v-tooltip="'Create New Revision'" v-on:click="newRevision" id="revise"><i class="fa fa-plus" aria-hidden="true"></i></li>
        </ul>

        <ul class="navContainerRight">
            <li v-tooltip="'View Logs'" v-on:click="viewLogs"><i class="fa fa-info-circle" aria-hidden="true"></i></li>
            <li v-tooltip="'View Messaging'" v-on:click="viewMessaging"><i class="fa fa-comments" aria-hidden="true"></i></li>
        </ul>
    </div>
</template>

<script>
    import { store } from '../store';

    export default {
        data () {
            return {
            }

        },
        mounted() {
            Vue.bus.on('goToEntry', function(elm) {
                if(elm === store.state.currentElm) {
                    return false;
                }

                $('.revision-comment.isActive .isActive').fadeOut(250, function() {
                    $(this).removeClass('isActive');
                    $('.revision-comment.isActive .usercomm_'+elm).fadeIn(250, function() {
                        $(this).addClass('isActive');

                    });
                });

            });

            Vue.bus.on('goToRevision', function(elm) {
                if(elm === store.state.currentProof) {
                    return false;
                }

                $('.revision-comment').removeClass('isActive');
                $('.revcom_'+elm).addClass('isActive');

            });
        },
        methods: {
            goHome() {
                location.assign("/");
            },
            newRevision() {
                location.assign("/admin/project/add/" + store.state.project.file_path);
            },
            showSidePictureNavigation() {
                if(!$('#pagesLeft').hasClass('active')) {
                    $('#pagesLeft').animate({
                        width: '12%'
                    },300, function() {
                        $('.pagesLeftContent', this).fadeIn(300);
                        $(this).addClass('active');
                    });
                }

            },
            viewMessaging() {
                $('#messageContainer').fadeIn(500);
            },
            viewLogs() {
                $('#logsContainer').fadeIn(500);
            },
            hideAll(_callback) {
                $('#revisions, #comments, #messaging').fadeOut(300, function() {
                    _callback();
                });
            }
        },
        name: "project-navigation-admin"
    }
</script>

<style scoped>

</style>