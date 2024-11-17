<template>
    <div class="tab-pane text-muted show active"  role="tabpanel">


        <div class="tab-pane-box">
            <div class="row tab-pane colored-tab-pane">
            <span class="tab-title"> {{ __('pricing') }} </span>


        <div class="mb-3 col-lg-12">
            <label class="form-label"> {{ __('pricing type') }}  </label>
            <select @change="set_currency()" class="form-control" v-model="form.pricing_type">
                <option value="in_house"> In House </option>
                <option value="out_house"> Out House </option>
            </select>
        </div>

        <div class="mb-3 col-lg-6">
            <label class="form-label"> {{ __('original price') }}  </label>
           <div class="input-group ">
            <span class="input-group-text" id="basic-addon1">
                <span v-if="main_currency() && form.pricing_type=='out_house'" class="fw-bold fs-14"> {{ main_currency().prefix }} </span>
                <span v-else class="fw-bold fs-14"> {{ __('SAR')}} </span>
            </span>
            <input type="number" min="0" step="0.1" :placeholder="__('price')" class="form-control" v-model="form.price" :class="{ 'is-invalid': errors.name } ">
            </div>
            <div class="text-danger" v-html="errors.price" />

        </div>
        <div class="mb-3 col-lg-6">
            <label class="form-label"> {{ __('sale price') }}  </label>
           <div class="input-group ">
            <span class="input-group-text" id="basic-addon1">
                <span v-if="main_currency() && form.pricing_type=='out_house'" class="fw-bold fs-14"> {{ main_currency().prefix }} </span>
                <span v-else class="fw-bold fs-14"> {{ __('SAR')}} </span>
            </span>
            <input type="number" min="0" step="0.1" :placeholder="__('sale price')" class="form-control" v-model="form.sale_price" :class="{ 'is-invalid': errors.name } ">
            </div>
            <div class="text-danger" v-html="errors.sale_price" />
        </div>
        <div class="mb-3 col-lg-6" v-if="form.pricing_type=='out_house'">
            <label class="form-label"> {{ __('Discount Percentage On Sale Price') }}  </label>
           <div class="input-group ">
            <span class="input-group-text" id="basic-addon1"><span class="fw-bold fs-14"> % </span></span>
            <input type="number" min="0" step="0.1" :placeholder="__('Discount Percentage On Sale Price')" class="form-control" v-model="form.discount_percentage_selling_price" :class="{ 'is-invalid': errors.name } ">
            </div>
            <div class="text-danger" v-html="errors.discount_percentage_selling_price" />
        </div>
        <div class="mb-3 col-lg-6" v-if="form.pricing_type=='out_house'">
            <label class="form-label"> {{ __('Packaging Fees') }}  </label>
           <div class="input-group ">
            <span class="input-group-text" id="basic-addon1"><span v-if="main_currency()" class="fw-bold fs-14"> % </span></span>
            <input type="number" min="0" step="0.1" :placeholder="__('Packaging Fees')" class="form-control" v-model="form.packaging_shipping_fees" :class="{ 'is-invalid': errors.name } ">
            </div>
            <div class="text-danger" v-html="errors.packaging_shipping_fees" />
        </div>
        <div class="mb-3 col-lg-6" v-if="form.pricing_type=='out_house'">
            <label class="form-label"> {{ __('Management Fees') }}  </label>
           <div class="input-group ">
            <span class="input-group-text" id="basic-addon1"><span v-if="main_currency()" class="fw-bold fs-14"> %</span></span>
            <input type="number" min="0" step="0.1" :placeholder="__('Management Fees')" class="form-control" v-model="form.management_fees" :class="{ 'is-invalid': errors.name } ">
            </div>
            <div class="text-danger" v-html="errors.management_fees" />
        </div>
        <div class="mb-3 col-lg-6" v-if="form.pricing_type=='out_house'">
            <label class="form-label"> {{ __('Profit Percentage') }}  </label>
           <div class="input-group ">
            <span class="input-group-text" id="basic-addon1"><span class="fw-bold fs-14"> % </span></span>
            <input type="number" min="0" step="0.1" :placeholder="__('Profit Percentage')" class="form-control" v-model="form.profit_percentage" :class="{ 'is-invalid': errors.name } ">
            </div>
            <div class="text-danger" v-html="errors.profit_percentage" />
        </div>
        <div class="mb-3 col-lg-6" v-if="form.pricing_type=='out_house'">
            <label class="form-label"> {{ __('Tax Percentage') }} (VAT) </label>
           <div class="input-group ">
            <span class="input-group-text" id="basic-addon1"><span class="fw-bold fs-14"> % </span></span>
            <input type="number" min="0" step="0.1" :placeholder="__('Tax Percentage')" class="form-control" v-model="form.tax_percentage" :class="{ 'is-invalid': errors.name } ">
            </div>
            <div class="text-danger" v-html="errors.tax_percentage" />
        </div>
        <div class="mb-3 col-lg-6" v-if="form.pricing_type=='out_house'">
            <label class="form-label"> {{ __('Shipping Percentage') }}  </label>
           <div class="input-group ">
            <span class="input-group-text" id="basic-addon1"><span class="fw-bold fs-14"> % </span></span>
            <input type="number" min="0" step="0.1" :placeholder="__('Shipping Percentage')" class="form-control" v-model="form.commercial_percentage" :class="{ 'is-invalid': errors.name } ">
            </div>
            <div class="text-danger" v-html="errors.commercial_percentage" />
        </div>
        <div class="mb-3 col-lg-6" v-if="form.pricing_type=='out_house'">
            <label class="form-label"> {{ __('Manual Price Adjustment') }}  </label>
           <div class="input-group ">
            <span class="input-group-text" id="basic-addon1"><span v-if="main_currency()" class="fw-bold fs-14"> {{ main_currency().prefix }} </span></span>
            <input type="number" min="0" step="0.1" :placeholder="__('Manual Price Adjustment')" class="form-control" v-model="form.manual_price_adjustment" :class="{ 'is-invalid': errors.name } ">
            </div>
            <div class="text-danger" v-html="errors.manual_price_adjustment" />
        </div>
        <div class="col-12"  v-if="form.pricing_type=='out_house'">
            <label class="form-label"> {{ __('Final Price') }} </label>
            <currencies-exchange :value="form.final_price=final_price()" :currencies="currencies"></currencies-exchange>
        </div>
        <div class="col-12"  v-if="form.pricing_type=='out_house'">
            <label class="form-label"> {{ __('Final Selling Price') }} </label>
            <currencies-exchange :value="form.final_selling_price=final_selling_price()" :currencies="currencies"></currencies-exchange>
        </div>
        </div>
        </div>




        </div>
