<template>
    <div
            v-bind:class="[{ isActive: isActive }, 'elem elem_' + keyValue]"
            v-bind:data-val="keyValue"
    >

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

    </div>
</template>

<script>
    import { store } from '../store';
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
            }
        }
    }
</script>
