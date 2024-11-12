<script setup>
import GuestLayout from '@/HomeLayouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Reset Password" />

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="log-form">
                    <form @submit.prevent="submit">
                    
                    <div class="from-input mt-3">
                        <label for="">{{__('Password')}}</label>
                        <input v-model="form.password" type="password" :placeholder="__('Enter your Password')" />
                        <div class="text-danger" v-html="form.errors.password"></div>
                    </div>
                    <div class="from-input mt-3">
                        <label for="">{{__('Confirm Password')}}</label>
                        <input v-model="form.password_confirmation" type="password" :placeholder="__('Enter your Password')" />
                        <div class="text-danger" v-html="form.errors.password_confirmation"></div>
                    </div>
                    
                    <div class="button-login text-center+">
                        <button class="" type="submit">{{ __('Reset password') }}</button>
                    </div>
                    </form>
                </div>

                    
                </div>
            </div>
        </div>
        
    </GuestLayout>
</template>
