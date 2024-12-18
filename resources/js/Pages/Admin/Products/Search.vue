<template>
    <div>
    <auth-layout>

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
                            <a href="javascript:void(0);" @click="multi_destroy()" class="btn btn-danger">{{__('delete')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal flip fade" id="filesModal"  data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center p-5" v-if="selected_product && selected_product.files">
                    <div v-if="selected_product.files.length>2">
                        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <div v-for="image,index in selected_product.files" :key="index" class="carousel-item" :class="{'active':index==0}">
                                        <img v-lazy="image.image" class="d-block w-100" alt="...">
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
    <div class="modal fade" id="moveModal" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel1">{{__('move to')}}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="" v-if="form.type=='brand'">
                    <label class="form-label"> {{ __('brands') }} </label>
                    <v-select v-model="form.brand_id" :options="brands" :reduce="brand => brand.id" label="translated_name" />
                    <div class="text-danger" v-html="form.errors.get('brand_id')"></div>
                </div>
                <div class="" v-if="form.type=='category'">
                    <label class="form-label"> {{ __('category') }} </label>
                        <v-select v-model="form.category_id" :options="get_categories_with_parents(categories)" :reduce="category => category.id" label="parents_name" />
                    <div class="text-danger" v-html="form.errors.get('category_id')"></div>
                </div>
                <div class="" v-if="form.type=='collection'">
                    <label class="form-label"> {{ __('collection') }} </label>
                        <v-select v-model="form.collection_id" :options="collections" :reduce="collection => collection.id" label="translated_name" />
                    <div class="text-danger" v-html="form.errors.get('collection_id')"></div>
                </div>
                <div class="" v-if="form.type=='return_policy'">
                    <label class="form-label"> {{ __('return policy') }} </label>
                        <v-select v-model="form.return_policy_id" :options="return_policies" :reduce="return_policy => return_policy.id" label="translated_name" />
                    <div class="text-danger" v-html="form.errors.get('return_policy_id')"></div>
                </div>
                <div class="" v-if="form.type=='product_group'">
                    <label class="form-label"> {{ __('product group') }} </label>
                        <v-select v-model="form.product_group_id" :options="product_groups" :reduce="product_group => product_group.id" label="translated_name" />
                    <div class="text-danger" v-html="form.errors.get('product_group_id')"></div>
                </div>
                <div class="" v-if="form.type=='model_number'">
                    <label class="form-label"> {{ __('model number') }} </label>
                    <input type="text" class="form-control" v-model="form.model_number">
                    <div class="text-danger" v-html="form.errors.get('model_number')"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">{{__('close')}}</button>
                <button :disabled="form.busy" @click="move()" type="button" class="btn btn-primary"> {{ __('save') }} </button>
                <button v-if="form.type=='collection'" :disabled="form.busy" @click="remove_collection()" type="button" class="btn btn-danger"> {{ __('remove collection') }} </button>
            </div>
        </div>
    </div>
    </div>
    <div class="modal fade" id="detailsModal" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel1">{{__('details')}}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body" v-if="details">
                <div>
                <h4 > Product Attributes  </h4>
               <p>
                 <span  v-for="attribute,index in details.attributes" :key="index">
                    <span v-for="option,index in attribute.product_options" :key="index">
                        {{ option.option.translated_name }} -
                    </span>
                </span>
                </p>
                <!-- <p>
                    <span v-for="option,index in attribute.product_options" :key="index">
                        {{ option.option.translated_name }} -
                    </span>
                </p> -->
            </div>
                <h4 > Product category </h4>
                <p> <span v-if="details.category"> {{ details.category.parents_name }} </span> </p>
                <h4 > Product brand </h4>
                <p> <span v-if="details.brand"> {{ details.brand.translated_name }} </span> </p>
                <h4 > Product collection </h4>
                <p> <span v-if="details.collection"> {{ details.collection.translated_name }} </span> </p>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">
                    {{__('close')}}
                </button>
            </div>
        </div>
    </div>
    </div>
    <div class="modal fade" id="pricingModal" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel1">{{__('pricing')}}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="" >
                    <label class="form-label"> {{ __('formula') }} </label>
                    <v-select v-model="form.formula_id" :options="formulas" :reduce="formula => formula.id" label="name" />
                    <div class="text-danger" v-html="form.errors.get('formula_id')"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">{{__('close')}}</button>
                <button :disabled="form.busy" @click="update_products_formula()" type="button" class="btn btn-primary"> {{ __('save') }} </button>
            </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="groupModal" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel1">{{__('group')}}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="" >
                    <label class="form-label"> {{ __('select primary product') }} </label>
                    <div class="row">
                        <div class="col-md-4" v-for="product,index in get_checked_products()" :key="index">
                            <div class="img-box" @click="form.primary_id=product.id" :class="{'selected':product.id==form.primary_id}">
                                <img class="w-100" v-lazy="product.image">
                            </div>
                        </div>
                    </div>
                    <div class="text-danger" v-html="form.errors.get('formula_id')"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">{{__('close')}}</button>
                <button :disabled="form.busy" @click="update_products_group('add')" type="button" class="btn btn-primary"> {{ __('save') }} </button>
                <button :disabled="form.busy" @click="update_products_group('remove')" type="button" class="btn btn-danger"> {{ __('remove from group') }} </button>
            </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="attributesModal" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel1">{{__('attributes')}}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <add-attributes :attributes="attributes" :form="form"></add-attributes>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">{{__('close')}}</button>
                <button :disabled="form.busy || form.attributes_ids.length==0" @click="update_products_attributes('add')" type="button" class="btn btn-primary"> {{ __('add attributes') }} </button>
                <button :disabled="form.busy || form.attributes_ids.length==0" @click="update_products_attributes('remove')" type="button" class="btn btn-danger"> {{ __('remove attributes') }} </button>
            </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="colorsModal" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel1">{{__('colors and sizes')}}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <add-colors :colors="colors" :sizes="sizes" :form="form"></add-colors>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">{{__('close')}}</button>
                <button :disabled="form.busy" @click="update_products_colors('add')" type="button" class="btn btn-primary"> {{ __('add') }} </button>
                <button :disabled="form.busy" @click="update_products_colors('remove')" type="button" class="btn btn-danger"> {{ __('remove') }} </button>
            </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="discountModal" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel1">{{__('discount')}}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="" >
                    <label class="form-label"> {{ __('dicount percentage on selling price') }} (%) </label>
                   <input type="number" class="form-control" v-model="form.discount_percentage_selling_price">
                    <div class="text-danger" v-html="form.errors.get('discount_percentage_selling_price')"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">{{__('close')}}</button>
                <button :disabled="form.busy" @click="update_products_discount()" type="button" class="btn btn-primary"> {{ __('save') }} </button>
            </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="renameModal" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel1">{{__('rename')}}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2" >
                    <div class="mb-2" v-for="locale,index in $page.props.locales" :key="index">
                    <label class="form-label"> {{ __('product name') }} ({{ locale.name }}) </label>
                    <input v-model="form['name_'+locale.symbol]" class="form-control">
                    </div>
                    <div class="text-danger" v-html="form.errors.get('name')"></div>
                </div>
                <div class="mb-2" >
                    <label class="form-label"> {{ __('brand') }} </label>
                    <select class="form-control" v-model="form.has_brand">
                        <option :value="1"> Has brand </option>
                        <option :value="0"> No brand </option>
                    </select>
                    <div class="text-danger" v-html="form.errors.get('has_brand')"></div>
                </div>
                <div class="mb-2" >
                    <label class="form-label"> {{ __('vendor') }} </label>
                    <select class="form-control" v-model="form.has_vendor">
                        <option :value="1"> Has vendor </option>
                        <option :value="0"> No vendor </option>
                    </select>
                    <div class="text-danger" v-html="form.errors.get('has_vendor')"></div>
                </div>
                <div class="mb-2" >
                    <label class="form-label"> {{ __('sku') }} </label>
                    <select class="form-control" v-model="form.has_sku">
                        <option :value="1"> Has sku </option>
                        <option :value="0"> No sku </option>
                    </select>
                    <div class="text-danger" v-html="form.errors.get('has_sku')"></div>
                </div>
                <div class="mb-2" >
                    <label class="form-label"> {{ __('attribute') }} </label>
                    <select class="form-control" v-model="form.has_attribute">
                        <option :value="1"> Has attribute </option>
                        <option :value="0"> No attribute </option>
                    </select>
                    <div class="text-danger" v-html="form.errors.get('has_attribute')"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">{{__('close')}}</button>
                <button :disabled="form.busy" @click="update_products_rename()" type="button" class="btn btn-primary"> {{ __('save') }} </button>
            </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="translateProductsModal" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel1">{{__('translation')}}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2" >
                    <label class="form-label"> product name (Turkish)</label>
                    <div class="input-group mb-3">
                    <input :disabled="true" type="text" class="form-control"
                       v-model="form.name_tr">
                    <span class="input-group-text btn btn-success" id="basic-addon2"> translate with ai </span>
                </div>
                    <div class="text-danger" v-html="form.errors.get('name')"></div>
                </div>
                <div class="mb-2" v-for="locale,index in $page.props.locales" :key="index">
                    <label class="form-label"> {{ __('product name') }} ({{ locale.name }}) </label>
                    <input v-model="form['name_'+locale.symbol]" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">{{__('close')}}</button>
                <button :disabled="form.busy" @click="update_translate_products()" type="button" class="btn btn-primary"> {{ __('save') }} </button>
            </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="translateDescModal" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel1">{{__('translation')}}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2" >
                    <label class="form-label"> product description (Turkish)</label>
                    <div class="input-group mb-3">
                    <textarea rows="5" :disabled="true" type="text" class="form-control"
                       v-model="form.desc_tr"></textarea>
                    <span class="input-group-text btn btn-success" id="basic-addon2"> translate with ai </span>
                </div>
                    <div class="text-danger" v-html="form.errors.get('desc')"></div>
                </div>
                    <!-- <textarea rows="5" v-model="form['desc_'+locale.symbol]" class="form-control"></textarea> -->
                    <product-langtext-area :form="form" label="Description" ></product-langtext-area>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">{{__('close')}}</button>
                <button :disabled="form.busy" @click="update_translate_description()" type="button" class="btn btn-primary"> {{ __('save') }} </button>
            </div>
            </div>
        </div>
    </div>



        <div class="container-fluid">
            <page-header></page-header>
            <div class="card card-body">

                <div class="row" >
                    <div class="col-md-12 mb-3">
                        <label class="form-label"> {{ __('source url') }} </label>
                        <input v-model="search_form.source_link" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label"> {{ __('group url') }} </label>
                        <input v-model="search_form.group_link" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label"> {{ __('product sku') }} </label>
                        <input v-model="search_form.sku" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label"> {{ __('product model number') }} </label>
                        <input v-model="search_form.model_number" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label"> {{ __('created date') }} </label>
                        <flat-pickr :config="config" class="form-control" v-model="search_form.range"/>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"> {{ __('attributes') }} </label>
                        <v-select v-model="search_form.option_id" :options="get_options()" :reduce="option => option.id" label="languaged_name" />
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"> {{ __('category') }} </label>
                        <v-select v-model="search_form.category_id" :options="get_categories_with_parents(categories)" :reduce="category => category.id" label="parents_name" />
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label"> {{ __('brands') }} </label>
                        <v-select v-model="search_form.brand_id" :options="brands" :reduce="brand => brand.id" label="translated_name" />
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label"> {{ __('vendors') }} </label>
                        <v-select v-model="search_form.company_name" :options="vendors" :reduce="vendor => vendor.name" label="name" />
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label"> {{ __('imported source') }} </label>
                        <v-select v-model="search_form.imported_source" :options="['trendyol']"  />
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label"> {{ __('status') }} </label>
                        <v-select v-model="search_form.status" :options="[{id:'1',name:'active'},{id:'0',name:'disabled'}]" :reduce="status => status.id" label="name" />
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label"> {{ __('Product Discountable') }} </label>
                        <v-select v-model="search_form.discount_price" :options="[{id:1,name:'Discountable'},{id:0,name:'Not Discountable'}]" :reduce="status => status.id" label="name" />
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label"> {{ __('Imported By User') }} </label>
                        <v-select v-model="search_form.admin_id" :options="admins" :reduce="admin => admin.id" label="email" />
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label"> {{ __('collection') }} </label>
                        <v-select v-model="search_form.collection_id" :options="collections" :reduce="collection => collection.id" label="translated_name" />
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label"> {{ __('Product ontop') }} </label>
                        <v-select v-model="search_form.ontop" :options="[{id:'1',name:'ontop'},{id:'0',name:'Not ontop'}]" :reduce="status => status.id" label="name" />
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label"> {{ __('Product Featured') }} </label>
                        <v-select v-model="search_form.featured" :options="[{id:'1',name:'Featured'},{id:'0',name:'Not Featured'}]" :reduce="status => status.id" label="name" />
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label"> {{ __('Product new arrival') }} </label>
                        <v-select v-model="search_form.new_arrival" :options="[{id:'1',name:'New Arrival'},{id:'0',name:'Not New Arrival'}]" :reduce="status => status.id" label="name" />
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label"> {{ __('Product Trending') }} </label>
                        <v-select v-model="search_form.trending" :options="[{id:'1',name:'Trending'},{id:'0',name:'Not Trending'}]" :reduce="status => status.id" label="name" />
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label"> {{ __('Product Related') }} </label>
                        <v-select v-model="search_form.related" :options="[{id:'1',name:'Related'},{id:'0',name:'Not Related'}]" :reduce="status => status.id" label="name" />
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label"> {{ __('Product Highlighted') }} </label>
                        <v-select v-model="search_form.highlighted" :options="[{id:'1',name:'Highlighted'},{id:'0',name:'Not Highlighted'}]" :reduce="status => status.id" label="name" />
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label"> {{ __('Show Products') }} </label>
                        <v-select v-model="search_form.show_products" :options="[{id:10,name:'10 products'},{id:100,name:'100 products'},{id:500,name:'500 products'}]" :reduce="status => status.id" label="name" />
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label"> {{ __('Sort By') }} </label>
                        <v-select v-model="search_form.order_by" :options="[{id:'sku',name:'sku'},{id:'price',name:'original price'},{id:'discount_price',name:'discount price'},{id:'final_price',name:'final price'},{id:'final_selling_price',name:'final selling price'},{id:'profit_percentage',name:'profit percentage'}]" :reduce="status => status.id" label="name" />
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label"> {{ __('Sort Type') }} </label>
                        <v-select v-model="search_form.order_type" :options="[{id:'asc',name:'Ascending'},{id:'desc',name:'Descinding'}]" :reduce="status => status.id" label="name" />
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label"> {{ __('stock status') }} </label>
                        <v-select v-model="search_form.stock_status" :options="[{id:'in stock',name:'in stock'},{id:'out of stock',name:'out of stock'}]" :reduce="status => status.id" label="name" />
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label"> {{ __('Show Products  With No Translation') }} </label>
                        <v-select v-model="search_form.translated_status" :options="[{id:'1',name:'Only Products With No Translation'},{id:'2',name:'Only Products With Translation'},{id:0,name:'All Products'}]" :reduce="status => status.id" label="name" />
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label"> {{ __('Product Description') }} </label>
                        <v-select v-model="search_form.description_status" :options="[{id:'1',name:'There is a description'},{id:2,name:'There is no description'}]" :reduce="status => status.id" label="name" />
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label"> {{ __('Product Attributes') }} </label>
                        <v-select v-model="search_form.attributes_status" :options="[{id:'1',name:'Products has attributes'},{id:'2',name:'Products has no attributes'}]" :reduce="status => status.id" label="name" />
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label"> {{ __('Product Colors') }} </label>
                        <v-select v-model="search_form.color_status" :options="[{id:'1',name:'Products has colors'},{id:'2',name:'Products has no colors'}]" :reduce="status => status.id" label="name" />
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label"> {{ __('colors') }} </label>
                        <v-select v-model="search_form.color_id" :options="get_color_options()" :reduce="option => option.id" label="languaged_name" />
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label"> {{ __('product back groups') }} </label>
                        <v-select v-model="search_form.product_group_id" :options="product_groups" :reduce="option => option.id" label="translated_name" />
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label"> {{ __('products front group') }} </label>
                        <v-select v-model="search_form.group_status" :options="[{id:'1',name:'Products in group'},{id:'2',name:'Products not in group'}]" :reduce="status => status.id" label="name" />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label"> {{ __('original name') }} (turkish) </label>
                        <input v-model="search_form.name_tr" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label"> {{ __('name') }} (English) </label>
                        <input v-model="search_form.name_en" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label"> {{ __('name') }} (Arabic) </label>
                        <input v-model="search_form.name_ar" class="form-control">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label"> {{ __('original desc') }} (turkish) </label>
                        <textarea v-model="search_form.desc_tr" class="form-control"></textarea>
                    </div>
                </div>

            <div class="accordion accordion-primary" id="accordionPrimaryExample">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingPrimaryOne">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapsePrimaryOne" aria-expanded="true"
                            aria-controls="collapsePrimaryOne">
                            search by price
                        </button>
                    </h2>
                    <div id="collapsePrimaryOne" class="accordion-collapse collapse show"
                        aria-labelledby="headingPrimaryOne"
                        data-bs-parent="#accordionPrimaryExample">
                        <div class="accordion-body">
                            <div class="row">
                    <div class="col-md-4">
                        <label class="form-label"> {{ __('original price') }} </label>
                        <div class="input-group mb-3">
                            <input type="number" min="0" class="form-control"
                               v-model="search_form.price" >
                            <span class="input-group-text"> TRY </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"> {{ __('sale price') }} </label>
                        <div class="input-group mb-3">
                            <input type="number" min="0" class="form-control"
                               v-model="search_form.sale_price" >
                            <span class="input-group-text"> TRY </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">discount percentage (%)</label>
                        <div class="input-group mb-3">
                            <input type="number" min="0" class="form-control"
                               v-model="search_form.discount_percentage_selling_price" >
                            <span class="input-group-text"> % </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"> {{ __('discount price') }} </label>
                        <div class="input-group mb-3">
                            <input type="number" min="0" class="form-control"
                               v-model="search_form.discount_price" >
                            <span class="input-group-text"> TRY </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"> {{ __('final price') }} </label>
                        <div class="input-group mb-3">
                            <input type="number" min="0" class="form-control"
                               v-model="search_form.final_price" >
                            <span class="input-group-text"> TRY </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"> {{ __('final selling price') }} </label>
                        <div class="input-group mb-3">
                            <input type="number" min="0" class="form-control"
                               v-model="search_form.final_selling_price" >
                            <span class="input-group-text"> TRY </span>
                        </div>
                    </div>
                </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingPrimaryTwo">
                        <button class="accordion-button collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#collapsePrimaryTwo"
                            aria-expanded="false" aria-controls="collapsePrimaryTwo">
                            Search by price range
                        </button>
                    </h2>
                    <div id="collapsePrimaryTwo" class="accordion-collapse collapse"
                        aria-labelledby="headingPrimaryTwo"
                        data-bs-parent="#accordionPrimaryExample">
                        <div class="accordion-body">
                           <div class="row">
                            <div class="col-md-4">
                                <label class="form-label"> original price (TRY)</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">Min</span>
                                    <input type="number" min="0" v-model="search_form.price_range[0]" class="form-control" >
                                    <span class="input-group-text" id="basic-addon1">Max</span>
                                    <input type="number" min="0" v-model="search_form.price_range[1]" class="form-control" >
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"> discount price (TRY) </label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">Min</span>
                                    <input type="number" min="0" v-model="search_form.discount_price_range[0]" class="form-control" >
                                    <span class="input-group-text" id="basic-addon1">Max</span>
                                    <input type="number" min="0" v-model="search_form.discount_price_range[1]" class="form-control" >
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"> source discount percentage (%) </label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">Min</span>
                                    <input type="number" min="0" v-model="search_form.discount_percentage_range[0]" class="form-control" >
                                    <span class="input-group-text" id="basic-addon1">Max</span>
                                    <input type="number" min="0" v-model="search_form.discount_percentage_range[1]" class="form-control" >
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"> final price (TRY) </label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">Min</span>
                                    <input type="number" min="0" v-model="search_form.final_price_range[0]" class="form-control" >
                                    <span class="input-group-text" id="basic-addon1">Max</span>
                                    <input type="number" min="0" v-model="search_form.final_price_range[1]" class="form-control" >
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"> discount percentage (%) </label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">Min</span>
                                    <input type="number" min="0" v-model="search_form.discount_percentage_selling_price_range[0]" class="form-control" >
                                    <span class="input-group-text" id="basic-addon1">Max</span>
                                    <input type="number" min="0" v-model="search_form.discount_percentage_selling_price_range[1]" class="form-control" >
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"> final selling price (TRY) </label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">Min</span>
                                    <input type="number" min="0" v-model="search_form.final_selling_price_range[0]" class="form-control" >
                                    <span class="input-group-text" id="basic-addon1">Max</span>
                                    <input type="number" min="0" v-model="search_form.final_selling_price_range[1]" class="form-control" >
                                </div>
                            </div>
                           </div>
                           <button @click="clear_price_ranges()" class="btn btn-warning btn-sm"> Clear price ranges </button>
                        </div>
                    </div>
                </div>

            </div>

                <div class="my-2 btn-list">
                    <button class="btn btn-primary" @click="search()"> {{ __('filter') }} </button>
                    <button class="btn btn-info" @click="reset()"> {{ __('reset') }} </button>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> {{ __('products management') }} </h3>
                </div>
                <div class="card-body">
                    <div class="btn-list">
                    <button @click="form.type='brand'" :disabled="checked.length==0" data-bs-target="#moveModal" data-bs-toggle="modal"
                     class="btn btn-primary"> <i class="ri-share-forward-line"></i>
                      {{ __('move to brand') }}
                    </button>
                    <button @click="form.type='category'" :disabled="checked.length==0" data-bs-target="#moveModal" data-bs-toggle="modal"
                     class="btn btn-primary"> <i class="ri-share-forward-line"></i>
                      {{ __('move to category') }}
                    </button>
                    <button @click="form.type='collection'" :disabled="checked.length==0" data-bs-target="#moveModal" data-bs-toggle="modal"
                     class="btn btn-primary"> <i class="ri-share-forward-line"></i>
                      {{ __('move to collection') }}
                    </button>
                    <button @click="form.type='return_policy'" :disabled="checked.length==0" data-bs-target="#moveModal" data-bs-toggle="modal"
                     class="btn btn-primary"> <i class="ri-share-forward-line"></i>
                      {{ __('move to return policy') }}
                    </button>
                    <button @click="form.type='product_group'" :disabled="checked.length==0" data-bs-target="#moveModal" data-bs-toggle="modal"
                     class="btn btn-primary"> <i class="ri-share-forward-line"></i>
                      {{ __('move to product group') }}
                    </button>
                    <button @click="form.type='model_number'" :disabled="checked.length==0" data-bs-target="#moveModal" data-bs-toggle="modal"
                     class="btn btn-primary"> <i class="ri-code-line"></i>
                      {{ __('product model number') }}
                    </button>
                    <button :disabled="checked.length==0" data-bs-target="#pricingModal" data-bs-toggle="modal"
                     class="btn btn-primary"> <i class="ri-currency-line"></i>
                      {{ __('pricing products') }}
                    </button>
                    <button :disabled="checked.length==0" data-bs-target="#discountModal" data-bs-toggle="modal"
                     class="btn btn-primary"> <i class="ri-currency-line"></i>
                      {{ __('discount products') }}
                    </button>
                    <button :disabled="checked.length<=1" data-bs-target="#groupModal" data-bs-toggle="modal"
                     class="btn btn-primary"> <i class="ri-price-tag-3-line"></i>
                      {{ __('group') }}
                    </button>
                    <button :disabled="checked.length==0" data-bs-target="#attributesModal" data-bs-toggle="modal"
                     class="btn btn-primary"> <i class="ri-price-tag-3-line"></i>
                      {{ __('add attribute') }}
                    </button>
                    <button :disabled="checked.length==0" data-bs-target="#colorsModal" data-bs-toggle="modal"
                     class="btn btn-primary"> <i class="ri-price-tag-3-line"></i>
                      {{ __('add colors and sizes') }}
                    </button>
                    <button @click="set_product_name()" :disabled="checked.length==0" data-bs-target="#renameModal" data-bs-toggle="modal"
                     class="btn btn-primary">
                      {{ __('rename') }}
                    </button>
                    <button @click="set_product_name()" :disabled="checked.length==0" data-bs-target="#translateProductsModal" data-bs-toggle="modal"
                     class="btn btn-primary">
                      {{ __('translate products') }}
                    </button>
                    <button @click="set_product_desc()" :disabled="checked.length==0" data-bs-target="#translateDescModal" data-bs-toggle="modal"
                     class="btn btn-primary">
                      {{ __('translate discription') }}
                    </button>
                    <button @click="export_excel()" :disabled="form.busy || checked.length==0"
                     class="btn btn-success">
                      {{ __('export excel') }}
                    </button>


                    <button v-if="check_permissions(['delete product'])" :disabled="checked.length==0" data-bs-target="#warningModal" data-bs-toggle="modal"
                     class="btn btn-danger">  <i class="ri-delete-bin-line"></i>
                      {{ __('delete') }}
                    </button>
                    </div>

                    <div class="btn-list mt-3">
                    <button @click="update_product_status('status',1)" :disabled="checked.length==0 || form.busy"
                     class="btn btn-success"> <i class="ri-check-line"></i>
                      {{ __('activate') }}
                    </button>
                    <button @click="update_product_status('status',0)" :disabled="checked.length==0 || form.busy"
                     class="btn btn-default"> <i class="ri-close-line"></i>
                      {{ __('deactivate') }}
                    </button>

                    <button @click="update_product_status('featured',1)" :disabled="checked.length==0 || form.busy"
                     class="btn btn-success"> <i class="ri-star-s-fill"></i>
                      {{ __('feactured') }}
                    </button>
                    <button @click="update_product_status('featured',0)" :disabled="checked.length==0 || form.busy"
                     class="btn btn-default"> <i class="ri-star-s-line"></i>
                      {{ __('unfeatured') }}
                    </button>
                    <button @click="update_product_status('new_arrival',1)" :disabled="checked.length==0 || form.busy"
                     class="btn btn-success"> <i class="ri-star-s-fill"></i>
                      {{ __('new arrival') }}
                    </button>
                    <button @click="update_product_status('new_arrival',0)" :disabled="checked.length==0 || form.busy"
                     class="btn btn-default"> <i class="ri-star-s-line"></i>
                      {{ __('not new arrival') }}
                    </button>
                    <button @click="update_product_status('ontop',1)" :disabled="checked.length==0 || form.busy"
                     class="btn btn-success"> <i class="ri-star-s-fill"></i>
                      {{ __('products on top') }}
                    </button>
                    <button @click="update_product_status('ontop',0)" :disabled="checked.length==0 || form.busy"
                     class="btn btn-default"> <i class="ri-star-s-line"></i>
                      {{ __('products not on top') }}
                    </button>

                    </div>
                    <div class="btn-list mt-3">
                    <button @click="update_product_status('related',1)" :disabled="checked.length==0 || form.busy"
                     class="btn btn-success">
                      {{ __('related') }}
                    </button>
                    <button @click="update_product_status('related',0)" :disabled="checked.length==0 || form.busy"
                     class="btn btn-default">
                      {{ __('not related') }}
                    </button>
                    <button @click="update_product_status('trending',1)" :disabled="checked.length==0 || form.busy"
                     class="btn btn-success">
                      {{ __('trending') }}
                    </button>
                    <button @click="update_product_status('trending',0)" :disabled="checked.length==0 || form.busy"
                     class="btn btn-default">
                      {{ __('not trending') }}
                    </button>
                    <button @click="update_product_status('highlighted',1)" :disabled="checked.length==0 || form.busy"
                     class="btn btn-success">
                      {{ __('highlighted') }}
                    </button>
                    <button @click="update_product_status('highlighted',0)" :disabled="checked.length==0 || form.busy"
                     class="btn btn-default">
                      {{ __('not highlighted') }}
                    </button>
                    <button @click="update_product_status('most_sold',1)" :disabled="checked.length==0 || form.busy"
                     class="btn btn-success">
                      {{ __('most_sold') }}
                    </button>
                    <button @click="update_product_status('most_sold',0)" :disabled="checked.length==0 || form.busy"
                     class="btn btn-default">
                      {{ __('not most_sold') }}
                    </button>
                    <button @click="update_product_status('home_forniture',1)" :disabled="checked.length==0 || form.busy"
                     class="btn btn-success">
                      {{ __('home_forniture') }}
                    </button>
                    <button @click="update_product_status('home_forniture',0)" :disabled="checked.length==0 || form.busy"
                     class="btn btn-default">
                      {{ __('not home_forniture') }}
                    </button>


                    </div>
                </div>
            </div>

            {{ searched_products.data }}

            <div class="card card-body">
                <div class="d-flex justify-content-between mb-2">
                    <div>
                        <span class="mx-2"> All Results  : <span class="badge bg-primary"> {{  products.total }} </span> </span>
                        <span class="mx-2"> Shown Products  : <span class="badge bg-primary"> <span v-if="products.data"> {{ products.data.length }} </span> </span> </span>
                        <span class="mx-2"> Selected Products  : <span class="badge bg-primary"> {{ checked.length }} </span> </span>
                    </div>
                    <div class="d-flex justify-content-between" v-if="products.data">
                        <div class="input-group mb-3 mx-2">
                            <span @click="jump_page()" class="input-group-text cursor-pointer" id="basic-addon1">jumb to page</span>
                            <input v-model="form.page" style="width:60px" type="text" class="form-control">
                        </div>
                        <vue-pagination :currentPage="currentPage" :totalPages="products.last_page" :fetchData="search"></vue-pagination>
                    </div>
                </div>
                <div class="table-responsive scrollable clickable" v-if="!search_form.busy && !form.busy">
                    <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col"><input  v-model="checkAll" class="form-check-input" type="checkbox" id="checkboxNoLabel" aria-label="..."></th>
                                    <th scope="col">{{__('image')}}</th>
                                    <th scope="col">{{__('details')}}</th>
                                    <th scope="col">{{__('sizes')}}</th>
                                    <th scope="col">{{__('gallery')}}</th>
                                    <th scope="col">{{__('stock status')}}</th>
                                    <th scope="col">{{__('original price')}}</th>
                                    <th scope="col">{{__('source discount percentage')}} % </th>
                                    <th scope="col">{{__('source discount price')}} </th>
                                    <th scope="col">{{__('sale price')}}</th>
                                    <th scope="col">product original name</th>
                                    <th scope="col">product  name (English)</th>
                                    <th scope="col">product name (العربية)</th>
                                    <th scope="col">Product ID (SKU) </th>
                                    <th scope="col">Product Model Number </th>
                                    <th scope="col">product original desc</th>
                                    <th scope="col">product  desc (English)</th>
                                    <th scope="col">product desc (العربية)</th>
                                    <th scope="col">{{__('username')}}</th>
                                    <th scope="col">{{__('quantity')}}</th>


                                    <th scope="col">{{__('discount percentage selling price')}}</th>
                                    <th scope="col">{{__('packaging fees')}}</th>
                                    <th scope="col">{{__('management fees')}}</th>
                                    <th scope="col">{{__('profit percentage')}}</th>
                                    <th scope="col">{{__('VAT')}}</th>
                                    <th scope="col">{{__('Shipping percentage')}}</th>
                                    <th scope="col">{{__('manual price adjustment')}}</th>
                                    <th scope="col">{{__('final price')}}</th>
                                    <th scope="col">{{__('final selling price')}} (TRY)</th>
                                    <th scope="col">{{__('final selling price')}} (USD)</th>
                                    <th scope="col">{{__('final selling price')}} (SAR)</th>
                                    <th scope="col">{{__('category')}}</th>
                                    <th scope="col">{{__('brand')}}</th>
                                    <th scope="col">{{__('collection')}}</th>
                                    <th scope="col"> company name </th>
                                    <th scope="col"> company product id </th>

                                    <th scope="col"> featured </th>
                                    <th scope="col"> related </th>
                                    <th scope="col"> most sold </th>
                                    <th scope="col"> trending </th>
                                    <th scope="col"> highlighted </th>
                                    <th scope="col"> new arrival </th>
                                    <th scope="col"> on top </th>
                                    <th scope="col">{{__('status')}}</th>
                                    <th scope="col">created date</th>
                                    <th scope="col">last tracked date</th>
                                    <th scope="col">edited date</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="product,index in products.data" :key="index" :class="{'row_checked':checked.includes(product.id)}" @click="check_product(product.id)">
                                    <th scope="row"><input v-model="checked" :value="product.id" class="form-check-input" type="checkbox" id="checkboxNoLabel1" aria-label="..."></th>

                                    <td>
                                        <div class="hover-image">
                                        <img width="120" v-if="product.image" v-lazy="product.image" class="avatar-sm  admin-profile-image" alt="admin-profile-image">
                                        <img width="120" v-else v-lazy="$page.props.auth.default_img" class="avatar-sm  admin-profile-image" alt="admin-profile-image">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            <div class="btn-list">
                                            <div class="btn-group">
                                                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    {{__('details')}}
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-primary">
                                                    <li><a @click="get_details(product.id)" data-bs-target="#detailsModal" data-bs-toggle="modal" href="javascript:void(0);" class="dropdown-item"><i class="ri-eye-line"></i> details</a></li>
                                                    <li><a target="_blank" :href="product.group_link" class="dropdown-item  btn-success"><i class="ri-eye-line"></i> {{ __('group Link') }} </a></li>
                                                    <li><a target="_blank" :href="product.source_link" class="dropdown-item  btn-warning"><i class="ri-eye-line"></i> {{ __('source Link') }} </a></li>
                                                    <li><a target="_blank" :href="'/product/'+product.id+'/'+product.slug" class="dropdown-item  btn-info"><i class="ri-eye-line"></i> {{ __('store Link') }} </a></li>
                                                    <li><a v-if="check_permissions(['edit product'])" target="_blank" :href="route('products.edit',product.id)" class="dropdown-item  btn-primary"><i class="ri-edit-line"></i> {{__('edit')}} </a></li>

                                                    <li><a v-if="check_permissions(['delete product'])" @click="item=product" data-bs-target="#warningModal" data-bs-toggle="modal" href="javascript:void(0);" class="dropdown-item btn btn-danger"><i class="ri-delete-bin-line"></i> {{__('delete')}}  </a></li>
                                                </ul>
                                            </div>
                                            </div>



                                        </div>
                                    </td>
                                    <td>

                                        <span v-if="product.sizes"  style="width:200px;display: block;">
                                            <span v-if="product.sizes.length>0">
                                                <span>
                                                    <span class="fw-bold" v-for="size,index in product.sizes" :key="index"><span v-if="index>0"> / </span>( {{   size.name_tr  }}
                                                    -
                                                    <span v-if="size.pivot.inStock" class="text-success"> In stock </span>
                                                    <span v-else class="text-danger"> Out Of Stock </span>
                                                    )</span>
                                                </span>
                                            </span>
                                        </span>
                                    </td>
                                    <td>
                                         <button @click="selected_product=product"
                                         data-bs-toggle="modal"
                                         data-bs-target="#filesModal"
                                          class="btn btn-primary"> {{ __('view gallery') }} </button>
                                    </td>
                                    <td> {{product.stock_status}} </td>
                                    <td>{{product.price}}  </td>
                                    <td>{{product.discount_percentage}}</td>
                                    <td>{{product.discount_price}}</td>
                                    <td><span data-bs-toggle="tooltip" data-bs-custom-class="tooltip-primary" title="$original price  - $source discount price">{{product.final_price}}
                                        <span><i class="ri-question-line"></i> </span>

                                    </span> </td>
                                    <td><span style="width:200px;display:block">{{product.name_tr}} </span></td>
                                    <td><span style="width:200px;display:block">{{product.name_en}}</span></td>
                                    <td><span style="width:200px;display:block">{{ product.name_ar}}</span></td>
                                    <td> {{ product.sku }} </td>
                                    <td> {{ product.model_number }} </td>
                                    <td>{{ substr(product.desc_tr,40)}}</td>
                                    <td> <span v-html="substr(product.desc_en,100)"></span></td>
                                    <td><span v-html="substr(product.desc_ar,100)"></span></td>
                                    <td>
                                        <span v-if="product.admin"> {{ product.admin.name }} </span>
                                    </td>
                                    <td>{{product.quantity}}</td>

                                    <td>{{product.discount_percentage_selling_price}}</td>
                                    <td>{{product.packaging_shipping_fees}}</td>
                                    <td>{{product.management_fees}}</td>
                                    <td>{{product.profit_percentage}}</td>
                                    <td>{{product.tax_percentage}}</td>
                                    <td>{{product.commercial_percentage}}</td>
                                    <td>{{product.manual_price_adjustment}}</td>
                                    <td>{{product.final_price}}</td>
                                    <td>{{product.final_selling_price}}</td>
                                    <td>{{exchange_price(product.final_selling_price,'USD')}}</td>
                                    <td>{{exchange_price(product.final_selling_price,'SAR')}}</td>
                                    <td> <span v-if="product.category"> {{product.category.translated_name}} </span> </td>
                                    <td> <span v-if="product.bran"> {{product.brand.translated_name}} </span> </td>
                                    <td> <span v-if="product.collection"> {{product.collection.translated_name}} </span> </td>
                                    <td> {{product.company_name}} </td>
                                    <td> {{product.company_product_id}} </td>
                                    <td>
                                        <span v-if="product.featured==1"> yes </span>
                                        <span v-else> no </span>
                                    </td>
                                    <td>
                                        <span v-if="product.related==1"> yes </span>
                                        <span v-else> no </span>
                                    </td>
                                    <td>
                                        <span v-if="product.most_sold==1"> yes </span>
                                        <span v-else> no </span>
                                    </td>
                                    <td>
                                        <span v-if="product.trending==1"> yes </span>
                                        <span v-else> no </span>
                                    </td>
                                    <td>
                                        <span v-if="product.highlighted==1"> yes </span>
                                        <span v-else> no </span>
                                    </td>
                                    <td>
                                        <span v-if="product.new_arrival==1"> yes </span>
                                        <span v-else> no </span>
                                    </td>
                                    <td>
                                        <span v-if="product.ontop==1"> yes </span>
                                        <span v-else> no </span>
                                    </td>
                                    <td>
                                        <span v-if="product.status==0" class="badge bg-warning-transparent"> {{ __('not active') }} </span>
                                        <span v-else  class="badge bg-success-transparent"> {{ __('active') }} </span>
                                    </td>
                                    <td> {{formateDate(product.created_at)}} </td>
                                    <td> {{ product.tracked_at }} </td>
                                    <td> {{formateDate(product.updated_at)}} </td>


                                </tr>

                            </tbody>

                        </table>
                </div>
                <div v-else class="mt-5">
                    <div style="margin: 0 auto;display: block;" class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
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
import vSelect from 'vue-select'
import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import AddAttributes from './AddAttributes.vue';
import AddColors from './AddColors.vue';
import VuePagination from '@/Components/Elements/VuePagination.vue';
import ProductLangtextArea from '@/Components/Elements/ProductLangtextArea.vue';
import { useForm } from '@inertiajs/vue3';

    export default{
        components: { AuthLayout, PageHeader, vSelect, flatPickr, AddAttributes, AddColors,VuePagination, ProductLangtextArea},
        props:{
            options:Array,
            vendors:Array,
            brands:Array,
            categories:Array,
            admins:Array,
            collections:Array,
            return_policies:Array,
            formulas:Array,
            attributes:Array,
            currencies:Array,
            sizes:Array,
            colors:Array,
            product_groups:Array,
        },
        mounted(){
            this.search(this.currentPage)
        },
        data(){
            return {
                config:{
                    wrap: true, // set wrap to true only when using 'input-group'
                    altFormat: 'M j, Y',
                    altInput: true,
                    mode: 'range',
                    dateFormat: 'Y-m-d',
                    // locale: Hindi, // locale for this instance only
                },
                use_form:useForm({

                }),
                form:new Form({
                    id:null,
                    name_tr:null,
                    name_en:null,
                    name_ar:null,
                    desc_tr:null,
                    desc_en:null,
                    desc_ar:null,

                    type:null,
                    category_id:null,
                    brand_id:null,
                    color_id:null,
                    collection_id:null,
                    return_policy_id:null,
                    product_group_id:null,
                    formula_id:null,
                    primary_id:null,
                    products_ids:[],
                    status:0,
                    featured:0,
                    new_arrival:0,
                    statontopus:0,
                    home:0,
                    trending:0,
                    highlight:0,
                    most_sold:0,
                    home_forniture:0,
                    column:null,
                    discount_percentage_selling_price:0,
                    checked:[],
                    attributes_ids:[],
                    colors_ids:[],
                    sizes_ids:[],
                    has_brand:1,
                    has_vendor:1,
                    has_sku:1,
                    has_attribute:1,
                    page:null,
                    attribute_update_type:'add',
                    color_update_type:'add',
                    group_update_type:'add',
                    model_number:null
                }),
                search_form:new Form({
                    id:null,
                    name_en:null,
                    name_ar:null,
                    name_tr:null,
                    desc_tr:null,
                    category_id:null,
                    brand_id:null,
                    collection_id:null,
                    product_group_id:null,
                    company_name:null,
                    source_link:null,
                    sku:null,
                    product_sku:null,
                    price:null,
                    sale_price:null,
                    discount_price:null,
                    discount_percentage_selling_price:null,
                    final_price:null,
                    final_selling_price:null,
                    range:null,
                    order_by:null,
                    order_type:null,
                    show_products:null,
                    price_range:[0,10000000],
                    discount_price_range:[0,10000000],
                    discount_percentage_range:[0,100],
                    final_price_range:[0,10000000],
                    final_selling_price_range:[0,10000000],
                    discount_percentage_selling_price_range:[0,100],
                    option_id:null,
                    color_status:null,
                    group_status:null,
                    color_id:null,
                    // attributes_status:null,
                }),
                products:{},
                searched_products:{},
                checked:[],
                details:null,
                currentPage: 1,
                selected_product:null,
                totalCount:null

            }
        },
        methods:{
            clear_price_ranges(){
                    this.search_form.price_range=[0,10000000]
                    this.search_form.discount_price_range=[0,10000000]
                    this.search_form.discount_percentage_range=[0,100]
                    this.search_form.final_price_range=[0,10000000]
                    this.search_form.final_selling_price_range=[0,10000000]
                    this.search_form.discount_percentage_selling_price_range=[0,100]
            },
           get_options(){
            let options = []
            this.options.forEach((el)=>{
                el['languaged_name'] = el.name.en + '/' + el.name.ar
                options.push(el)
            })
            return options
           },
           get_color_options(){
            let options = []
            this.colors.forEach((el)=>{
                el['languaged_name'] = el.name.en + '/' + el.name.ar
                options.push(el)
            })
            return options
           },
           search(page=1){
            this.checked=[]
            this.form.checked=[]
                this.search_form.post('/admin/api/search_products?page='+page)
                .then((resp)=>{
                    this.products=resp.data.products
                    this.totalCount=resp.data.totalCount
                })
           },
           reset(){
            this.search_form.reset()
            this.search(this.products.current_page)
           },

           move(){
            this.form.products_ids=this.checked
                this.form.post('/admin/api/move_to')
                    .then((resp)=>{
                        this.search(this.products.current_page)
                        $('#moveModal').modal('hide')
                        this.form.products_ids=[]
                        this.checked=[]
                    })

           },
           remove_collection(){
            this.form.products_ids=this.checked
            this.form.post('/admin/api/remove_collections')
                    .then((resp)=>{
                        this.search(this.products.current_page)
                        $('#moveModal').modal('hide')
                        this.form.products_ids=[]
                        this.checked=[]
                    })
           },
           multi_destroy(){
            axios.post('/admin/api/multi_destroy_products',{checked:this.checked})
                 .then((resp)=>{
                    this.checked.forEach((el)=>{

                        $('#warningModal').modal('hide')
                        this.checked=[]
                    })
                 })
                 this.search(this.products.current_page)
           },
           set_product_name(){
           let product = this.products.data.find((e)=>e.id==this.checked[0])
           this.form.name_tr=product.name_tr
           this.form.name_en=product.name_en
           this.form.name_ar=product.name_ar
           },
           set_product_desc(){
           let product = this.products.data.find((e)=>e.id==this.checked[0])
           this.form.desc_tr=product.desc_tr
           this.form.desc_en=product.desc_en
           this.form.desc_ar=product.desc_ar
           },
           update_product_status(column,value){
            this.form.column=column
            this.form[column]=value
            this.form.checked=this.checked
            this.form.post('/admin/api/update_status')
                .then((resp)=>{
                    this.search(this.products.current_page)
                })
           },
           update_products_formula(){
            this.form.checked=this.checked
            this.form.post('/admin/api/update_products_formula')
                 .then((resp)=>{
                    $('#pricingModal').modal('hide')
                    this.search(this.products.current_page)
                 })
           },
           update_products_group(type){
            this.form.checked=this.checked
            this.form.group_update_type=type
            this.form.post('/admin/api/update_products_group')
                 .then((resp)=>{
                    $('#groupModal').modal('hide')
                    this.search(this.products.current_page)
                 })
           },
           update_products_discount(){
            this.form.checked=this.checked
            this.form.post('/admin/api/update_products_discount')
                 .then((resp)=>{
                    $('#discountModal').modal('hide')
                    this.search(this.products.current_page)
                 })
           },
           update_products_attributes(type){
            this.form.checked=this.checked
            this.form.attribute_update_type=type
            this.form.post('/admin/api/update_products_attributes')
                 .then((resp)=>{
                    $('#attributesModal').modal('hide')
                    this.search(this.products.current_page)
                    this.form.attributes_ids=[]
                 })
           },
           export_excel(){
            this.form.checked=this.checked
            this.form.post('/admin/add_ids_to_session')
                .then((resp)=>{
                    window.location.href='/admin/export_products_excel'
                })

           },
           update_products_colors(type){
            this.form.checked=this.checked
            this.form.color_update_type=type
            this.form.post('/admin/api/update_products_colors')
                 .then((resp)=>{
                    $('#colorsModal').modal('hide')
                    this.search(this.products.current_page)
                    this.form.colors_ids=[]
                    this.form.sizes_ids=[]
                 })
           },
           jump_page(){
            if(this.form.page!=null){
                this.currentPage=parseInt(this.form.page)
                this.search(this.currentPage)
                this.form.page=null
            }
           },
           update_products_rename(){
            this.form.checked=this.checked
            this.form.post('/admin/api/update_products_rename')
                 .then((resp)=>{
                    $('#renameModal').modal('hide')
                    this.search(this.products.current_page)
                 })
           },
           update_translate_products(){
            this.form.checked=this.checked
            this.form.post('/admin/api/update_translate_products')
                 .then((resp)=>{
                    $('#translateProductsModal').modal('hide')
                    this.search(this.products.current_page)
                 })
           },
           update_translate_description(){
            this.form.checked=this.checked
            this.form.post('/admin/api/update_translate_description')
                 .then((resp)=>{
                    $('#translateDescModal').modal('hide')
                    this.search(this.products.current_page)
                 })
           },
           check_product(id){
            if(this.checked.includes(id)){
                let index = this.checked.indexOf(id)
                this.checked.splice(index,1)
            }else{
                this.checked.push(id)
            }
           },
           get_details(id){
            axios.get('/admin/api/get_products_details/'+id)
                 .then((resp)=>{
                    this.details=resp.data
                 })
           },
           get_checked_products(){
            if(this.checked.length>0 && this.products.total){
               let filtered = this.products.data.filter(product => this.checked.includes(product.id));
               let returned=[]
               filtered.forEach((el)=>{
                el.translated_name=el.name_tr
                returned.push(el)
               })
               return returned
            }else{
               return []
            }

           },


        },
        computed: {
            checkAll: {
            get: function () {
                return this.products.data ? this.checked.length == this.products.data.length : false;
            },
            set: function (value) {
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
.img-box{height: 250px;padding: 10px;border:2px solid rgb(160, 160, 160);
    cursor:pointer;border-radius:10px;margin-bottom:5px}
.img-box img{height: 100%;}
.img-box.selected{border-color:#845adf}
</style>
