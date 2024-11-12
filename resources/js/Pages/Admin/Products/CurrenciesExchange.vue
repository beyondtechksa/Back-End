<template>
    <div>

        <ul class="list-unstyled currencies" aria-labelledby="dropdownMenuLink">
            <li v-for="currency,index in currencies" :key="index">
                <a class="dropdown-item d-flex justify-content-between" href="javascript:void(0);">
                  <span> <img width="10" v-lazy="currency.flag" /> {{ currency.prefix }} </span>
                  <span> {{ (value * currency.exchange_rate).toFixed(2) }} </span>
                </a>
        </li>
        </ul>
    </div>
</template>

<script>

    export default{
        props:{
            value:0,
            currencies:Array
        }
    }

</script>

<style>

    .currencies{width: 100%;}
    .currencies li{display: inline-block;
    width: 165px;
    background: #e7e7e7;
    border-left: 1px solid #ababab;}

</style>
