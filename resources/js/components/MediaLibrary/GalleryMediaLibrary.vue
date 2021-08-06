<template>
    <div>
        <div v-if="attachedImage.length" class="d-inline-block mb-1 mr-2" style="position: relative;"
             v-for="(img,index) in attachedImage">
            <button type="button" class="close remove-image" @click="removeParticularImage(img,index)">
                <span aria-hidden="true">&times;</span>
            </button>
            <img class="thumbnail" :src="img.path" alt="" style="margin-right:5px">
            <input type="hidden" name="gallery[]" :value="img.id">
        </div>
        <div class="d">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter1">
                Add gallery Images
            </button>

        </div>


        <div class="modal fade  bd-example-modal-xl" id="exampleModalCenter1" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);" tabindex="0" role="dialog"
             aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered  modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Media

                        </h5>
                        <!--                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">-->
                        <!--                            <span aria-hidden="true">&times;</span>-->
                        <!--                        </button>-->
                        <button v-if="image" type="button" @click="deleteImage" class="btn btn-danger"><i
                            class="la la-trash-alt"></i></button>

                    </div>
                    <div class="modal-body" style="overflow: auto">

                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home2" role="tab"
                                   aria-controls="home" aria-selected="true">Media Library</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#upload2" role="tab"
                                   aria-controls="profile" aria-selected="false">Upload</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home2" role="tabpanel" aria-labelledby="home-tab"
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
                                            <div class="col-md-2 mb-1" v-for="img in images">

                                                <div class="custom-control custom-checkbox image-checkbox">
                                                    <input type="checkbox" class="custom-control-input"
                                                           :id="'ck2g'+img.id "
                                                           :checked="isChecked(img.id)?'checked':false"
                                                           :value="img" v-model="image">
                                                    <label class="custom-control-label" :for="'ck2g'+img.id">
                                                        <img :src="img.path" alt="" class="thumbnail">
                                                    </label>
                                                    <div class="caption">
                                                        {{ img.filename }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div v-if="images.length" v-observe-visibility="loadMoreImage"></div>
                                        </div>
                                    </div>
                                    <!--                                    <div class="col-md-3">-->
                                    <!--                                        <button v-if="image" type="button" @click="deleteImage" class="btn btn-danger" ><i class="la la-trash-alt"></i></button>-->
                                    <!--                                    </div>-->
                                </div>

                            </div>
                            <div class="tab-pane fade" id="upload2" role="tabpanel" aria-labelledby="profile-tab">
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" @click="setImage">
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

    export default {
        name: "MediaLibrary",
        components: {
            vueDropzone: vue2Dropzone
        },


        mixins: [mixin],

        props: ['type'],
        methods: {
            removeParticularImage(img, ind) {
                this.attachedImage.splice(ind, 1)
            },
            setImage() {
                this.attachedImage = this.image;
            },
            isChecked(id) {
                return _.find(this.attachedImage, function (o) {
                    return o.id === id;
                }) !== undefined;

            }

        },

        created() {
            this.attachedImage = JSON.parse(this.media);
            this.image = this.attachedImage;
        },
        watch: {
            image: function (val) {
                if (!this.image.length){
                    this.image = this.attachedImage;
                }

            },
        },
    }
</script>

<style scoped>
    .caption {
        height: 45px;
        overflow: hidden;
    }

    .remove-image {
        position: absolute;
        right: 0;
        position: absolute;
        right: 0;
        border-radius: 50%;
        background: #000;
        width: 20px;
        height: 20px;
        line-height: 24px;
        color: #fff;
    }

</style>
