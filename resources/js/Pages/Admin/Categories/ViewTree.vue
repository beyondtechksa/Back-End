<template>
    <div>
        <auth-layout>
        <div class="container-fluid">
        <page-header></page-header>
        <div class="card card-body">
        <tree :form="form"></tree>
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
import Tree from './Tree.vue';
export default {
  components: { AuthLayout, PageHeader, useForm, FormBox, Tree},
  props:{
    errors:Object,
  },
  data(){
    return {
       form:useForm({

       })
    }
  },
  methods:{
        
        
  }

}



</script>