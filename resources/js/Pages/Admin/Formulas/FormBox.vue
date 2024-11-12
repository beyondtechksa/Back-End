<template>
    <file-manager ref="file" @fileUploaded="fileUploaded()"></file-manager>
    <div>

        <div class="row">
        <div class="mb-3 col-md-6">
            <label class="form-label"> {{ __('Formula Name') }} </label>
            <input v-model="form.name" :placeholder="__('Formula name')" type="text" class="form-control" :class="{'is-invalid':errors.name}" >
            <div class="text-danger" v-html="errors.name" />
        </div>
        <div class="mb-3 col-md-6">
            <label class="form-label"> {{ __('Formula note') }} </label>
            <input v-model="form.note" :placeholder="__('Formula note')" type="text" class="form-control" :class="{'is-invalid':errors.note}" >
            <div class="text-danger" v-html="errors.note" />
        </div>

        <div class="mb-3 col-lg-6">
            <label class="form-label"> {{ __('VAT') }}  </label>
           <div class="input-group ">
            <span class="input-group-text" id="basic-addon1"><span class="fw-bold fs-14"> % </span></span>
            <input type="number" min="0" step="0.1" :placeholder="__('VAT')" class="form-control" v-model="form.discount_percentage_selling_price" :class="{ 'is-invalid': errors.name } ">
            </div>
            <div class="text-danger" v-html="errors.discount_percentage_selling_price" />
        </div>
        <div class="mb-3 col-lg-6">
            <label class="form-label"> {{ __('Packaging Fees') }}  </label>
           <div class="input-group ">
            <span class="input-group-text" id="basic-addon1"><span v-if="main_currency()" class="fw-bold fs-14"> % </span></span>
            <input type="number" min="0" step="0.1" :placeholder="__('Packaging&Shipping Fees')" class="form-control" v-model="form.packaging_shipping_fees" :class="{ 'is-invalid': errors.name } ">
            </div>
            <div class="text-danger" v-html="errors.packaging_shipping_fees" />
        </div>
        <div class="mb-3 col-lg-6">
            <label class="form-label"> {{ __('Management Fees') }}  </label>
           <div class="input-group ">
            <span class="input-group-text" id="basic-addon1"><span v-if="main_currency()" class="fw-bold fs-14"> % </span></span>
            <input type="number" min="0" step="0.1" :placeholder="__('Management Fees')" class="form-control" v-model="form.management_fees" :class="{ 'is-invalid': errors.name } ">
            </div>
            <div class="text-danger" v-html="errors.management_fees" />
        </div>
        <div class="mb-3 col-lg-6">
            <label class="form-label"> {{ __('Profit Percentage') }}  </label>
           <div class="input-group ">
            <span class="input-group-text" id="basic-addon1"><span class="fw-bold fs-14"> % </span></span>
            <input type="number" min="0" step="0.1" :placeholder="__('Profit Percentage')" class="form-control" v-model="form.profit_percentage" :class="{ 'is-invalid': errors.name } ">
            </div>
            <div class="text-danger" v-html="errors.profit_percentage" />
        </div>

        <div class="mb-3 col-lg-6">
            <label class="form-label"> {{ __('Shipping Percentage') }}  </label>
           <div class="input-group ">
            <span class="input-group-text" id="basic-addon1"><span class="fw-bold fs-14"> % </span></span>
            <input type="number" min="0" step="0.1" :placeholder="__('Shipping Percentage')" class="form-control" v-model="form.commercial_percentage" :class="{ 'is-invalid': errors.name } ">
            </div>
            <div class="text-danger" v-html="errors.commercial_percentage" />
        </div>
        <div class="mb-3 col-lg-6">
            <label class="form-label"> {{ __('Manual Price Adjustment') }}  </label>
           <div class="input-group ">
            <span class="input-group-text" id="basic-addon1"><span v-if="main_currency()" class="fw-bold fs-14"> {{ main_currency().prefix }} </span></span>
            <input type="number" min="0" step="0.1" :placeholder="__('Manual Price Adjustment')" class="form-control" v-model="form.manual_price_adjustment" :class="{ 'is-invalid': errors.name } ">
            </div>
            <div class="text-danger" v-html="errors.manual_price_adjustment" />
        </div>


        </div>



    </div>
</template>


<script>
import LangtextInput from '@/Components/Elements/LangtextInput.vue';
import LangtextArea from '@/Components/Elements/LangtextArea.vue';
import FileManager from '@/Components/FileManager.vue';
    export default {
        components:{LangtextInput, LangtextArea, FileManager},
        props:{
            form:Object,
            errors:Object,
            currencies:Array
        },
        data(){
            return {

            }
        },
        methods:{
            main_currency(){
            let main = this.currencies.find((e)=>e.main==1)
            return main
        },
        }
    }

</script>
