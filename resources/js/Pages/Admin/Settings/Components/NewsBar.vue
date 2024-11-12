<template>
    <div class="card">
        <div class="card-header"> {{ setting.name }} </div>
        <div class="card-body">
            <form @submit.prevent="save_setting()">
                <div class="mb-3">
                    <langtext-input :label="__('news text')" :form="form" model="key"></langtext-input>
                    <div class="text-danger" v-html="form.errors.key" />
                </div>
                <div>
                <div class="hstack gap-2">
                    <button :disabled="form.processing" type="submit" class="btn btn-primary btn-md"><i class="ri-save-line fs-16 align-middle d-inline-block"></i> {{__('save')}}  </button>
                </div>
                </div>
            </form>
        </div>
    </div>
</template>


<script>
import {useForm  } from '@inertiajs/vue3';
import LangtextInput from '@/Components/Elements/LangtextInput.vue';
export default{
  components: { LangtextInput },
    props:{setting:Object,type:String,collections:Array},
        data(){
        return {
            form:useForm({
                id:this.setting.id,
                key:this.setting.key
            }),
            
        }
    },
    methods:{
        save_setting(){
            this.form.post(route('settings.update'))
        }
       
    }
    }

</script>