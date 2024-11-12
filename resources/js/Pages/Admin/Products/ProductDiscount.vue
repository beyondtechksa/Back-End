<template>
    <div class="tab-pane-box">
        <div class="row tab-pane colored-tab-pane">
            <span class="tab-title"> {{ __('discount') }} </span>
        <div class="mb-3 col-lg-4">
            <label class="form-label"> {{ __('discount price') }}  <span v-if="main_currency()" class="fw-bold fs-14"> ({{ main_currency().prefix }}) </span>  </label>
            <input @input="handleInput(0,9999999999.99)" class="form-control" type="number" max="10000000" step="0.1" v-model="form.discount_price" :class="{ 'is-invalid': errors.discount_price } ">
            <div class="text-danger" v-html="errors.discount_price"></div>
           
        </div>
        <div class="mb-3 col-lg-4">
            <label class="form-label"> {{ __('start at') }} </label>
            <input class="form-control" type="date" v-model="form.start_at" :class="{ 'is-invalid': errors.start_at } ">
            <div class="text-danger" v-html="errors.start_at"></div>
        </div>
        <div class="mb-3 col-lg-4">
            <label class="form-label"> {{ __('end at') }} </label>
            <input class="form-control" type="date" v-model="form.end_at" :class="{ 'is-invalid': errors.end_at } ">
            <div class="text-danger" v-html="errors.end_at"></div>
        </div>
        <div class="col-12">
            <currencies-exchange :value="form.discount_price" :currencies="currencies"></currencies-exchange>
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
                let main = this.currencies.find((e)=>e.main==1)

                return main
            },
            handleInput(min,max){
            if(this.form.discount_price<min){
                this.form.discount_price = min
            }
            if(this.form.discount_price > max){
                this.form.discount_price = max
            }
          }
        }
    }
</script>