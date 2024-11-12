<template>
    
    
    
    <div class="page">
        <!-- Sidebar Start -->
        <header-box></header-box>
        <sidebar-box></sidebar-box>

        <div class="main-content app-content">
            <slot />
            <alerts></alerts>
      </div>
      <footer class="footer mt-auto py-3 bg-white text-center">
            <div class="container">
                <span class="text-muted"> Copyright © <span id="year"></span> <a
                        href="javascript:void(0);" class="text-dark fw-semibold">Ynex</a>.
                    Designed with <span class="bi bi-heart-fill text-danger"></span> by <a href="javascript:void(0);">
                        <span class="fw-semibold text-primary text-decoration-underline">Spruko</span>
                    </a> All
                    rights
                    reserved
                </span>
            </div>
        </footer>
    </div>

    
    </template>


    <script>
    import { Link } from '@inertiajs/vue3';
    import HeaderBox from './HeaderBox.vue';
    import SidebarBox from './SidebarBox.vue';
    import Alerts from '@/Components/Alerts.vue';

    import '@/js/bootstrap.bundle.min.js'
    import '@/js/sticky.js'
    import '@/js/simplebar.min.js'
    import '@/js/simplebar.js'
    import '@/js/custom.js'
    export default{
        components:{Link, HeaderBox, SidebarBox, Alerts},
        mounted(){

        }
    }
    </script>