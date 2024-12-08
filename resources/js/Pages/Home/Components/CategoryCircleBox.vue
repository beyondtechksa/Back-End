<template>
    <a @click="go_shop(category)" href="javascript:void(0)" class="box-inner-categories"> 
        <div class="Categories-card-inner">
        <div class="image-box">
            <img v-lazy="category.image" alt="" />
        </div>
        <div class="title pt-3">
            <h5>  {{ category.translated_name }}    </h5>
        </div>
        </div>
    </a>
</template>

<script>
import { useForm } from '@inertiajs/vue3'

export default{
    props:{category:Object},
    data(){
        return {
            form:useForm({
                category_id:this.category.id
            })
        }
    },
    methods:{
        go_shop(category){
            this.form.category_id=category.id
            this.form.get('shop')
        }
    }
}

</script>
