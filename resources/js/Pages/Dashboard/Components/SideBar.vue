<template>





<div class="col-lg-3 col-md-3">
    <div class="admin-usename">
    <div class="d-flex justify-content-between align-items-center">
    <div>
    <h3>{{$page.props.auth.user.name}}</h3>
    <p>{{$page.props.auth.user.email}}</p>
    </div>
    <div class="show_menu" @click="show_menu=!show_menu">
        <a href="javascript:void(0)" class=""> <i class="fa fa-bars"></i> </a>
    </div>
</div>
    </div>
    <div
    class="nav flex-column nav-pills me-3 admin-collection-button" :class="{'d-block':show_menu}"
    id="v-pills-tab"
    role="tablist"
    aria-orientation="vertical"
    >
    <Link
        class="nav-link"
        :href="route('dashboard')"
    >
        {{__('My Account')}}
        <i class="fa-solid fa-chevron-right"></i>
    </Link>
    <Link
        class="nav-link"
        :href="route('profile.orders')"
    >
        {{__('My Orders')}}
        <i class="fa-solid fa-chevron-right"></i>
    </Link>
    <Link
        class="nav-link"
        :href="route('profile.track_order')"
    >
        {{__('Track My Orders')}}
        <i class="fa-solid fa-chevron-right"></i>
    </Link>
    <!-- <Link
        class="nav-link"
        :href="route('profile.addresses')"
    >
        {{__('Payment Methods')}}
        <i class="fa-solid fa-chevron-right"></i>
    </Link> -->
    <Link
        class="nav-link"
        :href="route('profile.addresses')"
    >
        {{__('My Addresses')}}
        <i class="fa-solid fa-chevron-right"></i>
    </Link>
    <a @click="logout()"
    href="javascript:void(0)"
        class="nav-link"
        id="v-pills-settings-tab"
        data-bs-toggle="pill"
        data-bs-target="#v-pills-settings"
        type="a"
        role="tab"
        aria-controls="v-pills-settings"
        aria-selected="false"
        style="color: #e73737;"
    >
        {{__('Log Out')}}
    </a>
    </div>
</div>

</template>


<script>
import { useForm } from '@inertiajs/vue3'

    export default {
        data(){
            return {
                form :useForm({

                }),
                show_menu:false
            }
        },  
        methods:{
            logout(){
                this.form.post(route('logout'))
            }
        }
    }

</script>

<style scoped>
.d-block{display:block !important}
</style>