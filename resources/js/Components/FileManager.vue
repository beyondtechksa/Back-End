<template>
    <div class="modal fade" :id="id!=null?id:'filemanagerModal'" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="staticBackdropLabel">{{__('file manager')}}
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body" style="height: 520px;overflow: hidden;">
                <div class="file-manager-container p-2 gap-2 d-sm-flex">
                <div class="file-manager-navigation">
                    <div class="d-flex align-items-center justify-content-between w-100 p-3 border-bottom">
                        <div>
                            <h6 class="fw-semibold mb-0">{{__('folders')}}</h6>
                        </div>
                        <div class="dropdown">
                            <button v-if="!newMode" @click="newModal()" class="btn btn-sm btn-icon btn-primary-light btn-wave waves-light" type="button" >
                                <i class="ri-add-line"></i>
                            </button>
                            <button v-else @click="closeNewModal()" class="btn btn-sm btn-icon btn-danger-light btn-wave waves-light" type="button" >
                                <i class="ri-close-line"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-3" v-if="newMode || editMode">
                        <form @submit.prevent="newMode?create():edit()">
                        <div class="input-group">
                            <input v-model="form.name" type="text" class="form-control bg-light border-0" :placeholder="__('folder name')" aria-describedby="button-addon2">
                            <button :disabled="form.name==''|| form.busy" class="btn btn-primary" type="submit">
                                <i class="ri-edit-line"></i>
                            </button>
                        </div>
                        <div class="text-danger pt-2 fs-12" v-html="form.errors.get('name')" />
                    </form>
                    </div>
                    <!-- <div class="p-3 border-bottom">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0" placeholder="Search Files" aria-describedby="button-addon2">
                            <button class="btn btn-light" type="button" id="button-addon2"><i class="ri-search-line text-muted"></i></button>
                        </div>
                    </div> -->
                    <div>
                        <ul class="list-unstyled files-main-nav" id="files-main-nav">
                            <li @click="show_media()" class="files-type" :class="{'active':media_category_id==''}">
                                <a href="javascript:void(0)">
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            <i class="ri-folder-2-line fs-16"></i>
                                        </div>
                                        <span class="flex-fill text-nowrap">
                                            {{__('all folders')}}
                                        </span>

                                    </div>
                                </a>
                            </li>
                            <li @click="show_media(category)" class="files-type" v-for="category,index in mediaCategories" :key="index" :class="{'active':category.id==media_category_id}">
                                <a href="javascript:void(0)">
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            <i class="ri-folder-2-line fs-16"></i>
                                        </div>
                                        <span class="flex-fill text-nowrap">
                                            {{category.name}}
                                        </span>
                                        <div class="dropdown  me-1">
                                        <button class="btn btn-sm btn-icon btn-primary-light btn-wave waves-light waves-effect" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ri-more-2-fill"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" @click="editModal(category)" href="javascript:void(0)">{{__('edit')}}</a></li>
                                            <li><a class="dropdown-item" @click="del_category(category)" href="javascript:void(0)">{{__('delete')}}</a></li>
                                        </ul>
                                    </div>

                                    </div>
                                </a>
                            </li>




                        </ul>
                    </div>
                </div>
                <div class="file-manager-folders">
                    <div class="d-flex p-3 flex-wrap gap-2 align-items-center justify-content-between border-bottom">
                        <div>
                            <h6 class="fw-semibold mb-0"> {{ __('files') }} </h6>
                        </div>
                        <div class="d-flex gap-2">
                            <button id="folders-close-btn" class="d-sm-none d-block btn btn-icon btn-sm btn-danger-light">
                                <i class="ri-close-fill"></i>
                            </button>


                            <label v-if="!form.progress" for="fileupload" class="btn btn-sm btn-outline-dark  btn-wave waves-light">
                                <i class="ri-upload-line align-middle me-1"></i> {{ __('upload file') }}
                                <input type="file" class="d-none" id="fileupload" @input="upload">
                            </label>
                            <span v-else> {{ __('uploading') }} ...... </span>
                            <div class="text-danger pt-2 fs-12" v-html="form.errors.get('file')" />

                        </div>
                    </div>
                    <div v-if="form.progress" class="progress progress-lg mb-5 custom-progress-3 progress-animate" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar" :style="'width:'+ form.progress.percentage +'%'">
                            <div class="progress-bar-value"> {{ form.progress.percentage }} %</div>
                        </div>
                    </div>
                    <div class="p-3 file-folders-container" id="file-folders-container" style="height:400px !important">

                        <div class="row mb-3">
                            <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6" v-for="file,index in media" :key="index" @dblclick="select_file()">
                                <div class="p-3 text-center uploaded-file" :class="{'selected':selected_media.id==file.id}" @click="selected_media=file">
                                    <span @click="del_media(file)" class="delete-icon"> <i class="ri-delete-bin-line"></i> </span>
                                    <div class="file-details mb-3">
                                        <img v-if="check_type(file.type)=='image'" v-lazy="file.path" alt="">
                                    </div>
                                    <div>
                                        <p class="mb-0 text-muted fs-10">{{file.size}} |{{ file.name.slice(0,20) }} </p>
                                    </div>
                                </div>
                            </div>

                            <div class="my-3 mb-5 text-center">
                            <button type="button" class="btn btn-primary" :disabled="form.busy" @click="loadMore()"> Load More </button>
                            </div>

                        </div>



                    </div>
                </div>

            </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                    data-bs-dismiss="modal">{{__('close')}}</button>
                <button :disabled="!selected_media" @click="select_file()" type="button" class="btn btn-primary">{{__('select')}}</button>
            </div>
        </div>
    </div>
