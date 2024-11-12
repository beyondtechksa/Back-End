<script setup>
import GuestLayout from '@/HomeLayouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head :title="__('Register')" />
        <main>
      <div class="Login-user">
        <div class="Login-panel">
          <div class="heading-login">
            <h3>{{__('Welcome To')}}</h3>
            <h2>RIYA</h2>
          </div>

          <div class="User-Login-sign-button">
            <div class="d-flex">
              <Link  :href="route('login')">{{__('Login')}}</Link>
              <Link class="active-login">{{__('Sign up')}}</Link>
            </div>
          </div>

          <!-- ===== form  ===== -->

          <div class="log-form">
            <form @submit.prevent="submit">
              <div class="from-input">
                <label for=""> {{ __('Name') }} </label>
                <input v-model="form.name" type="text" :placeholder="__('Enter your Name')" />
                <div class="text-danger" v-html="form.errors.name"></div>
              </div>
              <div class="from-input mt-3">
                <label for=""> {{ __('Email Address') }} </label>
                <input v-model="form.email" type="email" :placeholder="__('Enter your Email')" />
                <div class="text-danger" v-html="form.errors.email"></div>
              </div>
              <div class="from-input mt-3">
                <label for="">{{__('Password')}}</label>
                <input v-model="form.password" type="password" :placeholder="__('Enter your Password')" />
                <div class="text-danger" v-html="form.errors.password"></div>
              </div>
              <div class="from-input mt-3">
                <label for="">{{__('Password Confirmation')}}</label>
                <input v-model="form.password_confirmation" type="password" :placeholder="__('Confirm your Password')" />
              </div>

              <div class="button-login text-center+">
                <button class="" type="submit">{{ __('Sign up') }}</button>
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
