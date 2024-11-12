<template>

    <Head :title="$page.props.page_title"/>
    <app>
        <div class="container-cum">
        <h1 class="my-3 fs-30"> {{ page.translated_title }} </h1>
        <div v-html="page.translated_desc"></div>
        </div>
        
    </app>

</template>


<script>
import App from '@/HomeLayouts/AppLayout.vue';
import ProductBox from './Components/ProductBox.vue';

export default {
    components: {App, ProductBox},
    props: {
        page: Object,

    },
    mounted() {
    },
    data() {
        return {
            
        }
    },
    methods: {
        filter() {
            this.form.get('shop', {preserveState: false, preserveScroll: true})
        },
        
    },
   
    
}

</script>



