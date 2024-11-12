<template>
    <div class="row">
        <div class="col-lg-4">
                <div class="attributes">
                    <div v-show="check_attribute(attr.id)"
                     v-for="attr,index in form.attributes_ids" :key="index"
                     class="d-flex attribue" :class="{'active':selected_attribute && selected_attribute.id==attr.id}"
                     @click="click_selected_attribute(attr)">
                        <span @click="delete_attribute(attr.id)" class="delete-bin"><i class="ri-delete-bin-line"></i></span>
                        <span v-if="check_attribute(attr.id)" class="mx-2"> {{ check_attribute(attr.id).translated_name }} </span>
                    </div>
                </div>
                <!-- <select class="form-control mt-3" v-model="attribute" @change="select_attribute()">
                    <option :value="null"> {{ __('add attribute') }}  </option>
                    <option v-show="!check_form_attribute(attr.id)" v-for="attr,index in attributes" :key="index" :value="attr"> {{ attr.name.en }} / {{ attr.name.ar }}  </option>
                </select> -->

                <div class="list-group mt-2">
                    <input @keyup="searching" v-model="search" class="form-control" :placeholder=" __('filter')">
                    <button @click="select_attribute(attr)" v-show="!check_form_attribute(attr.id)" v-for="attr,index in searched_attributes" :key="index" type="button" class="list-group-item list-group-item-action">{{ attr.name.en }} / {{ attr.name.ar }}</button>
                </div>
            </div>
            <div class="col-lg-8">
                <input @keyup="searching_options" v-model="search_option" class="form-control" :placeholder=" __('filter')">
                <div class="table-responsive" v-if="selected_attribute!=null">
                    <table class="table text-nowrap">
                        <thead>
                            <tr>
                                <th> {{ __('option value') }} </th>
                                <!-- <th> {{ __('quantity') }} </th>
                                <th> {{ __('price') }} </th>
                                <th> {{ __('ation') }} </th> -->
                            </tr>
                        </thead>
                        <tbody v-if="get_form_attribute()">
                            <tr v-for="option,index in searched_options" :key="index">
                                <td>
                                    <input :checked="option.status?true:false" type="checkbox" v-model="option.status">
                                    {{ option.name.en }} / {{ option.name.ar }}
                                </td>
                                <!-- <td>
                                    <input :placeholder="__('quantity')" type="number" v-model="option.status" class="form-control">
                                </td>
                                <td>
                                    <input :placeholder="__('price')" type="number" v-model="option.price" class="form-control">
                                </td> -->
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
    </div>
</template>

<script>

    export default {
        props:{
            attributes:Array,
            form:Object,
        },
        data(){
            return {
                selected_attribute:null,
                attribute:null,
                search:null,
                search_option:null,
                searched_attributes:this.attributes,
                searched_options:[]
            }
        },
        methods:{
            searching(){
                if(this.search)
                this.searched_attributes=this.attributes.filter((el)=>{
                    return el.name.en.includes(this.search)  || el.name.ar.includes(this.search)
                });
                else
                this.searched_attributes=this.attributes
            },
            searching_options(){
                if(this.search_option)
                this.searched_options=this.get_form_attribute().options.filter((el)=>{
                    return el.name.en.includes(this.search_option)  || el.name.ar.includes(this.search_option)
                });
                else
                this.searched_options=this.get_form_attribute().options
            },
            select_attribute(attr){
                this.attribute=attr
                this.form.attributes_ids.push(this.attribute)
                this.selected_attribute=this.attribute
                this.attribute=null
                let options=this.get_form_attribute().options
                this.searched_options=this.reset_option(options)

            },
            reset_option(options){
                let returned=[];
                options.forEach((el)=>{
                    el.status=false
                })
                return returned
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
            },
            click_selected_attribute(attr){
                this.selected_attribute=attr
                this.searched_options=this.get_form_attribute().options
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
