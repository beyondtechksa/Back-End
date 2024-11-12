
<template>
<auth-layout>
            <div class="container-fluid">
                <page-header></page-header>
                <div class="card custom-card">

                    <div class="table-responsive" v-if="settings.length>0">
                        <table class="table text-nowrap table-striped">
                          <thead >
                                <tr>

                                    <th scope="col">{{ __('ID') }}</th>
                                    <th scope="col">{{ __('Key') }}</th>
                                    <th scope="col">{{ __('Action') }}</th>

                                </tr>
                            </thead>
                                    <tbody>
                                        <tr v-for="setting,index in settings" :key="index">

                                            <td>
                                                <a  href="#" class="fw-medium">#{{setting.id}}</a >
                                            </td>
                                            <td>
                                               {{setting.key}}
                                              </td>


                                            <td>
                                                <div class="d-flex flex-wrap gap-2">

                                                  <Link :href="route('general_settings.edit',setting.id)" class="btn btn-icon btn-sm btn-primary"><i class="ri-edit-line"></i></Link>

                                                </div>
                                            </td>

                                        </tr>

                                    </tbody>

                        </table>
                        <div class="pagination  mx-2 px-2">
                            <Pagination :links="settings.links" />
                        </div>
                    </div>
                    <div v-else>
                        <no-elements></no-elements>
                    </div>
                </div>
            </div>
        </auth-layout>
</template>


<script>

import AuthLayout from "../Layouts/AuthLayout.vue"
import PageHeader from '@/Components/PageHeader.vue'
import { useForm } from '@inertiajs/vue3';


export default {
  components: {
    AuthLayout,
    PageHeader
  },
  props:{
    settings:Object
  },
  data(){
    return {
      edit_route:'settings.edit',
      form:useForm({
        id:'',
      }),
      item:{}
    }
  },
  methods:{


  },
  computed: {

        }
};
</script>
