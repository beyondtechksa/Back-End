<template>
    <div>
        <auth-layout>
            <div class="container-fluid">
                <page-header></page-header>
                <div class="card custom-card">

                    <div class="table-responsive draggable-table" v-if="categories.length>0">
                        <table class="table text-nowrap table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">{{__('image')}}</th>
                                    <th scope="col">{{__('category name')}}</th>
                                    <th scope="col">{{__('date')}}</th>
                                    <!-- <th scope="col"> Show In Navbar </th> -->
                                    <!-- <th scope="col"> mark as top category </th> -->
                                    <th scope="col">{{__('action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <draggable v-model="dragged_categories" group="parent"  @end="endDrag()">
                                    <template  #item="{element:category}">
                                <tr>

                                   <td>
                                    <div class="">
                                        <img width="50" v-if="category.image" v-lazy="category.image" class=" img-thumbnail admin-profile-image" alt="admin-profile-image">
                                        <img width="50" v-else v-lazy="$page.props.auth.default_img" class=" img-thumbnail admin-profile-image" alt="admin-profile-image">
                                    </div>
                                   </td>
                                    <td>
                                        {{ substr(category.name.en+' / '+category.name.ar,50)}}

                                    </td>

                                    <td>
                                        {{formateDate(category.created_at)}}
                                    </td>
                                    <!-- <td>
                                        <div class="form-check form-check-lg form-switch px-3">
                                            <input @change="update_shown_status(category)" :checked="category.show_in_navbar!=0"  class="form-check-input" type="checkbox" role="switch"
                                                id="switch-lg">
                                        </div>
                                    </td> -->
                                    <!-- <td>
                                        <div class="form-check form-check-lg form-switch px-3">
                                            <input @change="update_top_status(category)" :checked="category.mark_as_top_category!=0"  class="form-check-input" type="checkbox" role="switch"
                                                id="switch-lg">
                                        </div>
                                    </td> -->

                                    <td>
                                        <div class="hstack gap-2 fs-15">

                                            <Link v-if="check_permissions(['edit product category'])" :href="route('categories.edit',category.id)" class="btn btn-icon btn-sm btn-primary"><i class="ri-edit-line"></i></Link>
                                        </div>
                                    </td>
                                </tr>
                                </template>
                                </draggable>
                            </tbody>

                        </table>

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
import { Form } from 'vform';
import draggable from 'vuedraggable'
export default {
  components: { AuthLayout, PageHeader, NoElements, DeleteModal, draggable},
  props:{
    categories:Array
  },
  data(){
    return {
        form:useForm({
            search:this.$page.props.ziggy.query.search??null,
        }),
        vform:new Form({
            id:null,
        }),
        item:Object,
        checked:new Array,
        dragged_categories:this.categories
    }
  },
  methods:{

    endDrag(){
      axios.post('/admin/api/update_categories_top_list',{categories:this.dragged_categories,type:'mobile'})
    },
    update_top_status(category){
        this.vform.id=category.id
        this.vform.post('/admin/category/update_top_status')
             .then((resp)=>{

             })
    },
  },

}

</script>

<style>
    .draggable-table div{
        display: contents;
    }
    .draggable-table tr{
        cursor: grab;
    }
</style>
