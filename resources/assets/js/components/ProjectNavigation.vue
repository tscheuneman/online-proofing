<template>
    <div id="projectNavigation">
        <template v-if="$store.state.needResponse">
            <div id="customerFiles">
                <div id="closeFiles"><i class="fa fa-times"></i></div>
                <h4>
                    Upload Files
                </h4>
                <hr>
                <form id="customerFileEntry" enctype="multipart/form-data" @submit.prevent="submitForm">
                    <input type="hidden" name="project_id" :value="$store.state.project.id">
                    <input class="form-control" type="file" id="files" name="files[]" multiple required />
                    <br />
                    <textarea name="comments" class="form-control" id="" cols="30" rows="10"></textarea>
                    <br>
                    <button id="submitFileUpload" class="btn btn-secondary"><i class="fa fa-floppy-o" aria-hidden="true"></i> Submit</button>
                </form>

            </div>
        </template>

        <ul class="navContainer">
            <li v-tooltip="'See all pages'" v-on:click="showSidePictureNavigation"><i class="fa fa-picture-o" aria-hidden="true"></i></li>
            <li v-tooltip="'Go Home'"><i class="fa fa-home" aria-hidden="true"></i></li>
            <li v-tooltip="'Choose new color'" v-if="$store.state.needResponse" id="colorPick" v-on:click="showColors"><i class="fa fa-eyedropper" aria-hidden="true"></i></li>
        </ul>
        <color-picker v-model="colors" @ok="onOk" />

        <ul class="navContainerRight" v-if="$store.state.needResponse">
            <li v-tooltip="'Submit your revisions'" v-on:click="submitRevisions"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></li>
            <li v-tooltip="'Upload new Files'" v-on:click="uploadNewFiles"><i class="fa fa-upload" aria-hidden="true"></i></li>
            <li id="approve" v-tooltip="'Approve Project'" v-on:click="approveProject"><i class="fa fa-thumbs-up" aria-hidden="true"></i></li>
        </ul>
    </div>
</template>

<script>
    import { store } from './store';

    export default {
        data () {
            return {
                colors: Array,
            }

        },
        mounted() {
            this.colors = {
                hex: '#194d33',
                hsl: { h: 150, s: 0.5, l: 0.2, a: 1 },
                hsv: { h: 150, s: 0.66, v: 0.30, a: 1 },
                rgba: { r: 25, g: 77, b: 51, a: 1 },
                a: 1
            }
        },
        methods: {
            onOk () {
               $('#colorPick').css('backgroundColor', this.colors.hex);
               this.showColors();
                store.commit('changeColor', this.colors.hex);
            },
            showColors() {
                $('.vc-photoshop').fadeToggle(500);
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
            uploadNewFiles() {
                $('#customerFiles').fadeIn(500);
            },
            submitRevisions() {
                if(store.state.revisionEntries.length <= 0) {
                    alert('No revisions');
                    return false;
                }
                let returnData = [];
                let comments  = store.state.revisionEntries;
                for(let y = 0; y < store.state.canvasItems.length; y++) {
                    let thisElm = {};
                    let commentElm = [];
                    let currentElm = $('.elem_'+y).data('val');
                    thisElm['data'] = document.getElementById('canvas_'+y).toDataURL();
                    comments.forEach(function(elm) {
                        if(elm.page === currentElm) {
                            commentElm.push(elm);
                        }
                    });
                    thisElm['comments'] = commentElm;
                    returnData.push(thisElm);
                }

                $('#loader').fadeIn(500, function() {
                    axios.post('/project', {
                        dataArray: JSON.stringify(returnData),
                        projectID: store.state.project.id
                    })
                        .then(function (response) {
                            let returnData = response.data;
                            if (returnData.status === "Success") {
                                location.assign("/");
                            }
                            else {
                                alert(returnData.message);
                                $('#loader').fadeOut(500);
                            }
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                });
            },
            submitForm() {
                let customerForm = document.getElementById('customerFileEntry');
                let formData = new FormData(customerForm);
                $('#loader').fadeIn(500, function() {
                    axios.post('/user/files', formData)
                        .then(function (response) {
                            location.assign("/");
                            $('#loader').fadeOut(500);
                        })
                        .catch(function (error) {
                            location.assign("/");
                            $('#loader').fadeOut(500);
                        });

                });
            },
            approveProject() {
                $('#loader').fadeIn(500, function() {
                    axios.post('/project/approve', {
                        projectID: store.state.project.id
                    })
                        .then(function (response) {
                            console.log(response);
                            let returnData = response.data;
                            if(returnData.status === "Success") {
                                alert(returnData.message);
                                location.assign("/");
                            }
                            else {
                                alert(returnData.message);
                                $('#loader').fadeOut(500);
                            }
                        })
                        .catch(function (error) {
                            console.log(error);
                        });
                });
            }
        },
        name: "project-navigation"
    }
</script>

<style scoped>


</style>