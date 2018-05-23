<template>
    <div id="proof">
        <proofEntry
                :key="i"
                v-for="(m,i) in $store.state.entries"
                :clickable="true"
                :entry="{m}"
                :proofEntry="i"
        />
    </div>
</template>

<script>
    import { store } from './store';
    export default {
        props: {
            project: String,
            user: String
        },
        data () {
            return {

            }
        },
        mounted() {
            let project_id = this.project;
            let self = this;
            axios.get('/proof/api/info/project/'+project_id)
                .then(function (response) {
                    let returnData = response.data;
                    store.commit('addProject', returnData);
                    let entries = returnData.entries;
                        if(returnData.completed) {
                            entries.unshift('approved');
                        }
                    store.commit('addEntries', entries);
                    self.loadInEntries();
                })
                .catch(function (error) {
                    alert("Failed to initialize cart");
                    console.log(error);
                });
        },
        created() {
            Vue.bus.on('goToEntry', function(elm) {
                if(elm === store.state.currentElm) {
                    alert('That is the current page');
                    return false;
                }
                $('.proof-entry.isActive .isActive').fadeOut(250, function() {
                    $(this).removeClass('isActive');
                    $('.proof-entry.isActive .elem_'+elm).fadeIn(250, function() {
                        $(this).addClass('isActive');
                        store.commit('changeActiveElm', elm);
                    });
                    $('.thumbnailImage').removeClass('isActive');
                    $('.thumb_'+elm).addClass('isActive');

                });

            });

            Vue.bus.on('goToRevision', function(elm) {
                if(elm === store.state.currentProof) {
                    alert('That is the current revision');
                    return false;
                }

                $('.proof-entry.isActive').fadeOut(250, function() {
                    $(this).removeClass('isActive');
                    $('.proof-entry.proof_'+elm).fadeIn(250, function() {
                        $(this).addClass('isActive');
                        store.commit('changeCurrentProof', elm);

                        $('.entry').removeClass('isActive');
                        $('.entry_'+elm).addClass('isActive');

                        $('.navigation-entry').removeClass('isActive');
                        $('.nav_'+elm).addClass('isActive');

                        let active_elm = $('.proof-entry.isActive .elem.isActive').data('val');

                        store.commit('changeActiveElm', active_elm);

                        $('.navigation-entry.isActive .thumbnailImage').removeClass('isActive');
                        $('.navigation-entry.isActive .thumb_'+active_elm).addClass('isActive');

                        $('.userCommentPageRevision').removeClass('isActive');
                        $('.commentEntry ').removeClass('active');
                        $('#commentOverlay').remove();

                        $('.revision-comment').removeClass('isActive');
                        $('.revcom_'+elm).addClass('isActive');

                    });
                });

            });
        },
        methods: {
            loadInEntries: function() {
                Vue.bus.emit('loadEntries');
            },
            loadInFirstEntry: function() {

            },
        },
        name: "proof"
    }
</script>

<style scoped>

</style>