<template>
    <div>
        <auth-layout>
            <div class="container-fluid">
                <page-header></page-header>
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="d-flex w-100">
                            <div class="me-auto p-2">
                                <div class="d-flex">
                                    <div class="search-box">
                                        <input type="text" v-model="form.search" class="form-control" :placeholder=" __('filter')">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="table-responsive" v-if="subscribes.data.length>0">
                        <table class="table text-nowrap table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">{{__('email')}}</th>
                                    <th scope="col">{{__('date')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="sub,index in subscribes.data" :key="index">

                                   <td>{{sub.email}}</td>
                                   <td>{{ sub.created_at.substring(0,10)}}</td>
                                </tr>
                            </tbody>

                        </table>
                        <div class="pagination  mx-2 px-2">
                            <Pagination :links="subscribes.links" />
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
      subscribes:Object
  },
  data(){
    return {
        form:useForm({
            search:'',
        }),
        item:Object,
        checked:new Array
    }
  },



}

</script>
