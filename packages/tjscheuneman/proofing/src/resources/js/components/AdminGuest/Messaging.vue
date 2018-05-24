<template>
    <drag-resize
            :w="300"
            :h="500"
            :drag-handle="'.threadHead'"
            id="messageContainer"
    >
        <div id="messaging">
            <div class="threadHead">
                <div
                        class="closeItem left"
                        @click="goBack"
                >
                    <i class="fa fa-chevron-circle-left" aria-hidden="true"></i>
                </div>
                <div
                        class="closeItem"
                        @click="closeMessaging"
                >
                    <i class="fa fa-times" aria-hidden="true"></i>
                </div>
                <h3>Messaging</h3>
                <hr>
            </div>
            <template v-if="$store.state.activeMessages === null">
                <button
                        id="createThreadLabel"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                        class="messageAction btn btn-submission">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i> Create Thread
                </button>
                <div class="dropdown-menu" aria-labelledby="createThreadLabel">
                    <div class="px-3 py-3">
                        <div class="form-group">
                            <label for="threadName">Thread Name</label>
                            <input type="text" ref="threadName" class="form-control" placeholder="Thread Name...">
                        </div>
                        <div class="dropdown-divider"></div>
                        <button
                                id="addThread"
                                type="submit"
                                class="btn btn-primary"
                                @click="createThread"
                        >
                            Create
                        </button>
                    </div>
                </div>

                <hr>
                <div class="threads">

                    <template v-if="$store.state.messages.length < 1">
                        <p class="noMessages">No Threads</p>
                    </template>
                    <template v-else>
                        <messageThread
                                :key="i"
                                v-for="(m,i) in $store.state.messages"
                                :message="{m}"
                                :messageID="i"
                        >

                        </messageThread>
                    </template>

                </div>
            </template>

            <template v-else-if="$store.state.activeMessages.length < 1">
                <button
                        id="createThreadMessage"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                        class="messageAction btn btn-submission">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i> Add Message
                </button>

                <div class="dropdown-menu" aria-labelledby="createThreadMessage">
                    <div class="px-3 py-3">
                        <div class="form-group">
                            <label for="message">Thread Name</label>
                            <textarea id="message" ref="message" class="form-control" cols="22" rows="5">

                            </textarea>
                        </div>
                        <div class="dropdown-divider"></div>
                        <button
                                id="addMessage"
                                type="submit"
                                class="btn btn-primary"
                                @click="createMessage"
                        >
                            Create
                        </button>
                    </div>
                </div>
                
                <hr>
                <div class="threads">
                    <p class="noMessages">No Messages</p>
                </div>
            </template>

            <template v-else>
                <button
                        id="createThreadMessageTwo"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                        class="messageAction btn btn-submission">
                    <i class="fa fa-plus-circle" aria-hidden="true"></i> Add Message
                </button>

                <div class="dropdown-menu" aria-labelledby="createThreadMessageTwo">
                    <div class="px-3 py-3">
                        <div class="form-group">
                            <label for="message2">Thread Name</label>
                            <textarea id="message2" ref="message" class="form-control" cols="22" rows="5">

                            </textarea>
                        </div>
                        <div class="dropdown-divider"></div>
                        <button
                                id="addMessage2"
                                type="submit"
                                class="btn btn-primary"
                                @click="createMessage"
                        >
                            Create
                        </button>
                    </div>
                </div>

                <hr>
                <div class="threads">
                    <messageEntry
                            :key="i"
                            v-for="(m,i) in $store.state.activeMessages"
                            :message="{m}"
                            :messageID="i"
                    >

                    </messageEntry>
                </div>
            </template>
                <br class="clear" />

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
            createThread: function() {
                let self = this;
                let threadName = this.$refs.threadName.value;

                axios.post('/message/api/thread', {
                    thread_name: threadName,
                    project_id: store.state.project.file_path
                })
                    .then(function (response) {
                        let returnData = response.data;
                        if(returnData.status === "Success") {
                            self.reloadThreads();
                        }
                        else {
                            alert(returnData.message);
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });

            },
            createMessage: function() {
                let self = this;
                let message = this.$refs.message.value;
                axios.post('/message/api', {
                    thread: store.state.activeThread.id,
                    message: message
                })
                    .then(function (response) {
                        let returnData = response.data;
                        if(returnData.status === "Success") {
                            self.reloadMessages();
                        }
                        else {
                            alert(returnData.message);
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            reloadThreads: function() {
                let self = this;
                store.state.activeMessages = null;
                store.state.activeThread = null;

                self.reloadAllThreads();
            },
            reloadAllThreads: function() {
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
            reloadMessages: function() {
                axios.get('/message/api/' + store.state.activeThread.id, {
                }).then(function (response) {
                    let returnData = response.data;
                    if(returnData.status === "Success") {
                        let threadData = returnData.message;
                        store.commit('changeActiveMessages', threadData);
                    }
                    else {
                        alert(returnData.message);
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            },
            closeMessaging: function() {
                $('#messageContainer').fadeOut(500);
            },
            goBack: function() {
                this.reloadThreads();
            }
        }
    }
</script>

<style scoped>

</style>