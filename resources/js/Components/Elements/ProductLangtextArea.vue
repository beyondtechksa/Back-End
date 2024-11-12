<template>
    <div class="row">
        <div  v-for="locale,index in $page.props.locales" :key="index" class="mb-3">
            <label class="form-label">
                <span class="" id="basic-addon1">
                    <img v-lazy="locale.logo" width="30" height="30">
                </span>
                 {{ label }} ({{ locale.symbol }})
            </label>
            <div class="mb-1">
                <QuillEditor :placeholder="__('start typing here')+'....'" v-model:content="form['desc_'+locale.symbol]" :options="options" contentType="html" theme="snow" />
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
