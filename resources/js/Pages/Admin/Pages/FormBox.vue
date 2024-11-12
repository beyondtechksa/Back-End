<template>

    <div>
        <div class="row">
                    <div class="mb-3">
                        <langtext-input :label="__('page name')" :form="form"></langtext-input>
                        <div class="text-danger" v-html="errors.name" />
                    </div>
                    <div class="mb-3">
                        <langtext-input :label="__('page title')" model="title" :form="form"></langtext-input>
                        <div class="text-danger" v-html="errors.title" />
                    </div>
                    <div class="mb-3">
                    <langtext-area :quillEditor="true" :label="__('page description')" :column="form.desc"
                                    :form="form"></langtext-area>
                    <div class="text-danger" v-html="errors.desc"/>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch"
                                id="flexSwitchCheckChecked" v-model="form.show_in_footer_bar" :checked="form.show_in_footer_bar==1">
                            <label class="form-check-label cursor-pointer" for="flexSwitchCheckChecked">{{__('show in footer bar')}}</label>
                        </div>
                        <div class="text-danger" v-html="errors.show_in_footer_bar" />
                    </div>


                </div>
    </div>
</template>


<script>

import LangtextInput from '@/Components/Elements/LangtextInput.vue'
import LangtextArea from '@/Components/Elements/LangtextArea.vue'
export default{
  components: { LangtextInput, LangtextArea },
        props:{
            form:Object,
            errors:Object,
        },
        methods:{

        }


    }


</script>
