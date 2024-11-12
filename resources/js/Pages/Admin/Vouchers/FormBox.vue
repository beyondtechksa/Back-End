<template>
    <file-manager ref="file" @fileUploaded="fileUploaded()"></file-manager>
    <div>
    
        <div class="row">
        <div class="mb-3 col-md-6">
            <label class="form-label"> {{ __('Voucher Name') }} </label>
            <input v-model="form.name" :placeholder="__('Voucher name')" type="text" class="form-control" :class="{'is-invalid':errors.name}" >
            <div class="text-danger" v-html="errors.name" />
        </div>
        <div class="mb-3 col-md-6">
            <label class="form-label"> {{ __('Voucher Code') }} </label>
            <div class="input-group">
            <input v-model="form.code" :placeholder="__('Voucher Code')" type="text" class="form-control" :class="{'is-invalid':errors.code}" >
            <span class="input-group-text btn btn-success" @click="generateRandomString(10)" id="basic-addon1">{{__('generat code')}}</span>    
            </div>
            <div class="text-danger" v-html="errors.code" />
        </div>
        <div class="mb-3 col-md-6">
            <label class="form-label"> {{ __('Voucher Reference') }} </label>
            <input v-model="form.reference" :placeholder="__('Voucher Reference')" type="text" class="form-control" :class="{'is-invalid':errors.reference}" >
            <div class="text-danger" v-html="errors.reference" />
        </div>
        <div class="mb-3 col-md-6">
            <label class="form-label"> {{ __('Voucher Reference Details') }} </label>
            <input v-model="form.reference_details" :placeholder="__('Voucher Reference Details')" type="text" class="form-control" :class="{'is-invalid':errors.reference_details}" >
            <div class="text-danger" v-html="errors.reference_details" />
        </div>
        
        <div class="mb-3 col-md-6">
            <label class="form-label"> {{ __('Currency') }} </label>
            <select class="form-control" v-model="form.currency_id" :class="{'is-invalid':errors.currency_id}">
                <option :value="null"> {{ __('select') }} </option>
                <option v-for="currency,index in currencies" :key="index" :value="currency.id"> {{ currency.prefix }} </option>
            </select>
            <div class="text-danger" v-html="errors.currency_id" />
        </div>
        <div class="mb-3 col-md-6">
            <label class="form-label"> {{ __('Amount') }} </label>
            <div class="input-group">
            <span class="input-group-text" id="basic-addon1"> % </span>   
            <input v-model="form.amount" :placeholder="__('Amount')" type="number" min="0" :step="0.1" class="form-control" :class="{'is-invalid':errors.amount}" >
            </div>
            <div class="text-danger" v-html="errors.amount" />
        </div>
        <div class="mb-3 col-md-6">
            <label class="form-label"> {{ __('Voucher Valid Until') }} </label>
            <input v-model="form.valid_untill" :placeholder="__('Voucher Valid Until')" type="date" class="form-control" :class="{'is-invalid':errors.valid_untill}" >
            <div class="text-danger" v-html="errors.valid_untill" />
        </div>
        <div class="mb-3">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch"
                    id="flexSwitchCheckChecked" v-model="form.status" :checked="form.status==1">
                <label class="form-check-label cursor-pointer" for="flexSwitchCheckChecked">{{__('active status')}}</label>
            </div>
            <div class="text-danger" v-html="errors.status" />
        </div>
       
       
        </div>
        

      
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
            currencies:Array
        },
        data(){
            return {

            }
        },
        methods:{
            generateRandomString(length) {
                const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                let result = '';
                for (let i = 0; i < length; i++) {
                    const randomIndex = Math.floor(Math.random() * characters.length);
                    result += characters.charAt(randomIndex);
                }
                this.form.code= result;
            }
        }
    }

</script>