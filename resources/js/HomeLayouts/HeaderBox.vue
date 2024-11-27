<template>
    <!-- Header area -->
    <header class="main-header-area">
        <div class="container-cum" style="overflow:visible !important;    z-index: 9999;">
            <nav class="navbar navbar-expand-lg bg-body-tertiary" :dir="$page.props.locale=='ar'?'rtl':'ltr'">
                <div class="container-fluid" style="padding:0">
                    <!-- ============= -->

                    <!-- ====== Mobile and Teb Selected Language ======= -->
                    <div class="Selected-respons">
                        <div class="select-menu">
                            <div class="select">
                                    <a href="/lang/en" class="d-flex gap-1" v-if="$page.props.locale=='en'">
                                        <img src="/home/img/Ellipse-2.svg" style="width: 25px" alt=""/>
                                        <span>EN</span>
                                    </a>
                                    <a href="/lang/en" class="d-flex gap-1" v-else-if="$page.props.locale=='ar'">
                                        <img src="/home/img/arabic.svg" style="width: 25px" alt=""/>
                                        <span>AR</span>
                                    </a>
                                    <div class="options-list">
                                        <a @click="change_lang('ar')" href="/lang/ar" class=" d-flex gap-1"
                                           v-if="$page.props.locale=='en'">
                                            <img src="/home/img/arabic.svg" style="width: 25px;display:inline" alt=""/>
                                            <span>AR</span>
                                        </a>
                                        <a @click="change_lang('en')" href="/lang/en" class=" d-flex gap-1"
                                           v-else-if="$page.props.locale=='ar'">
                                            <img src="/home/img/Ellipse-2.svg" style="width: 25px;display:inline"
                                                 alt=""/>
                                            <span>EN</span>
                                        </a>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <!-- ====== Mobile and Teb Selected Language ======= -->
                    <Link v-if="base_logo_setting" class="navbar-brand" :href="route('welcome')">
                        <img v-lazy="base_logo_setting" alt="Brand Logo"/>
                    </Link>

                    <!-- ===== Mobile and Teb Notification   ===== -->

                    <div class="Notification-inner">
                        <div class="d-flex gap-3 align-items-center">
                            <Link  :href="route('dashboard')"  class="wishlistlist">
                                <img src="/home/img/icon-user-lg.svg" alt=""/>
                            </Link>
                            <Link :href="route('cart')" class="bel-icon d-flex">
                                <img src="/home/img/iconShop.svg" alt=""/>
                                <span class="count">{{
                                            $page.props.auth.user ? carts.length : countCookie
                                        }}</span>
                            </Link>
                        </div>
                    </div>

                    <!-- =========   Mobile version  ======== -->
                    <div class="align-items-center gap-4 sm-mob-navbar">
                        <div class="beyond-system d-flex gap-2 ms-3 align-items-center">
                            <div class="user">
                                <a href="User-Admin.html">
                                    <img src="/home/img/icon-user-lg.svg" alt=""/>
                                </a>
                            </div>
                            <div class="shop-cart">
                                <a href="#">
                                    <img src="/home/img/iconShop.svg" alt=""/>
                                    <span class="count">0</span>
                                </a>
                            </div>
                        </div>
                        <a
                            href="#"
                            class="offcanvas-button"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasRight"
                            aria-controls="offcanvasRight"
                        >
                            <img src="/home/img/bars.svg" alt=""/>
                        </a>
                    </div>

                    <!-- =========   Mobile version  ======== -->

                    <div class="navbar-collapse justify-content-between gap-4" id="navbarSupportedContent">

                        <div class="categories-list2">
                            <ul>
                                <li v-for="category,index in categories" :key="index">
                                    <a :class="{'active':category.id==main_category.id}" @click="select_main_category(category)" href="javascript:void(0)" >{{ category.translated_name }}</a>


                                </li>

                            </ul>
                        </div>
                    <div class="d-flex">
                    <form @submit.prevent="filter()" class="d-flex position-relative" role="search">
                            <div class="search-input d-flex align-items-center gap-2">
                                <img @click="filter()" src="/home/img/search.svg" alt="" class="cursor-pointer"/>
                                <input
                                    class=""
                                    type="search"
                                    :placeholder=" __('filter')"
                                    aria-label="Search"
                                    v-model="vform.search_value"
                                    @keyup="search_products"
                                />
                                <div class="suggestion-list" :class="{'showNow':vform.search_value}">
                                <div class="products" v-if="products.length>0">
                                   <Link :href="route('product.show',product.id)" class="d-flex product-list gap-2" v-for="product,index in products" :key="index">
                                    <img v-lazy="product.image">
                                    <div class="details">
                                    <h3 class="brand"> <span v-if="product.brand"> {{product.brand.translated_name}} </span> </h3>
                                    <span class="name"> {{substr(product['name_'+$page.props.locale],20)}}</span>
                                    <div class="price d-flex gap-2">

                                        <del v-if="product.discount_percentage_selling_price>0"> {{ __('SAR') }} {{ (product.final_selling_price/(1-(product.discount_percentage_selling_price/100))).toFixed(2) }}  </del>
                                        <span class="final"> {{ __('SAR') }} {{ product.final_selling_price }}  </span>
                                    </div>
                                    </div>
                                   </Link>
                                </div>
                                <div v-else class="text-center py-3">
                                    <i class="ri-user-line"></i>
                                    <Link class="site-btn-sm" :href="route('products.shop')"> {{__('Shop now')}} </Link>
                                </div>
                                </div>
                            </div>
                        </form>
                        <div class="beyond-system d-flex gap-2 ms-3 align-items-center" style="width: 260px;">
                            <div class="user">
                                <a @click="show_user_menu=!show_user_menu, show_user_cart=false" href="javascript:void(0)" >
                                    <img width="28" src="/home/img/icon-user-lg.svg" alt=""/>
                                </a>
                                <div v-if="show_user_menu" class="user-menu">
                                    <h3 class="heading" v-show="$page.props.auth.user"> {{ $page.props.auth.user.name }} </h3>
                                    <div v-if="!$page.props.auth.user" class="btn-list d-flex justify-content-between gap-2">
                                        <Link :href="route('login')" class="btn-outline-main w-100"> {{__('Login')}} </Link>
                                        <Link :href="route('register')" class="site-btn-sm w-100"> {{__('Sign up')}} </Link>
                                    </div>
                                    <div class="links">
                                        <Link :href="route('dashboard')" class="link d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="fa fa-user"></i>
                                                <span> {{ __('My Account') }} </span>
                                            </div>
                                            <i class="fa fa-chevron-right"></i>
                                        </Link>
                                        <Link :href="route('profile.orders')" class="link d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="fa fa-bars"></i>
                                                <span> {{ __('My Orders') }} </span>
                                            </div>
                                            <i class="fa fa-chevron-right"></i>
                                        </Link>
                                        <Link :href="route('profile.track_order')" class="link d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="fa fa-truck"></i>
                                                <span> {{ __('Track My Orders') }} </span>
                                            </div>
                                            <i class="fa fa-chevron-right"></i>
                                        </Link>
                                        <!-- <Link :href="route('profile.addresses')" class="link d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="fa fa-credit-card-alt"></i>
                                                <span> {{ __('Payment Methods') }} </span>
                                            </div>
                                            <i class="fa fa-chevron-right"></i>
                                        </Link> -->
                                        <Link :href="route('profile.addresses')" class="link d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="fa fa-home"></i>
                                                <span> {{ __('My Addresses') }} </span>
                                            </div>
                                            <i class="fa fa-chevron-right"></i>
                                        </Link>
                                        <Link @click="logout()" v-if="$page.props.auth.user" class="link d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="fa fa-sign-out"></i>
                                                <span> {{ __('Log Out') }} </span>
                                            </div>
                                            <i class="fa fa-chevron-right"></i>
                                        </Link>

                                    </div>
                                </div>
                            </div>
                            <div class="shop-cart">
                                <a @click="show_user_cart=!show_user_cart, show_user_menu=false" href="javascript:void(0)">
                                    <img width="28" src="/home/img/iconShop.svg" alt=""/>
                                    <span class="count">{{
                                            $page.props.auth.user ? carts.length : countCookie
                                        }}</span>
                                </a>
                                <div v-if="show_user_cart" class="user-cart">
                                    <h3 class="heading"> {{__('My Cart')}} </h3>
                                    <div v-if="$page.props.auth.user">
                                        <div v-if="carts.length>0">
                                            <div class="products">
                                                <Link :href="route('product.show',cart.product.id)" class="d-flex product-list gap-2" v-for="cart,index in carts" :key="index">
                                                    <img v-lazy="cart.product.image">
                                                    <div class="details">
                                                    <span class="name"> {{substr(cart.product['name_'+$page.props.locale],20)}}</span>
                                                    <div class="d-flex align-items-center justify-content-between gap-2">
                                                        <span  v-if="cart.color"> <strong> {{ __('Color') }} : </strong> {{cart.color}} </span>
                                                        <span  v-if="cart.size"> <strong> {{ __('Size') }} : </strong> {{cart.size}} </span>
                                                    </div>

                                                    <div class="price d-flex gap-2">
                                                        <del v-if="cart.product.discount_percentage_selling_price>0"> {{ (cart.product.final_selling_price/(1-(cart.product.discount_percentage_selling_price/100))).toFixed(2) }}  {{ __('SAR') }} </del>
                                                        <span class="final"> {{ cart.product.final_selling_price }}  {{ __('SAR') }} </span>
                                                    </div>
                                                    </div>
                                                </Link>
                                                </div>
                                            <div class="btn-list mt-3 d-flex justify-content-between gap-2">
                                                <Link :href="route('cart')" class="btn-outline-main w-100"> {{__('View Cart')}} </Link>
                                                <Link :href="route('cart.checkout.address')" class="site-btn-sm w-100"> {{__('Checkout')}} </Link>
                                            </div>
                                            </div>
                                            <div class="mb-2" v-else>
                                                <h4 class="mb-3"> {{ __('You have no items in your shopping cart.') }} </h4>
                                                <Link :href="route('cart')" class="btn-outline-main w-100"> {{__('View Cart')}} </Link>
                                            </div>
                                    </div>
                                    <div v-else-if="countCookie > 0">
                                        <div class="products">
                                            <Link :href="route('product.show',cart.product.id)" class="d-flex product-list gap-2" v-for="cart,index in cookieCart" :key="index">
                                                <img v-lazy="cart.product.image">
                                                <div class="details">
                                                    <span class="name"> {{substr(cart.product['name_'+$page.props.locale],20)}}</span>
                                                    <div class="d-flex align-items-center justify-content-between gap-2">
                                                        <span  v-if="cart.color"> <strong> {{ __('color') }} : </strong> {{ cart.color}} </span>
                                                        <span  v-if="cart.size"> <strong> {{ __('size') }} : </strong> {{ cart.size}} </span>
                                                    </div>
                                                    <div class="price d-flex gap-2">
                                                        <del v-if="cart.product.discount_percentage_selling_price>0">  {{ (cart.product.final_selling_price/(1-(cart.product.discount_percentage_selling_price/100))).toFixed(2) }}  {{ __('SAR') }}</del>
                                                        <span class="final">  {{ cart.product.final_selling_price }}  {{ __('SAR') }} </span>
                                                    </div>
                                                </div>
                                            </Link>
                                        </div>
                                        <div class="btn-list mt-3 d-flex justify-content-between gap-2">
                                            <Link :href="route('cart')" class="btn-outline-main w-100"> {{__('View Cart')}} </Link>
                                            <Link :href="route('cart.checkout.address')" class="site-btn-sm w-100"> {{__('Checkout')}} </Link>
                                        </div>
                                    </div>
                                    <div class="mb-2" v-else>
                                        <h4 class="mb-3"> {{ __('You have no items in your shopping cart.') }} </h4>
                                        <Link :href="route('cart')" class="btn-outline-main w-100"> {{__('View Cart')}} </Link>
                                    </div>
                                </div>
                            </div>
                            <div class="wishlist">
                                <Link :href="route('favourites')">
                                    <img width="28" src="/home/img/icon-heart.svg" alt=""/>
                                    <span class="count"
                                          v-if="$page.props.auth.user">{{
                                            $page.props.auth.user.favourites.length
                                        }}</span>
                                </Link>
                            </div>
                            <div class="select-menu">
                                <div class="select">
                                    <a href="/lang/en" class="d-flex gap-1" v-if="$page.props.locale=='en'">
                                        <img src="/home/img/Ellipse-2.svg" style="width: 25px" alt=""/>
                                        <span>EN</span>
                                    </a>
                                    <a href="/lang/en" class="d-flex gap-1" v-else-if="$page.props.locale=='ar'">
                                        <img src="/home/img/arabic.svg" style="width: 25px" alt=""/>
                                        <span>AR</span>
                                    </a>
                                    <div class="options-list">
                                        <a @click="change_lang('ar')" href="/lang/ar" class=" d-flex gap-1"
                                           v-if="$page.props.locale=='en'">
                                            <img src="/home/img/arabic.svg" style="width: 25px;display:inline" alt=""/>
                                            <span>AR</span>
                                        </a>
                                        <a @click="change_lang('en')" href="/lang/en" class=" d-flex gap-1"
                                           v-else-if="$page.props.locale=='ar'">
                                            <img src="/home/img/Ellipse-2.svg" style="width: 25px;display:inline"
                                                 alt=""/>
                                            <span>EN</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </nav>
        </div>
        <div class="search-response">
            <form class="d-flex" role="search" :dir="$page.props.locale=='ar'?'rtl':'ltr'">
                <div class="search-input d-flex align-items-center gap-2">
                    <img src="/home/img/search.svg" alt="" class=""/>
                    <input
                        class=""
                        type="search"
                        :placeholder="__('What are you looking for ?')"
                        aria-label="Search"
                        v-model="vform.search_value"
                        @keyup="search_products"
                    />
                    <div class="suggestion-list" :class="{'showNow':vform.search_value}">
                                <div class="products" v-if="products.length>0">
                                   <Link :href="route('product.show',product.id)" class="d-flex product-list gap-2" v-for="product,index in products" :key="index">
                                    <img v-lazy="product.image">
                                    <div class="details">
                                    <h3 class="brand"><span v-if="product.brand"> {{product.brand.translated_name}} </span> </h3>
                                    <span class="name"> {{substr(product['name_'+$page.props.locale],20)}}</span>
                                    <div class="price d-flex gap-2">
                                        <del v-if="product.discount_percentage_selling_price"> {{ __('SAR') }} {{ (cart.product.final_selling_price/(1-(cart.product.discount_percentage_selling_price/100))).toFixed(2) }}  </del>
                                        <span class="final"> {{ __('SAR') }} {{ product.final_selling_price }}  </span>
                                    </div>
                                    </div>
                                   </Link>
                                </div>
                                <div v-else class="text-center py-3">
                                    <i class="ri-user-line"></i>
                                    <Link class="site-btn-sm" :href="route('products.shop')"> {{__('Shop now')}} </Link>
                                </div>
                            </div>
                </div>
            </form>
        </div>

        <div class="categories-list2 main-category-mobile">
            <ul>
                <li v-for="category,index in categories" :key="index">
                    <a :class="{'active':category.id==main_category.id}" @click="select_main_category(category)" href="javascript:void(0)" >{{ category.translated_name }}</a>
                </li>
            </ul>
        </div>

        <!-- ======  Categories  =====  -->
        <div class="categories-inner position-relative"
        @mouseleave="main_child=null"
        >
            <div class="categories-content container-cum">
                <div class="categories-list">
                    <ul v-if="main_category">
                        <li class="" v-for="child,index in main_category.children" :key="index">
                            <Link class="" @mouseover="select_main_child(child)" :href="'/shop?category_id='+child.id" >{{ child.translated_name }}</Link>
                        </li>
                        <li class="">
                            <Link class=" sale" @mouseover="main_child=null" :href="'/products?type=sale&category_id='+main_category.id" >
                                {{ __('Sale') }}
                                <img src="/home/img/discount.svg" />
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>
            <div style="height: 300px;
    position: absolute;
    left: 0;
    top: 0px;
    width: 100%;" v-if="main_category && main_child">
            <div class="dropdown-links" v-if="main_category.children.length>0">
                <div class="d-flex">
                <div class="shop-by-categories">
                    <h2 class="heading mb-3"> {{ __('Shop By Category') }} </h2>
                    <div class="row w-100">
                    <div class="col-6" v-for="child2,index in main_child.children" :key="index" >
                    <Link :href="'/shop?category_id='+child2.id"> {{ child2.translated_name }} </Link>
                    </div>
                    </div>
                </div>
                <div class="parent-image">
                    <img v-lazy="main_child.image">
                    <Link class=" mt-3 btn-outline-main w-100 d-block" :href="'/shop?category_id='+main_child.id"> {{__('Shop now')}} </Link>
                </div>
                </div>
            </div>
            </div>
        </div>


        <!-- ===== Mobile Bootom Menu === -->


    </header>

    <nav class="Mobile_nav">
        <div class="nav-box">
            <ul class="nav-container">
                <li class="nav__item">
                    <Link :href="route('welcome')" class="nav__item-link active">
                        <div class="nav__item-icon">
                            <img src="/home/img/home-Regular.svg" alt=""/>
                        </div>
                        <span class="nav__item-text">{{ __('Home') }}</span>
                    </Link>
                </li>
                <li class="nav__item">
                    <Link :href="route('categories')" class="nav__item-link">
                        <div class="nav__item-icon">
                            <img src="/home/img/store.svg" alt=""/>
                        </div>
                        <span class="nav__item-text">{{ __('Categories') }}</span>
                    </Link>
                </li>
                <li class="nav__item">
                    <a href="#" class="nav__item-link">
                        <div class="nav__item-icon">
                            <img src="/home/img/Frame12.svg" alt=""/>
                        </div>
                        <span class="nav__item-text">{{ __('New') }}</span>
                    </a>
                </li>
                <li class="nav__item">
                    <Link :href="route('cart')" class="nav__item-link">
                        <div class="nav__item-icon">
                            <img src="/home/img/iconShop.svg" alt=""/>
                        </div>
                        <span class="nav__item-text">{{ __('Cart') }}</span>
                    </Link>
                </li>
                <li class="nav__item">
                    <Link :href="route('dashboard')" class="nav__item-link">
                        <div class="nav__item-icon">
                            <img src="/home/img/icon-user-lg.svg" alt=""/>
                        </div>
                        <span class="nav__item-text">{{ __('Account') }}</span>
                    </Link>

                </li>
            </ul>
        </div>
    </nav>


    <div @click="show_user_menu=false , show_user_cart=false , vform.search_value=null" v-if="show_user_menu || show_user_cart || main_child || vform.search_value" class="user-menu-overlay">

    </div>

