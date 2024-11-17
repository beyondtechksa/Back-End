<template>
    <div v-if="links.length>3" class="mt-3">

    <nav aria-label="Page navigation">
        <ul class="pagination mb-0 float-end">
          <li class="page-item" v-for="(link,index) in links" :key="index">
              <Link :disabled="link.url == null" class="page-link" :class="{'active':link.active}" :href="link.url+'&search='+search" tabindex="-1" v-html="link.label"  />
          </li>

        </ul>
    </nav>

  </div>

  </template>

  <script>
  import {Link} from '@inertiajs/vue3'
  export default {
    components:{Link},
    props: {
      links: Array,
      search:{
        type:String,
        default:''
      }
    },
  }
  </script>
