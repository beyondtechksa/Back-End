<template>
    <Link :href="collection.link??''" :class="classs">
    <div class="collection-box">
        <img :style="'height:'+heightt+'px'" v-lazy="collection.image[$page.props.locale]" alt="" />
        <div class="collection-title">
        <h4 class="text-white">{{collection.name[$page.props.locale]}}</h4>
        </div>
    </div>
    </Link>
</template>

<script>

    export default {
        props:{
            collection:Object,
            classs:String,
            heightt:String
        }
    }
</script>
