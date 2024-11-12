<template>
    <div>
        <div class="modal flip fade" id="warningModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center p-5">
                    <i class="ri-alert-line text-danger fs-70"></i>
                    <div class="">
                        <h4 class="mb-3"> {{ __('warning') }} </h4>
                        <p class="text-muted mb-4"> {{ __('you are about to delete some records that will not be recovered') }} ! </p>
                        <div class="hstack gap-2 justify-content-center">
                            <a id="closeModal" href="javascript:void(0);" class="btn btn-light" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> {{__('close')}}</a>
                            <a href="javascript:void(0);" @click="!item?multi_destroy():destroy()" class="btn btn-danger">{{__('delete')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="modal flip fade" id="scrapFinishedModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center p-5">
                    <i class="ri-alert-line text-success fs-70"></i>
                    <div class="">
                        <h4 class="mb-3"> {{ __('success') }} </h4>
                        <p class="text-muted mb-4"> {{ __('your products have been scraped successfully !') }} ! </p>
                        <div class="hstack gap-2 justify-content-center">
                            <a id="closeModal" href="javascript:void(0);" class="btn btn-light" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> {{__('close')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="modal flip fade" id="scrapNotFinishedModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center p-5">
                    <i class="ri-alert-line text-warning fs-70"></i>
                    <div class="">
                        <h4 class="mb-3"> {{ __('warning') }} </h4>
                        <p class="text-muted mb-4"> {{ scrap_form.products_number - products.length }} have not been scraped </p>
                        <div class="hstack gap-2 justify-content-center">
                            <a id="closeModal" href="javascript:void(0);" class="btn btn-light" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> {{__('close')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="modal flip fade" id="savedFinishedModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center p-5">
                    <i class="ri-alert-line text-success fs-70"></i>
                    <div class="">
                        <h4 class="mb-3"> {{ __('success') }} </h4>
                        <p class="text-muted mb-4"> {{ __('your products have been saved successfully !') }} ! </p>
                        <div class="hstack gap-2 justify-content-center">
                            <a id="closeModal" href="javascript:void(0);" class="btn btn-light" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> {{__('close')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal flip fade" id="filesModal"  data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center p-5" v-if="selected_product">
                    <div v-if="selected_product.images.length>2">
                        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <div v-for="image,index in selected_product.images" :key="index" class="carousel-item" :class="{'active':index==0}">
                                        <img v-lazy="image" class="d-block w-100" alt="...">
                                    </div>

                                </div>
                                <button class="carousel-control-prev" type="button"
                                    data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button"
                                    data-bs-target="#carouselExampleControls" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
        <div class="modal flip fade" id="savedNotFinishedModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center p-5">
                    <i class="ri-alert-line text-warning fs-70"></i>
                    <div class="">
                        <h4 class="mb-3"> {{ __('warning') }} </h4>
                        <p class="text-muted mb-4">
                             <strong>{{ products.length }}</strong>
                             products have not been saved , please try saving them again
                        </p>
                        <div class="hstack gap-2 justify-content-center">
                            <a id="closeModal" href="javascript:void(0);" class="btn btn-light" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> {{__('close')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <auth-layout>
            <div class="container-fluid">
                <page-header></page-header>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">
                        <select class="form-control" v-model="scrap_form.site">
                            <option :value="null"> {{ __('select site for scraping') }} </option>
                            <option value="https://www.trendyol.com/"> {{ __('trendyol') }} </option>
                        </select>
                    </span>
                    <span class="input-group-text" id="basic-addon1">
                    <input :disabled="check_scraping()" width="100" v-model="scrap_form.products_number" type="number" min="1" class="form-control" :placeholder="__('Enter products number')">
                    </span>
                    <input :disabled="check_scraping()" v-model="scrap_form.url" type="text" class="form-control" :placeholder="__('Enter the url of product')"
                        aria-label="Username" >
                    <button :disabled="scrap_form.busy || check_scraping()" @click="fetch_date()" class="btn btn-primary"> {{ __('fetch data') }} </button>
                    <button :disabled="form.busy || products.total==0" @click="save_products()" class="btn btn-success"> {{ __('save selected') }} </button>
                </div>
                <div class="text-danger mb-3" v-html="scrap_form.errors.get('url')">  </div>
                <div class="text-danger mb-3" v-html="scrap_form.errors.get('products_number')">  </div>

                <div class="card card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="products-count fs-20"> Scraped Products : <strong class="fs-22"> {{ products.total }}</strong>
                            <button v-if="scraping" class="btn btn-light" type="button" disabled>
                                <span class="spinner-grow spinner-grow-sm align-middle" role="status"
                                    aria-hidden="true"></span>
                                scraping products...
                            </button>
                            <button v-if="saving" class="btn btn-light" type="button" disabled>
                                <span class="spinner-grow spinner-grow-sm align-middle" role="status"
                                    aria-hidden="true"></span>
                                saving products...
                            </button>
                        </span>
                        <div class="btn-list">
                            <button v-if="scraping" @click="stop_scrap()" class="btn btn-danger"> <i class="ri-stop-circle-line"></i> {{ __('stop scraping') }} </button>
                        <button @click="get_temp_products()" class="btn btn-light"> <i class="ri-refresh-line"></i> {{ __('load scraped products') }} </button>
                        <!-- <button @click="run_faild_jobs()" class="btn btn-success"> <i class="ri-refresh-line"></i> {{ __('run faild jobs') }} </button> -->
                        </div>
                    </div>
                </div>

                <div class="card card-body">
                    <div class="mb-3 alert alert-success" v-if="success_message">  {{ success_message }} </div>
                    <div v-if="form.progress" class="progress progress-lg mb-5 custom-progress-3 progress-animate" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar" :style="'width:'+ form.progress.percentage +'%'">
                            <div class="progress-bar-value"> {{ form.progress.percentage }} %</div>
                        </div>
                    </div>
                    <div v-if="scrap_form.progress" class="progress progress-lg mb-5 custom-progress-3 progress-animate" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                        <div class="progress-bar" :style="'width:'+ scrap_form.progress.percentage +'%'">
                            <div class="progress-bar-value"> {{ scrap_form.progress.percentage }} %</div>
                        </div>
                    </div>
                    <div class="my-3" v-if="products.total>0">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label"> {{ __('brand') }} </label>
                                <v-select v-model="form.brand_id" :options="brands" :reduce="brand => brand.id" label="translated_name" />
                                <div class="text-danger mb-3" v-html="form.errors.get('brand_id')" />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"> {{ __('pricing formula') }} </label>
                                <v-select v-model="form.formula_id" :options="formulas" :reduce="formula => formula.id" label="name" />
                                <div class="text-danger mb-3" v-html="form.errors.get('formula_id')" />
                            </div>
                        </div>
                        <div>
                            <div class="my-2">
                            <label class="form-label"> {{ __('category') }} </label>
                            <v-select v-model="form.category_id" :options="get_categories_with_parents(categories)" :reduce="category => category.id" label="parents_name" />
                            <div class="text-danger mb-3" v-html="form.errors.get('category_id')" />
                            </div>
                            <tree :categories="categories" :form="form"></tree>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mb-2">
                            <button v-if="checked.length>0 && check_permissions(['delete product'])" class="btn btn-danger-ghost btn-wave" data-bs-target="#warningModal" data-bs-toggle="modal"><i class="ri-delete-bin-line me-1 align-bottom"></i> {{ __('delete selected') }} </button>
                        </div>
                    <div class="table-responsive scrollable" v-if="!scrap_form.progress">

                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col"><input  v-model="checkAll" class="form-check-input" type="checkbox" id="checkboxNoLabel" aria-label="..."></th>
                                    <th scope="col">{{__('id')}}</th>
                                    <th scope="col">{{__('image')}}</th>
                                    <th scope="col">{{__('gallery')}}</th>
                                    <th scope="col">{{__('product name')}}</th>
                                    <th scope="col">{{__('product desc')}}</th>
                                    <th scope="col">{{__('original price')}}</th>
                                    <th scope="col">{{__('discount price')}}</th>
                                    <th scope="col">{{__('discount percentage')}} (%)</th>
                                    <th scope="col">{{__('final price')}}</th>
                                    <th scope="col">{{__('sku')}}</th>
                                    <th scope="col">{{__('sizes')}}</th>
                                    <th scope="col">{{__('stock status')}}</th>
                                    <th scope="col">{{__('added by')}}</th>
                                    <th scope="col">{{__('action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="product,index in products.data" :key="index">
                                    <th scope="row"><input v-model="checked" :value="product.id" class="form-check-input" type="checkbox" id="checkboxNoLabel1" aria-label="..."></th>
                                    <td> {{ product.product_id }} </td>
                                    <td>
                                        <div class="">
                                        <img width="100" v-if="product.images.length>0" v-lazy="product.images[0]" class="avatar-sm img-thumbnail admin-profile-image" alt="admin-profile-image">
                                        <img width="100" v-else v-lazy="$page.props.auth.default_img" class="avatar-sm img-thumbnail admin-profile-image" alt="admin-profile-image">
                                    </div>
                                    </td>
                                    <td>
                                         <button @click="selected_product=product"
                                         data-bs-toggle="modal"
                                         data-bs-target="#filesModal"
                                          class="btn btn-primary"> {{ __('view gallery') }} </button>
                                    </td>
                                    <td>{{ substr(product.name,80)}}</td>
                                    <td>{{ substr(product.desc,100)}}</td>
                                    <td>{{product.price}}</td>
                                    <td>{{product.discount_price }}</td>
                                   <td> {{ product.discount_percentage }} </td>
                                   <td> {{ product.final_price }} </td>
                                   <td> {{ product.sku }}  </td>
                                   <td>
                                    <span v-if="product.sizes_ids && product.sizes_ids.length>0" style="width:200px;display: block;">
                                        <span>
                                        <span class="fw-bold" v-for="option,index in product.sizes_ids" :key="index"><span v-if="index>0"> / </span>( {{   getSizeName(sizes,option.id)  }}
                                        -
                                        <span v-if="option.inStock" class="text-success"> In stock </span>
                                        <span v-else class="text-danger"> Out Of Stock </span>
                                         )</span>
                                        </span>
                                    </span>
                                  </td>

                                   <td>
                                    {{product.stock_status}}
                                    </td>
                                   <td>
                                    <span v-if="product.admin_id"> {{ product.admin.name }} </span>
                                    </td>

                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            <a  @click="item=product"  data-bs-target="#warningModal" data-bs-toggle="modal" href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger"><i class="ri-delete-bin-line"></i></a>
                                            <a  target="_blank" :href="product.link" class="btn btn-icon btn-sm btn-primary"><i class="ri-eye-line"></i></a>
                                        </div>
                                    </td>
                                </tr>

                            </tbody>

                        </table>


                    </div>
                    <div v-else class="mt-5">
                        <div style="margin: 0 auto;display: block;" class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div class="mt-2" v-if="products.total>0">
                            <vue-pagination :currentPage="currentPage" :totalPages="products.last_page" :fetchData="get_temp_products"></vue-pagination>
                        </div>
                </div>
        </div>
    </auth-layout>
    </div>

</template>



<script>
import Form from 'vform';
import AuthLayout from '../Layouts/AuthLayout.vue'
import PageHeader from '@/Components/PageHeader.vue'
import Tree from '../Categories/Tree.vue';
import vSelect from 'vue-select'
import VuePagination from '@/Components/Elements/VuePagination.vue';


    export default{
        components: { AuthLayout, PageHeader, Tree, vSelect, VuePagination},
        props:{brands:Array,
            formulas:Array,
            categories:Array,
            sizes:Array,
            colors:Array,
        },

        mounted(){
            this.get_temp_products(this.products.current_page)
            setInterval(() => {
                this.get_temp_products(this.products.current_page)
            }, 20000);
        },
        data(){
            return {
                scrap_form:new Form({
                    site:null,
                    url:localStorage.getItem('scrap_url'),
                    products_number:localStorage.getItem('scrap_products_number'),
                }),
                form:new Form({
                    id:null,
                    products:[],
                    brand_id:null,
                    category_id:null,
                    formula_id:null,
                    type:'scrap'
                }),
                products:{},
                checked:[],
                success_message:null,
                storeIntervalId:null,
                scrapIntervalId:null,
                item:null,
                scraping:localStorage.getItem('scraping'),
                saving:false,
                currentPage:1,
                selected_product:null
            }
        },
        methods:{
            fetch_date(){
                this.success_message=null
                this.scrap_form.post('/admin/scrap-products')
                this.scraping=true
                localStorage.setItem('scraping',true)
                localStorage.setItem('scrap_products_number',this.scrap_form.products_number)
                localStorage.setItem('scrap_url',this.scrap_form.url)

            },
            check_scraping(){
                if(localStorage.getItem('scraping')=='true'){
                    return true
                }
                return false
            },
            stop_scrap(){
                axios.post('/admin/delete_my_jobs')
                     .then((resp)=>{
                         localStorage.removeItem('scraping');
                         localStorage.removeItem('scrap_url');
                         localStorage.removeItem('scrap_products_number');
                         this.scrap_form.url=null
                         this.scrap_form.products_number=null
                         this.scraping=false
                     })
            },
            destroy(){
            axios.post('/admin/api/delete_tem_product/'+this.item.id)
                 .then((resp)=>{
                     let index =this.products.data.indexOf(this.item)
                     this.products.data.splice(index,1)
                     $('#warningModal').modal('hide')
                    })
            },
            multi_destroy(){
            axios.post('/admin/api/delete_multiple_tem_product',{checked:this.checked})
                 .then((resp)=>{
                    //  this.checked.forEach((el)=>{
                    //     let product=this.products.data.find((e)=>e.id==el)
                    //     let index =this.products.data.indexOf(product)
                    //     this.products.data.splice(index,1)
                    // })
                    this.get_temp_products()

                    $('#warningModal').modal('hide')
                    })
            },
            save_products(){
                this.saving=true
                clearInterval(this.scrapIntervalId)

                this.form.post('/admin/api/save_scraped_products')
                     .then((resp)=>{
                        this.success_message=resp.data
                        this.storeIntervalId = setInterval(this.check_saved_products, 10000);
                        // this.products.data=[]
                })
            },

            get_brands(){
                let results=[]
                this.brands.forEach((el)=>{
                    results.push(el.translated_name)
                })
                return results
            },
            get_temp_products(page=1){
                    axios.get('/admin/api/get_temp_products/scrap?page='+page)
                    .then((resp)=>{
                        this.products=resp.data
                        if(this.scraping && resp.data.total >= localStorage.getItem('scrap_products_number')){
                            this.stop_scrap()
                        }


                    })
            },
            run_faild_jobs(){
                axios.post('/admin/api/run_faild_jobs')
                     .then((resp)=>{
                        this.check_scraped_products()
                     })
            },
            check_saved_products(){

                let saved=this.products.total
                axios.get('/admin/api/get_temp_products/scrap')
                     .then((resp)=>{
                        this.products=resp.data


                        if(this.products.total == 0 ){
                            $('#savedFinishedModal').modal('show')
                                clearInterval(this.storeIntervalId)
                                this.saving=false
                            }
                            // else if(this.products.total > 0 && saved == this.products.total){
                            //     $('#savedNotFinishedModal').modal('show')
                            //     clearInterval(this.storeIntervalId)
                            //     this.saving=false
                            // }
                    })

            },
            check_scraped_products(){
            this.scraping=true
                let scraped=this.products.data.length
                axios.get('/admin/api/get_temp_products/scrap?page='+this.currentPage)
                     .then((resp)=>{
                        this.products=resp.data

                            if(this.products.data.length >= this.scrap_form.products_number ){
                                    $('#scrapFinishedModal').modal('show')
                                    clearInterval(this.scrapIntervalId)
                                    this.scraping=false
                                }else if(scraped == this.products.data.length){
                                    $('#scrapNotFinishedModal').modal('show')
                                    clearInterval(this.scrapIntervalId)
                                    this.scraping=false
                                }
                    })

            },


        },
        computed: {
            checkAll: {
            get: function () {
                return this.products.data ? this.checked.length == this.products.data.length : false;
            },
            set: function (value) {
                this.item=null
                var checked =new Array;
                if (value) {
                this.products.data.forEach(function (user) {
                    checked.push(user.id);
                });
                }
                this.checked = checked;
            }
            }
        }

    }

</script>

<style>
@import "vue-select/dist/vue-select.css";
</style>