</template>

<script>
import {Link, useForm} from '@inertiajs/vue3';
import Form from 'vform';


export default {
    mounted() {

        let news_bar=this.$page.props.settings.find((e)=>e.slug=='news_bar')
        this.news_bar_setting=news_bar?news_bar.value:null
        axios.get('/get_nav_categories')
            .then((resp) => {
                this.categories = resp.data
                if(resp.data.length>0){
                    this.select_main_category(resp.data[0])
                }
            })
            axios.get(route('cart.get'))
             .then((resp)=>{
                this.carts = resp.data
             })

    },
    components: {Link},
    setup() {
        const form = useForm({
            search:null,
        });
        return {form}
    },
    data() {
        return {
            categories: [],
            news_bar_setting: null,
            logo_setting:null,
            vform:new Form({
                search_value:null,
            }),
            products:[],
            show_user_menu:false,
            show_user_cart:false,
            main_category:null,
            main_child:null,
            cookieCart:null,
            carts:[]
        }
    },
    methods: {
        filter(){
            this.form.search=this.vform.search_value
            this.form.get(route('products.shop'))
        },
        search_products(){
            this.vform.get('/search_products')
                .then((resp)=>{
                    this.products=resp.data
                })
        },
        change_lang(lang) {
            sessionStorage.setItem('locale', lang);

            window.location.href = '/lang/' + lang
        },
        select_main_category(category){
            this.main_category=category
        },
        select_main_child(child){
            this.main_child=child

        },
        getCookieData(){

            axios.get('/get-cookie-cart')
                .then((carts)=>{
                    this.cookieCart=carts.data
                })
        },
        logout(){
            this.form.post(route('logout'))
        }
    },
    computed: {
        countCookie() {
            let cookie = this.getCookie('user_cart')
            if (!cookie){
                this.cookieCart=null
                return 0
            }
            this.getCookieData()
            const decodedString = decodeURIComponent(cookie);
            const jsonObject = JSON.parse(decodedString);
            return jsonObject.length
        }
    },
}

