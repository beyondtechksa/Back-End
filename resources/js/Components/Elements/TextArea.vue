<template>
    <div class="row">
        <div class="mb-3">
            <label class="form-label">
                <span class="" id="basic-addon1">
                    <!-- <img v-lazy="locale.logo" width="30" height="30"> -->
                </span>
                 {{ label }} ({{ model }})
            </label>
            <div class="mb-1">
                <QuillEditor :placeholder="__('start typing here')+'....'" v-model:content="form[model]" :options="options" contentType="html" theme="snow" />
            </div>
        </div>
    </div>


</template>

<script>
import { QuillEditor } from '@vueup/vue-quill'

import '@vueup/vue-quill/dist/vue-quill.snow.css';
import '@vueup/vue-quill/dist/vue-quill.core.css';

    export default{
        props:{
            form:Object,
            label:String,
            model:String,
        },
        components:{QuillEditor},
        data(){
            return {
                options:{
                modules: {
                toolbar: [['bold', 'italic', 'underline'],
                ['emoji'],
                [{ 'color': []},{ 'background': [] }],
                [{ 'align': [] }],
                [{ 'font': [] }],
                [{'size':[]}]
                ],

                }
            },
            }
        }
    }

</script>
