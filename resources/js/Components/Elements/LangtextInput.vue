<template>
     <div class="row" v-if="model!=null">
            <div class="col-lg-6" v-for="locale,index in $page.props.locales" :key="index">
                <label class="form-label"> {{ label }} ({{ locale.symbol }}) </label>
                <div class="input-group mb-1">
                    <span class="input-group-text" id="basic-addon1">
                        <img v-lazy="locale.logo" width="30" height="30">
                    </span>
                    <input v-model="form[model][locale.symbol]" type="text" class="form-control" :placeholder="label">
                </div>
            </div>
    </div>

    <div class="row" v-else>
            <div class="col-lg-6" v-for="locale,index in $page.props.locales" :key="index">
               <div class="d-flex justify-content-between align-items-center">
                 <label class="form-label"> {{ label }} ({{ locale.symbol }}) </label>
                 <div class="btn-list" v-if="ai">
                    <button class="btn btn-primary btn-sm"> {{ __('Ai translate') }} </button>
                    <button class="btn btn-success btn-sm"> {{ __('Ai rename') }} </button>
                 </div>
                 </div>
                <div class="input-group mb-1">
                    <span class="input-group-text" id="basic-addon1">
                        <img v-lazy="locale.logo" width="30" height="30">
                    </span>
                    <input v-model="form.name[locale.symbol]" type="text" class="form-control" :placeholder="label">
                </div>
            </div>
    </div>

</template>

<script>

    export default{
        props:{
            form:Object,
            label:String,
            model:String,
            ai:Boolean
        }
    }

</script>