</div>

</template>

<script>
import Form from "vform"
// import '@/js/file-manager.js'

    export default{
        components:{Form},
        props:{
            id:String
        },
        mounted(){
            this.get_media_categories();
            this.get_media();
        },
        data(){
            return {
                newMode:false,
                editMode:false,
                form:new Form({
                    id:'',
                    name:'',
                    file:'',
                    media_category_id:'',
                    media_type:'image',
                    offset:0,

                }),
                media_category_id:'',
                previous_media_category_id:'',
                selected_media:'',
                mediaCategories:[],
                media:[]
            }
        },
        methods:{
            newModal(){
                this.newMode=true
                this.editMode=false
            },
            editModal(category){
                this.newMode=false
                this.form.id=category.id
                this.form.name=category.name
                this.editMode=true
            },
            closeNewModal(){
                this.newMode=false
            },
            create(){
                this.form.file=null
                this.form.post('/admin/api/create_media_category')
                    .then((resp)=>{
                        this.form.reset()
                        this.newMode=false
                        this.mediaCategories.unshift(resp.data)
                    })

            },
            edit(){
                this.form.post('/admin/api/update_media_category')
                    .then((resp)=>{
                        this.form.reset()
                        this.editMode=false
                        let category = this.mediaCategories.find((e)=>e.id==resp.data.id)
                        let index=this.mediaCategories.indexOf(category)
                        this.mediaCategories[index]=resp.data

                    })
            },
            del_category(category){
                if(confirm(this.__('are you sure for this action'))){
                    this.form.id=category.id
                    this.form.post('/admin/api/delete_media_category')
                    .then((resp)=>{
                        let mediaCategory = this.mediaCategories.find((e)=>e.id==category.id)
                        let index=this.mediaCategories.indexOf(mediaCategory)
                        this.mediaCategories.splice(index,1)
                        this.media_category_id=''
                        this.get_media()
                    })
                }
            },
            del_media(media){
                if(confirm(this.__('are you sure for this action'))){
                    this.form.id=media.id
                    this.form.post('/admin/api/delete_media')
                    .then((resp)=>{
                        let mediamedia = this.media.find((e)=>e.id==media.id)
                        let index=this.media.indexOf(mediamedia)
                        this.media.splice(index,1)
                    })
                }
            },
            get_media_categories(){
                axios.get('/admin/api/get_media_categories/image')
                     .then((resp)=>{
                        this.mediaCategories=resp.data
                     })
            },
            get_media(){
                this.form.get('/admin/api/get_media/image/'+this.media_category_id)
                     .then((resp)=>{
                        this.media=resp.data
                     })
            },
            loadMore(){
                this.form.offset+=20
                this.form.get('/admin/api/get_media/image/'+this.media_category_id)
                     .then((resp)=>{
                        resp.data.forEach((el)=>{
                            this.media.push(el)
                        })
                     })
            },
            show_media(category=''){
                if(category){
                    this.previous_media_category_id=this.media_category_id
                    this.media_category_id=category.id
                    this.form.media_category_id=category.id
                }else{
                    this.media_category_id=''
                }
                this.get_media()
            },
            upload(e){
            let file=e.target.files[0]
            this.form.file=file
            const reader=new FileReader()
            reader.onload= ()=>{
                this.form.file=reader.result
            }
            reader.readAsDataURL(file)

            this.form.post('/admin/api/upload_file')
                      .then((resp)=>{
                        this.media.unshift(resp.data)
                        this.form.file=null
                      })
            },
            sum_all(){
                let count=0
                this.mediaCategories.forEach((e)=>{
                    count+=e.media_count
                })
                return count
            },
            check_type(str){
              return str.substring(0,str.indexOf('/'))
            },
            select_file(){
                this.$emit('fileUploaded')
                let id = this.id!=null?this.id:'filemanagerModal'
                $('#'+id).modal('hide')
            }

        },


    }

</script>

<style scoped>
.dropdown-menu li{padding: 0!important;}
@media (min-width: 576px){
   .file-manager-container .file-manager-navigation {
    width: 30rem;
}
.file-manager-folders{overflow: auto;}
}

.uploaded-file{cursor: pointer;}
.uploaded-file.selected{    border: 2px dashed #845adf;}

.uploaded-file{position: relative;  border: 2px dashed #ededed;}
.uploaded-file .delete-icon{position: absolute;
    width: 30px;
    height: 30px;
    background: #fff;
    text-align: center;
    line-height: 30px;
    border-radius: 50%;
    right: 0;
    top: 0;
    font-size: 20px;}
</style>
