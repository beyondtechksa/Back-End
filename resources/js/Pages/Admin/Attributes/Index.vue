<template>
    <div>
        <auth-layout>
            <div class="container-fluid">
                <page-header></page-header>
                <div class="card custom-card">
                  
                    <div class="table-responsive draggable-table" v-if="attributes.length>0">
                        <table class="table text-nowrap table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">{{__('attribute name')}}</th>
                                    <th scope="col">{{__('updated by')}}</th>
                                    <th scope="col">{{__('action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <draggable v-model="dragged_attributes" group="parent"  @end="endDrag()">
                                    <template  #item="{element:attribute}">
                                <tr>
                                    
                                  
                                    <td>
                                        {{ substr(attribute.name.en+' / '+attribute.name.ar,50)}}
                                     
                                    </td>
                                    <td> {{attribute.admin?attribute.admin.name:null}} </td>
                                 
                                    <!-- <td>
                                        <div class="form-check form-check-lg form-switch px-3">
                                            <input @change="update_shown_status(attribute)" :checked="attribute.show_in_navbar!=0"  class="form-check-input" type="checkbox" role="switch"
                                                id="switch-lg">
                                        </div>
                                    </td> -->
                                    <!-- <td>
                                        <div class="form-check form-check-lg form-switch px-3">
                                            <input @change="update_top_status(attribute)" :checked="attribute.mark_as_top_attribute!=0"  class="form-check-input" type="checkbox" role="switch"
                                                id="switch-lg">
                                        </div>
                                    </td> -->

                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            
                                            <Link v-if="check_permissions(['edit product attribute'])" :href="route('attributes.edit',attribute.id)" class="btn btn-icon btn-sm btn-primary"><i class="ri-edit-line"></i></Link>
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
    attributes:Array
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
        dragged_attributes:this.attributes
    }
  },
  methods:{
 
    endDrag(){
      axios.post('/admin/api/update_attributes_list',{checked:this.dragged_attributes})
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