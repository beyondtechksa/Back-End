<template>
    <div class="tab-pane text-muted show active" id="maintab1" role="tabpanel">
        <div class="tab-pane-box">
            <div class="row tab-pane colored-tab-pane">
            <span class="tab-title"> {{ __('name & description') }} </span>
            <div class="mb-3">
                <div class="row">
                    <div class="col-lg-6">
                        <label class="form-label"> name (en) </label>
                        <div class="input-group mb-1">
                            <span class="input-group-text" id="basic-addon1">
                                <!-- <img v-lazy="locale.logo" width="30" height="30"> -->
                            </span>
                            <input v-model="form.name_en" type="text" class="form-control" placeholder="name (en)">
                        </div>
                        <div class="text-danger" v-html="errors.name_en" />
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label"> name (ar) </label>
                        <div class="input-group mb-1">
                            <span class="input-group-text" id="basic-addon1">
                                <!-- <img v-lazy="locale.logo" width="30" height="30"> -->
                            </span>
                            <input v-model="form.name_ar" type="text" class="form-control" placeholder="name (ar)">
                        </div>
                        <div class="text-danger" v-html="errors.name_ar" />
                    </div>
                </div>
            </div>
        <div class="mb-3">
            <!-- <langtext-area :label="__('product description')" :form="form" :quillEditor="true"></langtext-area> -->
            <div class="row">
                <div class="col-md-6">
                    <text-area :label="__('product description')" :form="form" model="desc_en"></text-area>
                    <div class="text-danger" v-html="errors.desc_en" />
                </div>
                <div class="col-md-6">
                    <text-area :label="__('product description')" :form="form" model="desc_ar"></text-area>
                    <div class="text-danger" v-html="errors.desc_ar" />
                </div>
            </div>
        </div>
        </div>
        </div>

        <div class="tab-pane-box">
            <div class="row tab-pane colored-tab-pane">
            <span class="tab-title"> {{ __('detatils') }} </span>
        <div class="mb-3 col-lg-4">
            <label class="form-label"> {{ __('category') }} </label>
            <v-select v-model="form.category_id" :options="get_categories_with_parents(categories)" :reduce="category => category.id" label="parents_name" />
            <div class="text-danger" v-html="errors.category_id" />
        </div>
        <div class="mb-3 col-lg-4">
            <label class="form-label"> {{ __('brand') }} </label>
            <select class="form-control" v-model="form.brand_id">
                <option :value="null"> {{ __('select category') }} </option>
                <option v-for="brand,index in brands" :key="index" :value="brand.id"> {{ brand.translated_name }} </option>
            </select>
            <div class="text-danger" v-html="errors.brand_id" />
        </div>
        <div class="mb-3 col-lg-4">
            <label class="form-label"> {{ __('sku') }} </label>
            <input :placeholder="__('sku')" class="form-control" v-model="form.sku" :class="{ 'is-invalid': errors.name } ">
            <div class="text-danger" v-html="errors.sku" />
        </div>

        <div class="mb-3 col-lg-4">
            <label class="form-label"> {{ __('tax type') }} </label>
            <select class="form-control" v-model="form.tax_id">
                <option :value="null"> {{ __('select tax type') }} </option>
                <option v-for="tax,index in taxs" :key="index" :value="tax.id"> {{ tax.translated_name }} </option>
            </select>
        </div>
        <div class="mb-3 col-lg-4">
            <label class="form-label"> {{ __('quantity') }} </label>
            <input type="number" step="0.1" :placeholder="__('quantity')" class="form-control" v-model="form.quantity" :class="{ 'is-invalid': errors.name } ">
            <div class="text-danger" v-html="errors.quantity" />
        </div>
        <div class="mb-3 col-lg-4">
            <label class="form-label"> {{ __('shipping status') }} </label>
            <select class="form-control" v-model="form.shipping">
                <option :value="null"> {{ __('select') }} </option>
                <option :value="1"> {{ __('yes') }} </option>
                <option :value="0"> {{ __('no') }} </option>
            </select>
        </div>

        </div>
        </div>

        <div class="tab-pane-box" v-if="form.shipping">
            <div class="row tab-pane colored-tab-pane">
            <span class="tab-title"> {{ __('shipping') }} </span>
        <div class="mb-3 col-lg-3">
            <label class="form-label"> {{ __('diminsions unit') }} </label>
            <select class="form-control" v-model="form.dimension_unit">
                <option :value="null"> {{ __('select diminsions unit') }} </option>
                <option value="centimeter"> {{ __('centimeter') }} </option>
                <option value="inch"> {{ __('inch') }} </option>
            </select>
            <div class="text-danger" v-html="errors.dimension_unit" />
        </div>
        <div class="mb-3 col-lg-3">
            <label class="form-label"> {{ __('length') }} </label>
            <input type="number" step="0.1" :placeholder="__('length')" class="form-control" v-model="form.length" :class="{ 'is-invalid': errors.length } ">
            <div class="text-danger" v-html="errors.length" />
        </div>
        <div class="mb-3 col-lg-3">
            <label class="form-label"> {{ __('width') }} </label>
            <input type="number" step="0.1" :placeholder="__('width')" class="form-control" v-model="form.width" :class="{ 'is-invalid': errors.width } ">
            <div class="text-danger" v-html="errors.width" />
        </div>
        <div class="mb-3 col-lg-3">
            <label class="form-label"> {{ __('height') }} </label>
            <input type="number" step="0.1" :placeholder="__('height')" class="form-control" v-model="form.height" :class="{ 'is-invalid': errors.height } ">
            <div class="text-danger" v-html="errors.height" />
        </div>
        <div class="mb-3 col-lg-6">
            <label class="form-label"> {{ __('weight unit') }} </label>
            <select class="form-control" v-model="form.weight_unit">
                <option :value="null"> {{ __('select weight unit') }} </option>
                <option value="kilogram"> {{ __('kilogram') }} </option>
                <option value="kilogram"> {{ __('kilogram') }} </option>
                <option value="kilogram"> {{ __('kilogram') }} </option>
            </select>
            <div class="text-danger" v-html="errors.weight_unit" />
        </div>
        <div class="mb-3 col-lg-6">
            <label class="form-label"> {{ __('weight') }} </label>
            <input type="number" step="0.1" :placeholder="__('weight')" class="form-control" v-model="form.weight" :class="{ 'is-invalid': errors.weight } ">
            <div class="text-danger" v-html="errors.weight" />
        </div>
        </div>
        </div>

        <div class="mb-3">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch"
                    id="flexSwitchCheckChecked" v-model="form.status" :checked="form.status==1">
                <label class="form-check-label cursor-pointer" for="flexSwitchCheckChecked">{{__('active status')}}</label>
            </div>
            <div class="text-danger" v-html="errors.status" />
        </div>
        </div>
</template>


<script>
import LangtextInput from '@/Components/Elements/LangtextInput.vue'
import LangtextArea from '@/Components/Elements/LangtextArea.vue'
import CurrenciesExchange from './CurrenciesExchange.vue'
import vSelect from 'vue-select'
import TextArea from '@/Components/Elements/TextArea.vue'


    export default{
  components: { LangtextInput, LangtextArea, CurrenciesExchange, vSelect, TextArea },
        props:{
            form:Object,
            errors:Object,
            categories:Array,
            brands:Array,
            taxs:Array,
            currencies:Array,
        },
        data(){
            return {
                options:{
                modules: {
                toolbar: [['bold', 'italic', 'underline'],
                ['emoji'],
                [{ 'color': []},{ 'background': [] }],
                [{ 'align': [] }],
                [{ 'font': [] }],
                [{'size':[]}]
                ],

                }
            },
            }
        },

    }
</script>


<style>
@import "vue-select/dist/vue-select.css";
</style>
