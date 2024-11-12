<template>
    <div>
        <auth-layout>
        <div class="container-fluid">
        <page-header></page-header>
        <div class="row justify-content-center">
         <div class="">
        <div class="card custom-card">

            <div class="card-body">
                <form @submit.prevent="edit">

                <div class=" px-0 mx-0 mb-3">
                    <product-details :form="form" :errors="errors" :categories="categories" :brands="brands" :taxs="taxs" :currencies="currencies"></product-details>
                    <product-pricing :form="form" :errors="errors" :currencies="currencies"></product-pricing>
                    <!-- <product-discount :form="form" :errors="errors" :currencies="currencies"></product-discount> -->
                    <product-specifications :form="form"></product-specifications>
                    <product-attributes :form="form" :errors="errors" :attributes="attributes"></product-attributes>
                    <product-gallery :form="form" :errors="errors"></product-gallery>

                </div>

                                    <div class="mb-3">
                                        <div class="hstack gap-2">
                                            <button
                                                :disabled="form.processing"
                                                type="submit"
                                                class="btn btn-primary btn-md"
                                            >
                                                <i
                                                    class="ri-save-line fs-16 align-middle d-inline-block"
                                                ></i>
                                                {{ __("update") }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </auth-layout>
    </div>
</template>

<script>
import AuthLayout from "../Layouts/AuthLayout.vue";
import PageHeader from "@/Components/PageHeader.vue";
import { useForm } from "@inertiajs/vue3";
import FileManager from "@/Components/FileManager.vue";
import ProductDetails from "./ProductDetails.vue";
import ProductSpecifications from "./ProductSpecifications.vue";
import ProductAttributes from "./ProductAttributes.vue";
import ProductDiscount from "./ProductDiscount.vue";
import ProductPricing from "./ProductPricing.vue";
import ProductGallery from "./ProductGallery.vue";
export default {
  components: { AuthLayout, PageHeader, useForm, FileManager, ProductDetails, ProductSpecifications, ProductAttributes, ProductDiscount, ProductGallery, ProductPricing},
  props:{
    product:Object,
    errors:Object,
    categories:Array,
    attributes:Array,
    product_attributes:Array,
    brands:Array,
    files:Array,
    currencies:Array,
  },
  data(){
    return {
        form:useForm({
            name_en:this.product.name_en,
            name_ar:this.product.name_ar,
            desc_en:this.product.desc_en,
            desc_ar:this.product.desc_ar,
            category_id:this.product.category_id,
            brand_id:this.product.brand_id,
            sku:this.product.sku,
            price: this.product.price,
            sale_price: this.product.sale_price,
            discount_percentage_selling_price: this.product.discount_percentage_selling_price,
            packaging_shipping_fees: this.product.packaging_shipping_fees,
            management_fees: this.product.management_fees,
            profit_percentage: this.product.profit_percentage,
            tax_percentage: this.product.tax_percentage,
            commercial_percentage: this.product.commercial_percentage,
            manual_price_adjustment: this.product.manual_price_adjustment,
            final_price: this.product.final_price,
            final_selling_price: this.product.final_selling_price,
            quantity:this.product.quantity,
            tax_id:this.product.tax_id,
            image:this.product.image,
            shipping:1,
            dimension_unit:this.product.dimension_unit,
            weight_unit:this.product.weight_unit,
            length:this.product.length,
            width:this.product.width,
            height:this.product.height,
            weight:this.product.weight,
            attributes_ids:this.get_product_attributes(),
            discount_price: this.product.discount_price,
            start_at: this.product.start_at,
            end_at: this.product.end_at,
            status:true,
            files:this.files
        }),
        taxs:[
            {id:1,translated_name:'tax1'},
            {id:2,translated_name:'tax2'},
        ]
    }
  },
  methods:{
        edit(){
            this.form.put(route('products.update',this.product.id))
        },
        check_src(){
        if(this.form.image!=null && this.form.image!=''){
            return this.form.image
        }else{
        return this.$page.props.auth.default_img
        }
        },
        fileUploaded(){
           this.form.image=this.$refs.file.selected_media.path
        },

        get_product_attributes(){
            let product = this.product
            let attrs = product.product_attributes
            let attributes=[];
            attrs.forEach((el)=>{
                let single_attribute=el.attribute
                single_attribute['options']=el.product_options
                attributes.push(single_attribute)
            })

            return attributes
        }

  }

}



</script>
