<template>
    <div class="elem">
        <template v-if="initalVal">
            <div class="image"
                 :style="{height: elementHeight + 'px', width: elementWidth + 'px' }"
                 ref="canvasElm"
            >

            </div>
        </template>
    </div>
</template>

<script>
    import { store } from './store';
    export default {
        name: "image-entry",
        props: {
            image: Object,
            initalVal: Boolean,
            linkVal: String
        },
        data() {
            return {
                elementWidth: null,
                elementHeight: null,
                link: null,
            }
        },
        mounted() {
            let self = this;
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

                        if (height > overAllHeight) {
                            self.elementWidth = overAllHeight * aspectRatioWidth;
                            self.elementHeight = overAllHeight;
                        }
                    }
                }
            }
            else {
                if (height > overAllHeight) {
                    self.elementWidth = overAllHeight * aspectRatioWidth;
                    self.elementHeight = overAllHeight;

                    if (width > overAllWidth) {
                        self.elementWidth = overAllWidth;
                        self.elementHeight = overAllWidth * aspectRatioHeight;
                    }
                }
            }

            this.link = this.linkVal + '/' + this.image.z.file;
            self.populateCanvas();
        },
        methods: {
            populateCanvas: function () {
                let self = this;
                let globWidth = this.elementWidth;
                let globHeight = this.elementHeight;

                let canvasImage = new Image(globWidth, globHeight);
                canvasImage.onload = function () {
                    console.log(globWidth + ' | ' + globHeight);
                    let canvas = self.createCanvas(self.$refs.canvasElm, globWidth, globHeight, false);
                    canvas.getContext("2d").drawImage(canvasImage, 0, 0, globWidth, globHeight);
                    store.commit('addCanvas', canvas.getContext("2d"));
                    self.initDraw(canvas, self.$refs.canvasElm, globWidth, globHeight);

                };
                canvasImage.src = self.link;

            },
            createCanvas: function (containerEl, width, height, prepend) {
                let containerElement = containerEl;
                let canvas = document.createElement('canvas');
                canvas.style['position'] = 'absolute';
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
                let canvas;
                let ctx;

                let isDown = false;

                let self = this;

                function handleMouseDown(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    // get references to the canvas and context
                    canvas = self.createCanvas(elm, width, height, true);
                    ctx = canvas.getContext("2d");

                    // style the context
                    ctx.strokeStyle = "blue";
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
                    console.log('up');
                }

                function handleMouseUp(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    // the drag is over, clear the dragging flag
                    isDown = false;



                    if(confirm('Keep?')) {
                        canvases.getContext("2d").drawImage(canvas, 0, 0);
                        $(canvas).remove();
                    }
                    else {
                        $(canvas).remove();
                        canvas = null;
                        ctx = null;
                    }
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

                    console.log(mouseX + ' | ' + mouseY);

                    // clear the canvas
                    ctx.clearRect(0, 0, canvas.width, canvas.height);

                    // calculate the rectangle width/height based
                    // on starting vs current mouse position
                    let width = mouseX - startX;
                    let height = mouseY - startY;

                    // draw a new rect from the start position
                    // to the current mouse position
                    ctx.strokeRect(startX, startY, width, height);
                }

                $(elm).mousedown(function(e){handleMouseDown(e);});
                $(elm).mousemove(function(e){handleMouseMove(e);});
                $(elm).mouseup(function(e){handleMouseUp(e);});
                $(canvas).mouseout(function(e){handleMouseOut(e);});

            }
        }
    }
</script>