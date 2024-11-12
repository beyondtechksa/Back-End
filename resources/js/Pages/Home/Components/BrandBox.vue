<template>
    <Link :href="brand.link">
    <div class="shop_brand_box">
        <img v-lazy="brand.image" alt="" />
        <div class="shop_brand_title">
            <h4>{{brand.name[$page.props.locale]}}</h4>
        </div>
    </div>
    </Link>
</template>


<script>

    export default {
        props:{brand:Object}
    }

</script>
