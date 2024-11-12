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
                                        <input v-model="editform.search" type="text" class="form-control" :placeholder=" __('filter')">
                                    </div>
                                </div>
                            </div>
                            <div class="p-2">
                                <div class="btn-list">
                                <button class="btn btn-primary" @click="filter()"><i class="ri-search-2-fill me-1 align-bottom"></i> {{ __('filter') }} </button>
                                </div>
                            </div>
                        </div>
                        <div class="text-center w-100"> <h4 > {{ language.name }} </h4> </div>
                    </div>
                    <div class="card-body">

                    <div class="table-responsive" v-if="words.data.length>0">
                        <form @submit.prevent="translate">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th scope="col">{{__('key')}}</th>
                                    <th scope="col">{{__('value')}}</th>
                                    <th scope="col">{{__('action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="word,index in words.data" :key="index">
                                    <td>
                                        <a v-show="get_url(word.key)" class="btn btn-icon btn-warning btn-sm mx-2" target="_blank" :href="word_url(get_url(word.key),word.key)">  <i class="ri-eye-line"></i>  </a>
                                       <span> {{word.key}}  </span>
                                    </td>
                                    <td>
                                        <input v-if="check_editing(word)" required v-model="editform.value" :placeholder="__('value')" type="text" class="form-control" :class="{ 'is-invalid': errors.value } " >
                                       <span v-else> {{word.value}} </span>
                                    </td>


                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            <a v-if="!check_editing(word)" @click="editModal(word)" href="javascript:void(0)" class="btn btn-icon btn-sm btn-primary"><i class="ri-edit-line"></i></a>
                                            <button :disabled="editform.processing" v-else href="javascript:void(0)" class="btn btn-sm btn-success"><i class="ri-edit-line"></i> {{__('save')}}</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                        <div class="pagination  mx-2 px-2">
                            <Pagination :links="words.links" />
                        </div>
                        </form>

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
import DeleteModal from '@/Components/DeleteModal.vue';
import { useForm } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';

export default{
    components: { AuthLayout, PageHeader, DeleteModal, Pagination},
    props:{
        errors:Object,
        words:Object,
        language:Object,
        keys_with_url:Object,
    },
    data(){
        return {
            editform:useForm({
                id:'',
                search:'',
                language_id:this.language.id,
                key:'',
                value:'',
            }),
            item:Object

        }
    },
    methods:{
        translate(){
            this.editform.put(route('words.update',this.editform.id),{ preserveState: false, preserveScroll: true })
            this.editform.reset()
            this.editMode=false
        },
        edit(){
            this.editform.put(route('words.update',this.editform.id),{ preserveState: false, preserveScroll: true })
            this.editMode=false

        },
        editModal(item){
            this.item=item
            this.editMode=true
            this.editform.id=item.id
            this.editform.key=item.key
            this.editform.value=item.value
            this.editform.url=item.url
        },
        check_editing(item){
            if(item.id==this.item.id && this.editMode){
                return true
            }else if(item.id==this.item.id && this.editform.processing){
                return true
            }else{
                return false
            }
        },

        filter(){
            this.editform.get('/admin/words_filter')
        },
        get_url(key){
           let lang= this.keys_with_url.find((e)=>e.key==key)
           return lang.url

        }

    }

}

</script>
