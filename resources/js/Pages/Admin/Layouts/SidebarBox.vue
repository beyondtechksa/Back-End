<template>
  <aside class="app-sidebar sticky" id="sidebar">

  <!-- Start::main-sidebar-header -->
  <div class="main-sidebar-header">
      <Link href="/admin" class="header-logo">
          <img v-lazy="base_logo_setting" alt="logo" class="desktop-logo">
          <img v-lazy="base_favicon_setting" alt="logo" class="toggle-logo">
      </Link>
  </div>
  <!-- End::main-sidebar-header -->

  <!-- Start::main-sidebar -->
  <div class="main-sidebar" id="sidebar-scroll">

      <!-- Start::nav -->
      <nav class="main-menu-container nav nav-pills flex-column sub-open">
          <div class="slide-left" id="slide-left">
              <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"> <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path> </svg>
          </div>
          <ul class="main-menu">
              <!-- Start::slide__category -->
              <li class="slide__category"><span class="category-name">Main</span></li>
              <!-- End::slide__category -->
              <li class="slide">
                  <Link :href="route('admin.dashboard')" class="side-menu__item">
                      <i class="ri-home-3-line side-menu__icon"></i>
                      <span class="side-menu__label"> {{ __('home') }} </span>
                  </Link>
              </li>
              <!-- Start::slide -->

              <!-- End::slide -->
              <li class="slide has-sub"  v-if="check_permissions(['view product category'])">
                  <a href="javascript:void(0);" class="side-menu__item">
                      <i class="ri-list-check side-menu__icon"></i>
                      <span class="side-menu__label">{{__('categories')}}</span>
                      <i class="fe fe-chevron-right side-menu__angle"></i>
                  </a>
                  <ul class="slide-menu child1">

                    <li class="slide"  v-if="check_permissions(['view product category'])">
                      <Link :href="route('categories.index')"  class="side-menu__item">{{__('categories list')}}</Link>
                    </li>
                    <li class="slide"  v-if="check_permissions(['add product category'])">
                      <Link :href="route('categories.create')"  class="side-menu__item">{{__('add category ')}}</Link>
                    </li>
                    <li class="slide"  v-if="check_permissions(['add product category'])">
                      <Link :href="route('categories.tree')"  class="side-menu__item">{{__('category tree')}}</Link>
                    </li>
                    <li class="slide">
                      <Link :href="route('categories.top')"  class="side-menu__item">{{__('top categories')}}</Link>
                    </li>
                    <li class="slide">
                      <Link :href="route('categories.mobile_top')"  class="side-menu__item">{{__('mobile top categories')}}</Link>
                    </li>

                  </ul>
                </li>
                <li class="slide has-sub"  v-if="check_permissions(['view product'])">
                  <a href="javascript:void(0);" class="side-menu__item">
                      <i class="ri-product-hunt-line side-menu__icon"></i>
                      <span class="side-menu__label">{{__('products')}}</span>
                      <i class="fe fe-chevron-right side-menu__angle"></i>
                  </a>
                  <ul class="slide-menu child1">

                  <li class="slide"  v-if="check_permissions(['view product'])">
                    <Link :href="route('products.index')"  class="side-menu__item">{{__('products list')}}</Link>
                  </li>
                  <li class="slide"  v-if="check_permissions(['view product'])">
                    <Link :href="route('products.advanced_products')"  class="side-menu__item">{{__('advanced products list')}}</Link>
                  </li>
                  <li class="slide"  v-if="check_permissions(['add product'])">
                    <Link :href="route('products.create')"  class="side-menu__item">{{__('add product')}}</Link>
                  </li>
                  <li class="slide"  v-if="check_permissions(['add product'])">
                    <Link :href="'/admin/scrap-products'"  class="side-menu__item">{{__('scrap product')}}</Link>
                  </li>

                  <li class="slide"  v-if="check_permissions(['add product'])">
                    <Link :href="'/admin/scrap-company'"  class="side-menu__item">{{__('scrap company products')}}</Link>
                  </li>

                  <li class="slide"  v-if="check_permissions(['add product'])">
                    <Link :href="route('products.import','excel')"  class="side-menu__item">{{__('import from excel')}}</Link>
                  </li>
                  <li class="slide"  v-if="check_permissions(['add product'])">
                    <Link :href="'/admin/tracking-history'"  class="side-menu__item">{{__('tracking history')}}</Link>
                  </li>

                  <li class="slide"  v-if="check_permissions(['add product'])">
                    <Link :href="route('products.sizes')"  class="side-menu__item">{{__('product sizes')}}</Link>
                  </li>
                  <li class="slide"  v-if="check_permissions(['add product'])">
                    <Link :href="route('products.colors')"  class="side-menu__item">{{__('product colors')}}</Link>
                  </li>
                  <li class="slide"  v-if="check_permissions(['view product'])">
                    <Link :href="route('products.search')"  class="side-menu__item">{{__('search products')}}</Link>
                  </li>

                </ul>
              </li>
              <li class="slide has-sub"  v-if="check_permissions(['view order'])">
                  <a href="javascript:void(0);" class="side-menu__item">
                      <i class="ri-shopping-cart-line side-menu__icon"></i>
                      <span class="side-menu__label">{{__('sales')}}</span>
                      <i class="fe fe-chevron-right side-menu__angle"></i>
                  </a>
                  <ul class="slide-menu child1">

                    <li class="slide"  v-if="check_permissions(['view order'])">
                      <Link :href="route('orders.companies')"  class="side-menu__item">{{__('Company Requests')}}</Link>
                      <Link :href="route('orders.index')"  class="side-menu__item">{{__('all orders')}}</Link>
                      <Link :href="route('orders.index','opened')"  class="side-menu__item">{{__('opened orders')}}</Link>
                      <Link :href="route('orders.index',0)"  class="side-menu__item">{{__('pending orders')}}</Link>
                      <Link :href="route('orders.index',5)"  class="side-menu__item">{{__('completed orders')}}</Link>
                      <Link :href="route('orders.index',1)"  class="side-menu__item">{{__('canceled orders')}}</Link>
                      <Link :href="route('orders.return_requests')"  class="side-menu__item">{{__('return requests')}}</Link>
                    </li>

                  </ul>
              </li>
              <li class="slide has-sub"  v-if="check_permissions(['view collection'])">
                  <a href="javascript:void(0);" class="side-menu__item">
                      <i class="ri-group-line side-menu__icon"></i>
                      <span class="side-menu__label">{{__('customers')}}</span>
                      <i class="fe fe-chevron-right side-menu__angle"></i>
                  </a>
                  <ul class="slide-menu child1">

                     <li class="slide"  v-if="check_permissions(['view customer'])">
                      <Link :href="route('customers.index')"  class="side-menu__item">{{__('customers')}}</Link>
                    </li>

                  </ul>
              </li>
              <li class="slide has-sub" >
                  <a href="javascript:void(0);" class="side-menu__item">
                      <i class="ri-truck-line side-menu__icon"></i>
                      <span class="side-menu__label">{{__('shipping')}}</span>
                      <i class="fe fe-chevron-right side-menu__angle"></i>
                  </a>
                  <ul class="slide-menu child1">

                    <li class="slide" >
                      <Link :href="route('shipping.index')"  class="side-menu__item">{{__('shipping')}}</Link>
                    </li>

                  </ul>
              </li>

              <li class="slide has-sub"  v-if="check_permissions(['edit website interface'])">
                  <a href="javascript:void(0);" class="side-menu__item">
                      <i class="ri-global-line side-menu__icon"></i>
                      <span class="side-menu__label">{{__('Website interface')}}</span>
                      <i class="fe fe-chevron-right side-menu__angle"></i>
                  </a>
                  <ul class="slide-menu child1">

                    <li class="slide">
                      <Link :href="route('settings.index','web')"  class="side-menu__item">{{__('data entries')}}</Link>
                    </li>
                    <li class="slide">
                      <Link :href="route('settings.index','mobile')"  class="side-menu__item">{{__('mobile data entries')}}</Link>
                    </li>
                    <!-- <li class="slide">
                      <Link :href="route('general_settings.index')"  class="side-menu__item">{{__('general settings')}}</Link>
                    </li> -->
                    <li class="slide" v-if="check_permissions(['add page'])">
                      <Link :href="route('pages.index')"  class="side-menu__item">{{__('pages')}}</Link>
                    </li>

                  </ul>
              </li>
              <li class="slide has-sub"  v-if="check_permissions(['edit website interface'])">
                  <a href="javascript:void(0);" class="side-menu__item">
                      <i class="ri-android-line side-menu__icon"></i>
                      <span class="side-menu__label">{{__('mobile interface')}}</span>
                      <i class="fe fe-chevron-right side-menu__angle"></i>
                  </a>
                  <ul class="slide-menu child1">

                    <li class="slide">
                      <Link :href="route('settings.mobile_banners')"  class="side-menu__item">{{__('data entries')}}</Link>
                    </li>

                  </ul>
              </li>
              <li class="slide has-sub"  v-if="check_permissions(['view collection'])">
                  <a href="javascript:void(0);" class="side-menu__item">
                      <i class="ri-inbox-archive-line side-menu__icon"></i>
                      <span class="side-menu__label">{{__('collections')}}</span>
                      <i class="fe fe-chevron-right side-menu__angle"></i>
                  </a>
                  <ul class="slide-menu child1">

                    <li class="slide"  v-if="check_permissions(['view collection'])">
                      <Link :href="route('collections.index')"  class="side-menu__item">{{__('collections list')}}</Link>
                    </li>
                    <li class="slide"  v-if="check_permissions(['add collection'])">
                      <Link :href="route('collections.create')"  class="side-menu__item">{{__('add collection')}}</Link>
                    </li>

                  </ul>
              </li>
              <li class="slide has-sub"  v-if="check_permissions(['view vendor'])">
                  <a href="javascript:void(0);" class="side-menu__item">
                      <i class="ri-group-line side-menu__icon"></i>
                      <span class="side-menu__label">{{__('vendors')}}</span>
                      <i class="fe fe-chevron-right side-menu__angle"></i>
                  </a>
                  <ul class="slide-menu child1">

                    <li class="slide"  v-if="check_permissions(['view vendor'])">
                      <Link :href="route('vendors.index')"  class="side-menu__item">{{__('vendors list')}}</Link>
                    </li>
                    <li class="slide"  v-if="check_permissions(['add vendor'])">
                      <Link :href="route('vendors.create')"  class="side-menu__item">{{__('add vendor')}}</Link>
                    </li>

                  </ul>
                </li>
              <li class="slide has-sub"  v-if="check_permissions(['view shipping company'])">
                  <a href="javascript:void(0);" class="side-menu__item">
                      <i class="ri-group-line side-menu__icon"></i>
                      <span class="side-menu__label">{{__('shipping companies')}}</span>
                      <i class="fe fe-chevron-right side-menu__angle"></i>
                  </a>
                  <ul class="slide-menu child1">

                    <li class="slide"  v-if="check_permissions(['view shipping company'])">
                      <Link :href="route('shipping_companies.index')"  class="side-menu__item">{{__('shipping companies list')}}</Link>
                    </li>
                    <li class="slide"  v-if="check_permissions(['add shipping company'])">
                      <Link :href="route('shipping_companies.create')"  class="side-menu__item">{{__('add shipping company')}}</Link>
                    </li>

                  </ul>
                </li>
              <li class="slide has-sub"  v-if="check_permissions(['view product attribute'])">
                  <a href="javascript:void(0);" class="side-menu__item">
                      <i class="bi bi-tags side-menu__icon"></i>
                      <span class="side-menu__label">{{__('attributes')}}</span>
                      <i class="fe fe-chevron-right side-menu__angle"></i>
                  </a>
                  <ul class="slide-menu child1">

                    <li class="slide"  v-if="check_permissions(['view product attribute'])">
                      <Link :href="route('attributes.index')"  class="side-menu__item">{{__('attributes list')}}</Link>
                    </li>
                    <li class="slide"  v-if="check_permissions(['add product attribute'])">
                      <Link :href="route('attributes.create')"  class="side-menu__item">{{__('add attribute ')}}</Link>
                    </li>

                  </ul>
                </li>
                <li class="slide has-sub"  v-if="check_permissions(['view product brand'])">
                  <a href="javascript:void(0);" class="side-menu__item">
                      <i class="ri-blaze-line side-menu__icon"></i>
                      <span class="side-menu__label">{{__('brands')}}</span>
                      <i class="fe fe-chevron-right side-menu__angle"></i>
                  </a>
                  <ul class="slide-menu child1">

                    <li class="slide"  v-if="check_permissions(['view product brand'])">
                      <Link :href="route('brands.index')"  class="side-menu__item">{{__('brands list')}}</Link>
                    </li>
                    <li class="slide"  v-if="check_permissions(['add product brand'])">
                      <Link :href="route('brands.create')"  class="side-menu__item">{{__('add brand ')}}</Link>
                    </li>

                  </ul>
                </li>
              <li class="slide has-sub"  v-if="check_permissions(['view currency']) || check_permissions(['view pricing formula'])">
                  <a href="javascript:void(0);" class="side-menu__item">
                      <i class="ri-coins-line side-menu__icon"></i>
                      <span class="side-menu__label">{{__('pricing')}}</span>
                      <i class="fe fe-chevron-right side-menu__angle"></i>
                  </a>
                  <ul class="slide-menu child1">

                  <li class="slide"  v-if="check_permissions(['view currency'])">
                    <Link :href="route('currencies.index')"  class="side-menu__item">{{__('currencies list')}}</Link>
                  </li>
                  <li class="slide"  v-if="check_permissions(['view currency'])">
                    <Link :href="route('formulas.index')"  class="side-menu__item">{{__('pricing formulas list')}}</Link>
                  </li>


                </ul>
              </li>
              <li class="slide has-sub"  v-if="check_permissions(['view coupon'])">
                  <a href="javascript:void(0);" class="side-menu__item">
                      <i class="ri-coins-line side-menu__icon"></i>
                      <span class="side-menu__label">{{__('coupons')}}</span>
                      <i class="fe fe-chevron-right side-menu__angle"></i>
                  </a>
                  <ul class="slide-menu child1">

                  <li class="slide"  v-if="check_permissions(['view coupon'])">
                    <Link :href="route('coupons.index')"  class="side-menu__item">{{__('coupons list')}}</Link>
                  </li>
                  <li class="slide"  v-if="check_permissions(['add coupon'])">
                    <Link :href="route('coupons.create')"  class="side-menu__item">{{__('add coupon ')}}</Link>
                  </li>

                </ul>
              </li>
              <li class="slide has-sub"  v-if="check_permissions(['view voucher'])">
                  <a href="javascript:void(0);" class="side-menu__item">
                      <i class="ri-gift-line side-menu__icon"></i>
                      <span class="side-menu__label">{{__('vouchers')}}</span>
                      <i class="fe fe-chevron-right side-menu__angle"></i>
                  </a>
                  <ul class="slide-menu child1">

                  <li class="slide"  v-if="check_permissions(['view voucher'])">
                    <Link :href="route('vouchers.index')"  class="side-menu__item">{{__('vouchers list')}}</Link>
                  </li>
                  <li class="slide"  v-if="check_permissions(['add voucher'])">
                    <Link :href="route('vouchers.create')"  class="side-menu__item">{{__('add voucher ')}}</Link>
                  </li>

                </ul>
              </li>
              <li class="slide has-sub"  v-if="check_permissions(['view collection'])">
                  <a href="javascript:void(0);" class="side-menu__item">
                      <i class="ri-inbox-archive-line side-menu__icon"></i>
                      <span class="side-menu__label">{{__('reports')}}</span>
                      <i class="fe fe-chevron-right side-menu__angle"></i>
                  </a>
                  <ul class="slide-menu child1">

                    <li class="slide" >
                      <Link href="/admin/reports/main"  class="side-menu__item">main reports</Link>
                    </li>
                    <li class="slide" >
                      <Link href="/admin/reports/orders"  class="side-menu__item">orders reports</Link>
                    </li>
                    <li class="slide" >
                      <Link href="/admin/reports/products"  class="side-menu__item">products reports</Link>
                    </li>
                    <li class="slide" >
                      <Link href="/admin/reports/carts"  class="side-menu__item">carts reports</Link>
                    </li>

                  </ul>
              </li>
              <li class="slide has-sub"  v-if="check_permissions(['view collection'])">
                  <a href="javascript:void(0);" class="side-menu__item">
                      <i class="ri-bill-line side-menu__icon"></i>
                      <span class="side-menu__label">{{__('bills')}}</span>
                      <i class="fe fe-chevron-right side-menu__angle"></i>
                  </a>
                  <ul class="slide-menu child1">

                    <li class="slide"  v-if="check_permissions(['view bill'])">
                      <Link :href="route('bills.index')"  class="side-menu__item">{{__('bills list')}}</Link>
                    </li>

                  </ul>
              </li>
              <li class="slide has-sub"  v-if="check_permissions(['view rates'])">
                  <a href="javascript:void(0);" class="side-menu__item">
                      <i class="ri-coins-line side-menu__icon"></i>
                      <span class="side-menu__label">{{__('rates')}}</span>
                      <i class="fe fe-chevron-right side-menu__angle"></i>
                  </a>
                  <ul class="slide-menu child1">

                      <li class="slide"  v-if="check_permissions(['view rates'])">
                          <Link :href="route('rates.index')"  class="side-menu__item">{{__('rates list')}}</Link>
                      </li>

                  </ul>
              </li>
              <li class="slide has-sub">
                  <a href="javascript:void(0);" class="side-menu__item">
                      <i class="ri-coins-line side-menu__icon"></i>
                      <span class="side-menu__label">{{__('return policies')}}</span>
                      <i class="fe fe-chevron-right side-menu__angle"></i>
                  </a>
                  <ul class="slide-menu child1">

                      <li class="slide"  v-if="check_permissions(['view return policy'])">
                          <Link :href="route('return_policies.index')"  class="side-menu__item">{{__('return policies list')}}</Link>
                      </li>
                      <li class="slide"  v-if="check_permissions(['view return policy'])">
                          <Link :href="route('return_policies.create')"  class="side-menu__item">{{__('create return policy')}}</Link>
                      </li>
                      <li class="slide"  v-if="check_permissions(['view return reason'])">
                          <Link :href="route('return_reasons.index')"  class="side-menu__item">{{__('return reasons list')}}</Link>
                      </li>
                      <li class="slide"  v-if="check_permissions(['view return reason'])">
                          <Link :href="route('return_reasons.create')"  class="side-menu__item">{{__('create return reason')}}</Link>
                      </li>
                      <li class="slide"  v-if="check_permissions(['view return status'])">
                          <Link :href="route('return_statuses.index')"  class="side-menu__item">{{__('return statuses list')}}</Link>
                      </li>
                      <li class="slide"  v-if="check_permissions(['view return status'])">
                          <Link :href="route('return_statuses.create')"  class="side-menu__item">{{__('create return status')}}</Link>
                      </li>

                  </ul>
              </li>

              <li class="slide has-sub"  v-if="check_permissions(['view admin'])">
                  <a href="javascript:void(0);" class="side-menu__item">
                      <i class="ri-group-line side-menu__icon"></i>
                      <span class="side-menu__label">{{__('admins')}}</span>
                      <i class="fe fe-chevron-right side-menu__angle"></i>
                  </a>
                  <ul class="slide-menu child1">

                  <li class="slide"  v-if="check_permissions(['view admin'])">
                    <Link :href="route('admins.index')"  class="side-menu__item">{{__('admins list')}}</Link>
                  </li>
                  <li class="slide"  v-if="check_permissions(['add admin'])">
                    <Link :href="route('admins.create')"  class="side-menu__item">{{__('add admin ')}}</Link>
                  </li>

                </ul>
              </li>
              <li class="slide has-sub"  v-if="check_permissions(['view role'])">
                  <a href="javascript:void(0);" class="side-menu__item">
                      <i class="ri-team-line side-menu__icon"></i>
                      <span class="side-menu__label">{{__('roles')}}</span>
                      <i class="fe fe-chevron-right side-menu__angle"></i>
                  </a>
                  <ul class="slide-menu child1">

                  <li class="slide"  v-if="check_permissions(['view role'])">
                    <Link :href="route('roles.index')"  class="side-menu__item">{{__('roles list')}}</Link>
                  </li>
                  <li class="slide"  v-if="check_permissions(['add role'])">
                    <Link :href="route('roles.create')"  class="side-menu__item">{{__('add role ')}}</Link>
                  </li>

                </ul>
              </li>
              <li class="slide has-sub"  v-if="check_permissions(['edit settings'])">
                  <a href="javascript:void(0);" class="side-menu__item">
                      <i class="ri-settings-line side-menu__icon"></i>
                      <span class="side-menu__label">{{__('settings')}}</span>
                      <i class="fe fe-chevron-right side-menu__angle"></i>
                  </a>
                  <ul class="slide-menu child1">

                  <li class="slide">
                    <Link :href="route('general_settings.index')"  class="side-menu__item">{{__('general settings')}}</Link>
                  </li>

                </ul>
              </li>






              <!-- Start::slide__category -->
              <!-- <li class="slide__category"><span class="category-name">Pages</span></li> -->
              <!-- End::slide__category -->








          </ul>
          <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"> <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path> </svg></div>
      </nav>
      <!-- End::nav -->


  </div>
  <!-- End::main-sidebar -->



  </aside>
  </template>

  <script>
  // import '@/js/defaultmenu.min.js'
      export default{
          mounted(){
            this.set_settings()

            const ANIMATION_DURATION = 300;

  const sidebar = document.getElementById("sidebar");
  let mainContentDiv = document.querySelector(".main-content");

  const slideHasSub = document.querySelectorAll(".nav > ul > .slide.has-sub");

  const firstLevelItems = document.querySelectorAll(".nav > ul > .slide.has-sub > a");

  const innerLevelItems = document.querySelectorAll(".nav > ul > .slide.has-sub .slide.has-sub > a");

  class PopperObject {
    instance = null;
    reference = null;
    popperTarget = null;

    constructor(reference, popperTarget) {
      this.init(reference, popperTarget);
    }

    init(reference, popperTarget) {
      this.reference = reference;
      this.popperTarget = popperTarget;
      this.instance = Popper.createPopper(this.reference, this.popperTarget, {
        placement: "bottom",
        strategy: "relative",
        resize: true,
        modifiers: [
          {
            name: "computeStyles",
            options: {
              adaptive: false
            }
          },
        ]
      });

      document.addEventListener(
        "click",
        (e) => this.clicker(e, this.popperTarget, this.reference),
        false
      );

      const ro = new ResizeObserver(() => {
        this.instance.update();
      });

      ro.observe(this.popperTarget);
      ro.observe(this.reference);
    }

    clicker(event, popperTarget, reference) {
      if (
        sidebar.classList.contains("collapsed") &&
        !popperTarget.contains(event.target) &&
        !reference.contains(event.target)
      ) {
        this.hide();
      }
    }

    hide() {
      // this.instance.state.elements.popper.style.visibility = "hidden";
    }
  }

  class Poppers {
    subMenuPoppers = [];

    constructor() {
      this.init();
    }

    init() {
      slideHasSub.forEach((element) => {
        this.subMenuPoppers.push(
          new PopperObject(element, element.lastElementChild)
        );
        this.closePoppers();
      });
    }

    togglePopper(target) {
      if (window.getComputedStyle(target).visibility === "hidden" && window.getComputedStyle(target).visibility === undefined)
        target.style.visibility = "visible";
      else target.style.visibility = "hidden";
    }

    updatePoppers() {
      this.subMenuPoppers.forEach((element) => {
        element.instance.state.elements.popper.style.display = "none";
        element.instance.update();
      });
    }

    closePoppers() {
      this.subMenuPoppers.forEach((element) => {
        element.hide();
      });
    }
  }

  const slideUp = (target, duration = ANIMATION_DURATION) => {
    const { parentElement } = target;
    parentElement.classList.remove("open");
    target.style.transitionProperty = "height, margin, padding";
    target.style.transitionDuration = `${duration}ms`;
    target.style.boxSizing = "border-box";
    target.style.height = `${target.offsetHeight}px`;
    target.offsetHeight;
    target.style.overflow = "hidden";
    target.style.height = 0;
    target.style.paddingTop = 0;
    target.style.paddingBottom = 0;
    target.style.marginTop = 0;
    target.style.marginBottom = 0;
    window.setTimeout(() => {
      target.style.display = "none";
      target.style.removeProperty("height");
      target.style.removeProperty("padding-top");
      target.style.removeProperty("padding-bottom");
      target.style.removeProperty("margin-top");
      target.style.removeProperty("margin-bottom");
      target.style.removeProperty("overflow");
      target.style.removeProperty("transition-duration");
      target.style.removeProperty("transition-property");
    }, duration);
  };
  const slideDown = (target, duration = ANIMATION_DURATION) => {
    const { parentElement } = target;
    parentElement.classList.add("open");
    target.style.removeProperty("display");
    let { display } = window.getComputedStyle(target);
    if (display === "none") display = "block";
    target.style.display = display;
    const height = target.offsetHeight;
    target.style.overflow = "hidden";
    target.style.height = 0;
    target.style.paddingTop = 0;
    target.style.paddingBottom = 0;
    target.style.marginTop = 0;
    target.style.marginBottom = 0;
    target.offsetHeight;
    target.style.boxSizing = "border-box";
    target.style.transitionProperty = "height, margin, padding";
    target.style.transitionDuration = `${duration}ms`;
    target.style.height = `${height}px`;
    target.style.removeProperty("padding-top");
    target.style.removeProperty("padding-bottom");
    target.style.removeProperty("margin-top");
    target.style.removeProperty("margin-bottom");
    window.setTimeout(() => {
      target.style.removeProperty("height");
      target.style.removeProperty("overflow");
      target.style.removeProperty("transition-property");
      target.style.removeProperty("transition-duration");
    }, duration);
  };
  const slideToggle = (target, duration = ANIMATION_DURATION) => {
    let html = document.querySelector('html');
    if (!((html.getAttribute('data-nav-style') === "menu-hover" && html.getAttribute('data-toggled') === 'menu-hover-closed' && window.innerWidth >= 992) || (html.getAttribute('data-nav-style') === "icon-hover" && html.getAttribute('data-toggled') === 'icon-hover-closed' && window.innerWidth >= 992)) && target && target.nodeType != 3) {
      if (window.getComputedStyle(target).display === "none")
        return slideDown(target, duration);
      return slideUp(target, duration);
    }
  };

  const PoppersInstance = new Poppers();

  /**
   * wait for the current animation to finish and update poppers position
   */
  const updatePoppersTimeout = () => {
    setTimeout(() => {
      PoppersInstance.updatePoppers();
    }, ANIMATION_DURATION);
  };

  const defaultOpenMenus = document.querySelectorAll(".slide.has-sub.open");

  defaultOpenMenus.forEach((element) => {
    element.lastElementChild.style.display = "block";
  });

  /**
   * handle top level submenu click
   */
  firstLevelItems.forEach((element) => {
    element.addEventListener("click", () => {
      let html = document.querySelector('html');
      if (!((html.getAttribute('data-nav-style') === "menu-hover" && html.getAttribute('data-toggled') === 'menu-hover-closed' && window.innerWidth >= 992) || (html.getAttribute('data-nav-style') === "icon-hover" && html.getAttribute('data-toggled') === 'icon-hover-closed' && window.innerWidth >= 992))) {
        const parentMenu = element.closest(".nav.sub-open");
        if (parentMenu)
          parentMenu.querySelectorAll(":scope > ul > .slide.has-sub > a").forEach(
            el => {
              // window.getComputedStyle(el.nextElementSibling).display !== "none" &&
              if (el.nextElementSibling.style.display === "block" || window.getComputedStyle(el.nextElementSibling).display === "block") {
                slideUp(el.nextElementSibling)
              }
            }
          );
        slideToggle(element.nextElementSibling);
      }
    });
  });

  /**
   * handle inner submenu click
   */
  innerLevelItems.forEach((element) => {
    let html = document.querySelector('html');
    // if ((html.getAttribute('data-nav-style') !== "menu-hover" || html.getAttribute('data-nav-style') !== "icon-hover") ) {
    element.addEventListener("click", () => {
      const innerMenu = element.closest(".slide-menu");
      if (innerMenu)
        innerMenu.querySelectorAll(":scope .slide.has-sub > a").forEach(
          el => {
            // window.getComputedStyle(el.nextElementSibling).display !== "none" &&
            // ref || window.getComputedStyle(el.nextElementSibling).display === "block"
            if (el.nextElementSibling && el.nextElementSibling?.style.display === "block") {
              slideUp(el.nextElementSibling)
            }
          }
        );
      slideToggle(element.nextElementSibling);
    });
    // }
  });

  /**
   * menu styles
   */

   window.onbeforeunload = function (e) {
      var e = e || window.event;
      if(localStorage.getItem('sidebar-tab') && localStorage.getItem('sidebar-tab') ==='1'){
          localStorage.setItem('sidebar-tab',0);
      } else {
          localStorage.setItem('sidebar-tab',1);
      }
  };

  let headerToggleBtn, WindowPreSize;
  (() => {
    let html = document.querySelector('html');

    if(localStorage.getItem('sidebar-tab') && localStorage.getItem('sidebar-tab') ==='1'){
      html.setAttribute('data-toggled', 'icon-overlay-close');
    } else {
      html.removeAttribute('data-toggled', 'icon-overlay-close');
    }
    headerToggleBtn = document.querySelector('.sidemenu-toggle');
    headerToggleBtn.addEventListener('click', toggleSidemenu);

    let mainContent = document.querySelector('.main-content');
    if (window.innerWidth <= 992) {
      mainContent.addEventListener('click', menuClose);
    }
    else {
      mainContent.removeEventListener('click', menuClose);
    }

    WindowPreSize = [window.innerWidth];
    setNavActive();
    if (html.getAttribute('data-nav-layout') === "horizontal" && window.innerWidth >= 992) {
      clearNavDropdown();
      mainContent.addEventListener('click', clearNavDropdown);
    }
    else {
      mainContent.removeEventListener('click', clearNavDropdown);
    }


    window.addEventListener('resize', ResizeMenu);
    switcherArrowFn();

    if (
      !localStorage.getItem("ynexlayout") &&
      !localStorage.getItem("ynexnavstyles") &&
      !localStorage.getItem("ynexverticalstyles") &&
      !document.querySelector(".landing-body") &&
      document.querySelector("html").getAttribute("data-nav-layout") !==
      "horizontal"
    ) {
      // To enable sidemenu layout styles
      // iconTextFn();
      // detachedFn();
      // closedSidemenuFn();
      // doubletFn();
      if (!html.getAttribute("data-vertical-style") && !html.getAttribute("data-nav-style")) {
        iconOverayFn();
      }
    }


    function rtlFn() {
      let html = document.querySelector('html');
      html.setAttribute("dir", "rtl");
      document.querySelector("#style")?.setAttribute("href", "../assets/libs/bootstrap/css/bootstrap.rtl.min.css");
      //RTL
      if (localStorage.getItem('ynexrtl')) {
        document.querySelector('#switcher-rtl').checked = true;
      }
    }


    /* RTL Start */
    if (html.getAttribute('dir') === "rtl") {
      rtlFn();
    }
    /* RTL End */

    /* Horizontal Start */
    if (html.getAttribute('data-nav-layout') === "horizontal" && !document.querySelector('.landing-body') == true) {
      setTimeout(() => {
        horizontalClickFn();
      }, 1000);
    }
    /* Horizontal End */

    toggleSidemenu();

    if ((html.getAttribute('data-nav-style') === "menu-hover" || html.getAttribute('data-nav-style') === "icon-hover") && window.innerWidth >= 992) {
      clearNavDropdown();
    }
    if (window.innerWidth < 992) {
      html.setAttribute('data-toggled', 'close')
    }
  })()

  function ResizeMenu() {
    let html = document.querySelector('html');
    let mainContent = document.querySelector('.main-content');

    WindowPreSize.push(window.innerWidth);
    if (WindowPreSize.length > 2) { WindowPreSize.shift() }
    if (WindowPreSize.length > 1) {
      if ((WindowPreSize[WindowPreSize.length - 1] < 992) && (WindowPreSize[WindowPreSize.length - 2] >= 992)) {
        // less than 992;
        mainContent.addEventListener('click', menuClose);
        setNavActive()
        toggleSidemenu();
        mainContent.removeEventListener('click', clearNavDropdown);
      }

      if ((WindowPreSize[WindowPreSize.length - 1] >= 992) && (WindowPreSize[WindowPreSize.length - 2] < 992)) {
        // greater than 992
        mainContent.removeEventListener('click', menuClose);
        toggleSidemenu();
        if (html.getAttribute('data-nav-layout') === "horizontal") {
          clearNavDropdown()
          mainContent.addEventListener('click', clearNavDropdown);
        }
        else {
          mainContent.removeEventListener('click', clearNavDropdown);
        }
        html.removeAttribute('data-toggled')
      }
    }
    checkHoriMenu();
  }
  function menuClose() {
    let html = document.querySelector('html');
    html.setAttribute('data-toggled', 'close');
    document.querySelector("#responsive-overlay").classList.remove("active");
  }
  function toggleSidemenu() {
    let html = document.querySelector('html');
    let sidemenuType = html.getAttribute('data-nav-layout');

    if (window.innerWidth >= 992) {
      if (sidemenuType === "vertical") {
        sidebar.removeEventListener('mouseenter', mouseEntered);
        sidebar.removeEventListener('mouseleave', mouseLeave);
        sidebar.removeEventListener('click', icontextOpen);
        mainContentDiv.removeEventListener('click', icontextClose);
        let sidemenulink = document.querySelectorAll('.main-menu li > .side-menu__item');
        sidemenulink.forEach(ele => ele.removeEventListener('click', doubleClickFn))

        let verticalStyle = html.getAttribute('data-vertical-style');
        switch (verticalStyle) {
          // closed
          case "closed":
            html.removeAttribute('data-nav-style');
            if (html.getAttribute('data-toggled') === 'close-menu-close') {
              html.removeAttribute('data-toggled');
            }
            else {
              html.setAttribute('data-toggled', 'close-menu-close');
            }
            break;
          // icon-overlay
          case "overlay":

            html.removeAttribute('data-nav-style');
            if (html.getAttribute('data-toggled') === 'icon-overlay-close') {
              html.removeAttribute('data-toggled', 'icon-overlay-close');
              localStorage.setItem('sidebar-tab',1);
              sidebar.removeEventListener('mouseenter', mouseEntered);
              sidebar.removeEventListener('mouseleave', mouseLeave);
            }
            else {
              if (window.innerWidth >= 992) {
                  localStorage.setItem('sidebar-tab',0);
                if (!localStorage.getItem("ynexlayout")) {
                  html.setAttribute('data-toggled', 'icon-overlay-close');
                }
                sidebar.addEventListener('mouseenter', mouseEntered);
                sidebar.addEventListener('mouseleave', mouseLeave);
              }
              else {
                sidebar.removeEventListener('mouseenter', mouseEntered);
                sidebar.removeEventListener('mouseleave', mouseLeave);
              }
            }
            break;
          // icon-text
          case "icontext":
            html.removeAttribute('data-nav-style');
            if (html.getAttribute('data-toggled') === 'icon-text-close') {
              html.removeAttribute('data-toggled', 'icon-text-close');
              sidebar.removeEventListener('click', icontextOpen);
              mainContentDiv.removeEventListener('click', icontextClose);
            }
            else {
              html.setAttribute('data-toggled', 'icon-text-close');
              if (window.innerWidth >= 992) {
                sidebar.addEventListener('click', icontextOpen);
                mainContentDiv.addEventListener('click', icontextClose);
              }
              else {
                sidebar.removeEventListener('click', icontextOpen);
                mainContentDiv.removeEventListener('click', icontextClose);
              }
            }
            break;
          // doublemenu
          case "doublemenu":
            html.removeAttribute('data-nav-style');
            if (html.getAttribute('data-toggled') === 'double-menu-open') {
              html.setAttribute('data-toggled', 'double-menu-close');
              if (document.querySelector('.slide-menu')) {
                let slidemenu = document.querySelectorAll('.slide-menu');
                slidemenu.forEach(e => {
                  if (e.classList.contains('double-menu-active')) {
                    e.classList.remove('double-menu-active')
                  }
                })
              }
            }
            else {
              let sidemenu = document.querySelector('.side-menu__item.active');
              if (sidemenu) {
                html.setAttribute('data-toggled', 'double-menu-open');
                if (sidemenu.nextElementSibling) {
                  sidemenu.nextElementSibling.classList.add('double-menu-active');
                }
                else {
                  document.querySelector("html").setAttribute("data-toggled", "double-menu-close")
                }
              };
            }

            doublemenu();
            break;
          // detached
          case "detached":
            if (html.getAttribute('data-toggled') === 'detached-close') {
              html.removeAttribute('data-toggled', 'detached-close');
              sidebar.removeEventListener('mouseenter', mouseEntered);
              sidebar.removeEventListener('mouseleave', mouseLeave);
            }
            else {
              html.setAttribute('data-toggled', 'detached-close');
              if (window.innerWidth >= 992) {
                sidebar.addEventListener('mouseenter', mouseEntered);
                sidebar.addEventListener('mouseleave', mouseLeave);
              }
              else {
                sidebar.removeEventListener('mouseenter', mouseEntered);
                sidebar.removeEventListener('mouseleave', mouseLeave);
              }
            }
            break;
          // default
          case 'default':
            iconOverayFn();
            html.removeAttribute('data-toggled');

          // for making any vertical style as default
          // default:
          // iconOverayFn();
          // html.removeAttribute('data-toggled');
        }
        let menuclickStyle = html.getAttribute('data-nav-style');
        switch (menuclickStyle) {
          case 'menu-click':
            if (html.getAttribute('data-toggled') === 'menu-click-closed') {
              html.removeAttribute('data-toggled');
            }
            else {
              html.setAttribute('data-toggled', 'menu-click-closed');
            }
            break;
          case 'menu-hover':
            if (html.getAttribute('data-toggled') === 'menu-hover-closed') {
              html.removeAttribute('data-toggled');
              setNavActive()
            }
            else {
              html.setAttribute('data-toggled', 'menu-hover-closed');
              clearNavDropdown()
            }
            break;
          case 'icon-click':
            if (html.getAttribute('data-toggled') === 'icon-click-closed') {
              html.removeAttribute('data-toggled');
            }
            else {
              html.setAttribute('data-toggled', 'icon-click-closed');
            }
            break;
          case 'icon-hover':
            if (html.getAttribute('data-toggled') === 'icon-hover-closed') {
              html.removeAttribute('data-toggled');
              setNavActive()
            }
            else {
              html.setAttribute('data-toggled', 'icon-hover-closed');
              clearNavDropdown()
            }
            break;
          //for making any horizontal style as default
          default:
        }
      }
    }
    else {
      if (html.getAttribute('data-toggled') === 'close') {
        html.setAttribute('data-toggled', 'open');
        let i = document.createElement("div");
        i.id = "responsive-overlay";
        setTimeout(() => {
          if (document.querySelector("html").getAttribute("data-toggled") == "open") {
            document.querySelector("#responsive-overlay").classList.add("active");
            document
              .querySelector("#responsive-overlay")
              .addEventListener("click", () => {
                document
                  .querySelector("#responsive-overlay")
                  .classList.remove("active");
                console.log(i.id);
                menuClose();
              });
          }
          window.addEventListener("resize", () => {
            if (window.screen.width >= 992) {
              document.querySelector("#responsive-overlay").classList.remove("active");
            }
          });
        }, 100);
      }
      else {
        html.setAttribute('data-toggled', 'close');
      }
    }

  }
  function mouseEntered() {
    let html = document.querySelector('html');
    html.setAttribute('data-icon-overlay', 'open');
  }
  function mouseLeave() {
    let html = document.querySelector('html');
    html.removeAttribute('data-icon-overlay');
  }
  function icontextOpen() {
    let html = document.querySelector('html');
    html.setAttribute('data-icon-text', 'open');
  }
  function icontextClose() {
    let html = document.querySelector('html');
    html.removeAttribute('data-icon-text');
  }
  function closedSidemenuFn() {
    let html = document.querySelector('html');
    html.setAttribute('data-nav-layout', 'vertical');
    html.setAttribute('data-vertical-style', 'closed');
    toggleSidemenu();
  }
  function detachedFn() {
    let html = document.querySelector('html');
    html.setAttribute('data-nav-layout', 'vertical');
    html.setAttribute('data-vertical-style', 'detached');
    toggleSidemenu();
  }
  function iconTextFn() {
    let html = document.querySelector('html');
    html.setAttribute('data-nav-layout', 'vertical');
    html.setAttribute('data-vertical-style', 'icontext');
    toggleSidemenu();
  }
  function iconOverayFn() {
    let html = document.querySelector('html');
    html.setAttribute('data-nav-layout', 'vertical');
    html.setAttribute('data-vertical-style', 'overlay');
    toggleSidemenu();
    setNavActive();
  }
  function doubletFn() {
    let html = document.querySelector('html');
    html.setAttribute('data-nav-layout', 'vertical');
    html.setAttribute('data-vertical-style', 'doublemenu');
    toggleSidemenu();

    const menuSlideItem = document.querySelectorAll(
      ".main-menu > li > .side-menu__item"
    );


    // Create the tooltip element
    const tooltip = document.createElement("div");
    tooltip.className = "custome-tooltip";
    // tooltip.textContent = "This is a tooltip";

    // Set the CSS properties of the tooltip element
    tooltip.style.setProperty("position", "fixed");
    tooltip.style.setProperty("display", "none");
    tooltip.style.setProperty("padding", "0.5rem");
    tooltip.style.setProperty("font-weight", "500");
    tooltip.style.setProperty("font-size", "0.75rem");
    tooltip.style.setProperty("background-color", "rgb(15, 23 ,42)");
    tooltip.style.setProperty("color", "rgb(255, 255 ,255)");
    tooltip.style.setProperty("margin-inline-start", "45px");
    tooltip.style.setProperty("border-radius", "0.25rem");
    tooltip.style.setProperty("z-index", "99");

    menuSlideItem.forEach((e) => {
      // Add an event listener to the menu slide item to show the tooltip
      e.addEventListener("mouseenter", () => {
        tooltip.style.setProperty("display", "block");
        tooltip.textContent =
          e.querySelector(".side-menu__label").textContent;
        if (
          document
            .querySelector("html")
            .getAttribute("data-vertical-style") == "doublemenu"
        ) {
          e.appendChild(tooltip);
        }
      });

      // Add an event listener to hide the tooltip
      e.addEventListener("mouseleave", () => {
        tooltip.style.setProperty("display", "none");
        tooltip.textContent =
          e.querySelector(".side-menu__label").textContent;
        if (
          document
            .querySelector("html")
            .getAttribute("data-vertical-style") == "doublemenu"
        ) {
          e.removeChild(tooltip);
        }
      });
    });
  }
  function menuClickFn() {
    let html = document.querySelector('html');
    html.setAttribute('data-nav-style', 'menu-click');
    html.removeAttribute('data-hor-style');
    html.removeAttribute('data-vertical-style');

    toggleSidemenu();
    if (html.getAttribute('data-nav-layout') === 'vertical') {
      setNavActive();
    }
  }
  function menuhoverFn() {
    let html = document.querySelector('html');
    html.setAttribute('data-nav-style', 'menu-hover');
    html.removeAttribute('data-hor-style');
    html.removeAttribute('data-vertical-style');
    toggleSidemenu();
    if (html.getAttribute('data-toggled') === 'menu-hover-closed') {
      clearNavDropdown();
    }
  }
  function iconClickFn() {
    let html = document.querySelector('html');
    html.setAttribute('data-nav-style', 'icon-click');
    toggleSidemenu();
    if (html.getAttribute('data-nav-layout') === 'vertical') {
      setNavActive();
    }
  }
  function iconHoverFn() {
    let html = document.querySelector('html');
    html.setAttribute('data-nav-style', 'icon-hover');
    toggleSidemenu();
    clearNavDropdown();
  }
  function setNavActive() {
    let currentPath = window.location.pathname.split('/')[0];

    currentPath = location.pathname == "/" ? "/" : location.pathname.substring(1);
    currentPath = currentPath.substring(currentPath.lastIndexOf("/") + 1);
    let sidemenuItems = document.querySelectorAll('.side-menu__item');
    sidemenuItems.forEach(e => {
      if (currentPath === "/") {
        currentPath = "index.html";
      }
      if (e.getAttribute('href') === currentPath) {
        e.classList.add('active');
        e.parentElement.classList.add('active');
        let parent = e.closest('ul');
        let parentNotMain = e.closest('ul:not(.main-menu)');
        let hasParent = true;
        // while (hasParent) {
        if (parent) {
          parent.classList.add('active');
          parent.previousElementSibling.classList.add('active');
          parent.parentElement.classList.add('active');

          if (parent.parentElement.classList.contains("has-sub")) {
            let elemrntRef = parent.parentElement.parentElement.parentElement
            elemrntRef.classList.add("open", "active")
            elemrntRef.firstElementChild.classList.add("active")
            elemrntRef.children[1].style.display = "block"
            Array.from(elemrntRef.children[1].children).map((element) => {
              if (element.classList.contains("active")) {
                element.children[1].style.display = "block"
              }
            })
          }

          if (parent.classList.contains("child1")) {
            slideDown(parent);
          }
          parent = parent.parentElement.closest('ul');
          //
          if (parent && parent.closest('ul:not(.main-menu)')) {
            parentNotMain = parent.closest('ul:not(.main-menu)');
          }
          if (!parentNotMain) hasParent = false;
        }
        else {
          hasParent = false;
        }
      }
      // }
    })
  }
  function clearNavDropdown() {
    let sidemenus = document.querySelectorAll('ul.slide-menu');
    sidemenus.forEach(e => {
      let parent = e.closest('ul');
      let parentNotMain = e.closest('ul:not(.main-menu)');
      if (parent) {
        let hasParent = parent.closest('ul.main-menu');
        while (hasParent) {
          parent.classList.add('active');
          slideUp(parent);
          //
          parent = parent.parentElement.closest('ul');
          parentNotMain = parent.closest('ul:not(.main-menu)');
          if (!parentNotMain) hasParent = false;
        }
      }
    })
  }
  function switcherArrowFn() {
    let slideLeft = document.querySelector('.slide-left');
    let slideRight = document.querySelector('.slide-right');

    // used to remove is-expanded class and remove class on clicking arrow buttons
    function slideClick() {
      let slide = document.querySelectorAll('.slide');
      let slideMenu = document.querySelectorAll('.slide-menu');
      slide.forEach((element, index) => {
        if (element.classList.contains('is-expanded') == true) {
          element.classList.remove('is-expanded');
        }
      });
      slideMenu.forEach((element, index) => {
        if (element.classList.contains('open') == true) {
          element.classList.remove('open');
          element.style.display = 'none';
        }
      });
    }

    checkHoriMenu();

    slideLeft.addEventListener('click', () => {

      let menuNav = document.querySelector('.main-menu');
      let mainContainer1 = document.querySelector('.main-sidebar');
      let marginLeftValue = Math.ceil(Number(window.getComputedStyle(menuNav).marginLeft.split('px')[0]));
      let marginRightValue = Math.ceil(Number(window.getComputedStyle(menuNav).marginRight.split('px')[0]));
      let mainContainer1Width = mainContainer1.offsetWidth;
      if (menuNav.scrollWidth > mainContainer1.offsetWidth) {
        if (!(document.querySelector('html').getAttribute('dir') === 'rtl')) {
          if (marginLeftValue < 0 && !(Math.abs(marginLeftValue) < mainContainer1Width)) {
            menuNav.style.marginRight = 0;
            menuNav.style.marginLeft = Number(menuNav.style.marginLeft.split('px')[0]) + Math.abs(mainContainer1Width) + 'px';
            slideRight.classList.remove('d-none');
          } else if (marginLeftValue >= 0) {
            menuNav.style.marginLeft = '0px';
            slideLeft.classList.add('d-none');
            slideRight.classList.remove('d-none');
          } else {
            menuNav.style.marginLeft = '0px';
            slideLeft.classList.add('d-none');
            slideRight.classList.remove('d-none');
          }
        } else {
          if (marginRightValue < 0 && !(Math.abs(marginRightValue) < mainContainer1Width)) {
            menuNav.style.marginLeft = 0;
            menuNav.style.marginRight = Number(menuNav.style.marginRight.split('px')[0]) + Math.abs(mainContainer1Width) + 'px';
            slideRight.classList.remove('d-none');
          } else if (marginRightValue >= 0) {
            menuNav.style.marginRight = '0px';
            slideLeft.classList.add('d-none');
            slideRight.classList.remove('d-none');
          } else {
            menuNav.style.marginRight = '0px';
            slideLeft.classList.add('d-none');
            slideRight.classList.remove('d-none');
          }
        }
      }
      else {
        document.querySelector(".main-menu").style.marginLeft = "0px";
        document.querySelector(".main-menu").style.marginRight = "0px";
        slideLeft.classList.add('d-none');
      }

      let element = document.querySelector(".main-menu > .slide.open")
      let element1 = document.querySelector(".main-menu > .slide.open >ul")
      if (element) {
        element.classList.remove("open")
      }
      if (element1) {
        element1.style.display = "none"
      }

      slideClick();
      return;
      //
    });
    slideRight.addEventListener('click', () => {
      let menuNav = document.querySelector('.main-menu');
      let mainContainer1 = document.querySelector('.main-sidebar');
      let marginLeftValue = Math.ceil(Number(window.getComputedStyle(menuNav).marginLeft.split('px')[0]));
      let marginRightValue = Math.ceil(Number(window.getComputedStyle(menuNav).marginRight.split('px')[0]));
      let check = menuNav.scrollWidth - mainContainer1.offsetWidth;
      let mainContainer1Width = mainContainer1.offsetWidth;

      if (menuNav.scrollWidth > mainContainer1.offsetWidth) {
        if (!(document.querySelector('html').getAttribute('dir') === 'rtl')) {
          if (Math.abs(check) > Math.abs(marginLeftValue)) {
            menuNav.style.marginRight = 0;
            if (!(Math.abs(check) > Math.abs(marginLeftValue) + mainContainer1Width)) {
              mainContainer1Width = Math.abs(check) - Math.abs(marginLeftValue);
              slideRight.classList.add('d-none');
            }
            menuNav.style.marginLeft = Number(menuNav.style.marginLeft.split('px')[0]) - Math.abs(mainContainer1Width) + 'px';
            slideLeft.classList.remove('d-none');
          }
        } else {
          if (Math.abs(check) > Math.abs(marginRightValue)) {
            menuNav.style.marginLeft = 0;
            if (!(Math.abs(check) > Math.abs(marginRightValue) + mainContainer1Width)) {
              mainContainer1Width = Math.abs(check) - Math.abs(marginRightValue);
              slideRight.classList.add('d-none');
            }
            menuNav.style.marginRight = Number(menuNav.style.marginRight.split('px')[0]) - Math.abs(mainContainer1Width) + 'px';
            slideLeft.classList.remove('d-none');
          }
        }
      }

      let element = document.querySelector(".main-menu > .slide.open")
      let element1 = document.querySelector(".main-menu > .slide.open >ul")
      if (element) {
        element.classList.remove("open")
      }
      if (element1) {
        element1.style.display = "none"
      }

      slideClick();
      return;
    });
  }
  function checkHoriMenu() {
    let menuNav = document.querySelector('.main-menu');
    let mainContainer1 = document.querySelector('.main-sidebar');
    let slideLeft = document.querySelector('.slide-left');
    let slideRight = document.querySelector('.slide-right');
    let marginLeftValue = Math.ceil(Number(window.getComputedStyle(menuNav).marginLeft.split('px')[0]));
    let marginRightValue = Math.ceil(Number(window.getComputedStyle(menuNav).marginRight.split('px')[0]));
    let check = menuNav.scrollWidth - mainContainer1.offsetWidth;
    // Show/Hide the arrows
    if (menuNav.scrollWidth > mainContainer1.offsetWidth) {
      slideRight.classList.remove('d-none');
      slideLeft.classList.add('d-none');
    }
    else {
      slideRight.classList.add('d-none');
      slideLeft.classList.add('d-none');
      menuNav.style.marginLeft = '0px';
      menuNav.style.marginRight = '0px';
    }
    if (!(document.querySelector('html').getAttribute('dir') === 'rtl')) {
      // LTR check the width and adjust the menu in screen
      if (menuNav.scrollWidth > mainContainer1.offsetWidth) {
        if (Math.abs(check) < Math.abs(marginLeftValue)) {
          menuNav.style.marginLeft = -check + 'px';
          slideLeft.classList.remove('d-none');
          slideRight.classList.add('d-none');
        }
      }
      if (marginLeftValue == 0) {
        slideLeft.classList.add('d-none');
      }
      else {
        slideLeft.classList.remove('d-none');
      }
    }
    else {
      // RTL check the width and adjust the menu in screen
      if (menuNav.scrollWidth > mainContainer1.offsetWidth) {
        if (Math.abs(check) < Math.abs(marginRightValue)) {
          menuNav.style.marginRight = -check + 'px';
          slideLeft.classList.remove('d-none');
          slideRight.classList.add('d-none');
        }
      }
      if (marginRightValue == 0) {
        slideLeft.classList.add('d-none');
      }
      else {
        slideLeft.classList.remove('d-none');
      }
    }
    if (marginLeftValue != 0 || marginRightValue != 0) {
      slideLeft.classList.remove('d-none');
    }
  }

  //

  ['switcher-icon-click', 'switcher-icon-hover', 'switcher-horizontal'].map((element) => {
    if (document.getElementById(element)) {
      document.getElementById(element).addEventListener("click", () => {
        let menuNav = document.querySelector('.main-menu');
        let mainContainer1 = document.querySelector('.main-sidebar');
        setTimeout(() => {
          if (menuNav.offsetWidth > mainContainer1.offsetWidth) {
            document.getElementById("slide-right").classList.remove("d-none")
          }
          else {
            document.getElementById("slide-right").classList.add("d-none")
          }
        }, 100);
      })
    }
  })


  // double-menu click toggle start
  function doublemenu() {
    if (window.innerWidth >= 992) {
      let html = document.querySelector('html');
      let sidemenulink = document.querySelectorAll('.main-menu > li > .side-menu__item');
      sidemenulink.forEach(ele => { ele.addEventListener('click', doubleClickFn) })

    }
  }
  function doubleClickFn() {
    var $this = this;
    let html = document.querySelector('html');
    var checkElement = $this.nextElementSibling;
    if (checkElement) {
      if (!checkElement.classList.contains('double-menu-active')) {
        if (document.querySelector('.slide-menu')) {
          let slidemenu = document.querySelectorAll('.slide-menu');
          slidemenu.forEach(e => {
            if (e.classList.contains('double-menu-active')) {
              e.classList.remove('double-menu-active');
              html.setAttribute('data-toggled', 'double-menu-close');
            }
          })
        }
        checkElement.classList.add('double-menu-active');
        html.setAttribute('data-toggled', 'double-menu-open');
      }
    }
  }
  // double-menu click toggle end

  window.addEventListener("unload", () => {
    let mainContent = document.querySelector('.main-content');
    mainContent.removeEventListener('click', clearNavDropdown);
    window.removeEventListener('resize', ResizeMenu);
    let sidemenulink = document.querySelectorAll('.main-menu li > .side-menu__item');
    sidemenulink.forEach(ele => ele.removeEventListener('click', doubleClickFn))

  });

  // for menu scroll to top active page
  let customScrollTop = () => {
    document.querySelectorAll(".side-menu__item").forEach((ele) => {
      if (ele.classList.contains("active")) {
        let elemRect = ele.getBoundingClientRect();
        if (
          ele.children[0] &&
          ele.parentElement.classList.contains("has-sub") &&
          elemRect.top > 435
        ) {
          ele.scrollIntoView({ behavior: "smooth" });
        }
      }
    });
  };
  setTimeout(() => {
    customScrollTop();
  }, 300);
  // for menu scroll to top active page

        // for menu click active close
        document.querySelector(".main-content").addEventListener("click", () => {
          document.querySelectorAll(".slide-menu").forEach((ele) => {
            if (document.querySelector("html").getAttribute("data-toggled") == 'menu-click-closed' || document.querySelector("html").getAttribute("data-toggled") == 'icon-click-closed') {
              ele.style.display = "none"
            }
          })
        })
      },
      data(){
        return {
          logo:null,
          favicon:null
        }
      },
      methods:{
        set_settings(){
          let logo=this.$page.props.settings.find((e)=>e.slug=='logo')
          let favicon=this.$page.props.settings.find((e)=>e.slug=='favicon')
          this.logo=logo?logo.value:null
          this.favicon=favicon?favicon.value:null
        }
      }

      }
  </script>

