<template>
    
    <auth-layout>
      <Head :title="__('profile')" />
      <div class="container">

        <page-header></page-header>

        <ul class="list-group pb-3">
            <li v-for="setting,index in settings" :key="index"
                class="list-group-item d-flex justify-content-between align-items-center fw-semibold">
                <span>
                  {{setting.name}} 
                <span v-if="['shop_by_brand','our_collection','shop_by_section','banners2','single_banner','single_banner2','single_banner3','single_banner4','single_banner5','single_banner6','banners3'].includes(setting.slug)" class="text-success"> h:flexible </span>
                <span class="text-success" v-else>
                  <span v-if="setting.slug=='top_slider'"> 250px </span>
                  <span v-else-if="setting.slug=='top_banner'"> 180px , 370px , h:flexible </span>
                  <span v-else-if="setting.slug=='small_banners'"> 100px </span>

                </span>
                </span>
                <div class="d-flex gap-5 align-items-center">
                  <span v-if="!['news_bar','vat','logo','favicon'].includes(setting.slug)">
                  <div class="custom-toggle-switch d-flex align-items-center">
                    <input @change="update_setting_status(setting.id)" :checked="setting.status==1" :id="'toggleswitchPrimary'+setting.slug" name="toggleswitch0018" type="checkbox">
                    <label :for="'toggleswitchPrimary'+setting.slug" class="label-primary"></label>
                  </div>
                </span>

                 <Link :href="route('settings.edit',{type:type,id:setting.id})" class="badge bg-primary btn btn-primary">{{__('edit')}}</Link>
                </div>
              </li>
        </ul>

      </div>
      
       
    </auth-layout>
</template>

<script>
import AuthLayout from '../Layouts/AuthLayout.vue'
import PageHeader from '@/Components/PageHeader.vue'
import {useForm  } from '@inertiajs/vue3';
export default {
  components: { AuthLayout, PageHeader },
  props:{
        settings:Array,
        type:String
    },
  data(){
        return {
            form:useForm({
                id:null,
               
            }),
            
        }
    },
    methods:{
      update_setting_status(id){
        axios.post('/admin/update_setting_status/'+id)
      }  
       
    }

  }
</script>
    
