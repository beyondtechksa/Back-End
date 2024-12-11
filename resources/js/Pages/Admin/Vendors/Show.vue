<template>
    <div>
        <delete-modal  url="/admin/vendor/transactions/delete" :id="item.id" :checked="[]"></delete-modal>
        <div class="modal fade" id="TransactionModal" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel1">{{__('Add Balance')}}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label"> {{ __('amount') }} (SAR) </label>
                    <input v-model="form.amount" :placeholder="__('enter amount')" type="number" class="form-control" :class="{'is-invalid':errors.amount}" >
                    <div class="text-danger" v-html="errors.amount" />
                </div>
                <div class="mb-3">
                    <label class="form-label"> {{ __('description') }}  </label>
                    <input v-model="form.description" :placeholder="__('enter description')" type="text" class="form-control" :class="{'is-invalid':errors.description}" >
                    <div class="text-danger" v-html="errors.description" />
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">{{__('close')}}</button>
                <button :disabled="form.processing" @click="add_balance()" type="button" class="btn btn-primary"> {{ __('save') }} </button>
            </div>
        </div>
    </div>
    </div>

        <auth-layout>
            <div class="container-fluid">
                <page-header></page-header>
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between w-100">
                            <div> 
                                Transactions ({{ vendor.wallet?vendor.wallet.balance:0 }})
                            </div>
                            <div>
                                <button data-bs-toggle="modal" data-bs-target="#TransactionModal" class="btn btn-primary"> Add Balance </button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" v-if="transactions.total>0">
                        <table class="table text-nowrap table-striped">
                            <thead>

                            <tr>
                                <th scope="col"> Transaction ID</th>
                                <th scope="col"> Amount </th>
                                <th scope="col"> Description </th>
                                <th scope="col"> type </th>
                                <th scope="col"> Date </th>
                                <th scope="col"> Action </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="transaction,index in transactions.data" :key="index">

                                <td>#{{transaction.id}} </td>
                                <td>{{transaction.amount}} </td>
                                <td>{{transaction.description}} </td>


                                <td>
                                        <span class="bg-primary badge text-white p-2" v-if="transaction.type=='credit'">
                                            {{ transaction.type }}
                                        </span>
                                        <span class="bg-danger badge text-white p-2" v-else>
                                            {{transaction.type}}
                                        </span>
                                    

                                </td>
                                <td>
                                    {{ formateDate(transaction.created_at) }}
                                </td>
                                <td>
                                    <a  @click="item=transaction" data-bs-target="#warningModal" data-bs-toggle="modal" href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger"><i class="ri-delete-bin-line"></i></a>
                                </td>
                            
                            </tr>
                            </tbody>
                        </table>
                        <div class="pagination  mx-2 px-2">
                            <Pagination :links="transactions.links"/>
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
export default {
  components: { AuthLayout, PageHeader, NoElements, DeleteModal, Pagination},
  props:{
    vendor:Object,
    transactions:Object,
    errors:Object
  },
  data(){
    return {
        form:useForm({
            search:'',
            vendor_id:this.vendor.id,
            amount:null,
            description:null
        }),
        item:Object,
    }
  },
  methods:{
    add_balance(){
        this.form.post(route('vendors.transaction.store'),{
            onSuccess : ()=>{
                $('#TransactionModal').modal('hide')
            }
        })
    }
  },


}

</script>
