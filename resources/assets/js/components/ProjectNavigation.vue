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
            <li v-tooltip="'Go Home'" id="lastLeft" v-on:click="goHome"><i class="fa fa-home" aria-hidden="true"></i></li>
            <li v-tooltip="'Choose new color'" v-if="$store.state.needResponse && !$store.state.project.completed" id="colorPick" v-on:click="showColors"><i class="fa fa-eyedropper" aria-hidden="true"></i></li>
            <li v-tooltip="'Move Element'" v-if="$store.state.needResponse && !$store.state.project.completed" id="moveELm" v-on:click="moveElm"><i class="fa fa-arrows" aria-hidden="true"></i></li>
            <li v-tooltip="'Zoom Out Element'" v-if="$store.state.needResponse && !$store.state.project.completed" v-on:click="zoomElmMinus"><i class="fa fa-search-minus" aria-hidden="true"></i></li>
            <li v-tooltip="'Zoom In Element'" v-if="$store.state.needResponse && !$store.state.project.completed" v-on:click="zoomElmPlus"><i class="fa fa-search-plus" aria-hidden="true"></i></li>
            <li v-tooltip="'Center Element'" v-if="$store.state.needResponse && !$store.state.project.completed" v-on:click="centerElement"><i class="fa fa-bullseye" aria-hidden="true"></i></li>

        </ul>
        <color-picker v-model="colors" @ok="onOk" />

        <ul class="navContainerRight" v-if="$store.state.needResponse && !$store.state.project.completed">
            <li v-tooltip="'Submit your revisions'" v-on:click="submitRevisions"><i class="fa fa-paper-plane" aria-hidden="true"></i></li>
            <li v-if="$store.state.revisionEntries.length === 0" v-tooltip="'Upload new Files'" v-on:click="uploadNewFiles"><i class="fa fa-upload" aria-hidden="true"></i></li>
            <li v-if="$store.state.revisionEntries.length === 0" id="approve" v-tooltip="'Approve Project'" v-on:click="approveProject"><i class="fa fa-thumbs-up" aria-hidden="true"></i></li>
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
            goHome() {
                location.assign("/");
            },
            moveElm() {
                if(store.state.moveElement) {
                    $('#moveELm').removeClass('clicked');
                    store.state.moveElement = false;
                }
                else {
                    $('#moveELm').addClass('clicked');
                    store.state.moveElement = true;
                }

            },
            zoomElmPlus() {
                let parentElm = $('.proof-entry.isActive .elem.isActive');
                let maxHeight = $('canvas', parentElm).attr('height');
                let maxWidth = $('canvas', parentElm).attr('width');
                let elm = $('canvas', parentElm);
                let newWidth = elm.width() * 1.25;
                let newHeight = elm.height() * 1.25;

                let diffX = elm.width() * .25;
                    diffX = (parseInt(elm.css('left'), 10) - (diffX / 2));

                let diffY = elm.height() * .25;
                    diffY = (parseInt(elm.css('top'), 10) - (diffY / 2));

                if(newWidth > maxWidth || newHeight > maxHeight) {
                    newWidth = maxWidth;
                    newHeight = maxHeight;
                    diffX = parseInt(elm.css('left'), 10) - ((newWidth - elm.width()) / 2);
                    diffY = parseInt(elm.css('top'), 10) - ((newHeight - elm.height()) / 2);
                }

                elm.width(newWidth);
                elm.height(newHeight);

                let styles = {
                    top : diffY + 'px',
                    left: diffX + 'px'
                };
                elm.css(styles)


            },
            zoomElmMinus() {
                let parentElm = $('.proof-entry.isActive .elem.isActive');
                let minHeight = $('.image', parentElm).height();
                let minWidth = $('.image', parentElm).width();
                let elm = $('canvas', parentElm);
                let newWidth = elm.width() * .75;
                let newHeight = elm.height() * .75;
                let setNormal = false;



                let diffX = elm.width() * .25;
                    diffX = (parseInt(elm.css('left'), 10) + (diffX / 2));

                let diffY = elm.height() * .25;
                    diffY = (parseInt(elm.css('top'), 10) + (diffY / 2));

                if(newWidth < minWidth || newHeight < minHeight) {
                    newWidth = minWidth;
                    newHeight = minHeight;
                    diffX = parseInt(elm.css('left'), 10);
                    diffY = parseInt(elm.css('top'), 10);
                    setNormal = true;
                }

                elm.width(newWidth);
                elm.height(newHeight);

                let styles = {
                    top : diffY + 'px',
                    left: diffX + 'px'
                };
                elm.css(styles);

                if(setNormal) {
                    this.centerElement();
                }
            },
            centerElement() {
                let parentElm = $('.proof-entry.isActive .elem.isActive');
                let elm = $('canvas', parentElm);

                let elmWidth = elm.width();
                let elmHeight = elm.height();
                let styles = {
                    top : 'calc(50% - '+elmHeight / 2+'px)',
                    left: 'calc(50% - '+elmWidth / 2+'px)'
                };
                elm.css(styles);
            },
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