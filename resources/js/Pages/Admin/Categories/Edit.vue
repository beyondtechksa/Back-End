<template>
    <div>
        <auth-layout>
        <div class="container-fluid">
        <page-header></page-header>

        <div class="row justify-content-center">
         <div class="col-md-10">  
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">
                    {{ $page.props.page_name }} 
                </div>
                <div class="prism-toggle">
                    <button @click="back()" class="btn btn-sm btn-danger-light"> {{ __('cancel') }} <i class="ri-arrow-left-line ms-2 align-middle d-inline-block"></i></button>
                </div>
            </div>
            <div class="card-body">
                <form @submit.prevent="edit">
               
                   <form-box :form="form" :errors="errors"  :categories_with_parents="categories_with_parents" :category="category" :parents="parents"></form-box>
                  
                    <div class="mb-3">
                        <div class="hstack gap-2">
                            <button :disabled="form.processing" type="submit" class="btn btn-primary btn-md"><i class="ri-save-line fs-16 align-middle d-inline-block"></i> {{__('save')}}  </button>
                        </div>
                    </div>
          
                </form>
            </div>
            <div class="card-body">
                <h5> {{ __('category children') }} </h5>
                <ul class="list-unstyled categories-list">
                    <li v-for="child,index in children" :key="index"> {{ child.name.en }} / {{ child.name.ar }}
                       <span class="child" v-for="child2,index in child.all_children" :key="index"> <i class="ri-arrow-right-line"></i> {{ child2.name.en }} / {{ child2.name.ar }} </span>
                    </li>
                </ul>
            </div>
        </div>
        </div>
        </div> 
        </div>
        </auth-layout>
    </div>
</template>


<script>


import AuthLayout from '../Layouts/AuthLayout.vue'
import PageHeader from '@/Components/PageHeader.vue'
import { useForm } from '@inertiajs/vue3';
import FormBox from './FormBox.vue';
export default {
  components: { AuthLayout, PageHeader, FormBox},
  props:{
    errors:Object,
    category:Object,
    children:Array,
    categories_with_parents:Array,
    parents:Array
  },
  data(){
    return {
        form:useForm({
            name:this.category.name,
            menu_name:this.category.menu_name,
            desc:this.category.desc,
            slug:this.category.slug,
            image:this.category.image,
            category_id:this.category.category_id,
            status:this.category.status==1?true:false,
        }),
    }
  },
  methods:{
        edit(){
                this.form.put(route('categories.update',this.category.id))
            },
        
            
  },


}



</script>



<style> 
    .categories-list{}
    .categories-list li{background: #22163e;
    color: #fff;
    padding: 0.7em;}

    .child i{font-size: 24px;
    margin: 0 10px;
    margin-top: -17px !important;
    display: inline-block;
    position: relative;
    top: 4px;}
</style>