</script>

<style>
    .product-list{margin-bottom: 5px;}
    .product-list img{width:80px;    height: 100px;}
    .product-list .brand{font-weight: bold;font-size: 18px;padding-top: 8px;}
    .product-list .name{color: #646464;
        font-size: 14px;}
    .product-list .price{font-weight: bold;}
    .product-list .price .final{color: #9600c1;}
    .categories-list > ul li:hover .dropdown-links{display: block;}
    .categories-list > ul li {position: relative}

    .dropdown-links{
        position: absolute;
        display: block;
        background: #fff;
        width: 700px;
        min-width: 200px;
        padding: 10px;
        z-index: 99;
        border-radius: 5px;
        top: 32px;
        left: 30px;
        right: 30px;
        box-shadow: 0 4px 10px #6c6a6a;
}
.dropdown-links li {padding-bottom: 5px;}
.dropdown-links li a{display: block;width: 100%;}
.shop-by-categories{
    padding-top: 20px;
    width: 500px;
    text-align: center;
    border-right: 1px solid #ddd;
    max-height: 300px;
    overflow: auto;
}
.shop-by-categories h2{font-weight: bold;font-size: 22px;text-transform: uppercase;color: #000;}
.shop-by-categories a{
    display: block;
    color: #000;
    padding: 10px 40px;
    text-align: left;
}
.parent-image{width: 240px;padding: 10px;}
.parent-image img{height: auto;
    width: 100%;}
.shop-by-categories a:hover{color:#9600c1}
.categories-inner{background:#000;z-index: 999;}
.categories-inner .categories-list > ul li a{
    color:#fff;
    text-transform:uppercase
    }
.sale{    background: #ff1313;
    padding-left: 10px;
    padding-right: 10px;}
    .sale img{width:30px;display: inline-block;}




@media screen and (min-width: 100px) and (max-width: 640px) {

  div#navbarSupportedContent {
    display: none  !important;
}
}
</style>

