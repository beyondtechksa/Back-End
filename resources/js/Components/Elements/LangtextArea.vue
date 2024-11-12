<template>
    <div class="row" v-if="quillEditor">
        <div  v-for="locale,index in $page.props.locales" :key="index" class="mb-3">
            <label class="form-label">
                <span class="" id="basic-addon1">
                    <img v-lazy="locale.logo" width="30" height="30">
                </span>
                 {{ label }} ({{ locale.symbol }})
            </label>
            <div class="mb-1">
                <QuillEditor :placeholder="__('start typing here')+'....'" v-model:content="form.desc[locale.symbol]" :options="options" contentType="html" theme="snow" />
            </div>
        </div>
    </div>
    <div class="row" v-else>
        <div  v-for="locale,index in $page.props.locales" :key="index">
            <label class="form-label"> {{ label }} ({{ locale.symbol }}) </label>
            <div class="input-group mb-1">
                <span class="input-group-text" id="basic-addon1">
                    <img v-lazy="locale.logo" width="30" height="30">
                </span>
                <textarea v-model="form.desc[locale.symbol]" rows="3" class="form-control" :placeholder="label"></textarea>
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
            quillEditor:Boolean,
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
