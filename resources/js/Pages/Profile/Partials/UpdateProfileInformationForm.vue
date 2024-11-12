<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    first_name:user.first_name,
    last_name:user.last_name,
    email:user.email,
    date_of_birth:user.date_of_birth,
    place_of_residence:user.place_of_residence,
    qualification:user.qualification,
    specialization:user.specialization,
    language_level:user.language_level,
    phone:user.phone,
});

    function upload(e){
            let file=e.target.files[0]
            form.avatar=file
            // const reader=new FileReader()
            // reader.onload= ()=>{
            //     form.avatar=reader.result
            // }
            // reader.readAsDataURL(file)
    }

</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100"> {{ __('Profile Information') }} </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __("Update your account's profile information and email address.") }}
            </p>
        </header>

        <form @submit.prevent="form.post(route('profile.update'))" class="mt-6 space-y-6">
            <div class="row">
            <div class="col-md-6">
            <div>
                <InputLabel for="first_name" :value="__('first name')" />

                <TextInput
                    id="first_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.first_name"
                    required
                    autofocus
                    autocomplete="first_name"
                />

                <InputError class="mt-2" :message="form.errors.first_name" />
            </div>
            </div>
            <div class="col-md-6">
            <div>
                <InputLabel for="last_name" :value="__('last name')" />

                <TextInput
                    id="last_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.last_name"
                    required
                    autofocus
                    autocomplete="last_name"
                />

                <InputError class="mt-2" :message="form.errors.last_name" />
            </div>
            </div>
            <div class="col-md-6">
            <div class="mt-4">
                <InputLabel for="date_of_birth" :value="__('date of birth')" />

                <TextInput
                    id="date_of_birth"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.date_of_birth"
                    required
                    autofocus
                    autocomplete="date_of_birth"
                />

                <InputError class="mt-2" :message="form.errors.date_of_birth" />
            </div>
            </div>
            <div class="col-md-6">
            <div class="mt-4">
                <InputLabel for="place_of_residence" :value="__('place of residence')" />

                <TextInput
                    id="place_of_residence"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.place_of_residence"
                    required
                    autofocus
                    autocomplete="place_of_residence"
                />

                <InputError class="mt-2" :message="form.errors.place_of_residence" />
            </div>
            </div>
            <div class="col-md-6">
            <div class="mt-4">
                <InputLabel for="qualification" :value="__('qualification')" />

                <TextInput
                    id="qualification"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.qualification"
                    required
                    autofocus
                    autocomplete="qualification"
                />

                <InputError class="mt-2" :message="form.errors.qualification" />
            </div>
            </div>
            <div class="col-md-6">
            <div class="mt-4">
                <InputLabel for="specialization" :value="__('specialization')" />

                <TextInput
                    id="specialization"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.specialization"
                    required
                    autofocus
                    autocomplete="specialization"
                />

                <InputError class="mt-2" :message="form.errors.specialization" />
            </div>
            </div>
            <div class="col-md-6">
            <div class="mt-4">
                <InputLabel for="language_level" :value="__('language level')" />

                <TextInput
                    id="language_level"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.language_level"
                    required
                    autofocus
                    autocomplete="language_level"
                />

                <InputError class="mt-2" :message="form.errors.language_level" />
            </div>
            </div>
            
            <div class="col-md-6">
            <div class="mt-4">
                <InputLabel for="phone" :value="__('phone')" />

                <TextInput
                    id="phone"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.phone"
                    required
                    autofocus
                    autocomplete="phone"
                />

                <InputError class="mt-2" :message="form.errors.phone" />
            </div>
            </div>
            <div class="col-md-12">
            <div class="mt-4">
                <InputLabel for="email" :value="__('email')" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>
            </div>
           
        </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                    {{ __('Your email address is unverified.') }}
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                    >
                        {{ __('Click here to re-send the verification email.') }}
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 font-medium text-sm text-green-600 dark:text-green-400"
                >
                   {{ __('A new verification link has been sent to your email address.') }}
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">{{__('save')}}</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">{{ __('saved') }}.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
