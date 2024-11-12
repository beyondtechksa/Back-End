<template>
    <file-manager ref="gallery" @fileUploaded="fileUploaded()"></file-manager>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <span> {{ setting.name }} </span>

        </div>
        <div class="card-body">
            <form @submit.prevent="save_setting()">
                <div class="row position-relative">

                    <div>
                        <div class="mb-3">
                            <input type="number" step="0.1" placeholder="vat %" class="form-control" v-model="form.value">
                        </div>
                    </div>
                </div>
                <div class="text-danger" v-html="form.errors.value"/>
                <div class="hstack gap-2">
                    <button :disabled="form.processing" type="submit" class="btn btn-primary btn-md"><i
                        class="ri-save-line fs-16 align-middle d-inline-block"></i> {{ __('save') }}
                    </button>
                </div>

            </form>
        </div>
    </div>
</template>


<script>
import {useForm} from '@inertiajs/vue3';
import LangtextInput from '@/Components/Elements/LangtextInput.vue';
import FileManager from '@/Components/FileManager.vue';

export default {
    components: {LangtextInput, FileManager},
    props: {setting: Object, collections: Array},
    data() {
        return {
            form: useForm({
                id: this.setting.id,
                value: this.setting.value,
            }),
        }
    },
    methods: {
        save_setting() {
            this.form.post(route('settings.update'))
        },

    }
}

</script>

<style>

</style>
