<template>
    <div>


        <div v-if="attachedImage.length" class="d-block mb-1" v-for="img in attachedImage">
            <img class="thumbnail selected-thumbnail" :src="img.path" alt="">
            <input type="hidden" name="upload" :value="img.id">
        </div>
        <div class="d">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#mediaLibrary">
                Select Featured Image
            </button>
            <button type="button" v-if="attachedImage.length" class="btn btn-danger" @click="removeImage">
                Remove Image
            </button>
        </div>


        <div class="modal fade  bd-example-modal-xl" id="mediaLibrary" tabindex="2" data-backdrop="false"
             style="background-color: rgba(0, 0, 0, 0.5);" role="dialog"
             aria-labelledby="mediaLibraryTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered  modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="mediaLibraryTitle">Media

                        </h5>
                        <!--                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">-->
                        <!--                            <span aria-hidden="true">&times;</span>-->
                        <!--                        </button>-->
                        <button v-if="image" type="button" @click="deleteImage" class="btn btn-danger"><i
                            class="la la-trash-alt"></i></button>

                    </div>
                    <div class="modal-body" style="overflow: auto">

                        <Tab/>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab"
                            >

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-12 p-0 pb-2">
                                                <input style="width: 200px; float:right; margin-right:20px;" type="text"
                                                       class="form-control" v-model="search" placeholder="Search">
                                            </div>
                                        </div>
                                        <div class="row" style="height: 500px;overflow-y:auto">
                                            <div :class="galleryClass">
                                                <div class="row" style="height: 500px;overflow-y:auto">
                                                    <div class="mb-1"
                                                         :class="galleryClass==='col-md-12'?'col-md-2':'col-md-3'"
                                                         v-for="img in images">

                                                        <div class="custom-control custom-checkbox image-checkbox"
                                                             @dblclick="setAndExit(img)">
                                                            <input type="radio" class="custom-control-input"
                                                                   :id="'ck2'+img.id " :value="img" v-model="image">
                                                            <label class="custom-control-label" :for="'ck2'+img.id">
                                                                <img :src="img.path" alt="" class="thumbnail">
                                                            </label>
                                                            <div class="caption">
                                                                {{ img.filename }}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div v-if="images.length"
                                                         v-observe-visibility="loadMoreImage"></div>
                                                </div>

                                            </div>
                                            <div class="col-md-3" v-if="image.length===undefined">
                                                <div class="form-group">
                                                    <label for="caption">Caption</label>
                                                    <input type="text" @input="saveCaption" class="form-control"
                                                           v-model="caption" id="caption"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--                                    <div class="col-md-3">-->
                                    <!--                                        <button v-if="image" type="button" @click="deleteImage" class="btn btn-danger" ><i class="la la-trash-alt"></i></button>-->
                                    <!--                                    </div>-->
                                </div>

                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <vue-dropzone ref="myVueDropzone" id="dropzone" :options="dropzoneOptions"
                                              @vdropzone-success="fileUploaded"
                                              :useCustomSlot=true>
                                    <div class="dropzone-custom-content" style="margin: 17%">
                                        <div class="subtitle"><i class='la la-cloud-upload'></i>Click to select a file
                                            from your computer ...or
                                        </div>
                                        <h3 class="dropzone-custom-title">Drag and drop to upload content!</h3>

                                    </div>
                                </vue-dropzone>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal" data-dismiss="modal">Close
                        </button>
                        <button type="button" id="dismissbtn" class="btn btn-primary" data-dismiss="modal"
                                @click="setImage">
                            Select
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import vue2Dropzone from 'vue2-dropzone'
import 'vue2-dropzone/dist/vue2Dropzone.min.css'
import mixin from '../../mixins'
import {debounce,findIndex} from "lodash";

export default {
    name: "MediaLibrary",
    components: {
        vueDropzone: vue2Dropzone
    },
    data() {
        return {
            caption: ""
        }
    },
    mixins: [mixin],
    props: ['type'],

    methods: {
        setAndExit(img) {
            this.attachedImage = [img];
            this.image = [];
            $("#dismissbtn").trigger('click');
        },
        setImage() {
            this.attachedImage = [this.image];
        },
        closeModal() {
            this.image = [];
        },
        saveCaption: debounce(function (event) {
            axios.post('/back-door/media/' + this.image.id, { // <== use axios.post
                caption: this.caption,
                _method: 'patch'                   // <== add this field
            })
                .then(response=> {
                    const i= findIndex(this.images,img=>img.id===this.image.id)
                    this.images[i].caption=this.caption;
                })
                .catch(function (error) {

                })
        }, 800)
    },
    watch: {
        image: function (val) {
            this.galleryClass = val.length === undefined ? "col-md-9" : "col-md-12";
            this.caption = val.caption;
        }
    },
    created() {
        this.attachedImage = JSON.parse(this.media);
    }
}
</script>

<style scoped>
.caption {
    height: 45px;
    overflow: hidden;
}
</style>
