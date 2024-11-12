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
