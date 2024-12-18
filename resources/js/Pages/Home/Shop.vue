<template>

    <Head :title="$page.props.page_title"/>
    <app>

        <div class="shop-filter-option">
            <div class="shop-serv">
                <a href="javascript:void(0)" class="Recommended">
                    <img src="/home/img/sliders-h.svg" alt=""/>
                    <span>{{ __('Recommended ranking') }}</span>
                </a>
                <div class="select-dropdown">
                    <ul class="sortFilter">
                        <li @click="filter_data.sort = 'desc'">{{ __('Latest') }}</li>
                        <li @click="filter_data.sort = 'high_discount'">{{ __('Highest Desicount') }}</li>
                        <li @click="filter_data.sort = 'low_price'">{{ __('Price (Low First)') }}</li>
                        <li @click="filter_data.sort = 'high_price'">{{__('Price (High First)')}}</li>
                    </ul>
                </div>
            </div>

          <div class="product-filter filterbutton ">
            <a href="javascript:void(0)" class="shopFilterButton active-btn d-flex" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
              <span>Filter</span>
              <img src="/home/img/filter.svg" alt="">
            </a>
          </div>
        </div>
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight">
      <div class="offcanvas-header">
        <div class=""></div>
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="offcanvas"
          aria-label="Close"
        ></button>
      </div>
      <div class="offcanvas-body">
        <div class="filter-top-heading">
          <h3 class="d-flex align-items-center gap-1">
            <a href="javascript:void(0)">
              <img src="/img/Close.svg" alt="" data-bs-dismiss="offcanvas" aria-label="Close">
            </a>
            <span class="fs-5"> Filter</span>
          </h3>
          <a  data-bs-dismiss="offcanvas" aria-label="Close">close </a>
        </div>

        <div
                                        class="accordion accordion-flush filter-category"
                                        id="accordionFlushExample"
                                    >
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button
                                                    class="accordion-button collapsed"
                                                    type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#flush-collapseOne"
                                                    aria-expanded="false"
                                                    aria-controls="flush-collapseOne"
                                                >
                                                    {{ __('Categories') }}
                                                </button>
                                            </h2>
                                            <div
                                                id="flush-collapseOne"
                                                class="accordion-collapse collapse"
                                                data-bs-parent="#accordionFlushExample"
                                            >
                                                <div class="accordion-body">
                                                    <div class="list">
                                                        <form class="d-flex position-relative" role="search">
                                                            <div class="search-input d-flex align-items-center gap-2" style="height: 43px;padding: 0 8px;">
                                                                <img src="/home/img/search.svg" alt="" class="">

                                                                <input v-model="search_categories" class="" type="search" :placeholder=" __('filter')" aria-label="Search">


                                                        </div>
                                                    </form>

                                                        <ul class="py-3">
                                                            <li class="level1" v-for="(category,index) in filteredCategories"
                                                                :key="index">
                                                                <label @click="toggle_select_category(category)" class="main-category d-flex justify-content-between"
                                                                       :for="'category-check'+index">
                                                                      <span> {{ category.translated_name }} </span>
                                                                      <span v-if="check_opened_category(category)"> <i class="fa fa-chevron-up"></i> </span>
                                                                      <span v-else> <i class="fa fa-chevron-down"></i> </span>
                                                                </label>

                                                                <ul v-if="category.children && category.children.length>0 && check_opened_category(category)">
                                                                    <li v-for="(sub, subIndex) in category.children"
                                                                        :key="subIndex">
                                                                        <div v-if="sub.children && sub.children.length > 0 ">
                                                                            <label @click="toggle_select_category(sub)" class="main-category d-flex justify-content-between"
                                                                                   :for="'subcategory-check' + index + '-' + subIndex">
                                                                                <span> {{ sub.translated_name }} </span>
                                                                                <span v-if="check_opened_category(sub)"> <i class="fa fa-chevron-up"></i> </span>
                                                                                <span v-else> <i class="fa fa-chevron-down"></i> </span>
                                                                            </label>
                                                                        </div>
                                                                        <div class="d-flex justify-content-between align-items-center" v-else>
                                                                        <div>
                                                                        <input type="checkbox"
                                                                               :id="'subcategory-check' + index + '-' + subIndex"
                                                                               v-model="filter_data.subCategories"
                                                                               :value="sub.id"
                                                                               @change="filterData"
                                                                               :checked="isChecked(sub.id)"
                                                                        />
                                                                        <label class="px-2"
                                                                               :for="'subcategory-check' + index + '-' + subIndex">
                                                                               {{ substr(sub.translated_name,18)}}</label>
                                                                        </div>
                                                                        <span class="badge bg-dark">{{sub.products_count}}</span>
                                                                        </div>

                                                                        <ul v-show="check_opened_category(sub)">
                                                                            <li v-for="(subTwo, subIndexTwo) in sub.children"
                                                                                :key="subIndexTwo">
                                                                                <div  v-if="subTwo.children && subTwo.children.length">
                                                                                    <label @click="toggle_select_category(subTwo)" class="main-category d-flex justify-content-between"
                                                                                           :for="'subTwoCategory-check' + index + '-' + subIndex + '-' + subIndexTwo">
                                                                                        <span> {{ subTwo.translated_name }} </span>
                                                                                        <span v-if="check_opened_category(subTwo)"> <i class="fa fa-chevron-up"></i> </span>
                                                                                        <span v-else> <i class="fa fa-chevron-down"></i> </span>
                                                                                    </label>
                                                                                </div>
                                                                                <div class="d-flex justify-content-between align-items-center" v-else>
                                                                                    <div>
                                                                                        <input type="checkbox"
                                                                                               :id="'subTwoCategory-check' + index + '-' + subIndex + '-' + subIndexTwo"
                                                                                               v-model="filter_data.subCategories"
                                                                                               :value="subTwo.id"
                                                                                               @change="filterData"
                                                                                               :checked="isChecked(subTwo.id)"
                                                                                        />
                                                                                        <label class="px-2"
                                                                                               :for="'subTwoCategory-check' + index + '-' + subIndex + '-' + subIndexTwo">
                                                                                            {{ substr(subTwo.translated_name,18)}}</label>
                                                                                    </div>
                                                                                    <span class="badge bg-dark">{{subTwo.products_count}}</span>
                                                                                </div>
                                                                                <ul v-show="check_opened_category(subTwo)">
                                                                                    <li v-for="(subThree, subIndexThree) in subTwo.children"
                                                                                        :key="subIndexThree">
                                                                                        <div  v-if="subThree.children && subThree.children.length > 0">
                                                                                            <label @click="toggle_select_category(subThree)" class="main-category d-flex justify-content-between"
                                                                                                   :for="'subThreeCategory-check' + index + '-' + subIndex + '-' + subIndexTwo + '-' + subIndexThree">
                                                                                                <span> {{ subThree.translated_name }} </span>
                                                                                                <span v-if="check_opened_category(subThree)"> <i class="fa fa-chevron-up"></i> </span>
                                                                                                <span v-else> <i class="fa fa-chevron-down"></i> </span>
                                                                                            </label>
                                                                                        </div>
                                                                                        <div class="d-flex justify-content-between align-items-center" v-else>
                                                                                            <div>
                                                                                                <input type="checkbox"
                                                                                                       :id="'subThreeCategory-check' + index + '-' + subIndex + '-' + subIndexTwo + '-' + subIndexThree"
                                                                                                       v-model="filter_data.subCategories"
                                                                                                       :value="subThree.id"
                                                                                                       @change="filterData"
                                                                                                       :checked="isChecked(subThree.id)"
                                                                                                />
                                                                                                <label class="px-2"
                                                                                                       :for="'subThreeCategory-check' + index + '-' + subIndex + '-' + subIndexTwo + '-' + subIndexThree">
                                                                                                    {{ substr(subThree.translated_name,18)}}</label>
                                                                                            </div>
                                                                                            <span class="badge bg-dark">{{subThree.products_count}}</span>
                                                                                        </div>

                                                                                    </li>
                                                                                </ul>


                                                                            </li>
                                                                        </ul>
                                                                    </li>
                                                                </ul>

                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button
                                                    class="accordion-button collapsed"
                                                    type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#flush-collapseTwo"
                                                    aria-expanded="false"
                                                    aria-controls="flush-collapseTwo"
                                                >
                                                    {{ __('Brands') }}
                                                </button>
                                            </h2>
                                            <div
                                                id="flush-collapseTwo"
                                                class="accordion-collapse collapse"
                                                data-bs-parent="#accordionFlushExample"
                                            >
                                                <div class="accordion-body">
                                                    <div class="filter-check ps-2">
                                                        <div class="check-box" v-for="brand,index in filteredBrands"
                                                             :key="index">
                                                            <input type="checkbox" :id="'brand-check'+index"
                                                                   v-model="filter_data.brands" :value="brand.id"
                                                                   @change="filterData"
                                                                   :checked="isCheckedBrand(brand.id)"
                                                            />
                                                            <label class="px-2"
                                                                   :for="'brand-check'+index">{{
                                                                    brand.translated_name
                                                                }}</label>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button
                                                    class="accordion-button collapsed"
                                                    type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#flush-collapseThree"
                                                    aria-expanded="false"
                                                    aria-controls="flush-collapseThree"
                                                >
                                                    {{__('Colors')}}
                                                </button>
                                            </h2>
                                            <div
                                                id="flush-collapseThree"
                                                class="accordion-collapse collapse"
                                                data-bs-parent="#accordionFlushExample"
                                            >
                                                <div class="accordion-body">
                                                    <div class="color-filter">
                                                        <form action="" class="pt-2 ps-2">
                                                            <div class="color-box">
                                                                <a v-for="color,index in colors" href="javascript:void(0)" :key="index"  @click="filter_data.color=color.id">
                                                                    <span v-if="color.image" class="sm-box">
                                                                        <img v-lazy="color.image">
                                                                    </span>
                                                                    <span v-else class="sm-box" :style="{ background: color.color_code }"></span>

                                                                    {{ color.translated_name }}
                                                                </a>

                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button
                                                    class="accordion-button collapsed"
                                                    type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#flush-collapseage"
                                                    aria-expanded="false"
                                                    aria-controls="flush-collapseage"
                                                >
                                                    {{__('Age')}}
                                                </button>
                                            </h2>
                                            <div
                                                id="flush-collapsrange"
                                                class="accordion-collapse collapse"
                                                data-bs-parent="#accordionFlushExample"
                                            >
                                                <div class="accordion-body">
                                                    <div class="wrapper">
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->

                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button
                                                    class="accordion-button collapsed"
                                                    type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#flush-collapseDiscount"
                                                    aria-expanded="false"
                                                    aria-controls="flush-collapseDiscount"
                                                >
                                                {{__('Discount')}}
                                                </button>
                                            </h2>
                                            <div
                                                id="flush-collapseDiscount"
                                                class="accordion-collapse collapse"
                                                data-bs-parent="#accordionFlushExample"
                                            >
                                                <div class="accordion-body">
                                                    <div class="wrapper">

                                                        <div class="mt-3 px-2">
                                                        <Slider @mouseup="handleRangeInputMouseDown" v-model="filter_data.discount" :min="0" :max="80" :range="true" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
