<template>
    <div id="pagesLeft">
        <div class="pagesLeftContent">
            <div
                    class="closeElm"
                    :clickable="true"
                    @click="closeThumbBar()"
            >
                <i class="fa fa-times" aria-hidden="true"></i>
            </div>
            <h3>Pages</h3>
            <eachEntry
                    :key="i"
                    v-for="(m,i) in $store.state.entries"
                    :clickable="true"
                    :entry="{m}"
                    :proofEntry="i"
            >

            </eachEntry>
        </div>

    </div>
</template>

<script>
    import { store } from './store';

    export default {
        data () {
            return {
                images: [],
                linkVal: null,
            }

        },
        mounted() {

        },
        methods: {
            goToEntry: function(m, i) {
                alert('hi');
            },
            closeThumbBar: function() {
                if($('#pagesLeft').hasClass('active')) {
                    $('#pagesLeft .pagesLeftContent').fadeOut(300, function() {
                        $('#pagesLeft').animate({
                            width: '0px'
                        },300, function() {
                            $(this).removeClass('active');
                        });
                    });
                }
            }
        },
        mounted() {
            let self = this;
        },
        name: "all-pages"
    }
</script>

<style scoped>

</style>