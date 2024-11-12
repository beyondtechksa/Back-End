<template>

    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-theme="blue_theme"  data-layout="horizontal" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
        
        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">
      
            <slot />
            
      </div>
      
    </div>

    
    </template>


    <script>
    import { Link } from '@inertiajs/vue3';
    import HeaderBox from './HeaderBox.vue';
    import FooterBox from './FooterBox.vue';
    import Alerts from '@/Components/Alerts.vue';
    export default{
        components:{Link, HeaderBox, FooterBox, Alerts}
    }
    </script>