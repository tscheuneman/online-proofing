<template>
    <div id="proof">
        <proofEntryGuest
                :key="i"
                v-for="(m,i) in $store.state.entries"
                :clickable="true"
                :entry="{m}"
                :proofEntry="i"
        />
    </div>
</template>

<script>
    import { store } from '../store';
    export default {
        props: {
            project: String,
            user: String,
            url: String,
        },
        data () {
            return {

            }
        },
        mounted() {
            store.state.userID = this.user;
            store.state.currentURL = this.url;
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
                    self.loadMessages();
                })
                .catch(function (error) {
                    alert("Failed to load project info");
                    console.log(error);
                });

            axios.get('/logs/api/'+project_id)
                .then(function (response) {
                    store.commit('addLogs', response.data);
                })
                .catch(function (error) {
                    alert("Failed to load logs");
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

                $('.userCommentPageRevision').removeClass('isActive');
                $('.commentEntry ').removeClass('active');
                $('#commentOverlay').remove();

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
                    });
                });

            });
        },
        methods: {
            loadInEntries: function() {
                Vue.bus.emit('loadEntries');
            },
            loadMessages: function() {
                axios.get('/message/api/thread/' + store.state.project.file_path, {
                })
                    .then(function (response) {
                        let returnData = response.data;
                        if(returnData.status === "Success") {
                            let threadData = returnData.message;
                            store.commit('popMessages', threadData);
                        }
                        else {
                            alert(returnData.message);
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
        },
        name: "proof-admin"
    }
</script>

<style scoped>

</style>