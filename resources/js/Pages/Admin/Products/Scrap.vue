<template>
    <div>

                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon1">
                        <select class="form-control" v-model="scrap_form.site">
                            <option :value="null"> {{ __('select site for scraping') }} </option>
                            <option value="https://www.trendyol.com/"> {{ __('trendyol') }} </option>
                        </select>
                    </span>
                    <input v-model="scrap_form.url" type="text" class="form-control" :placeholder="__('Enter the url of product')"
                        aria-label="Username" >
                    <button :disabled="scrap_form.busy" @click="fetch_date()" class="btn btn-primary"> {{ __('fetch data') }} </button>
                </div>
                <div class="text-danger" v-html="scrap_form.errors.get('url')">  </div>
        </div>
      
       
</template>



<script>
import Form from 'vform';
import AuthLayout from '../Layouts/AuthLayout.vue'
import PageHeader from '@/Components/PageHeader.vue'
    export default{
        components: { AuthLayout, PageHeader},
        props:{form:Object},
        data(){
            return {
                scrap_form:new Form({
                    site:null,
                    url:null,
                }),
                product:null
            }
        },
        methods:{
            fetch_date(){
                this.scrap_form.post('/admin/scrap-products')
                    .then((resp)=>{
                        console.log(resp.data)
                        // this.form.name.en=resp.data.name
                        // this.form.name.ar=resp.data.name
                        
                        // this.form.desc.en=resp.data.desc
                        // this.form.desc.ar=resp.data.desc
                        
                        // this.form.price=parseInt(resp.data.price.slice(0, -3))
                        // this.form.image=resp.data.images.length>0?resp.data.images[0]:null

                        // if(resp.data.images.length>2){
                        //     this.form.files=[]
                        //     resp.data.images.forEach((el,index)=>{
                        //         if(index>0){
                        //             let element =  {id:null,image:el}
                        //             this.form.files.push(element)
                        //         }
                        //     })
                        // }
                        
                        
                    })
            }
        }
        
    }

</script>