</template>

<script>
import CurrenciesExchange from './CurrenciesExchange.vue'
export default{
  components: { CurrenciesExchange },
  props:{
        form:Object,
        errors:Object,
        currencies:Array,
    },
    methods:{
        main_currency(){


        },
        set_currency(){
            let pricing_type = this.form.pricing_type
            if(pricing_type  == 'in_house'){
                this.form.currency_id = 2
            }else{
                this.form.currency_id = 3
            }
        },
        final_price(){
            let original_price=this.form.price!=null?parseFloat(this.form.price):0
            if(original_price){

                return original_price +
                (this.form.packaging_shipping_fees!=null?parseFloat(this.form.packaging_shipping_fees):0) +
                (this.form.management_fees!=null?parseFloat(this.form.management_fees):0)+
                (this.form.manual_price_adjustment!=null?parseFloat(this.form.manual_price_adjustment):0)+
                (this.form.profit_percentage!=null?parseFloat(this.form.profit_percentage):0*original_price/100)+
                (this.form.tax_percentage!=null?parseFloat(this.form.tax_percentage):0*original_price/100)+
                (this.form.commercial_percentage!=null?parseFloat(this.form.commercial_percentage):0*original_price/100)
            }else{
                return 0
            }

        },
        final_selling_price(){
            let sale_price = this.form.sale_price ? parseFloat(this.form.sale_price) : 0;

                if (sale_price) {
                    const start_price = sale_price;

                    const parseOrDefault = (value) => value ? parseFloat(value) : 0;

                    let packaging_shipping_fees = parseOrDefault(this.form.packaging_shipping_fees) * start_price / 100;
                    let management_fees = parseOrDefault(this.form.management_fees) * start_price / 100;
                    let profit_percentage = parseOrDefault(this.form.profit_percentage) * start_price / 100;
                    let commercial_percentage = parseOrDefault(this.form.commercial_percentage) * start_price / 100;
                    let manual_price_adjustment = parseOrDefault(this.form.manual_price_adjustment);

                    let final_before_vat = sale_price + packaging_shipping_fees + management_fees + profit_percentage + commercial_percentage + manual_price_adjustment;

                    let tax_percentage = parseOrDefault(this.form.tax_percentage);
                    let final_after_vat = final_before_vat + (final_before_vat * tax_percentage / 100);

                    let discount_percentage = parseOrDefault(this.form.discount_percentage_selling_price);
                    let final_price = final_after_vat - (final_after_vat * discount_percentage / 100);

                    return final_price;
                } else {
                    return 0;
                }

        },
    }
}
</script>
