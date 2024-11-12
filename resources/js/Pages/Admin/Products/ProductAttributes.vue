<template>
    <div class="tab-pane text-muted" id="maintab3" role="tabpanel">

        <div class="tab-pane-box">
            <div class="row tab-pane colored-tab-pane">
            <span class="tab-title"> {{ __('attributes') }} </span>
            <div class="col-lg-3">
                <div class="attributes">
                    <div v-show="check_attribute(attr.id)"
                     v-for="attr,index in form.attributes_ids" :key="index"
                     class="d-flex attribue" :class="{'active':selected_attribute && selected_attribute.id==attr.id}"
                     @click="selected_attribute=attr">
                        <span @click="delete_attribute(attr.id)" class="delete-bin"><i class="ri-delete-bin-line"></i></span>
                        <span v-if="check_attribute(attr.id)" class="mx-2"> {{ check_attribute(attr.id).translated_name }} </span>
                    </div>
                </div>
                <select class="form-control mt-3" v-model="attribute" @change="select_attribute()">
                    <option :value="null"> {{ __('add attribute') }}  </option>
                    <option v-show="!check_form_attribute(attr.id)" v-for="attr,index in attributes" :key="index" :value="attr"> {{ attr.translated_name }}  </option>
                </select>
            </div>
            <div class="col-lg-9">
                <div class="table-responsive" v-if="selected_attribute!=null">
                    <table class="table text-nowrap">
                        <thead>
                            <tr>
                                <th> {{ __('option value') }} </th>
                                <th> {{ __('quantity') }} </th>
                                <th> {{ __('price') }} </th>
                                <th> {{ __('ation') }} </th>
                            </tr>
                        </thead>
                        <tbody v-if="get_form_attribute()">
                            <tr v-for="option,index in get_form_attribute().options" :key="index">
                                <td>
                                    <span v-if="option.option">{{ option.option.translated_name }} </span>
                                    <span v-else>{{ option.translated_name }} </span>
                                </td>
                                <td>
                                    <input :placeholder="__('quantity')" type="number" v-model="option.quantity" class="form-control">
                                </td>
                                <td>
                                    <input :placeholder="__('price')" type="number" v-model="option.price" class="form-control">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>
    </div>
</template>


<script>
    export default{
        props:{
            form:Object,
            errors:Object,
            attributes:Array,

        },
        data(){
            return {
                attribute:null,
                selected_attribute:null
            }
        },
        methods:{
            select_attribute(){
                this.form.attributes_ids.push(this.attribute)
                this.selected_attribute=this.attribute
                this.attribute=null
            },
            get_form_attribute(){
                if(this.selected_attribute!=null){
                    let attr =  this.form.attributes_ids.find((e)=>e.id==this.selected_attribute.id)
                    if(attr){
                        return attr
                    }
                }
            },
            check_attribute(id){
               let attr = this.attributes.find((e)=>e.id==id)
               return attr
            },
            check_form_attribute(id){
                return this.form.attributes_ids.find((e)=>e.id==id)
            },
            delete_attribute(id){
               let attr = this.check_form_attribute(id)
               let index = this.form.attributes_ids.indexOf(attr)
               this.form.attributes_ids.splice(index,1)
               this.selected_attribute=null
            }
        }
    }
</script>

<style>
    .attribue{padding: 6px 10px;
    border-radius: 5px;
    cursor: pointer;}

    .attribue.active{background: #845adf;
    color: #fff;}

</style>
