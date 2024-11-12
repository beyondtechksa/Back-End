

<template>
   
  <Head title="Dashboard" />
  <app>
    <main class="user-admin">
      <div class="admin-container">
        <div class="container-cum">
          <div class="row mt-4">
            <side-bar></side-bar>
            <page-content>
              <div class="user-account-details">
                <div class="d-flex justify-content-between">
                <div class="account-title">
                    <div>
                    <h3>{{__('Create Address')}}</h3>
                    <p>{{__('Update your addresses')}}</p>
                    </div>
                </div>
                
                </div>

                <div class="General-form mt-3">
                <form @submit.prevent="save()" class="row">
                  <div  class="row">
                  <div class="col-md-6">
                    <div class="user-forminput">
                        <label for="First Name">{{__('First Name')}}</label>
                        <input style="width:100%" v-model="form.first_name"  />
                        <div class="text-danger"  v-html="form.errors.first_name" />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="user-forminput">
                        <label for="last Name">{{__('Last Name')}}</label>
                        <input style="width:100%" v-model="form.last_name"  />
                        <div class="text-danger"  v-html="form.errors.last_name" />
                    </div>
                  </div>
                  
                  <div class="col-md-12">
                    <div class="user-forminput">
                        <label for="First Name">{{__('Address Name')}}</label>
                        <input style="width:100%" v-model="form.type"  />
                        <div class="text-danger"  v-html="form.errors.type" />
                    </div>
                    </div>
               
                    <div class="col-md-6">
                    <div class="user-forminput">
                        <label for="First Name">{{__('Postal code')}}</label>
                        <input style="width:100%" v-model="form.postal_code"  />
                        <div class="text-danger"  v-html="form.errors.postal_code" />
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="user-forminput">
                        <label for="First Name">{{__('Street')}}</label>
                        <input style="width:100%" v-model="form.street"  />
                        <div class="text-danger"  v-html="form.errors.street" />
                    </div>
                    </div>
                    
                    <div class="col-md-6">
                    <div class="user-forminput">
                        <label for="First Name">{{__('City')}}</label>
                        <input style="width:100%" v-model="form.city"  />
                        <div class="text-danger"  v-html="form.errors.city" />
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="user-forminput">
                        <label for="First Name">{{__('Country')}}</label>
                        <v-select v-model="form.country" :options="translated_countries()" :reduce="country => country.name" label="translated_name" />
                        <div class="text-danger"  v-html="form.errors.country" />
                    </div>
                    </div>
                
                    <div class="col-md-12">
                    <div class="user-forminput">
                        <label for="First Name">{{__('Details')}}</label>
                        <input style="width:100%" v-model="form.details" type="text" />
                        <div class="text-danger"  v-html="form.errors.details" />
                    </div>
                    </div>
                    <div class="submit-button">
                    <button :disabled="form.processing" class="submit" type="submit">
                    {{__('Save')}}
                    </button>
                    
                </div>
                </div>
                </form>
                </div>
                
              </div>
            </page-content>
            
          </div>
        </div>
      </div>

      <!-- ==== Responsive Admin  ==== -->

      
    </main>
  </app>

</template>



<script>
import App from '@/HomeLayouts/AuthenticatedLayout.vue';
import SideBar from './Components/SideBar.vue';
import PageContent from './Components/PageContent.vue';
import { Form } from 'vform';
import { useForm } from '@inertiajs/vue3';
import vSelect from 'vue-select'
import countriesJson from './countries.json'
export default{
  components: { App, SideBar, PageContent, vSelect },
  props:{addresses:Array},
  data(){
    return {
      form:useForm({
        first_name:null,
        last_name:null,
        company_name:null,
        type:null,
        details:null,
        neighborhood:null,
        postal_code:null,
        street:null,
        building_name:null,
        city:null,
        country:null,
    }),
    countries:countriesJson
    }
  },
  methods:{
    save(){
      this.form.post(route('address.store'))
    },
   translated_countries(){
    let lang = this.$page.props.locale
    return this.countries.map(country => {
        return {
            id: country.id,
            name: country.name_en,
            translated_name: lang === 'ar' ? country.name_ar : country.name_en
        };
    });

   }
  }
}
</script>

<style>
@import "vue-select/dist/vue-select.css";
</style>