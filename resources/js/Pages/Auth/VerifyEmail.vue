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
    form.post(route('verification.send'));
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


          <!-- ===== form  ===== -->
          <div class="row justify-content-center">
            <div class="col-md-6">
          <div class="log-form">

            <p class="text-center mb-4">
                Thanks for signing up! Before getting started, could you verify your email address by clicking on the link
                we just emailed to you? If you didn't receive the email, we will gladly send you another.
            </p>
            <div class="mb-4 " v-if="verificationLinkSent">
            A new verification link has been sent to the email address you provided during registration.
        </div>


            <form @submit.prevent="submit">
            <div class="d-flex justify-content-between">
                <button class="custom-btn"  :disabled="form.processing">
                    Resend Verification Email
                </button>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                    >Log Out</Link
                >

            </div>
            </form>

          </div>
          </div>
          </div>


        </div>
      </div>
    </main>



    </GuestLayout>
</template>