<!--                                        <div class="accordion-item">-->
<!--                                            <h2 class="accordion-header">-->
<!--                                                <button-->
<!--                                                    class="accordion-button collapsed"-->
<!--                                                    type="button"-->
<!--                                                    data-bs-toggle="collapse"-->
<!--                                                    data-bs-target="#flush-collapsestock"-->
<!--                                                    aria-expanded="false"-->
<!--                                                    aria-controls="flush-collapsestock"-->
<!--                                                >-->
<!--                                                    {{ __('Stock') }}-->
<!--                                                </button>-->
<!--                                            </h2>-->
<!--                                            <div-->
<!--                                                id="flush-collapsestock"-->
<!--                                                class="accordion-collapse collapse"-->
<!--                                                data-bs-parent="#accordionFlushExample"-->
<!--                                            >-->
<!--                                                <div class="accordion-body">-->
<!--                                                    <div class="filter-check ps-2">-->
<!--                                                        <form action="">-->
<!--                                                            <div class="check-box">-->
<!--                                                                <input type="radio" v-model="filter_data.stock"-->
<!--                                                                       @change="filterData" value="in stock"-->
<!--                                                                       id="Stock"/>-->
<!--                                                                <label class="px-2" for="Stock"> {{ __('In Stock') }}</label>-->
<!--                                                            </div>-->
<!--                                                            <div class="check-box">-->
<!--                                                                <input type="radio" v-model="filter_data.stock"-->
<!--                                                                       @change="filterData" value="out of stock"-->
<!--                                                                       id="Stock2"/>-->
<!--                                                                <label class="px-2" for="Stock2"> {{ __('Out Of Stock') }} </label>-->
<!--                                                            </div>-->
<!--                                                            &lt;!&ndash;                                                            <div class="check-box">&ndash;&gt;-->
<!--                                                            &lt;!&ndash;                                                                <input type="checkbox" id="Upcomming"/>&ndash;&gt;-->
<!--                                                            &lt;!&ndash;                                                                <label for="Upcomming"> Upcomming </label>&ndash;&gt;-->
<!--                                                            &lt;!&ndash;                                                            </div>&ndash;&gt;-->
<!--                                                            &lt;!&ndash;                                                            <div class="check-box">&ndash;&gt;-->
<!--                                                            &lt;!&ndash;                                                                <input type="radio" v-model="filter_data.stock" value="in stock" id="Stock"/>&ndash;&gt;-->
<!--                                                            &lt;!&ndash;                                                                <label for="Stock"> Upcomming </label>&ndash;&gt;-->
<!--                                                            &lt;!&ndash;                                                            </div>&ndash;&gt;-->
<!--                                                        </form>-->
<!--                                                    </div>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </div>-->
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button
                                                    class="accordion-button collapsed"
                                                    type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#flush-collapssize"
                                                    aria-expanded="false"
                                                    aria-controls="flush-collapssize"
                                                >
                                                {{ __('Sizes') }}
                                                </button>
                                            </h2>
                                            <div
                                                id="flush-collapssize"
                                                class="accordion-collapse collapse"
                                                data-bs-parent="#accordionFlushExample"
                                            >
                                                <div class="accordion-body">
                                                    <div class="size-filter">
                                                        <form action="" class="pt-2 ps-2">
                                                            <div class="size-box">
                                                                <a v-for="size,index in sizes" href="javascript:void(0)" @click="filter_data.size=size.id">{{ size.translated_name }}</a>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button
                                                    class="accordion-button collapsed"
                                                    type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#flush-collapsrange"
                                                    aria-expanded="false"
                                                    aria-controls="flush-collapsrange"
                                                >
                                                {{ __('Price') }}
                                                </button>
                                            </h2>
                                            <div
                                                id="flush-collapsrange"
                                                class="accordion-collapse collapse"
                                                data-bs-parent="#accordionFlushExample"
                                            >
                                                <div class="accordion-body">
                                                    <div class="wrapper">

                                                        <div class="mt-3 px-2">
                                                        <Slider @mouseup="handleRangeInputMouseDown" v-model="filter_data.price" :min="0" :max="4000" :range="true" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button
                                                    class="accordion-button collapsed"
                                                    type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#flush-collapsGender"
                                                    aria-expanded="false"
                                                    aria-controls="flush-collapsGender"
                                                >
                                                {{ __('Gender') }}
                                                </button>
                                            </h2>
                                            <div
                                                id="flush-collapsGender"
                                                class="accordion-collapse collapse"
                                                data-bs-parent="#accordionFlushExample"
                                            >
                                                <div class="accordion-body">
                                                    <div class="Gender ps-2">
                                                        <ul>
                                                            <li>
                                                                <input type="checkbox" id="Man"/>
                                                                <label class="px-2" for="Man">Man</label>
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" id="Womans"/>
                                                                <label class="px-2" for="Womans">Womans</label>
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" id="Kids"/>
                                                                <label class="px-2" for="Kids" href="javascript:void(0)">Kids</label>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->


                                    </div>
      </div>
    </div>


        <main class="overflow-hidden shop-pages">
            <section class="shop_container">
                <div class="container-cum">
                    <div class="shop-content">
                        <div class="row">
                            <div class="col-xl-3 col-lg-4">
                                <div class="shop-left-container">
                                    <!-- =======  Filter Shop  ====== -->

                                    <div class="filter-top-heading">
                                        <h3>{{ __('Filter') }}</h3>
                                        <a :href="route('products.shop')">{{ __('Clear all') }}</a>
                                        <button ></button>
                                    </div>
                                    <div
                                        class="accordion accordion-flush filter-category"
                                        id="accordionFlushExample"
                                    >
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button
                                                    class="accordion-button collapsed"
                                                    type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#flush-collapseOne"
                                                    aria-expanded="false"
                                                    aria-controls="flush-collapseOne"
                                                >
                                                    {{ __('Categories') }}
                                                </button>
                                            </h2>
                                            <div
                                                id="flush-collapseOne"
                                                class="accordion-collapse collapse"
                                                data-bs-parent="#accordionFlushExample"
                                            >
                                                <div class="accordion-body">
                                                    <div class="list">
                                                        <form class="d-flex position-relative" role="search">
                                                            <div class="search-input d-flex align-items-center gap-2" style="height: 43px;padding: 0 8px;">
                                                                <img src="/home/img/search.svg" alt="" class="">

                                                                <input v-model="search_categories" class="" type="search" :placeholder=" __('filter')" aria-label="Search">


                                                        </div>
                                                    </form>

                                                    <ul class="py-3">
                                                            <li class="level1" v-for="(category,index) in filteredCategories"
                                                                :key="index">
                                                                <label @click="toggle_select_category(category)" class="main-category d-flex justify-content-between"
                                                                       :for="'category-check'+index">
                                                                      <span> {{ category.translated_name }} </span>
                                                                      <span v-if="check_opened_category(category)"> <i class="fa fa-chevron-up"></i> </span>
                                                                      <span v-else> <i class="fa fa-chevron-down"></i> </span>
                                                                </label>

                                                                <ul v-if="category.children && category.children.length>0 && check_opened_category(category)">
                                                                    <li v-for="(sub, subIndex) in category.children"
                                                                        :key="subIndex">
                                                                        <div v-if="sub.children && sub.children.length > 0 ">
                                                                            <label @click="toggle_select_category(sub)" class="main-category d-flex justify-content-between"
                                                                                   :for="'subcategory-check' + index + '-' + subIndex">
                                                                                <span> {{ sub.translated_name }} </span>
                                                                                <span v-if="check_opened_category(sub)"> <i class="fa fa-chevron-up"></i> </span>
                                                                                <span v-else> <i class="fa fa-chevron-down"></i> </span>
                                                                            </label>
                                                                        </div>
                                                                        <div class="d-flex justify-content-between align-items-center" v-else>
                                                                        <div>
                                                                        <input type="checkbox"
                                                                               :id="'subcategory-check' + index + '-' + subIndex"
                                                                               v-model="filter_data.subCategories"
                                                                               :value="sub.id"
                                                                               @change="filterData"
                                                                               :checked="isChecked(sub.id)"
                                                                        />
                                                                        <label class="px-2"
                                                                               :for="'subcategory-check' + index + '-' + subIndex">
                                                                               {{ substr(sub.translated_name,18)}}</label>
                                                                        </div>
                                                                        <span class="badge bg-dark">{{sub.products_count}}</span>
                                                                        </div>

                                                                        <ul v-show="check_opened_category(sub)">
                                                                            <li v-for="(subTwo, subIndexTwo) in sub.children"
                                                                                :key="subIndexTwo">
                                                                                <div  v-if="subTwo.children && subTwo.children.length">
                                                                                    <label @click="toggle_select_category(subTwo)" class="main-category d-flex justify-content-between"
                                                                                           :for="'subTwoCategory-check' + index + '-' + subIndex + '-' + subIndexTwo">
                                                                                        <span> {{ subTwo.translated_name }} </span>
                                                                                        <span v-if="check_opened_category(subTwo)"> <i class="fa fa-chevron-up"></i> </span>
                                                                                        <span v-else> <i class="fa fa-chevron-down"></i> </span>
                                                                                    </label>
                                                                                </div>
                                                                                <div class="d-flex justify-content-between align-items-center" v-else>
                                                                                    <div>
                                                                                        <input type="checkbox"
                                                                                               :id="'subTwoCategory-check' + index + '-' + subIndex + '-' + subIndexTwo"
                                                                                               v-model="filter_data.subCategories"
                                                                                               :value="subTwo.id"
                                                                                               @change="filterData"
                                                                                               :checked="isChecked(subTwo.id)"
                                                                                        />
                                                                                        <label class="px-2"
                                                                                               :for="'subTwoCategory-check' + index + '-' + subIndex + '-' + subIndexTwo">
                                                                                            {{ substr(subTwo.translated_name,18)}}</label>
                                                                                    </div>
                                                                                    <span class="badge bg-dark">{{subTwo.products_count}}</span>
                                                                                </div>
                                                                                <ul v-show="check_opened_category(subTwo)">
                                                                                    <li v-for="(subThree, subIndexThree) in subTwo.children"
                                                                                        :key="subIndexThree">
                                                                                        <div  v-if="subThree.children && subThree.children.length > 0">
                                                                                            <label @click="toggle_select_category(subThree)" class="main-category d-flex justify-content-between"
                                                                                                   :for="'subThreeCategory-check' + index + '-' + subIndex + '-' + subIndexTwo + '-' + subIndexThree">
                                                                                                <span> {{ subThree.translated_name }} </span>
                                                                                                <span v-if="check_opened_category(subThree)"> <i class="fa fa-chevron-up"></i> </span>
                                                                                                <span v-else> <i class="fa fa-chevron-down"></i> </span>
                                                                                            </label>
                                                                                        </div>
                                                                                        <div class="d-flex justify-content-between align-items-center" v-else>
                                                                                            <div>
                                                                                                <input type="checkbox"
                                                                                                       :id="'subThreeCategory-check' + index + '-' + subIndex + '-' + subIndexTwo + '-' + subIndexThree"
                                                                                                       v-model="filter_data.subCategories"
                                                                                                       :value="subThree.id"
                                                                                                       @change="filterData"
                                                                                                       :checked="isChecked(subThree.id)"
                                                                                                />
                                                                                                <label class="px-2"
                                                                                                       :for="'subThreeCategory-check' + index + '-' + subIndex + '-' + subIndexTwo + '-' + subIndexThree">
                                                                                                    {{ substr(subThree.translated_name,18)}}</label>
                                                                                            </div>
                                                                                            <span class="badge bg-dark">{{subThree.products_count}}</span>
                                                                                        </div>

                                                                                    </li>
                                                                                </ul>


                                                                            </li>
                                                                        </ul>
                                                                    </li>
                                                                </ul>

                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button
                                                    class="accordion-button collapsed"
                                                    type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#flush-collapseTwo"
                                                    aria-expanded="false"
                                                    aria-controls="flush-collapseTwo"
                                                >
                                                    {{ __('Brands') }}
                                                </button>
                                            </h2>
                                            <div
                                                id="flush-collapseTwo"
                                                class="accordion-collapse collapse"
                                                data-bs-parent="#accordionFlushExample"
                                            >
                                                <div class="accordion-body">
                                                    <form class="d-flex position-relative" role="search">
                                                        <div  class="search-input d-flex align-items-center gap-2" style="height: 43px; padding: 0px 8px;">
                                                            <img  src="/home/img/search.svg" alt="" class="">
                                                            <input  v-model="search_brands" class="" type="search" placeholder="Search" aria-label="Search">
                                                        </div>
                                                        </form>
                                                    <div class="filter-check ps-2">
                                                        <div class="check-box" v-for="brand,index in filteredBrands"
                                                             :key="index">
                                                            <input type="checkbox" :id="'brand-check'+index"
                                                                   v-model="filter_data.brands" :value="brand.id"
                                                                   @change="filterData"
                                                                   :checked="isCheckedBrand(brand.id)"
                                                            />
                                                            <label class="px-2"
                                                                   :for="'brand-check'+index">{{
                                                                    brand.translated_name
                                                                }}</label>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button
                                                    class="accordion-button collapsed"
                                                    type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#flush-collapseThree"
                                                    aria-expanded="false"
                                                    aria-controls="flush-collapseThree"
                                                >
                                                    {{__('Colors')}}
                                                </button>
                                            </h2>
                                            <div
                                                id="flush-collapseThree"
                                                class="accordion-collapse collapse"
                                                data-bs-parent="#accordionFlushExample"
                                            >
                                                <div class="accordion-body">
                                                    <div class="color-filter">
                                                        <form action="" class="pt-2 ps-2">
                                                            <div class="color-box">
                                                                <a v-for="color,index in colors" href="javascript:void(0)" :key="index"  @click="filter_data.color=color.id">
                                                                    <span v-if="color.image" class="sm-box">
                                                                        <img v-lazy="color.image">
                                                                    </span>
                                                                    <span v-else class="sm-box" :style="{ background: color.color_code }"></span>

                                                                    {{ color.translated_name }}
                                                                </a>

                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button
                                                    class="accordion-button collapsed"
                                                    type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#flush-collapseage"
                                                    aria-expanded="false"
                                                    aria-controls="flush-collapseage"
                                                >
                                                    {{__('Age')}}
                                                </button>
                                            </h2>
                                            <div
                                                id="flush-collapsrange"
                                                class="accordion-collapse collapse"
                                                data-bs-parent="#accordionFlushExample"
                                            >
                                                <div class="accordion-body">
                                                    <div class="wrapper">
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->

                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button
                                                    class="accordion-button collapsed"
                                                    type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#flush-collapseDiscount"
                                                    aria-expanded="false"
                                                    aria-controls="flush-collapseDiscount"
                                                >
                                                {{__('Discount')}} (%)
                                                </button>
                                            </h2>
                                            <div
                                                id="flush-collapseDiscount"
                                                class="accordion-collapse collapse"
                                                data-bs-parent="#accordionFlushExample"
                                            >
                                                <div class="accordion-body">
                                                    <div class="wrapper">
                                                        <div class="mt-3 px-2">
                                                        <Slider @mouseup="handleRangeInputMouseDown" v-model="filter_data.discount" :min="0" :max="80" :range="true" />
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button
                                                    class="accordion-button collapsed"
                                                    type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#flush-collapssize"
                                                    aria-expanded="false"
                                                    aria-controls="flush-collapssize"
                                                >
                                                {{ __('Sizes') }}
                                                </button>
                                            </h2>
                                            <div
                                                id="flush-collapssize"
                                                class="accordion-collapse collapse"
                                                data-bs-parent="#accordionFlushExample"
                                            >
                                                <div class="accordion-body">
                                                    <div class="size-filter">
                                                        <form action="" class="pt-2 ps-2">
                                                            <div class="size-box">
                                                                <a v-for="size,index in sizes" href="javascript:void(0)" @click="filter_data.size=size.id">{{ size.translated_name }}</a>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button
                                                    class="accordion-button collapsed"
                                                    type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#flush-collapsrange"
                                                    aria-expanded="false"
                                                    aria-controls="flush-collapsrange"
                                                >
                                                {{ __('Price') }} ({{__('SAR')}})
                                                </button>
                                            </h2>
                                            <div
                                                id="flush-collapsrange"
                                                class="accordion-collapse collapse"
                                                data-bs-parent="#accordionFlushExample"
                                            >
                                                <div class="accordion-body">
                                                    <div class="wrapper">
                                                        <div class="mt-3 px-2">
                                                        <Slider @mouseup="handleRangeInputMouseDown" v-model="filter_data.price" :min="0" :max="4000" :range="true" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button
                                                    class="accordion-button collapsed"
                                                    type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#flush-collapsGender"
                                                    aria-expanded="false"
                                                    aria-controls="flush-collapsGender"
                                                >
                                                {{ __('Gender') }}
                                                </button>
                                            </h2>
                                            <div
                                                id="flush-collapsGender"
                                                class="accordion-collapse collapse"
                                                data-bs-parent="#accordionFlushExample"
                                            >
                                                <div class="accordion-body">
                                                    <div class="Gender ps-2">
                                                        <ul>
                                                            <li>
                                                                <input type="checkbox" id="Man"/>
                                                                <label class="px-2" for="Man">Man</label>
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" id="Womans"/>
                                                                <label class="px-2" for="Womans">Womans</label>
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" id="Kids"/>
                                                                <label class="px-2" for="Kids" href="javascript:void(0)">Kids</label>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->


                                    </div>
                                </div>
                                <!-- =======  Mobile Response Filter  ========= -->
                            </div>
                            <div class="col-xl-9 col-lg-8">
                                <div class="shop-right-container" ref="targetDiv">
                                    <div class="shop-right-heading-content d-lg-block">

                                        <div
                                            class="shop-featurch d-flex justify-content-between mb-5"
                                        >
                                            <div class="shop-serv">
                                                <a href="javascript:void(0)" @click="filter_data.rate = 'high'" class="scored">
                                                    <img src="/home/img/star-Filled.svg" alt=""/>
                                                    <span>{{ __('Highly Scored Products') }}</span>
                                                </a>
                                                <a href="javascript:void(0)" class="delivery">
                                                    <img src="/home/img/package.svg" alt=""/>
                                                    <span>{{ __('Fast Delivery') }}</span>
                                                </a>
                                            </div>
                                            <div class="shop-serv">
                                                <a @click="recomended=!recomended" href="javascript:void(0)" class="Recommended">
                                                    <img src="/home/img/sliders-h.svg" alt=""/>
                                                    <span>{{ __('Recommended ranking') }}</span>
                                                </a>
                                                <div v-show="recomended" class="select-dropdown2">
                                                    <ul class="sortFilter">
                                                        <li @click="filter_data.sort = 'desc'">{{ __('Latest') }}</li>
                                                        <li @click="filter_data.sort = 'high_discount'">{{ __('Highest Desicount') }}</li>
                                                        <li @click="filter_data.sort = 'low_price'">{{ __('Price (Low First)') }}</li>
                                                        <li @click="filter_data.sort = 'high_price'">{{__('Price (High First)')}}</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- ======   Shop Products   =====   -->
                                    <div class="row shop-row">
                                        <div class="col-xl-3 col-lg-6 col-md-6 col-6" v-for="product,index in products"
                                             :key="index">
                                            <product-box :product="product"></product-box>
                                        </div>


                                        <!-- <div v-if="loading">
                                            <div class="spinner"></div>
                                        </div> -->
                                        <div id="load-more-box" ref="loadMoreBox" class="text-center w-100" v-if="products.length>0">
                                        <!-- <button style="margin: 0 auto;" @click="load_more()" class="site-btn"> {{__('Load More')}} </button> -->
                                        </div>
                                        <div v-if="loading" style="width: 100px;margin: 20px auto;">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="no-results" v-if="products.length==0">
                                        <h4> No Results Found </h4>
                                    </div>
                                    <!-- ======== Shop  Products  ======== -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </app>

