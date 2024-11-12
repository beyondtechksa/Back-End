<template>
    <file-manager ref="file" @fileUploaded="fileUploaded()"></file-manager>
    <div>
        <div class="mb-3">
            <langtext-input :label="__('return status name')" :form="form"></langtext-input>
            <div class="text-danger" v-html="errors.name" />
        </div>



        <!-- <div class="mb-3">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch"
                    id="flexSwitchCheckChecked" v-model="form.status" :checked="form.status==1">
                <label class="form-check-label cursor-pointer" for="flexSwitchCheckChecked">{{__('active status')}}</label>
            </div>
            <div class="text-danger" v-html="errors.status" />
        </div> -->
    </div>
</template>


<script>
import LangtextInput from '@/Components/Elements/LangtextInput.vue';
import LangtextArea from '@/Components/Elements/LangtextArea.vue';
import FileManager from '@/Components/FileManager.vue';
    export default {
        components:{LangtextInput, LangtextArea, FileManager},
        props:{
            form:Object,
            errors:Object,
        },
        data(){
            return {

            }
        },
        methods:{
            check_src(){
        if(this.form.image!=null){
            return this.form.image
        }else{
        return this.$page.props.auth.default_img
        }
        },
        fileUploaded(){
           this.form.image=this.$refs.file.selected_media.path
        },
        }
    }

</script>
