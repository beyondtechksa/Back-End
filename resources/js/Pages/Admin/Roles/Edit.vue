<template>
    <div>
        <auth-layout>
        <div class="container-fluid">
        <page-header></page-header>
        <div class="row justify-content-center">
         <div class="col-md-10">  
        <div class="card custom-card">
            <div class="card-header justify-content-between">
                <div class="card-title">
                    {{ $page.props.page_name }} 
                </div>
                <div class="prism-toggle">
                    <button @click="back()" class="btn btn-sm btn-danger-light"> {{ __('cancel') }} <i class="ri-arrow-left-line ms-2 align-middle d-inline-block"></i></button>
                </div>
            </div>
            <div class="card-body">
                <form @submit.prevent="edit">
                <div class="row">
                    <div class="mb-3">
                        <label for="input-label" class="form-label"> {{ __('role name') }} </label>
                        <input v-model="form.name" type="text" class="form-control" :class="{ 'is-invalid': errors.name } " >
                        <div class="text-danger" v-html="errors.name" />
                    </div>
                    <div class="mb-3 col-12">
                        <label for="input-label" class="form-label"> {{ __('permissions') }} </label>
                        <div class="row">
                            <div class="col-12">
                                <div class="custom-toggle-switch d-flex align-items-center mb-4">
                                <input :id="'toggleswitchPrimaryww'" :name="'toggleswitch001ww'" type="checkbox"  v-model="checkAll">
                                <label :for="'toggleswitchPrimaryww'" class="label-primary"></label>
                                <span class="ms-3">{{ __('check all') }}</span>
                            </div>
                            </div>
                        <div class="col-md-4 col-xl-3 px-0 pt-3" v-for="permission,index in permissions" :key="index">
                            <div class="custom-toggle-switch d-flex align-items-center mb-4">
                                <input :id="'toggleswitchPrimary'+index" :name="'toggleswitch001'+index" type="checkbox" :value="permission.name" v-model="form.permissions">
                                <label :for="'toggleswitchPrimary'+index" class="label-primary"></label>
                                <span class="ms-3">{{permission.name}}</span>
                            </div>
                        </div>
                    </div>
                        <div class="text-danger" v-html="errors.permissions" />
                    </div>
                  
                    <div class="col-lg-12">
                        <div class="hstack gap-2">
                            <button :disabled="form.processing || form.permissions.length==0" type="submit" class="btn btn-primary btn-md"><i class="ri-save-line fs-16 align-middle d-inline-block"></i> {{__('save')}}  </button>
                        </div>
                    </div>
                </div>
                </form>
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
import { useForm, router } from '@inertiajs/vue3';
export default {
  components: { AuthLayout, PageHeader},
  props:{
    errors:Object,
    role:Object,
    permissions:Array
  },
  data(){
    return {
        form:useForm({
            name:this.role.name,
            permissions:this.pluck(),
        }),
        checked:[]
    }
  },
  methods:{
        edit(){
                this.form.put(route('roles.update',this.role.id))
            },
            pluck(){
            let names=[]
            this.role.permissions.forEach(element => {
                names.push(element.name)
            });
            return names
        },
  },
  computed:{
    checkAll: {
    get: function () {
        return this.permissions ? this.form.permissions.length == this.permissions.length : false;
    },
    set: function (value) {
        var checked =new Array;
        if (value) {
        this.permissions.forEach(function (permission) {
            checked.push(permission.name);
        });
        }
        this.form.permissions = checked;
    }
    }
  }

}



</script>