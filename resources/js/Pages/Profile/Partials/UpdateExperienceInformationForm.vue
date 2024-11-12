

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100"> {{ __('Profile experiences') }} </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __("Update your experiences.") }}
            </p>
        </header>

        <form @submit.prevent="form.post(route('profile.update_experiences'))" class="mt-6 space-y-6">
            <div class="row position-relative border-bottom" v-for="exp,index in form.experiences" :key="index">
                <span @click="delete_experience(index)" class="delete-option"> <i class="ri-close-line"></i> </span>
                <div class="col-md-6">
                    <div class="mt-2">
                    <label class="form-label"> {{ __('job title') }} </label>
                    <input class="form-control" :placeholder="__('enter job title')" v-model="exp.job_title" type="text">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mt-2">
                    <label class="form-label"> {{ __('employer') }} </label>
                    <input class="form-control" :placeholder="__('specify an employer')" v-model="exp.employer" type="text" >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mt-2">
                    <label class="form-label"> {{ __('field activity') }} </label>
                    <select class="form-control" v-model="exp.field_activity" type="text" >
                    <option value=""> {{ __('select') }} </option>
                    <option value=""> {{ __('select') }} </option>
                    <option value=""> {{ __('select') }} </option>
                    <option value=""> {{ __('select') }} </option>
                    </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mt-2">
                    <label class="form-label"> {{ __('career level') }} </label>
                    <select class="form-control" v-model="exp.career_level" type="text"  >
                    <option value=""> {{ __('select') }} </option>
                    <option value=""> {{ __('select') }} </option>
                    <option value=""> {{ __('select') }} </option>
                    <option value=""> {{ __('select') }} </option>
                    </select>
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
                <div class="mt-2 col-12" v-for="task,i in exp.tasks" :key="i">
                    <div class="input-group mb-3">
                        <input v-model="task.name" type="text" class="form-control" :placeholder="__('enter your task name')"
                            aria-label="Recipient's username" aria-describedby="basic-addon2">
                        <span @click="delete_task(index,i)" class="input-group-text fs-20 cursor-pointer" id="basic-addon2"> <i class="ri-close-line"></i> </span>
                    </div>
                </div>
                <div class="col-12" >
                <button type="button" @click="add_task(index)" class="btn"> <i class="ri-add-line"></i> {{ __('add task') }} </button>
                </div>
            </div>
            <div class="mt-3 col-12" >
                <button type="button" @click="add_experience()" class="btn btn-primary-ghost btn-sm"> <i class="ri-add-line"></i> {{ __('add experience') }} </button>
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
        if(this.$page.props.auth.user.experiences_json.length>0){
            this.form.experiences=this.$page.props.auth.user.experiences_json
        }else{
            this.add_experience()
        }
    },
    data(){
        return {
            form:useForm({
                experiences:[]
            }),
            
        }
    },
    methods:{
        add_experience(){
            let experience={
                job_title:'',
                employer:'',
                field_activity:'',
                career_level:'',
                start_date:'',
                end_date:'',
                continue:true,
                tasks:[
                    {name:''}
                ]
            }
            this.form.experiences.push(experience)
        },
        delete_experience(index){
            if(this.form.experiences.length>1){
                this.form.experiences.splice(index,1)
            }
        },
        add_task(index){
            let experience=this.form.experiences[index]
            let tasks=experience.tasks
            tasks.push({name:''})
        },
        delete_task(index,i){
            let experience=this.form.experiences[index]
            let tasks=experience.tasks
            if(tasks.length>1){
                tasks.splice(i,1)
            }
        }
    }
}
</script>