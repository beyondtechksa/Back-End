<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/HomeLayouts/GuestLayout.vue';

import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head :title="__('Log in')" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <main>
      <div class="Login-user">
        <div class="Login-panel">
          <div class="heading-login">
            <h3>{{__('Welcome Back To')}}</h3>
            <h2>RIYA</h2>
          </div>

          <div class="User-Login-sign-button">
            <div class="d-flex">
              <Link href="#" class="active-login">{{__('Login')}}</Link>
              <Link :href="route('register')">{{__('Sign up')}}</Link>
            </div>
          </div>

          <!-- ===== form  ===== -->

          <div class="log-form">
            <form @submit.prevent="submit">
              <div class="from-input">
                <label for=""> {{ __('Email Address') }} </label>
                <input v-model="form.email" type="email" :placeholder="__('Enter your Email')" />
                <div class="text-danger" v-html="form.errors.email"></div>
              </div>
              <div class="from-input mt-3">
                <label for="">{{__('Password')}}</label>
                <input v-model="form.password" type="password" :placeholder="__('Enter your Password')" />
                <div class="text-danger" v-html="form.errors.password"></div>
              </div>
              <div class="forgot">
                <Link :href="route('password.request')" class="text-end">{{ __('Forgot your Passowrd ?') }}</Link>
              </div>
              <div class="button-login text-center+">
                <button class="" type="submit">{{ __('Login') }}</button>
              </div>
            </form>
          </div>

          <div class="other-login">
            <h3>Or via :</h3>

            <div class="social-auth">
              <a href="/auth/google">
                <img src="/home/img/Google.png" alt="" />
              </a>
              <!-- <a href="#">
                <img src="/home/img/facebook.png" alt="" />
              </a> -->
              <a href="/auth/apple">
                <img src="/home/img/apple.png" alt="" />
              </a>
            </div>


          </div>
        </div>
      </div>
    </main>



    </GuestLayout>
</template>
