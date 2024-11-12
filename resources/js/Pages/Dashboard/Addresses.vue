

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
                <div class="d-flex justify-content-between" style="overflow:hidden">
                <div class="account-title">
                    <div>
                    <h3>{{__('My Addresses')}}</h3>
                    <p>{{__('Update your addresses')}}</p>
                    </div>
                </div>
                <div>
                    <Link :href="route('address.create')" class="btn me-2 site-btn"> {{ __('New') }} </Link>
                </div>
                </div>

                <div class="table-responsive mt-5">
                    <table class="table">
                        <thead>
                            <tr>
                            <th> ID </th>
                            <th> {{ __('Address Name') }} </th>
                            <th> {{ __('Details') }} </th>
                            <th> {{ __('Options') }} </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="address,index in addresses" :key="index">
                                <td> {{address.id}} </td>
                                <td> {{ address.type }} </td>
                                <td> {{ address.details }} </td>
                                <td>
                                    <Link :href="route('address.edit',address.id)" class="btn btn-outline-dark"> {{ __('edit') }} </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
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

export default{
  components: { App, SideBar, PageContent },
  props:{addresses:Array},
  data(){
    return {
      form:useForm({
        type:null,
        details:null,
    })
    }
  },
  methods:{
    update(){
      this.form.post(route('profile.update'))
    },
   
  }
}
</script>
    