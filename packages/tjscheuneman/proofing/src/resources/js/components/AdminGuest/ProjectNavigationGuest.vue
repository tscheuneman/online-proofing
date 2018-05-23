<template>
    <div id="projectNavigation">
        <ul class="navContainer">
            <li v-tooltip="'See all pages'" v-on:click="showSidePictureNavigation"><i class="fa fa-picture-o" aria-hidden="true"></i></li>
            <li v-tooltip="'Go Home'" v-on:click="goHome"><i class="fa fa-home" aria-hidden="true"></i></li>
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

        },
        methods: {
            goHome() {
                location.assign("/");
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
        },
        name: "project-navigation"
    }
</script>

<style scoped>

</style>