</template>


<script>
import App from '@/HomeLayouts/AppLayout.vue';
import ProductBox from './Components/ProductBox.vue';
import {useForm} from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3'
import Slider from '@vueform/slider'

export default {
    components: {App, ProductBox,Slider,},
    props: {
        products: Array,
        categories: Array,
        brands: Array,
        sizes: Array,
        category_id:null,
        brand_id:null,
        // collection_id:null,
        product_type:null,
        colors:Array
    },
    mounted() {


        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                this.load_more();
                }
            });
            });

            observer.observe(this.$refs.loadMoreBox);



        if (this.category_id){
            this.filter_data.subCategories = this.category_id
            this.previousFilterData.subCategories = this.category_id
            // this.filterData()
        }
        if (this.brand_id){
            this.filter_data.brands = this.brand_id
            this.previousFilterData.brands = this.brand_id
            // this.filterData()
        }
        if (this.product_type){
            this.filter_data.product_type = this.product_type
            this.previousFilterData.product_type = this.product_type
        }
        // if (this.collection_id){
        //     this.filter_data.collection_id = this.collection_id
        //     this.previousFilterData.collection_id = this.collection_id
        // }
        this.filteredCategories = this.categories;
        this.filteredBrands = this.brands;
        // window.addEventListener('scroll', this.handleScroll);
    },
    beforeDestroy() {
        // window.removeEventListener('scroll', this.handleScroll);
    },
    data() {
        return {
            search_categories: null,
            search_brands: null,
            filteredCategories: [],
            filteredBrands: [],
            filter_data: {
                // 'categories': [],
                'subCategories': [],
                'brands': [],
                // 'stock': '',
                'size': '',
                'color':'',
                'price': [0,100000],
                'discount': [0,100],
                'limit': 12,
                'offset': 0, // Initial offset
                'sort':'',
                'rate':'',
                'product_type':'',
                // 'collection_id':''
            },
            priceRange:[0,10000],
            discountRange:[0,100],
            form: useForm({
                limit: this.$page.props.request.limit ? parseInt(this.$page.props.request.limit) : 12,
                category_id: this.$page.props.request.category_id,
            }),
            loading: false,
            selected_category_id:null,
            opend_categories_ids:[],
            recomended:false,
            previousFilterData: {
                'subCategories': [],
                'brands': [],
                // 'stock': '',
                'size': '',
                'color':'',
                'price': [0,100000],
                'discount': [0,100],
                'limit': 12,
                'offset': 0, // Initial offset
                'sort':'',
                'rate':'',
                'product_type':'',
                // 'collection_id':''

            },

        }
    },
    computed:{

    },
    methods: {
        toggle_select_category(category){
            let opend_categories_ids = this.opend_categories_ids
            if(opend_categories_ids.includes(category.id)){
                let index = opend_categories_ids.indexOf(category.id)
                opend_categories_ids.splice(index,1)
            }else{
                opend_categories_ids.push(category.id)
            }
        },
        check_opened_category(category){
            let opend_categories_ids = this.opend_categories_ids
            if(opend_categories_ids.includes(parseInt(category.id))){
                if(category.category_id!=null && !opend_categories_ids.includes(parseInt(category.category_id))){
                    return false
                }
                return true
            }
            return false
        },
        searchCats() {
            const query = this.search_categories.trim().toLowerCase();
            if (!query) {
                this.filteredCategories = this.categories;
                return;
            }
            function filterChildren(children) {
                return children.map(sub => {
                    if (sub.children) {
                        sub.children = filterChildren(sub.children);
                    }
                    const subMatches = sub.translated_name.toLowerCase().includes(query);
                    const hasFilteredChildren = sub.children && sub.children.length > 0;

                    if (subMatches || hasFilteredChildren) {
                        return sub;
                    } else {
                        return null;
                    }
                }).filter(sub => sub !== null);
            }
            this.filteredCategories = this.categories.map(category => {
                const filteredSubcategories = filterChildren(category.children || [])
                const categoryMatches = category.translated_name.toLowerCase().includes(query);
                const hasFilteredSubcategories = filteredSubcategories.length > 0;

                if (categoryMatches || hasFilteredSubcategories) {
                    return {
                        ...category,
                        children: filteredSubcategories
                    };
                }
            }).filter(category => category !== undefined);

            if (this.filteredCategories.length === 0)
                this.filteredCategories = [];

        },
        load_more() {
            this.loading = true
            setTimeout((e)=>{
                this.filter_data.offset += 12;
                // this.filter_data.limit += 12
                this.filterData()
            },1000)
        },
        filterData() {
            this.sendFilterRequest()
            this.loading = false
        },
        handleRangeInputMouseDown() {
            setTimeout((e)=>{
                this.filterData()
            },1000)
        },

         sendFilterRequest() {
             axios.post('/filter-shop-request', {'filter_data': this.filter_data}).then(res => {
                this.loading = false
                 if (this.hasFilterChanged()) {
                     this.filter_data.offset = 0
                     this.previousFilterData = { ...this.filter_data };
                     this.products.splice(0, this.products.length);
                     window.scrollTo({ top: 0, behavior: 'smooth' });
                     res.data.forEach(item => {
                         this.products.push(item);
                     });
                 } else {
                     if (res && res.data.length === 0 )
                         this.filter_data.offset = 0
                     this.products.push(...res.data);
                 }
                 // this.products.splice(0, this.products.length);
                 // res.data.forEach(item => {
                 //     this.products.push(item);
                 // });
                 // this.products.push(...res.data); // Append products

             }).catch(err => {
                console.log(err)
            })
        },
        isChecked(categoryId) {
            return this.category_id === categoryId;
        },
        isCheckedBrand(brandId) {
            return this.filter_data.brands.includes(brandId);
        },
        hasFilterChanged() {
            const { offset, ...currentFilter } = this.filter_data;
            const { offset: prevOffset, ...prevFilter } = this.previousFilterData || {};
            console.log('currentFilter',currentFilter)
            console.log('prevFilter',prevFilter)
            return  JSON.stringify(currentFilter) !== JSON.stringify(prevFilter);
        }
    },
    watch: {
        'filter_data.size'(val) {
            if (val)
                this.filterData()
        },
        'filter_data.color'(val) {
            if (val)
                this.filterData()
        },
        'filter_data.sort'(val) {
            if (val)
                this.filterData()
        },
        search_categories() {
            this.searchCats();
        },
        search_brands() {
            const query = this.search_brands.toLowerCase();
            if (!query) {
                this.filteredBrands = this.brands.filter(brand => brand.products_count > 0);
                return;
            }

            this.filteredBrands = this.brands.filter((brand) => {
                return brand.products_count > 0 && brand.translated_name.toLowerCase().includes(query);
            });
        },
        'filter_data.discount'(val){
            if (val)
                this.filterData()
        },
        'filter_data.price'(val){
            if (val)
                this.filterData()
        },
        'filter_data.rate'(val){
            if (val)
                this.filterData()
        }
    },

}

</script>


<style scoped>
.main-category{
    width: 100%;
    border-bottom: 1px solid #e5e5e5;
    margin-bottom: 8px;
    padding-bottom: 8px;
    font-size: 15px;
}
.level1 > label{font-size:16px !important}
.level1 > ul li label{font-size: 14px !important;
    color: #303030;
    font-weight: 400;}
.level1  ul li >ul li label {font-size: 13px !important;
    color: #303030;
    font-weight: 300;}
/* .level1{margin-bottom:30px} */
.list ul li label{font-size: 14px;}
.no-results{font-size: 30px;
    text-align: center;
    padding-top:300px;
    padding-bottom:300px;
    }
</style>
<style src="@vueform/slider/themes/default.css"></style>

<style>
.slider-connect {
    background: #9600c1;
}

.slider-tooltip {
    background: #9600c1;
    border: 1px solid #9600c1;
}

.fixed-side-menu {
    position: fixed;
    top: 160px !important; /* height of the fixed header */
}

</style>
