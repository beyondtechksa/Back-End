

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100"> {{ __('Profile exercises') }} </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __("Update your exercises.") }}
            </p>
        </header>

        <form @submit.prevent="form.post(route('profile.update_exercises'))" class="mt-6 space-y-6">
            <div class="row position-relative border-bottom" v-for="exp,index in form.exercises" :key="index">
                <span @click="delete_exercise(index)" class="delete-option"> <i class="ri-close-line"></i> </span>
                <div class="col-md-6">
                    <div class="mt-2">
                    <label class="form-label"> {{ __('specialization') }} </label>
                    <input class="form-control" :placeholder="__('indicate the field of study')" v-model="exp.specialization" type="text">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mt-2">
                    <label class="form-label"> {{ __('university') }} </label>
                    <input class="form-control" :placeholder="__('specify an university')" v-model="exp.university" type="text" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mt-2">
                    <label class="form-label"> {{ __('diploma') }} </label>
                    <input class="form-control" :placeholder="__('specify an diploma')" v-model="exp.diploma" type="text" >
                    </div>
                </div>
             
               
                <div class="col-md-6">
                    <div class="mt-2">
                    <label class="form-label"> {{ __('start date') }} </label>
                    <input class="form-control" v-model="exp.start_date" type="date" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mt-2">
                    <label class="form-label"> {{ __('start date') }} </label>
                    <input class="form-control" v-model="exp.end_date" type="date" >
                    </div>
                </div>
                <div class="mt-2 col-12">
                    <div class="form-check">
                    <input class="form-check-input primary" v-model="exp.continue" type="checkbox" value="" :id="'defaultCheck'+index">
                        <label class="form-check-label text-muted fw-normal" :for="'defaultCheck'+index">
                            {{__("I'm still part of the team")}} ?
                        </label>
                    </div>
                </div>
               
                
            </div>
            <div class="mt-3 col-12" >
                <button type="button" @click="add_exercise()" class="btn btn-primary-ghost btn-sm"> <i class="ri-add-line"></i> {{ __('add exercises') }} </button>
            </div>
            <div class="mt-3 col-12" >
                <button :disabled="form.processing" class="btn btn-dark bg-dark"> <i class="ri-add-line"></i> {{ __('save') }} </button>
            </div>
        </form>
    </section>
</template>


<script>
import {useForm} from '@inertiajs/vue3';
export default{
    props:{
    errors:Object,
  },
    mounted(){
        if(this.$page.props.auth.user.exercises_json.length>0){
            this.form.exercises=this.$page.props.auth.user.exercises_json
        }else{
            this.add_exercise()
        }
    },
    data(){
        return {
            form:useForm({
                exercises:[]
            }),
            
        }
    },
    methods:{
        add_exercise(){
            let exercise={
                specialization:'',
                university:'',
                diploma:'',
                start_date:'',
                end_date:'',
                continue:true,
                
            }
            this.form.exercises.push(exercise)
        },
        delete_exercise(index){
            if(this.form.exercises.length>1){
                this.form.exercises.splice(index,1)
            }
        },
        
       
    }
}
</script>