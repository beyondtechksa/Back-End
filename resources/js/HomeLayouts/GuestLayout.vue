<template>
    
    <header-box></header-box>
    <slot />

    <footer-box></footer-box>

</template>


<script>
import FooterBox from './FooterBox.vue'
import HeaderBox from './HeaderBox.vue'



export default{
  components: { HeaderBox, FooterBox },
    mounted(){
    
    }
}
</script>