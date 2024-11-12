<template>
    <div>
        <auth-layout>
        <div class="container-fluid">
        <page-header></page-header>
        <div class="row justify-content-center">
         <div class="">
        <div class="card custom-card">

            <div class="card-body">
                <div v-if="scrap_type=='web'">
                        <scrap :form="form"></scrap>
                    </div>
            <form @submit.prevent="create">
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
                                {{ __("create") }}
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
import Scrap from './Scrap.vue';
export default {
    components: {
        AuthLayout,
        PageHeader,
        useForm,
        FileManager,
        ProductDetails,
        ProductSpecifications,
        ProductAttributes,
        ProductDiscount,
        ProductGallery,
        Scrap,
        ProductPricing
    },
    props: {
        errors: Object,
        categories: Array,
        attributes: Array,
        brands: Array,
        currencies: Array,
        scrap_type:String
    },
    data() {
        return {
            form: useForm({
                name_en:null,
                name_ar:null,
                desc_en:null,
                desc_ar:null,
                category_id: null,
                currency_id: null,
                brand_id: null,
                sku: null,
                pricing_type:'in_house',
                price: null,
                sale_price: null,
                discount_percentage_selling_price: null,
                packaging_shipping_fees: null,
                management_fees: null,
                profit_percentage: null,
                tax_percentage: null,
                commercial_percentage: null,
                manual_price_adjustment: null,
                final_price: null,
                final_selling_price: null,
                quantity: null,
                tax_id: null,
                image: null,
                shipping: 0,
                dimension_unit: null,
                weight_unit: null,
                length: null,
                width: null,
                height: null,
                weight: null,
                discount_price: null,
                start_at: null,
                end_at: null,
                attributes_ids: [],
                status: true,
                files: [{ id: null, image: null }],
            }),
            taxs: [
                { id: 1, translated_name: "tax1" },
                { id: 2, translated_name: "tax2" },
            ],
        };
    },
    methods: {
        create() {
            this.form.post(route("products.store"));
        },

  }

}



</script>
