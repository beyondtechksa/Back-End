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
                            <img src="/assets/images/logo.png" alt="logo" class="desktop-logo">
                            <img src="/assets/images/logo.png" alt="logo" class="toggle-logo">
                        </a>
                    </div>
                </div>
                <!-- End::header-element -->

                <!-- Start::header-element -->
                <div class="header-element">
                    <!-- Start::header-link -->
                    <a aria-label="Hide Sidebar"
                       class="sidemenu-toggle header-link animated-arrow hor-toggle horizontal-navtoggle"
                       data-bs-toggle="sidebar" href="javascript:void(0);"><span></span></a>
                    <!-- End::header-link -->
                </div>
                <!-- End::header-element -->

            </div>
            <!-- End::header-content-left -->

            <!-- Start::header-content-right -->
            <div class="header-content-right">






                <!-- Start::header-element -->

                <!-- End::header-element -->
                <div class="header-element notifications-dropdown">
                    <!-- Start::header-link|dropdown-toggle -->
                    <a href="javascript:void(0);" class="header-link dropdown-toggle" data-bs-toggle="dropdown"
                       data-bs-auto-close="outside" id="messageDropdown" aria-expanded="false">
                        <i class="bx bx-bell header-link-icon"></i>
                        <span class="badge bg-secondary rounded-pill header-icon-badge pulse pulse-secondary"
                              id="notification-icon-badge">{{ notifications.length }}</span>
                    </a>
                    <!-- End::header-link|dropdown-toggle -->
                    <!-- Start::main-header-dropdown -->
                    <div class="main-header-dropdown dropdown-menu dropdown-menu-end" data-popper-placement="none">
                        <div class="p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <p class="mb-0 fs-17 fw-semibold">Notifications</p>
                                <span class="badge bg-secondary-transparent" id="notifiation-data">{{
                                        notifications.length
                                    }} Unread</span>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <ul class="list-unstyled mb-0" id="header-notification-scroll" v-if="notifications.length > 0">
                            <!--                            loop here-->
                            <li class="dropdown-item" v-for="not,index in notifications" :key="index">
                                <div class="d-flex align-items-start">
                                    <div class="pe-2">
                                        <span class="avatar avatar-md bg-primary-transparent avatar-rounded"><i
                                            class="ti ti-gift fs-18"></i></span>
                                    </div>
                                    <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                                        <div>
                                            <p @click="markAsRead(not.id)" class="mb-0 fw-semibold cursor-pointer">
                                                <span>      {{ not.data.text }}</span>
                                            </p>
                                            <span class="text-muted fw-normal fs-12 header-notification-text">
                                               {{ formateDate(not.created_at) }}
                                            </span>
                                        </div>

                                    </div>
                                </div>
                            </li>
                        </ul>
                        <!--                        <div class="p-3 empty-header-item1 border-top">-->
                        <!--                            <div class="d-grid">-->
                        <!--                                <a :href="route('notifications.index')" class="btn btn-primary">View All</a>-->
                        <!--                            </div>-->
                        <!--                        </div>-->
                        <div class="p-5 empty-item1 d-none" v-if="notifications.length === 0">
                            <div class="text-center">
                        <span class="avatar avatar-xl avatar-rounded bg-secondary-transparent">
                            <i class="ri-notification-off-line fs-2"></i>
                        </span>
                                <h6 class="fw-semibold mt-3">No New Notifications</h6>
                            </div>
                        </div>
                    </div>
                    <!-- End::main-header-dropdown -->
                </div>




                <!-- Start::header-element -->
                <div class="header-element">
                    <!-- Start::header-link|dropdown-toggle -->
                    <a href="#" class="header-link dropdown-toggle" id="mainHeaderProfile" data-bs-toggle="dropdown"
                       data-bs-auto-close="outside" aria-expanded="false">
                        <div class="d-flex align-items-center">
                            <div class="me-sm-2 me-0">
                                <img v-if="$page.props.auth.shipping" v-lazy="$page.props.auth.shipping.image" alt="img"
                                     width="32" height="32" class="rounded-circle">
                                <img v-else v-lazy="$page.props.auth.default_img" alt="img" width="32" height="32"
                                     class="rounded-circle">
                            </div>
                            <div class="d-sm-block d-none">
                                <p class="fw-semibold mb-0 lh-1">{{ $page.props.auth.shipping.name }}</p>
                                <span class="op-7 fw-normal d-block fs-11"> shipping </span>
                            </div>
                        </div>
                    </a>
                    <!-- End::header-link|dropdown-toggle -->
                    <ul class="main-header-dropdown dropdown-menu pt-0 overflow-hidden header-profile-dropdown dropdown-menu-end"
                        aria-labelledby="mainHeaderProfile">


                        <li><a @click="logout()" class="dropdown-item d-flex" href="javascript:void(0)"><i
                            class="ti ti-logout fs-18 me-2 op-7"></i>{{ __('logout') }}</a></li>
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
import {Link, useForm} from '@inertiajs/vue3';

export default {
    components: {Link},
    setup() {
        const form = useForm({
            email: '',
            password: ''
        });
        return {form}
    },
    data() {
        return {
            sounds: [],
            notifications: [],
            orders: [],
            unread: ''
        }
    },
    mounted() {
        this.loadNotifications()
        // this.loadOrders()
    },
    methods: {
        logout() {
            this.form.post(route('shipping.logout'))
        },
        collapse_menu() {
            let el = document.getElementById('main-wrapper')
            let attribute = el.getAttribute('data-sidebartype')
            if (attribute == 'full') {
                el.setAttribute('data-sidebartype', 'mini-sidebar')
            } else {
                el.setAttribute('data-sidebartype', 'full')
            }
        },
        play(music) {
            let audio = document.getElementById('music' + music.id)
            if (this.sounds.includes(music.id)) {
                audio.pause()
                const index = this.sounds.indexOf(music.id);
                if (index > -1) { // only splice array when item is found
                    this.sounds.splice(index, 1); // 2nd parameter means remove one item only
                }
            } else {
                audio.play()
                this.sounds.push(music.id)
            }

        },
        change_volume(music) {
            let audio = document.getElementById('music' + music.id)
            let volume = document.getElementById('customRange' + music.id).value
            audio.volume = volume
        },
        loadNotifications() {
            axios.get(route('shipping.notifications'))
                .then((resp) => {
                    this.notifications = resp.data
                })
        },

        markAsRead(id) {
            axios.put(route('shipping.mark.read', id))
                .then((response) => {
                    if (response && response.data.notification) {
                        let not = response.data.notification.data
                        this.$inertia.get(not.link)

                    }
                })
        }
    }
}

</script>

