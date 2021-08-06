import _ from "lodash";
import Tab from "../components/MediaLibrary/partials/Tabs";

export default {
    created: function () {
        this.fetchInitialImages();
    },
    props: ['url','media'],
    components:{Tab},
    data: function () {
        return {
            dropzoneOptions: {
                url: this.url,
                thumbnailWidth: 100,
                thumbnailHeight: 100,
                maxFilesize: 2,
                addRemoveLinks: true,
                success: function (file, response) {
                    let self = this;
                    self.removeFile(file);
                },
                headers: {"X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')}
            },
            images: [],
            image: [],
            attachedImage:[],
            search: null,
            galleryClass: 'col-md-12',
            awaitingSearch: false,
            lastPage: false,
            page: 1,
        }
    },

    methods: {
        fileUploaded(file, response) {
            if(response[0]){
                this.images.unshift(response[0]);
                $('[href="#home-tab"]').tab('show');
            }

        },
        fetchInitialImages(){
            window.axios('/back-door/medias?page=1').then(res => {
                this.images = res.data
                if (this.media) {
                    const img = _.filter(this.images, i => {
                        return i.id == this.media;
                    })
                    this.image = img;
                }
            }).catch(e => console.log(e))
        },
        removeImage() {
            this.attachedImage=[];
            this.image = [];
        },
        loadMoreImage(isVisible){
            if(!isVisible){return}
            if(!this.lastPage){
                this.page++;
                window.axios('/back-door/medias?page='+this.page).then(res => {
                    let data = res.data;
                    if(!data.length){
                        this.lastPage = true;
                        return;
                    }
                    this.images.push(...data)
                }).catch(e=>console.log(e))
            }
        },
        deleteImage(){
            var r = confirm("Are you sure? Confirm Delete?");
            if (!r) {
                return;
            }
            let id=[];
            if(this.image[0]===undefined){
                id=[this.image.id]
            }else {
                id=this.image.map(i=>i.id);
            }
            // this.images = this.images.filter(obj => id.indexOf(obj.id) == -1);
            axios.post(`/back-door/medias`, {
                id
            })
                .then(res=> {
                    // this.images.splice((index,1))
                    // this.images = res.data
                    if(this.image[0]===undefined){
                        var index = this.images.indexOf(this.image);
                        this.images.splice(index,1)
                    }else {
                        const self = this;
                        this.image.forEach(function (image) {
                            var index = self.images.indexOf(image);
                            self.images.splice(index,1)
                        })
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        }
    },
    watch: {
        search: function (val) {
            if (!this.awaitingSearch) {
                setTimeout(() => {
                    window.axios('/back-door/medias?q='+this.search).then(res => {
                        this.images = res.data;
                        // this.removeImage();
                    })
                        .catch();
                    this.awaitingSearch = false;
                }, 1000); // 1 sec delay
            }
            if(!this.search){
                this.images=[];
                this.page=1;
            }
            this.awaitingSearch = true;
        },
    },
}
