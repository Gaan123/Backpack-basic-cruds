<template>
    <div>
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Media

                </h5>
                <!--                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">-->
                <!--                            <span aria-hidden="true">&times;</span>-->
                <!--                        </button>-->
                <button v-if="image && deleteoption" type="button" @click="deleteImage" class="btn btn-danger"><i
                    class="la la-trash-alt"></i></button>
                <button type="button" v-if="selectoption" class="btn btn-primary" data-dismiss="modal"
                        @click="setImage">
                    Select
                </button>

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
                                    <div class="col-md-2 mb-1" v-for="img in images">

                                        <div class="custom-control custom-checkbox image-checkbox">
                                            <input :type="multiple?'checkbox':'radio'" class="custom-control-input"
                                                   :id="'ck2'+img.id " :value="img" v-model="image">
                                            <label class="custom-control-label" :for="'ck2'+img.id">
                                                <img :src="img.path" alt=""
                                                     class="thumbnail figure-img img-fluid rounded" style="">
                                            </label>
                                            <figcaption class="figure-caption"> {{ img.filename }}</figcaption>
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
            <div class="modal-footer" v-if="selectoption">
                <!--                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
                <button type="button" v-if="selectoption" class="btn btn-primary" data-dismiss="modal"
                        @click="setImage">
                    Select
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import vue2Dropzone from 'vue2-dropzone'
import 'vue2-dropzone/dist/vue2Dropzone.min.css'
import mixin from '../../mixins'

export default {
    name: "StandAloneMediaLibrary",
    components: {
        vueDropzone: vue2Dropzone
    },
    data() {
        return {
            image: []
        }
    },
    props: {
        'selectoption': {default: ""}, 'deleteoption': {default: ''}, 'multiple': {
            default: false,
            type: Boolean
        }
    },

    mixins: [mixin],

    methods: {

        setImage() {
            var funcNum = this.getUrlParam('CKEditorFuncNum');

            window.imageUrl = this.image.path;
            window.opener.CKEDITOR.tools.callFunction(funcNum, this.image.path, function () {
                let widthField, heightField, urlField,
                    dialog = this.getDialog();
                // let height = '100%';
                let width = '100%';
                console.log(this.image)
                if (dialog.getName() == 'image') {
                    widthField = dialog.getContentElement('info', 'txtWidth');
                    urlField = dialog.getContentElement('info', 'txtUrl');

                    if (urlField)
                        urlField.setValue(window.imageUrl);

                    if (widthField)
                        widthField.setValue(width);

                }

                return false;

            });
            window.close();
        },
        getUrlParam(paramName) {
            var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i');
            var match = window.location.search.match(reParam);

            return (match && match.length > 1) ? match[1] : '';
        },

    },

    created() {


    }
}
</script>

<style scoped>
.figure-caption {
    font-size: 90%;
    color: #869ab8;
    height: 40px;
    overflow: hidden;
}
</style>
