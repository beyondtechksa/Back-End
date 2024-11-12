<template>
    <Link :href="collection.link" >
    <div class="our_collectionbox">
        <img v-lazy="collection.image[$page.props.locale]" alt="" />
        <div class="out_collection-title">
        <h4>{{collection.name[$page.props.locale]}}</h4>
        </div>
    </div>
    </Link>

</template>

<script>

    export default {
        props:{
            collection:Object,
            classs:String
        }
    }
</script>
