<template>
    <div
         v-bind:class="[{ isActive: isActive }, 'elem elem_' + keyValue]"
         v-bind:data-val="keyValue"
    >

        <template v-if="$store.state.needResponse && proofVal === 0 && !$store.state.project.completed">
            <template v-if="initalVal">
                <div class="image"
                     :style="{height: elementHeight + 'px', width: elementWidth + 'px'}"
                     ref="canvasElm"
                >
                </div>
            </template>
        </template>
        <template v-else>
            <template v-if="initalVal">
                <div v-bind:src="link"
                     class="imageElm"
                     alt=""
                     :style="{height: elementHeight + 'px', width: elementWidth + 'px' }"
                     >
                    <img v-bind:src="link"
                         alt=""
                         :style="{height: elementHeight + 'px', width: elementWidth + 'px' }"
                    />
                <template v-if="entryVal !== null">
                    <commentEntry
                            :key="i"
                            v-for="(x,i) in entryVal[keyValue]"
                            :clickable="true"
                            :itemEntry="{x}"
                            :keyEntry="i"
                    >
                    </commentEntry>
                </template>
                </div>
            </template>
        </template>
    </div>
</template>

<script>
    import { store } from './store';
    export default {
        name: "image-entry",
        props: {
            keyValue: Number,
            image: Object,
            initalVal: Boolean,
            linkVal: String,
            entry: Object,
            proofVal: Number
        },
        data() {
            return {
                elementWidth: null,
                elementHeight: null,
                link: null,
                showModal:false,
                isActive: false,
                entryVal: null
            }
        },
        mounted() {
            let self = this;
            if(!store.state.needResponse || self.proofVal > 0) {
                if(this.entry.m.user_notes !== null) {
                    self.entryVal = JSON.parse(this.entry.m.user_notes);
                }
            }
            if(self.keyValue === store.state.currentElm) {
                self.isActive = true;
            }
            if (self.initalVal) {

                let width = self.image.z.width;
                let height = self.image.z.height;
                let overAllHeight = $('#proof').height();
                let overAllWidth = $('#proof').width();

                self.elementWidth = width;
                self.elementHeight = height;

                let aspectRatioWidth = width / height;
                let aspectRatioHeight = height / width;

                if (width > height) {
                    if (width > overAllWidth) {
                        self.elementWidth = overAllWidth;
                        self.elementHeight = overAllWidth * aspectRatioHeight;

                        if (self.elementHeight > overAllHeight) {
                            self.elementWidth = overAllHeight * aspectRatioWidth;
                            self.elementHeight = overAllHeight;
                        }
                    }
                }
                else {
                    if (height > overAllHeight) {
                        self.elementWidth = overAllHeight * aspectRatioWidth;
                        self.elementHeight = overAllHeight;

                        if (self.elementWidth > overAllWidth) {
                            self.elementWidth = overAllWidth;
                            self.elementHeight = overAllWidth * aspectRatioHeight;
                        }
                    }
                }

            this.link = this.linkVal + '/' + this.image.z.file;
            if(store.state.needResponse) {
                self.populateCanvas();
            }

            }
        },
        methods: {
            populateCanvas: function () {
                let self = this;
                let globWidth = this.elementWidth;
                let globHeight = this.elementHeight;

                let canvasImage = new Image(globWidth, globHeight);
                canvasImage.onload = function () {
                    let canvas = self.createCanvas(self.$refs.canvasElm, globWidth, globHeight, false, self.keyValue);
                    canvas.getContext("2d").drawImage(canvasImage, 0, 0, globWidth, globHeight);
                    store.commit('addCanvas', canvas);
                    self.initDraw(canvas, self.$refs.canvasElm, globWidth, globHeight);

                };
                canvasImage.src = self.link;

            },
            createCanvas: function (containerEl, width, height, prepend, keyVal) {
                let containerElement = containerEl;
                let canvas = document.createElement('canvas');
                canvas.style['position'] = 'absolute';
                canvas.setAttribute('id', 'canvas_'+keyVal);
                canvas.setAttribute('height', height);
                canvas.setAttribute('width', width);
                if(prepend) {
                    canvas.style['z-index'] = 99;
                    containerElement.prepend(canvas);
                }
                else {
                    containerElement.append(canvas);
                }

                return canvas;
            },
            initDraw: function (canvases, elm, width, height) {

                // this flage is true when the user is dragging the mouse
                // these vars will hold the starting mouse position
                let startX;
                let startY;
                let scrollX;
                let scrollY;
                let offsetX;
                let offsetY;
                let finalWidth;
                let finalHeight;
                let canvas;
                let ctx;
                let readyToClick = true;

                let isDown = false;

                let self = this;

                function handleMouseDown(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (e.button === 2) {
                        return;
                    }

                    if(!readyToClick) {
                        return;
                    }
                    // get references to the canvas and context
                    canvas = self.createCanvas(elm, width, height, true);
                    ctx = canvas.getContext("2d");

                    // style the context
                    ctx.strokeStyle = store.state.color;
                    ctx.lineWidth = 3;

                    // calculate where the canvas is on the window
                    // (used to help calculate mouseX/mouseY)
                    let $canvas = $(canvas);
                    let canvasOffset = $canvas.offset();
                    offsetX = canvasOffset.left;
                    offsetY = canvasOffset.top;
                    scrollX = $canvas.scrollLeft();
                    scrollY = $canvas.scrollTop();

                    // save the starting x/y of the rectangle
                    startX = parseInt(e.clientX - offsetX);
                    startY = parseInt(e.clientY - offsetY);

                    // set a flag indicating the drag has begun

                    isDown = true;
                }

                function handleMouseUp(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (e.button === 2) {
                        return;
                    }

                    if(!readyToClick || !isDown) {
                        return;
                    }

                    // the drag is over, clear the dragging flag
                    isDown = false;
                    readyToClick = false;

                    let value = self.promptUserInput();

                }

                function handleMouseOut(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    // the drag is over, clear the dragging flag
                    isDown = false;
                }

                function handleMouseMove(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    // if we're not dragging, just return
                    if (!isDown) {
                        return;
                    }

                    // get the current mouse position
                    let mouseX = parseInt(e.clientX - offsetX);
                    let mouseY = parseInt(e.clientY - offsetY);

                    // Put your mousemove stuff here

                    // clear the canvas
                    ctx.clearRect(0, 0, canvas.width, canvas.height);

                    // calculate the rectangle width/height based
                    // on starting vs current mouse position
                    let width = mouseX - startX;
                    let height = mouseY - startY;


                    finalWidth = width;
                    finalHeight = height;

                    // draw a new rect from the start position
                    // to the current mouse position
                    ctx.strokeRect(startX, startY, width, height);
                }

                Vue.bus.on('deleteCanvasLayer', function() {
                    $(canvas).remove();
                    canvas = null;
                    ctx = null;
                    readyToClick = true;
                });

                Vue.bus.on('saveCanvasLayer', function(elm) {
                    if(typeof startX !== "undefined" && typeof startY !== "undefined" && typeof finalWidth !== "undefined" && typeof finalHeight !== "undefined") {
                        canvases.getContext("2d").drawImage(canvas, 0, 0);
                        $(canvas).remove();
                        let data = {
                            startX: startX,
                            startY: startY,
                            width: finalWidth,
                            height: finalHeight,
                            comment: elm,
                            page: store.state.currentElm,
                            color: store.state.color
                        };
                        store.commit('addRevision', data);
                        readyToClick = true;
                        canvas = null;
                        ctx = null;
                    }
                });

                $(elm).mousedown(function(e){handleMouseDown(e);});
                $(elm).mousemove(function(e){handleMouseMove(e);});
                $(elm).mouseup(function(e){handleMouseUp(e);});
                $(canvas).mouseout(function(e){handleMouseOut(e);});

            },
            promptUserInput: function() {

                let displayData = '<div id="mask">' +
                    '<div class="modal">' +
                    '<div class="modal-dialog" role="document">' +
                    '<div class="modal-content">' +
                    '<div class="modal-header">' +
                    '<h5 class="modal-title">Confirm</h5>' +
                    '</div>' +
                    '<div class="modal-body">' +
                    '<label for="mainModalText">Comments</label>' +
                    '<textarea id="mainModalText" class="form-control" cols="30" rows="10"></textarea>' +
                    '</div>' +
                    '<div class="modal-footer">' +
                    '<button id="saveButton" type="button" class="btn btn-outline-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i> Save</button>' +
                    '<button id="discardButton" type="button" class="btn btn-outline-danger"><i class="fa fa-trash" aria-hidden="true"></i> Discard</button>' +
                    '</div>' +
                    '</div></div></div></div>';

                $('body').append(displayData);
                $('#mainModalText').focus();

                $('#saveButton').on('click', function(e){
                    e.preventDefault();
                    e.stopPropagation();
                    let data = $('#mainModalText').val();
                    $('#mask').fadeOut(250, function() {
                        $('#mask').remove();
                        Vue.bus.emit('saveCanvasLayer', data);
                    });

                });

                $('#discardButton').on('click', function(e){
                    e.preventDefault();
                    e.stopPropagation();
                    $('#mask').fadeOut(250, function() {
                        $('#mask').remove();
                        Vue.bus.emit('deleteCanvasLayer');
                    });
                });
            },
        }
    }
</script>
