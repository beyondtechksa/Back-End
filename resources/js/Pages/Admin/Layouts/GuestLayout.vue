<template>
  <div class="bg-white">

              
              <slot/>
      
  </div>
</template>

<script>
import '@/js/bootstrap.bundle.min.js'
    import '@/js/sticky.js'
    import '@/js/simplebar.min.js'
    import '@/js/simplebar.js'
    import '@/js/custom.js'
  import {Link} from '@inertiajs/vue3'
  export default{
      components:{Link}
  }
</script>