<template>
    <section class="">
          <div class="container-cum">
            <div class="banner-area-full">
              <Link :href="setting.link">
                <img class="mobile-hidden" :style="'height:'+heightt" v-lazy="setting.value[$page.props.locale]" alt="banner-content" />
                <img class="web-hidden" style="height:200px" v-lazy="setting.value['mobile_'+$page.props.locale]" alt="banner-content" />
              </Link>
            </div>
          </div>
        </section>
</template>

<script>
    export default {
        props:{
            setting:Object,
            heightt:{
              type:Object,
              default:'auto'
            }
        }
    }
</script>
