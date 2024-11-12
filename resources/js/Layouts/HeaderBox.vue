<template>
<header class="app-header">

<!-- Start::main-header-container -->
<div class="main-header-container container-fluid">

    <!-- Start::header-content-left -->
    <div class="header-content-left">

        <!-- Start::header-element -->
        <div class="header-element">
            <div class="horizontal-logo">
                <a href="index.html" class="header-logo">
                    <img src="/assets/images/brand-logos/desktop-logo.png" alt="logo" class="desktop-logo">
                    <img src="/assets/images/brand-logos/toggle-logo.png" alt="logo" class="toggle-logo">
                    <img src="/assets/images/brand-logos/desktop-dark.png" alt="logo" class="desktop-dark">
                    <img src="/assets/images/brand-logos/toggle-dark.png" alt="logo" class="toggle-dark">
                    <img src="/assets/images/brand-logos/desktop-white.png" alt="logo" class="desktop-white">
                    <img src="/assets/images/brand-logos/toggle-white.png" alt="logo" class="toggle-white">
                </a>
            </div>
        </div>
        <!-- End::header-element -->

        <!-- Start::header-element -->
        <div class="header-element">
            <!-- Start::header-link -->
            <a aria-label="Hide Sidebar" class="sidemenu-toggle header-link animated-arrow hor-toggle horizontal-navtoggle" data-bs-toggle="sidebar" href="javascript:void(0);"><span></span></a>
            <!-- End::header-link -->
        </div>
        <!-- End::header-element -->

    </div>
    <!-- End::header-content-left -->


    <!-- Start::header-content-right -->
    <div class="header-content-right">

        <!-- Start::header-element -->
        <div class="header-element">
            <!-- Start::header-link|dropdown-toggle -->
            <a href="#" class="header-link dropdown-toggle" id="mainHeaderProfile" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                <div class="d-flex align-items-center">
                    <div class="me-sm-2 me-0">
                        <img v-if="$page.props.auth.admin.avatar" v-lazy="$page.props.auth.admin.avatar" alt="img" width="32" height="32" class="rounded-circle">
                        <img v-else v-lazy="$page.props.auth.default_img" alt="img" width="32" height="32" class="rounded-circle">
                    </div>
                    <div class="d-sm-block d-none">
                        <p class="fw-semibold mb-0 lh-1">{{$page.props.auth.admin.name}}</p>
                        <span class="op-7 fw-normal d-block fs-11">{{$page.props.auth.role}}</span>
                    </div>
                </div>
            </a>
            <!-- End::header-link|dropdown-toggle -->
            <ul class="main-header-dropdown dropdown-menu pt-0 overflow-hidden header-profile-dropdown dropdown-menu-end" aria-labelledby="mainHeaderProfile">
                <li><Link class="dropdown-item d-flex" :href="route('profile.basic_info')"><i class="ti ti-user-circle fs-18 me-2 op-7"></i> {{ __('profile') }} </Link></li>
                <DropdownLink :href="route('logout')" method="post" as="button">
                    Log Out
                </DropdownLink>
            </ul>
        </div>
        <!-- End::header-element -->



</div>
<!-- End::header-content-right -->

</div>
<!-- End::main-header-container -->

</header>
</template>

<script>
import DropdownLink from '@/Components/DropdownLink.vue';
import { Link , useForm} from '@inertiajs/vue3';
    export default{
        components:{Link, DropdownLink},
        setup(){
        const form =useForm({
            email:'',
            password:''
            });
            return {form}
        },
        data(){
            return {
                sounds:[],
            }
        },
        methods:{
            logout(){
                this.form.post(route('admin.logout'))
            },
            collapse_menu(){
                let el=document.getElementById('main-wrapper')
               let attribute =el.getAttribute('data-sidebartype')
               if(attribute=='full'){
                el.setAttribute('data-sidebartype','mini-sidebar')
               }else{
                el.setAttribute('data-sidebartype','full')
               }
            },
            play(music){
                let audio = document.getElementById('music'+music.id)
                if(this.sounds.includes(music.id)){
                   audio.pause()
                   const index = this.sounds.indexOf(music.id);
                        if (index > -1) { // only splice array when item is found
                        this.sounds.splice(index, 1); // 2nd parameter means remove one item only
                        }
                }else{
                    audio.play()
                    this.sounds.push(music.id)
                }

            },
            change_volume(music){
                let audio = document.getElementById('music'+music.id)
                let volume=document.getElementById('customRange'+music.id).value
                audio.volume=volume
            }
        }
    }

</script>

