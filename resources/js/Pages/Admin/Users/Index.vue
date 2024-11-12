<template>
    <div>
        <auth-layout>
            <div class="container-fluid">
                <delete-modal @clearChecked="checked=[]" url="/admin/customers" :id="item.id" :checked="checked"></delete-modal>
                <div class="modal fade" id="mailModal" tabindex="-1"
                        aria-labelledby="mailModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h6 class="modal-title" id="mailModalLabel1">send mail for all customers</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form @submit.prevent="send_mail">
                                    <div class="mb-3">
                                        <label> subject </label>
                                        <input type="text" class="form-control" v-model="vform.subject" >
                                        <div class="text-danger" v-html="vform.errors.get('subject')"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label> Message </label>
                                        <textarea rows="8" type="text" class="form-control" v-model="vform.message" ></textarea>
                                        <div class="text-danger" v-html="vform.errors.get('message')"></div>
                                    </div>

                                    <div class="mb-3">
                                        <button :disabled="form.busy" type="submit" class="btn btn-primary"> Send </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

                <page-header></page-header>
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="d-flex w-100">
                            <div class="me-auto p-2" style="width:70%">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="search-box">
                                            <input v-model="form.search" type="text" class="form-control" :placeholder=" __('filter')">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                    <flat-pickr :config="config" class="form-control" v-model="form.created_at"/>
                                    </div>
                                    <div class="col-md-4">
                                        <button class="btn btn-primary" @click="filter()"><i class="ri-search-2-fill me-1 align-bottom"></i> {{  __('filter') }} </button>
                                    </div>
                                </div>
                            </div>

                            <div class="p-2 ">
                                <div class="btn-list">

                                <button data-bs-toggle="modal" data-bs-target="#mailModal" class="btn btn-primary"><i class="ri-mail-line me-1 align-bottom"></i> {{ __('mail for all') }} </button>
                                <button v-if="checked.length>0 && check_permissions(['delete customer'])" class="btn btn-danger-ghost btn-wave" data-bs-target="#warningModal" data-bs-toggle="modal"><i class="ri-delete-bin-line me-1 align-bottom"></i> {{ __('delete selected') }} </button>
                                    <button class="btn btn-primary" @click="exportTable"><i class="ri-search-2-fill me-1 align-bottom"></i> {{ __('export') }} </button>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive" v-if="users.data.length>0">
                        <table class="table text-nowrap table-striped">
                            <thead>
                                <tr>
                                    <th scope="col"><input  v-model="checkAll" class="form-check-input" type="checkbox" id="checkboxNoLabel" aria-label="..."></th>
                                    <th scope="col">{{__('name')}}</th>
                                    <th scope="col">{{__('email')}}</th>
                                    <th scope="col">{{__('phne')}}</th>
                                    <th scope="col">{{__('address')}}</th>
<!--                                    <th scope="col">{{__('gender')}}</th>-->
                                    <th scope="col">{{__('register at')}}</th>
                                    <th scope="col">{{__('action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="brand,index in users.data" :key="index">
                                    <th scope="row"><input v-model="checked" :value="brand.id" class="form-check-input" type="checkbox" id="checkboxNoLabel1" aria-label="..."></th>

                                   <td>
                                    {{brand.name}}
                                   </td>
                                    <td>
                                        {{brand.email}}

                                    </td>
                                    <td>
                                        {{brand.phone}}
                                    </td>
                                    <td>
                                        {{brand.address}}
                                    </td>
                                <td>
                                       {{brand.created_at}}
                                </td>


                                    <td>
                                        <Link :href="route('customers.show',brand.id)" class="btn btn-icon btn-primary btn-sm"><i class="ri-eye-line"></i></Link>
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                        <div class="pagination  mx-2 px-2">
                            <Pagination :links="users.links" />
                        </div>
                    </div>
                    <div v-else>
                        <no-elements></no-elements>
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
import NoElements from '@/Components/NoElements.vue';
import DeleteModal from '@/Components/DeleteModal.vue';
import Pagination from '@/Components/Pagination.vue';

import Form from 'vform';
import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';

export default {
  components: { AuthLayout, PageHeader, NoElements, DeleteModal, Pagination, flatPickr},
  props:{
      users:Object
  },
  data(){
    return {
        form:useForm({
            search:'',
            created_at:null
        }),
        vform:new Form({
            subject:'',
            message:null
        }),
        item:Object,
        checked:new Array,
        config:{
                    wrap: true, // set wrap to true only when using 'input-group'
                    altFormat: 'M j, Y',
                    altInput: true,
                    mode: 'range',
                    dateFormat: 'Y-m-d',
                    // locale: Hindi, // locale for this instance only
                },
    }
  },
  methods:{
    filter(){
        this.form.get('/admin/customer_filter')
    },

    send_mail(){
        this.vform.post('/admin/customers/send-mail/all')
            .then((resp)=>{
                $('#mailModal').modal('hide')
            })
    },

      async exportTable() {
          try {
              const response = await axios.get('/admin/customers-export/excel', {
                  responseType: 'blob'
              });
              const blob = new Blob([response.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
              const url = window.URL.createObjectURL(blob);
              const a = document.createElement('a');
              a.href = url;
              a.download = 'customers.xlsx';
              document.body.appendChild(a);
              a.click();
              a.remove();
              window.URL.revokeObjectURL(url);
          } catch (error) {
              console.error('Error downloading the file:', error);
          }
      }
  },
  computed: {
            checkAll: {
            get: function () {
                return this.users.data ? this.checked.length == this.users.data.length : false;
            },
            set: function (value) {
                var checked =new Array;
                if (value) {
                this.users.data.forEach(function (user) {
                    checked.push(user.id);
                });
                }
                this.checked = checked;
            }
            }
        }

}

</script>
