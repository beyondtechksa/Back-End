<template>
  <div class="modal flip fade" id="warningModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center p-5">
                    <i class="ri-alert-line text-danger fs-70"></i>
                    <div class="">
                        <h4 class="mb-3"> {{ __('warning') }} </h4>
                        <p class="text-muted mb-4"> {{ __('you are about to delete some records that will not be recovered') }} ! </p>
                        <div class="hstack gap-2 justify-content-center">
                            <a id="closeModal" href="javascript:void(0);" class="btn btn-light" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> {{__('close')}}</a>
                            <a href="javascript:void(0);" @click="checked.length>0?multi_destroy():destroy()" class="btn btn-danger">{{__('delete')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
 import { useForm } from '@inertiajs/vue3'; 
    export default {
        props:{
            url:String,
            id:String,
            checked:Array
        },
        data(){
            return {
                form :useForm({
                    checked:Array
                })
            }
        }  ,
        methods:{
            destroy(){
                this.form.delete(this.url+'/'+this.id)
                $('#warningModal').modal('hide')
            },
            multi_destroy(){
                this.form.checked=this.checked
                this.form.post(this.url+'/'+'multi_destroy')
                $('#warningModal').modal('hide')
                this.$emit('clearChecked')
            }
        }
    }
</script>


                