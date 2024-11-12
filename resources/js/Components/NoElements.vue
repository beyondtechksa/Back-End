<template>
 
 <div class="text-center pb-5 py-5 px-5 pb-0">
    <svg class="custom-alert-icon svg-dark" style="font-size: 100px;" xmlns="http://www.w3.org/2000/svg" height="4rem" viewBox="0 0 24 24" width="4rem" fill="#000000"><path d="M0 0h24v24H0z" fill="none"></path><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"></path></svg>
    <h3 class="pt-1 fs-40 text-capitalize"> {{ __('alert') }} ! </h3>
    <p class="fs-20"> {{ __('there are no elements found in this page') }} </p>
    
</div>
</template>

<script>
export default {

}
</script>

