<script setup>
import GuestLayout from '@/HomeLayouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        
        <div class="container">
        <div class="row my-5 justify-content-center">
            <div class="col-md-4">
              
                 <div class="heading-login mb-3">
                <h3>
                    {{ __("forget your password") }}
                 </h3>
                </div>

                <div v-if="status" class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                    {{ status }}
                </div>

                <div class="log-form">
            <form @submit.prevent="submit">
              <div class="from-input">
                <label for=""> {{ __('Email Address') }} </label>
                <input v-model="form.email" type="email" :placeholder="__('Enter your Email')" />
                <div class="text-danger" v-html="form.errors.email"></div>
              </div>
            
              <div class="button-login text-center+">
                <button  :disabled="form.processing" class="" type="submit">{{ __('Email Password Reset Link') }}</button>
              </div>
            </form>
          </div>


               
            </div>
        </div>
        </div>
        
    </GuestLayout>
</template>
