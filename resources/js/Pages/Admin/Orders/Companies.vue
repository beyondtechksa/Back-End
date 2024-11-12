<template>
    <div>

        <auth-layout>
            <div class="container-fluid">
                <page-header></page-header>
                <div class="card custom-card">

                    <div class="table-responsive">
                        <table class="table text-nowrap table-striped">
                            <thead>

                            <tr>
                                <th scope="col"> Company name </th>
                                <th scope="col"> products number </th>
                                <th scope="col">{{ __('action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="company,index in companies" :key="index">
                               
                                <td>#{{company}} </td>
                                <td> 20 </td>

                                <td>
                                    <div class="hstack gap-2 fs-15">
                                        <a :href="route('orders.export_company_requests',company)" class="btn btn-sm btn-success">
                                              <i class="ri-file-line"></i> export
                                        </a>
                                        <button @click="sendMail(company)" class="btn btn-sm btn-secondary">
                                              <i class="ri-mail-line"></i> Send Mail
                                        </button>
                                        <Link :href="route('orders.company_requests',company)" class="btn btn-sm btn-secondary">
                                              <i class="ri-eye-line"></i> all products
                                        </Link>
                                        <Link :href="route('orders.company_requests',{name:company,status:0})" class="btn btn-sm btn-primary">
                                              <i class="ri-eye-line"></i> pending products
                                        </Link>
                                        <Link :href="route('orders.company_requests',{name:company,status:1})" class="btn btn-sm btn-success">
                                              <i class="ri-eye-line"></i> request send 
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                     
                    </div>
                    
                </div>
            </div>
        </auth-layout>
    </div>
</template>


<script>

import AuthLayout from '../Layouts/AuthLayout.vue'
import PageHeader from '@/Components/PageHeader.vue'
import {useForm} from '@inertiajs/vue3';
import {Form} from 'vform';
import Swal from "sweetalert2";

export default {
    components: {AuthLayout, PageHeader,},
    props: {
        companies: Array
    },
    data() {
        return {
            form: useForm({
                search: '',
            }),
            vform: new Form({
                id: null,
                status: null
            }),
            item: Object,
            checked: new Array
        }
    },
    methods: {
        sendMail(company){
            Swal.fire({
              title: "Are you sure?",
              text: "Email will be send to the company",
              icon: "info",
              showCancelButton: true,
              confirmButtonColor: "#34c38f",
              cancelButtonColor: "#f46a6a",
              confirmButtonText: "Yes, send it!",
          }).then((result) => {
              if (result.value) {
                  this.$inertia.post(route('orders.company_email'),{company:company})
                  Swal.fire("status updated!", "Your item has been updated.", "success");
              }
          });
        }
    
        
    },
    computed: {
        
    }

}

</script>
