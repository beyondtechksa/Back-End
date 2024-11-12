<template>
    <div>
        <auth-layout>
            <div class="container-fluid">
                <delete-modal url="/admin/words" :id="item.id" :checked="[]"></delete-modal>
                <page-header></page-header>
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="d-flex w-100">
                            <div class="me-auto p-2">
                                <div class="d-flex">
                                    <div class="search-box">
                                        <input v-model="form.search" type="text" class="form-control" :placeholder=" __('filter')">
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
                    <div class="add-form" v-if="language.is_default">
                        <form @submit.prevent="create">
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <input required v-model="form.key" :placeholder="__('key')" type="text" class="form-control" :class="{ 'is-invalid': errors.key } " >
                                    <div class="text-danger" v-html="errors.key" />
                                </div>
                                <div class="col-md-3 mb-2">
                                    <input required v-model="form.value" :placeholder="__('value')" type="text" class="form-control" :class="{ 'is-invalid': errors.value } " >
                                    <div class="text-danger" v-html="errors.value" />
                                </div>
                                <div class="col-md-3 mb-2">
                                    <input v-model="form.url" :placeholder="__('url')" type="text" class="form-control" :class="{ 'is-invalid': errors.url } " >
                                    <div class="text-danger" v-html="errors.url" />
                                </div>
                                <div class="col-md-3 mb-2 pt-1">
                                    <button :disabled="form.processing" type="submit" class="btn btn-sm btn-primary btn-md"><i class="ri-save-line fs-16 align-middle d-inline-block"></i> {{__('create')}}  </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive" v-if="words.data.length>0">
                        <form @submit.prevent="edit">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th scope="col">{{__('key')}}</th>
                                    <th scope="col">{{__('value')}}</th>
                                    <th scope="col">{{__('url')}}</th>
                                    <th scope="col">{{__('action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="word,index in words.data" :key="index">
                                    <td>
                                        <input v-if="check_editing(word)" required v-model="editform.key" :placeholder="__('key')" type="text" class="form-control" :class="{ 'is-invalid': errors.key } " >
                                       <span v-else> {{word.key}} </span>
                                    </td>
                                    <td>
                                        <input v-if="check_editing(word)" required v-model="editform.value" :placeholder="__('value')" type="text" class="form-control" :class="{ 'is-invalid': errors.value } " >
                                       <span v-else> {{word.value}} </span>
                                    </td>
                                    <td>
                                        <input v-if="check_editing(word)" required v-model="editform.url" :placeholder="__('url')" type="text" class="form-control" :class="{ 'is-invalid': errors.url } " >
                                       <a class="text-primary" target="_blank" :href="word_url(word.url,word.key)" v-else> {{word.url}} </a>
                                    </td>

                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            <a v-if="!check_editing(word) &&  check_permissions(['edit language'])" @click="editModal(word)" href="javascript:void(0)" class="btn btn-icon btn-sm btn-primary"><i class="ri-edit-line"></i></a>
                                            <button :disabled="editform.processing" v-else href="javascript:void(0)" class="btn btn-sm btn-success"><i class="ri-edit-line"></i> {{__('save')}}</button>
                                            <a v-if="check_permissions(['delete language'])" @click="item=word" data-bs-target="#warningModal" data-bs-toggle="modal" href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger"><i class="ri-delete-bin-line"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>

                        </table>
                        </form>
                        <div class="pagination  mx-2 px-2">
                            <Pagination :links="words.links" />
                        </div>
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
import NoElements from '@/Components/NoElements.vue';
import Pagination from '@/Components/Pagination.vue';

export default{
    components: { AuthLayout, PageHeader, DeleteModal, NoElements, Pagination},
    props:{
        errors:Object,
        words:Object,
        language:Object
    },
    data(){
        return {
            form:useForm({
                search:'',
                language_id:this.language.id,
                key:'',
                value:'',
                url:''
            }),
            editform:useForm({
                search:'',
                id:'',
                language_id:this.language.id,
                key:'',
                value:'',
                url:''
            }),
            editMode:false,
            item:Object
        }
    },
    methods:{
        create(){
            this.form.post(route('words.store'))
            this.form.reset()
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
            this.form.get('/admin/words_filter')
        }
    }

}

</script>
