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
                        href="https://applai.tech" target="_blank" class="text-dark fw-semibold">Applai Teknoloji</a>.
                    All
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
    import {useToast} from "vue-toastification";
    const toast = useToast();

    import '@/js/bootstrap.bundle.min.js'
    import '@/js/sticky.js'
    import '@/js/simplebar.min.js'
    import '@/js/simplebar.js'
    import '@/js/custom.js'
    export default{
        components:{Link, HeaderBox, SidebarBox, Alerts},
        mounted() {
            this.checkSuccessMessage();
        },
        watch: {
            '$page.props.messages.success'(val) {
                if (val)
                    toast.success(val);
            },
        },
        methods: {
            checkSuccessMessage() {
                if (this.$page.props.messages.success) {
                    toast.success(this.$page.props.messages.success);
                }
            }
        }


    }
    </script>
