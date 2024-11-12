<template>
    <div class="row">
    <div class="col-lg-12">
        <div class="scroll">
        <label class="form-label"> Colors </label>
        <input @keyup="searching_colors" v-model="search_color" class="form-control" :placeholder=" __('filter')">
        <div class="table-responsive">
            <table class="table text-nowrap">
                <thead>
                    <tr>
                        <th> {{ __('color') }} </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="color,index in searched_colors" :key="index">
                        <td>
                            <input :value="color.id" type="checkbox" v-model="form.colors_ids">
                            {{ color.name.en }} / {{ color.name.ar }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        </div>
    </div>
    <!-- <div class="col-lg-6">
        <div class="scroll">
        <label class="form-label"> Sizes </label>
        <input @keyup="searching_sizes" v-model="search_size" class="form-control" :placeholder=" __('filter')">
        <div class="table-responsive">
            <table class="table text-nowrap">
                <thead>
                    <tr>
                        <th> {{ __('color') }} </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="color,index in searched_sizes" :key="index">
                        <td>
                            <input :value="color.id" type="checkbox" v-model="form.sizes_ids">
                            {{ color.name.en }} / {{ color.name.ar }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        </div>
    </div> -->
    </div>
</template>

<script>

    export default {
        props:{
            colors:Array,
            sizes:Array,
            form:Object,
        },
        data(){
            return {
                search_color:null,
                search_size:null,
                searched_colors:this.colors,
                searched_sizes:this.sizes
            }
        },
        methods:{
            searching_colors(){
                if(this.search_color)
                this.searched_colors=this.colors.filter((el)=>{
                    return (el.name.en && el.name.en.includes(this.search_color))  || (el.name.ar && el.name.ar.includes(this.search_color))
                });
                else
                this.searched_colors=this.colors
            },
            searching_sizes(){
                if(this.search_size)
                this.searched_sizes=this.sizes.filter((el)=>{
                    return el.name.en.toLowerCase().includes(this.search_size)  || el.name.ar.includes(this.search_size)
                });
                else
                this.searched_sizes=this.sizes
            },

        }
    }


</script>

<style>
    .attribue{padding: 6px 10px;
    border-radius: 5px;
    cursor: pointer;}

    .attribue.active{background: #845adf;
    color: #fff;}
    .scroll{height: 400px; overflow: auto;}
</style>
