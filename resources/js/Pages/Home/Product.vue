

<template>

    <Head :title="$page.props.page_title" />
      <app>

    <!-- =====  Header ENd ===== -->

    <main class="products-details container-cum overflow-hidden">
      <!-- ===== Product  Description  ===== -->
      <div class="product-info">
        <div class="container-cum">
          <div class="row product-information-details">
            <div class="col-lg-5 col-12 col-md-12 position-relative">
              <div class="d-flex pro-duct-slides">
                <div class="left-imgs">
                  <a :class="{'active':selected_image==product.image}" @click="selected_image=product.image" href="javascript:void(0)" class="active" >
                    <img v-lazy="product.image" alt="" />
                  </a>
                  <a :class="{'active':selected_image==file.image}" @click="selected_image=file.image" href="javascript:void(0)"   v-for="file,index in product.files" :key="index">
                    <img v-lazy="file.image" alt="" />
                  </a>

                </div>
                <div class="w-100 overflow-hidden swiper product-slider">
                  <div class="swiper-wrapper">
                    <div class="swiper-slide item">
                      <img v-lazy="selected_image" alt="">
                    </div>
                    <div class="swiper-slide responsive-swipper item" v-for="file,index in product.files" :key="index">
                      <img v-lazy="file.image" alt="">
                    </div>


                  </div>
                  <div class="pd_slider_arrow d-lg-none">
                    <button class="pd_slider_prev">
                      <img src="/home/img/chevron-circle-left.svg" alt="">
                    </button>
                    <button class="pd_slider_next">
                      <img src="/home/img/chevron-circle-right.svg" alt="">
                    </button>
                  </div>
                  <div class="swiper-pagination d-lg-none"></div>
                </div>

                <div class="product-offer" :style="{'left':$page.props.locale=='ar'?'34px':'unset','right':$page.props.locale=='ar'?'unset':'34px',}">
                  <a href="#" class="offer-btns" v-if="product.discount_percentage_selling_price>0">
                    <img src="/home/img/discount.svg" alt="" />
                    <span> {{ product.discount_percentage_selling_price }}</span>
                  </a>
                </div>
              </div>

              <div class="shop-card">
                <!-- <a @click="add_cart(product)" href="#">
                  <img src="/home/img/iconShop.svg" alt="" />
                </a> -->

                <!-- <a href="#">
                  <img src="/home/img/link.svg" alt="" />
                </a> -->
              </div>
            </div>
            <div class="col-lg-7 col-md-12 col-12">
              <div class="pro-information ps-xl-4">
                <div class="product-info-content">
                  <div class="pro-titled">
                    <h2>{{product['name_'+$page.props.locale]}}</h2>
                    <p class="d-flex justify-content-between">
                     <span v-if="product.brand">
                      <strong> {{__('Brand')}} </strong>: <Link :href="'/shop?brand_id='+product.brand.id">{{product.brand.translated_name}} </Link>
                      </span>
                      <span class="d-flex gap-2">
                        <social-sharing :product="product"></social-sharing>
                        <span  class="cursor-pointer product-favourite" @click.prevent="add_favourite(product)">
                          <img v-if="check_user_favourite(product)" src="/home/img/like.png"  alt="" />
                          <img v-else
                              src="/home/img/icon-heart.svg"
                              alt=""
                          />
                        </span>
                      </span>
                    </p>
                    <p><strong>{{__('sku')}} </strong>: <span>{{product.sku}}</span></p>

                      <div class="rattings-start responsive-start pt-2 pb-2">
                        <ul class="rattings">
                          <li>
                            <img src="/home/img/star-Filled.svg" alt="" />
                          </li>
                          <li>
                            <img src="/home/img/star-Filled.svg" alt="" />
                          </li>
                          <li>
                            <img src="/home/img/star-Filled.svg" alt="" />
                          </li>
                          <li>
                            <img src="/home/img/star-Filled.svg" alt="" />
                          </li>
                          <li>
                            <img src="/home/img/star.svg" alt="" />
                          </li>
                        </ul>
                        <p class="point">4.0/5.0</p>
                      </div>


                  </div>
                    <div class="rattings-start responsive-desk " v-if="product.rated>0">
                        <ul class="rattings">
                            <li v-for="n in 5" :key="n">
                                <img
                                    v-lazy="n <= Math.round(product.rated) ? '/home/img/star-Filled.svg' : '/home/img/star.svg'"
                                    alt=""
                                />
                            </li>
                        </ul>
                        <p class="point">{{ Math.round(product.rated) }}/5</p>
                    </div>
                  <div class="product-price justify-content-start">
                    <div class="current-price product-page">
                      <p>{{__('SAR')}}
                        <span> {{product.final_selling_price}} </span>
                        </p>
                    </div>
                    <div class="old-price product-page" v-if="product.discount_percentage_selling_price>0">
                      <p>{{__('SAR')}} {{ product.old_price }} </p>
                    </div>
                  </div>
                </div>
                <div class="product-varient">
                  <div class="mt-3" v-if="product.groups.length>0">
                      <Link class="group-item" v-for="group,index in product.groups" :key="index" :href="route('product.show',{id:group.id,slug:group.slug})"  :class="{'active':group.id==product.id}">
                        <img v-lazy="group.image" />
                      </Link>
                  </div>
                  <!-- color  -->
                  <div class="Size-varient" v-if="product.colors.length>0">
                    <div class="d-flex gap-1">
                      <h3>{{__('Color')}} :</h3>
                      <h4> {{ base_form.color }} </h4>
                    </div>
                    <div class="colorbtn">
                      <span  @click="select_color(color)" v-for="color,index in product.colors" :key="index" >
                      <a v-if="color.image" href="javascript:void(0)">
                        <img v-lazy="color.image">
                      </a>
                      <a v-else href="javascript:void(0)" :style="{'background-color':color.color_code,'border':'1px solid #ddd' }" ></a>

                      </span>
                    </div>
                    <div class="text-danger" v-html="base_errors.color" />
                  </div>
                  <!-- == Size === -->
                  <div class="Size-varient" v-if="product.sizes && product.sizes.length>0">
                    <div class="d-flex gap-1">
                      <h3>{{__('Size')}} : {{ base_form.size }}</h3>

                    </div>
                    <div class="sizebtn">
                      <a :class="{'active':size.name[currentLang] === base_form.size,'out_stock':!size.pivot.inStock }" @click="select_size(size)" v-for="size,index in product.sizes" :key="index" href="javascript:void(0)">{{size.name[currentLang]}}</a>
                    </div>
                    <div class="text-danger" v-html="base_errors.size" />
                  </div>



                  <!-- =====  Addto Card  ===== -->

                  <div class="add-button-inner" v-if="check_sizes_stock()">

                    <button v-if="$page.props.messages.success"  @click="add_cart(product)" href="javascript:void(0)" class="add-to">{{__('Added')}}
                    <i class="success fas fa-check-circle"></i>
                    </button>
                    <button :disabled="base_form.processing" v-else @click="add_cart(product)" href="javascript:void(0)" class="add-to">{{__('Add To Cart')}}
                    </button>

                  </div>
                  <div v-else class="out-of-stock">
                    {{__('this product is out of stock')}}
                  </div>


                  <!-- <div class="tamara d-flex gap-2">
                    <p> Lorem ipsum dolor sit <strong> 17 SAR </strong>, consectetur adipisicing elit. Assumenda natus, harum deserunt magnam excepturi dolore nulla ratione possimus
                    <a class="" href=""> Learn more </a>
                    </p>
                    <img width="150" src="/home/img/tamara.png">
                  </div> -->
                  <!-- ===== Branner Banner ====  -->
                  <div class="brand-banner-singleproduct">
                    <img src="/home/img/single.png" alt="" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="production-description">
        <div class="container-cum">
          <div class="row rows-info">
            <div class="col-lg-6 col-md-6 col-12">
                <div class="pro-details">
                <h2 class="">{{ __('Highlights') }} :</h2>
            </div>
              <div class="discription">
                <ul>
                <li v-show="attribute.product_options.length>0" v-for="attribute,index in product.product_attributes" :key="index">
                    {{attribute.attribute.translated_name}} :
                    <span v-for="option,index in attribute.product_options" :key="index">
                      {{ option.option.translated_name }}<span v-if="attribute.product_options.length>index+1">,</span>
                    </span>
                  </li>
                </ul>
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-12">

            </div>
          </div>

        </div>
      </div>
      <!-- ======== Product  Description ==== -->
      <!-- ======= Related Products  ====== -->
      <section class="Trending_Styles pt-5 pb-5 sm_pb_70">
        <div class="container-cum">
          <div class="title-top d-flex justify-content-between">
            <h2>{{ __('You May Also Like') }}</h2>
            <!-- <a href="#">See All</a> -->
          </div>
          <div class="shop-row pt-4 pb-4">
           <swiper-slider :slider_data="related"></swiper-slider>
          </div>
        </div>
      </section>
      <!-- ======= Related Products  ====== -->

      <!-- ====== style Product ======== -->

      <rating :rate="rate" :product="product"></rating>


    </main>
      </app>

  </template>



  <script>
    import App from '@/HomeLayouts/AppLayout.vue';
    import ProductBox from './Components/ProductBox.vue';
    import { useForm } from '@inertiajs/vue3';
import Rating from '@/Components/Rating.vue';
import SocialSharing from '@/Components/SocialSharing.vue';
import SwiperSlider from '@/Components/SwiperSlider.vue';

  export default {
      components: { App, ProductBox, Rating, SocialSharing, SwiperSlider },
      props:{
        product:Object,
        category:Object,
        parents:Array,
        related:Array,
        // sizes:Array,
        // colors:Array,
        rate:Boolean,
      },
      mounted(){
        setInterval((e)=>{
         let success = this.$page.props.messages.success
         if(success)
         this.$page.props.messages.success=null
        },10000)
        // if(this.product.sizes_ids.length>0){
        //   this.select_size(this.product.sizes_ids[0])
        // }
        // if(this.product.colors_ids.length>0){
        //   this.select_color(this.product.colors_ids[0])
        // }

        var swiper = new Swiper(".product-slider", {
        loop: false,
        slidesPerView: 1,
        spaceBetween: 0,
        speed: 700,
        navigation: {
          nextEl: ".pd_slider_next",
          prevEl: ".pd_slider_prev",
        },
        pagination: {
            el: ".swiper-pagination",
        },
        hashNavigation: {
            watchState: true,
        },
    });

    var currentIndex = swiper.activeIndex;

    // You can also listen to slide change events to get the index dynamically
    swiper.on('slideChange', function () {
    // Remove active class from all pagination items
    document.querySelectorAll('.pro-duct-slides .left-imgs a').forEach(function (el) {
        el.classList.remove('active');
      });
      // Add active class to the current pagination item
      var activeIndex = swiper.activeIndex;
      document.querySelector('.pro-duct-slides .left-imgs a:nth-child(' + (activeIndex + 1) + ')').classList.add('active');
    });
      },
      data(){
        return {
          form:useForm({

          }),
          selected_image:this.product.image,
          shareUrl: window.location.href,
          shareTitle: this.product['name_'+this.$page.props.locale],
          shareDescription: 'Check out this awesome Product!'
        }
      },
      methods:{
        get_color(id,column){
          let color = this.product.colors.find((e)=>e.id==id)
          if(color)
          return color[column]
        },
        // get_size(id,column){
        //   let size = this.product.sizes.find((e)=>e.id==id)
        //   if(size)
        //   return size[column]
        // },
        // base_form.size(id){
        //   this.base_form.size = this.get_size(id,'name')
        // },
        select_color(color){
          this.base_form.color = color.translated_name
        },
        select_size(size){
          if(size.pivot.inStock){
            this.base_form.size=size.name[this.currentLang]
          }
        },
        check_sizes_stock(){
          let stocks=[]
          this.product.sizes.forEach((el)=>{
            if(el.pivot.inStock){
              stocks.push(el)
            }
          })
          return stocks.length>0
        }



      },
      created(){

      }
  }

  </script>

  <style scoped>
    @media screen and (min-width: 768px) and (max-width: 991px) {
  .main-header-area {
    display: none !important;
  }
  .pro-titled h3 {
    color: #000;
    font-size: 17px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    margin: 5px 0;
    display: flex;
    align-self: center;
    gap: 7px;
  }

  .lustchild {
    display: block;
  }

  .pro-titled h3 span {
    color: #0000004d;
  }

  .responsive-desk {
    display: none !important;
  }

  .colors-varient h4 {
    color: #0000004d;
    font-size: 22px;
    font-style: normal;
    line-height: normal;
  }

  .colors-varient {
    margin-top: 0rem;
  }

  .responsive-start {
    display: flex !important;
    margin-top: -14px;
  }

  .product-info-content {
    border: none;
    padding-bottom: 5px;
  }
  .product-price {
    justify-content: end;
    align-items: center;
    gap: 10px;
  }
  .add-cart-sticky {
    display: block;
  }
  .add-button-inner {
    display: none !important;
  }

  .brand-banner-singleproduct {
    display: block !important;
    margin: 1rem 0;
  }

  .brand-banner-singleproduct img {
    width: 100%;
  }
  .bread_cam {
    display: none;
  }

  .product-information-details > div {
    padding: 0 !important;
  }
  .product-information-details {
    margin-top: 0px;
    margin-right: 0px;
    margin-left: 0px;
  }

  .left-imgs {
    display: none;
  }

  .pro-duct-slides {
    display: flex !important;
    flex-direction: column-reverse;
  }

  .left-imgs {
    display: flex;
    justify-content: space-between;
    gap: 5px;
    margin-top: 1rem;
    width: 100%;
  }
  .product-information-details .product-offer {
    z-index: 1;
    top: 5%;
  }
  .left-imgs img {
    height: 110px;
    width: 100%;
    margin-bottom: 8px;
  }

  .bread_cam .header-container {
    padding-left: 2rem;
    padding-right: 1rem;
  }
  .bread_cam .navigation-url ul {
    gap: 10px;
  }

  .bread_cam .navigation-url ul li a {
    color: #000000;
    font-weight: 400;
    font-size: 13px;
  }

  .pro-titled h2 {
    color: #000;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    margin: 10px 0;
  }
  .pro-titled h3 {
    color: #000;
    font-size: 20px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    margin: 10px 0;
  }
  .sizebtn a {
    border-radius: 38px;
    border: 2px solid #ececec;
    width: 130px;
    height: 40px;
    flex-shrink: 0;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #000000;
    font-weight: 600;
  }
  .produc-slide-img-sm img {
    height: 135px;
    border-radius: 5px;
    width: 120px;
    object-fit: cover;
  }

  .shop-card {
    display: flex !important;
    top: 160px !important;
    right: 10px !important;
  }
  .add-button-inner {
    display: flex;
    flex-direction: column;
    gap: 14px;
    margin-top: 1.5rem;
  }

  .add-button-inner button:disabled {cursor: not-allowed;}
  .add-button-inner button {
    border-radius: 14px;
    border: 2px solid #9600c1;
    height: 55px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: auto;
    font-size: 16px;
    margin-bottom:10px;
  }
  .pro-details h2 {
    color: #000;
    font-size: 20px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    margin-bottom: 15px;
  }

  .sizeGuide .size-title h2 {
    color: #000;
    font-size: 29px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    margin-bottom: 15px;
  }

  .products-details .title-top a {
    display: none;
  }

  .products-details .title-top h2 {
    font-size: 29px;
  }

  .discription ul li {
    color: #000;
    font-size: 17px;
    font-style: normal;
    font-weight: 500;
    line-height: normal;
    padding-bottom: 7px;
  }

  .discription p {
    color: #000;
    font-size: 17px;
    font-style: normal;
    font-weight: 500;
    line-height: normal;
  }

  .size-guide {
    margin-top: 8px;
  }
  .colors-varient h3 {
    color: #000;
    font-size: 24px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
  }

  .Size-varient h3 {
    color: #000;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
  }
  .add-button-inner button {
    border-radius: 14px;
    border: 3px solid #9600c1;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 350px;
    font-size: 18px;
  }

  .product-pric {
    display: none !important;
  }

  .rows-info {
    margin-top: 40px;
  }
  .colorbtn a ,.colorbtn img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: block;
  }
  .production-description {
    padding: 10px;
  }

  .products-details .Trending_Styles {
    padding-left: 10px;
    padding-right: 10px;
  }
  .style_products {
    display: none;
  }

  .product-slider img {
    height: 675px !important;
    width: 100%;
    border-radius: 0px !important;
  }

  .left-imgs {
    display: none !important;
  }
  .product-information-details .product-offer {
    display: none;
  }

  .pro-information {
    padding: 0 1rem;
  }

  .produc-slide-img-sm {
    display: flex !important;
  }
}
  </style>

  <style scoped>

  .pd_slider_arrow button{display: none;}
  .group-item{width:70px;display: inline-block;margin:4px;cursor: pointer;border:3px solid transparent}
  .group-item.active{border:3px solid #9600c1}
  .group-item img {width:100%}
  .add-to.selected{background: #551168;border-color:#551168;color:#fff}


  .style_products {
  background: #f8f8f8;
}
.bread_cam {
  padding-top: 15px;
  padding-bottom: 15px;
  border: 1px solid #e8e8e8;
}
.bread_cam .navigation-url ul {
  display: flex;
  align-items: center;
  gap: 1.3rem;
}
.bread_cam .navigation-url ul li img {
  transform: rotate(267deg);
}
.bread_cam .navigation-url ul li a {
  color: #000000;
  font-weight: 400;
  font-size: 20px;
}

.bread_cam .navigation-url ul li a:hover {
  color: #9600c1;
}

/*   Discription   */

.product-information-details {
  margin-top: 20px;
}

.rows-info {
  margin-top: 97px;
}

.pro-details h2 {
  color: #000;

  font-size: 20px;
  font-style: normal;
  font-weight: 700;
  line-height: normal;
  margin-bottom: 15px;
}

.discription p {
  color: #000;

  font-style: normal;
  font-weight: 500;
  line-height: normal;
}

.discription ul li {
  color: #000;

  font-style: normal;
  font-weight: 500;
  line-height: normal;
  padding-bottom: 7px;
}

.product-info-content {
  border-bottom: 1px solid #ddd;
  padding-bottom: 5px;
}

/* ==   Discription == */

.sizeGuide .size-title h2 {
  color: #000;

  font-size: 38px;
  font-style: normal;
  font-weight: 700;
  line-height: normal;
  margin-bottom: 15px;
}

.size-guide {
  margin-top: -65px;
}

.colors-varient {
  margin-top: 1rem;
}
.colors-varient h3 {
  color: #000;

  font-size: 28px;
  font-style: normal;
  font-weight: 700;
  line-height: normal;
}
.colors-varient h4 {
  color: #000;

  font-size: 28px;
  font-style: normal;
  line-height: normal;
}

.colorbtn {
  display: flex;
  align-items: center;
  gap: 15px;
  margin-top: 12px;
}
.colorbtn a {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  display: block;
}

.colorbtn a.active {
  border: 2px solid #9600c1;
}


.Size-varient h3 {
  color: #000;

  font-size: 20px;
  font-style: normal;
  font-weight: 700;
  line-height: normal;
}

.Size-varient h4 {
  color: #000;

  font-size: 28px;
  font-style: normal;

  line-height: normal;
}

.sizebtn {
  display: flex;
  gap: 0.5rem;
  margin: 1rem 0;
}
.sizebtn a {
  border-radius: 38px;
  border: 2px solid #ccc;
  width: 130px;
  height: 52px;
  flex-shrink: 0;
  background: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #000000;
  font-weight: 600;
}

.sizebtn a:hover {
  color: #fff;
  background-color: #9600c1;
  border-color: #9600c1;
}

.sizebtn a.active {
  background: #9600c1;color:#fff
}
.sizebtn a.out_stock {
  border-color: #c9c9c9;
  background:#ddd
}
.sizebtn a.out_stock:hover {
  color: #000;
  cursor:not-allowed
}
.discription ul {
  padding-left: 2rem;
}
.discription ul li {
  list-style: disc !important;
}

.add-button-inner {
  display: flex;
  flex-direction: column;
  gap: 14px;
}
.add-button-inner button {
    border-radius: 14px;
    border: 3px solid #9600c1;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    max-width: 350px;
    font-size: 18px;
}

.pro-titled h2 {
  color: #000;

  font-size: 20px;
  font-style: normal;
  font-weight: 700;
  line-height: normal;
  margin: 10px 0;
}

.pro-titled h3 {
  color: #000;

  font-size: 24px;
  font-style: normal;
  font-weight: 700;
  line-height: normal;
  margin: 10px 0;
}

.add-to {
  color: #9600c1;
}
.add-to:hover {
  background-color: #390049;
  color: #fff !important;
  border-color: #390049;
}

.Purchase {
  background: #9600c1;
  color: #fff;
}
.Purchase:hover {
  background-color: #390049;
  border-color: #390049;
}

.Purchase:hover {
  color: #fff !important;
}

.responsive-start {
  display: none !important;
}
.product-pric {
  display: none;
}
.add-cart-sticky {
  display: none;
}
.add-cart-sticky a {
  border-radius: 10px;
  border: 3px solid #9600c1;
  height: 50px;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  font-size: 16px;
  color: #fff;
  background: #9600c1;
}

.brand-banner-singleproduct {
  display: none;
}

.product-pric {
  display: none !important;
}

.add-cart-sticky {
  position: fixed;
  bottom: 3%;
  width: 90%;
  z-index: 9;
  padding: 10px 10px;
  border: none;
  display: none;
}

.product-slider img {
  height: 351px !important;
  width: 100%;
  border-radius: 0px !important;
}

.products-details .owl-nav {
  width: 100%;
}
.products-details .owl-dots {
  position: absolute;
  bottom: 0;
  left: 50%;
  transform: translate(-50%, -50%);
}
.products-details .owl-nav button {
  width: 30px;
  height: 30px;
  margin-left: 15px !important;
  margin-right: 15px !important;
  background: transparent;
  color: #fff;
  font-size: 10px !important;
}

.products-details .owl-nav button span {
  font-size: 18px !important;
}

.products-details .owl-dots button {
  width: 10px;
  height: 12px;
  border-radius: 50%;
  margin: 0 4px;
  background: hsla(0, 0%, 100%, 0.637);
}
.products-details .owl-dots button.active {
  background: #fff;
}
.produc-slide-img-sm {
  gap: 10px;
  margin: 15px 0;
}
.produc-slide-img-sm img {
  height: 60px;
  border-radius: 5px;
  width: 60px;
  object-fit: cover;
}

.products-details .product-slider .owl-carousel img {
  height: 351px;
}

.produc-slide-img-sm {
  display: none !important;
}

.products-details .shop-card {
  background: #fff;
  display: flex;
  flex-direction: column;
  gap: 5px;
  position: absolute;
  top: 15px;
  z-index: 1;
  padding: 4px;
  justify-content: center;
  border-radius: 50px;
  height: 95px;
  right: 25px;
  display: none;
}
.products-details .shop-card img {
  width: 30px;
}

.product-information-details .product-offer {
  z-index: 1;
  top: 36px;
  right: 34px;
  bottom: unset;
}

.product-slider img {
  height: 745px !important;
  width: 100%;
  border-radius: 0px !important;
  object-fit: cover !important;
}
.left-imgs {
  width: 180px;
  margin-right: 15px;
  max-height: 752px;
  overflow: auto;
}
.left-imgs img {
  height: auto;
  width: 82px;
  object-fit: cover;
  margin: 8px auto;
}

.add-button-inner button:hover {
  color: #9600c1;
}

.lustchild {
  display: none;
}

.left-imgs a {
  display: block;
}

.left-imgs a.active img {
  outline: 1px solid #000;
}

@media screen and (min-width: 1600px) {
  .product-slider img {
    height: 745px !important;
    width: 100%;
    border-radius: 0px !important;
  }
  .left-imgs {
    width: 180px;
    margin-right: 15px;
  }
  .left-imgs img {
    height: 180px;
    width: 100%;
    margin-bottom: 8px;
  }

  .products-details .owl-dots {
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translate(-50%, -50%);
    display: none;
  }

  .products-details .owl-nav {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #fff;
    display: none;

    justify-content: space-between;
  }
}

@media screen and (min-width: 1200px) and (max-width: 1600px) {
  .products-details {
    padding-left: 1rem;
    padding-right: 1rem;
  }
  .add-button-inner button {
    border-radius: 14px;
    border: 3px solid #9600c1;
    height: 70px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 350px;
    font-size: 26px;
  }

  .left-imgs {
    width: 210px;
    margin-right: 15px;
  }

  .size-guide {
    margin-top: -36px;
  }

  .style_products {
    display: none;
  }


  .bread_cam .header-container {
    max-width: 100%;
    padding-left: 30px;
    padding-right: 30px;
  }
  .bread_cam {
    padding-top: 15px;
    padding-bottom: 15px;
    border: 1px solid #e8e8e8;
  }

  .sizebtn a {
    border-radius: 38px;
    border: 1px solid #ccc;
    width: 100px;
    height: 50px;
    flex-shrink: 0;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #000000;
    font-weight: 600;
  }

  .size-guide {
    margin-top: -20px;
  }

  .product-pric {
    display: none !important;
  }
}

@media screen and (min-width: 992px) and (max-width: 1199px) {
  .pro-duct-slides {
    display: flex !important;
    flex-direction: column-reverse;
  }

  .lustchild {
    display: block;
  }
  .owl-carousel .owl-item img {
    height: 500px !important;
    object-fit: cover;
    width: 100%;
  }

  .left-imgs {
    display: flex;
    justify-content: space-between;
    gap: 5px;
    margin-top: 1rem;
    width: 100%;
  }

  .left-imgs img {
    width: 100%;
  }

  .product-information-details .product-offer {
    z-index: 1;
    top: 5%;
  }
  .left-imgs img {
    height: 110px;
    width: 100%;
    margin-bottom: 8px;
  }

  .bread_cam .navigation-url ul li a {
    color: #000000;
    font-weight: 400;
    font-size: 13px;
  }

  .bread_cam .header-container {
    padding-left: 2rem;
    padding-right: 1rem;
  }
  .bread_cam .navigation-url ul {
    gap: 10px;
  }

  .bread_cam .navigation-url ul li a {
    color: #000000;
    font-weight: 400;
    font-size: 13px;
  }

  .pro-titled h2 {
    color: #000;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    margin: 10px 0;
  }
  .pro-titled h3 {
    color: #000;
    font-size: 20px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    margin: 10px 0;
  }
  .sizebtn a {
    border-radius: 38px;
    border: 1px solid #ccc;
    width: 73px;
    height: 35px;
    flex-shrink: 0;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #000000;
    font-weight: 600;
  }
  .add-button-inner {
    display: flex;
    flex-direction: column;
    gap: 14px;
    margin-top: 1.5rem;
  }

  .add-button-inner button {
    border-radius: 14px;
    border: 3px solid #9600c1;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 350px;
    font-size: 18px;
  }
  .pro-details h2 {
    color: #000;
    font-size: 20px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    margin-bottom: 15px;
  }

  .sizeGuide .size-title h2 {
    color: #000;
    font-size: 29px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    margin-bottom: 15px;
  }

  .products-details .title-top a {
    display: none;
  }

  .products-details .title-top h2 {
    font-size: 29px;
  }

  .discription ul li {
    color: #000;
    font-style: normal;
    font-weight: 500;
    line-height: normal;
  }

  .discription p {
    color: #000;
    font-style: normal;
    font-weight: 500;
    line-height: normal;
  }

  .size-guide {
    margin-top: 8px;
  }
  .colors-varient h3 {
    color: #000;
    font-size: 24px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
  }

  .Size-varient h3 {
    color: #000;

    font-style: normal;
    font-weight: 700;
    line-height: normal;
  }
  .add-button-inner button {
    border-radius: 14px;
    border: 3px solid #9600c1;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 350px;
    font-size: 18px;
  }
  .colors-varient h4 {
    color: #000;
    font-size: 22px;
    font-style: normal;
    line-height: normal;
  }
  .product-pric {
    display: none !important;
  }

  .rows-info {
    margin-top: 40px;
  }
  .colorbtn a {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: block;
  }
  .production-description {
    padding: 10px;
  }

  .products-details .Trending_Styles {
    padding-left: 10px;
    padding-right: 10px;
  }
  .style_products {
    display: none;
  }

  .brand-banner-singleproduct {
    display: block !important;
  }
}



@media screen and (min-width: 425px) and (max-width: 767px) {
  .left-imgs {
    display: none;
  }

  .lustchild {
    display: block;
  }

  .brand-banner-singleproduct {
    display: block;
  }

  .products-details .shop-card {
    gap: 5px;
    top: 50px !important;
    border-radius: 50px;
    height: 85px;
    right: 28px;
    display: flex !important;
  }

  .bread_cam .navigation-url ul li a {
    color: #000000;
    font-weight: 400;
    font-size: 13px;
  }
  .bread_cam .header-container {
    padding-left: 2rem;
    padding-right: 1rem;
  }

  .left-imgs {
    display: none;
  }
  .product-information-details .product-offer {
    z-index: 1;
    top: 10%;
    display: none;
  }

  .products-details .shop-card {
    background: #fff;
    display: flex;
    flex-direction: column;
    gap: 5px;
    position: absolute;
    top: 15px;
    z-index: 1;
    padding: 4px;
    justify-content: center;
    border-radius: 50px;
    height: 130px;
    right: 25px;
    display: none;
  }

  .bread_cam .navigation-url ul {
    display: flex;
    align-items: center;
    gap: 0.3rem;
  }

  .production-description {
    padding-left: 10px;
    padding-right: 10px;
  }

  .bread_cam {
    display: none;
  }

  .brand-banner-singleproduct {
    display: block;
  }
  .brand-banner-singleproduct img {
    width: 100%;
  }
  .add-cart-sticky {
    position: fixed;
    bottom: 3%;
    width: 90%;
    z-index: 9;
    padding: 10px 10px;
    border: none;
    display: block;
  }


  .products-details .Trending_Styles {
    padding-left: 5px;
    padding-right: 5px;
  }
  .style_products {
    display: none;
  }
  .responsive-start {
    display: block !important;
    padding: 0 !important;

    margin-top: -3px;
  }

  .add-button-inner {
    display: none;
  }

  .responsive-desk {
    display: none !important;
  }
  .product-info-content {
    border: none;
  }

  .colors-varient {
    margin-top: 0 !important;
    position: relative;
  }
  .Size-varient {
    margin-top: 0.6rem;
  }

  .rows-info {
    margin-top: 0;
  }

  .product-pric {
    position: absolute;
    right: 0;
    bottom: -35px;
    align-items: center;
    gap: 5px !important;
    display: flex !important;
  }

  .product-pric .current-price p {
    font-size: 20px;
  }

  .production-description {
    padding-left: 10px;
    padding-right: 10px;
  }

  .pro-information {
    padding: 0 10px;
  }

  .pro-titled h2 {
    color: #000;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    margin: 10px 0;
  }

  .pro-details h2 {
    color: #000;
    font-size: 20px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    margin-bottom: 5px;
    margin-top: 8px;
  }

  .discription p {
    color: #000;
    font-size: 14px;
    font-style: normal;
    font-weight: 500;
    line-height: normal;
  }

  .discription ul li {
    color: #000;
    font-size: 14px;
    font-style: normal;
    font-weight: 500;
    line-height: normal;
  }

  .sizeGuide .size-title h2 {
    color: #000;
    font-size: 20px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    margin-bottom: 15px;
  }

  .size-guide {
    margin-top: 13px;
  }

  .size-tabel img {
    width: 100%;
  }

  .pro-titled h3 {
    color: #000;
    font-size: 17px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    margin: 5px 0;
    display: flex;
    align-self: center;
    gap: 7px;
  }
  .colorbtn {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 12px;
  }

  .pro-titled h3 span {
    color: #0000004d;
  }
  .Size-varient h4 {
    color: #000;
    font-size: 17px;
    font-style: normal;
    line-height: normal;
  }

  .Size-varient h3 {
    color: #000;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
  }
  .sizebtn {
    display: flex;
    gap: 0.5rem;
    margin: 1rem 0;
  }
  .colorbtn a {
    width: 27px;
    height: 27px;
    border-radius: 50%;
    display: block;
  }

  .colors-varient h3 {
    color: #000;
    font-size: 17px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
  }

  .colors-varient h4 {
    color: #0000004d;
    font-size: 17px;
    font-style: normal;
    line-height: normal;
  }

  .product-info .current-price {
    color: #9600c1;
    font-weight: 600;
    font-size: 17px;
  }

  .product-info .old-price p {
    color: rgba(0, 0, 0, 0.3);
    text-decoration: line-through;
    font-size: 16px;
    font-weight: 500;
  }

  .sizebtn a {
    border-radius: 38px;
    border: 2px solid #e9e7e7;
    width: 110px;
    height: 40px;
    flex-shrink: 0;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #000000;
    font-weight: 600;
  }

  .product-information-details {
    margin-top: 0;
  }
  .main-header-area {
    display: none !important;
  }

  .produc-slide-img-sm {
    display: flex !important;
  }
}

@media screen and (min-width: 326px) and (max-width: 425px) {
  .bread_cam {
    display: none;
  }

  .lustchild {
    display: block;
  }

  .product-information-details .product-offer {
    z-index: 1;
    top: 10%;
    display: none;
  }
  .products-details .shop-card {
    gap: 5px;
    top: 40px !important;
    border-radius: 50px;
    height: 70px !important;
    right: 28px;
    display: flex !important;
  }

  .left-imgs {
    display: none;
  }

  .products-details .shop-card img {
    width: 28px;
  }

  .products-details .product-slider img {
    height: 530px !important;
    width: 100%;
    border-radius: 0px !important;
  }

  .add-cart-sticky {
    position: fixed;
    bottom: 1%;
    width: 90%;
    z-index: 9;
    padding: 10px 10px;
    border: none;
    display: block;
  }


  .main-header-area {
    display: none !important;
  }
  .products-details .Trending_Styles {
    padding-left: 5px;
    padding-right: 5px;
  }
  .responsive-start {
    display: block !important;
    padding: 0 !important;

    margin-top: -3px;
  }

  .add-button-inner {
    display: none;
  }

  .responsive-desk {
    display: none !important;
  }
  .product-info-content {
    border: none;
  }

  .colors-varient {
    margin-top: 0 !important;
    position: relative;
  }
  .Size-varient {
    margin-top: 0.6rem;
  }

  .rows-info {
    margin-top: 0;
  }

  .product-pric {
    position: absolute;
    right: 0;
    bottom: -35px;
    align-items: center;
    gap: 5px !important;
    display: flex !important;
  }

  .product-pric .current-price p {
    font-size: 20px;
  }

  .production-description {
    padding-left: 10px;
    padding-right: 10px;
  }

  .pro-information {
    padding: 0 10px;
  }

  .pro-titled h2 {
    color: #000;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    margin: 10px 0;
  }

  .pro-details h2 {
    color: #000;
    font-size: 20px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    margin-bottom: 5px;
    margin-top: 8px;
  }

  .discription p {
    color: #000;
    font-size: 14px;
    font-style: normal;
    font-weight: 500;
    line-height: normal;
  }

  .discription ul li {
    color: #000;
    font-size: 14px;
    font-style: normal;
    font-weight: 500;
    line-height: normal;
  }

  .sizeGuide .size-title h2 {
    color: #000;
    font-size: 20px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    margin-bottom: 15px;
  }

  .size-guide {
    margin-top: 13px;
  }

  .size-tabel img {
    width: 100%;
  }

  .pro-titled h3 {
    color: #000;
    font-size: 17px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    margin: 5px 0;
    display: flex;
    align-self: center;
    gap: 7px;
  }
  .colorbtn {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 12px;
  }

  .pro-titled h3 span {
    color: #0000004d;
  }
  .Size-varient h4 {
    color: #000;
    font-size: 17px;
    font-style: normal;
    line-height: normal;
  }

  .Size-varient h3 {
    color: #000;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
  }
  .sizebtn {
    display: flex;
    gap: 0.5rem;
    margin: 1rem 0;
  }
  .colorbtn a {
    width: 27px;
    height: 27px;
    border-radius: 50%;
    display: block;
  }

  .colors-varient h3 {
    color: #000;
    font-size: 17px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
  }

  .colors-varient h4 {
    color: #0000004d;
    font-size: 17px;
    font-style: normal;
    line-height: normal;
  }

  .product-information-details {
    margin-top: 0;
  }
  .product-info .current-price {
    color: #9600c1;
    font-weight: 600;
    font-size: 17px;
  }

  .product-info .old-price p {
    color: rgba(0, 0, 0, 0.3);
    text-decoration: line-through;
    font-size: 16px;
    font-weight: 500;
  }

  .sizebtn a {
    border-radius: 38px;
    border: 2px solid #f1f1f1;
    width: 95px;
    height: 35px;
    flex-shrink: 0;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #000000;
    font-weight: 600;
  }

  .discription p {
    color: #000;
    font-style: normal;
    font-weight: 500;
    line-height: normal;
  }

  .discription ul li {
    color: #000;
    font-size: 16px;
    font-style: normal;
    font-weight: 500;
    line-height: normal;
  }

  .style_products {
    display: none;
  }
}

@media screen and (min-width: 326px) and (max-width: 375px) {
  .left-imgs {
    display: none;
  }

  .lustchild {
    display: block;
  }
  .product-information-details {
    margin-top: 0;
  }

  .product-information-details .product-offer {
    z-index: 1;
    top: 10%;
    display: none;
  }
  .products-details .shop-card {
    display: flex !important;
  }

  .produc-slide-img-sm {
    display: flex !important;
  }

  .products-details .shop-card img {
    width: 28px;
  }
  .add-cart-sticky {
    display: block;
  }

  .add-cart-sticky {
    position: fixed;
    bottom: 1%;
    width: 90%;
    z-index: 9;
    padding: 10px 10px;
    border: none;
  }

  .sizebtn a {
    border-radius: 38px;
    border: 1px solid #ccc;
    width: 82px;
    height: 35px;
    flex-shrink: 0;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #000000;
    font-weight: 600;
  }

  .style_products {
    display: none;
  }
  .brand-banner-singleproduct {
    display: block;
  }
}

@media screen and (min-width: 100px) and (max-width: 325px) {
  .bread_cam {
    display: none;
  }

  .lustchild {
    display: block;
  }
  .left-imgs {
    display: none;
  }

  .product-offer {
    display: none;
  }
  .products-details .shop-card {
    display: flex !important;
  }
  .products-details .shop-card img {
    width: 28px;
  }
  .add-cart-sticky {
    display: block;
  }

  .brand-banner-singleproduct {
    display: block;
  }
  .brand-banner-singleproduct img {
    width: 100%;
  }
  .add-cart-sticky {
    position: fixed;
    bottom: 1%;
    width: 90%;
    z-index: 9;
    padding: 10px 10px;
    border: none;
  }

  .products-details .Trending_Styles {
    padding-left: 5px;
    padding-right: 5px;
  }
  .style_products {
    display: none;
  }
  .responsive-start {
    display: block !important;
    padding: 0 !important;

    margin-top: -3px;
  }

  .add-button-inner {
    display: none;
  }

  .responsive-desk {
    display: none !important;
  }
  .product-info-content {
    border: none;
  }

  .colors-varient {
    margin-top: 0 !important;
    position: relative;
  }
  .Size-varient {
    margin-top: 0.6rem;
  }

  .rows-info {
    margin-top: 0;
  }

  .product-pric {
    position: absolute;
    right: 0;
    bottom: -35px;
    align-items: center;
    gap: 5px !important;
    display: flex !important;
  }

  .product-pric .current-price p {
    font-size: 20px;
  }

  .production-description {
    padding-left: 10px;
    padding-right: 10px;
  }

  .pro-information {
    padding: 0 10px;
  }

  .pro-titled h2 {
    color: #000;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    margin: 10px 0;
  }

  .pro-details h2 {
    color: #000;
    font-size: 20px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    margin-bottom: 5px;
    margin-top: 8px;
  }

  .discription p {
    color: #000;
    font-size: 14px;
    font-style: normal;
    font-weight: 500;
    line-height: normal;
  }

  .discription ul li {
    color: #000;
    font-size: 14px;
    font-style: normal;
    font-weight: 500;
    line-height: normal;
  }

  .sizeGuide .size-title h2 {
    color: #000;
    font-size: 20px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    margin-bottom: 15px;
  }

  .size-guide {
    margin-top: 13px;
  }

  .size-tabel img {
    width: 100%;
  }

  .pro-titled h3 {
    color: #000;
    font-size: 17px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
    margin: 5px 0;
    display: flex;
    align-self: center;
    gap: 7px;
  }
  .colorbtn {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 12px;
    display: none;
  }

  .produc-slide-img-sm {
    display: flex !important;
  }

  .pro-titled h3 span {
    color: #0000004d;
  }
  .Size-varient h4 {
    color: #000;
    font-size: 17px;
    font-style: normal;
    line-height: normal;
  }

  .Size-varient h3 {
    color: #000;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
  }
  .sizebtn {
    display: flex;
    gap: 0.5rem;
    margin: 1rem 0;
  }
  .colorbtn a {
    width: 27px;
    height: 27px;
    border-radius: 50%;
    display: block;
  }

  .colors-varient h3 {
    color: #000;
    font-size: 17px;
    font-style: normal;
    font-weight: 700;
    line-height: normal;
  }

  .colors-varient h4 {
    color: #0000004d;
    font-size: 17px;
    font-style: normal;
    line-height: normal;
  }

  .product-info .current-price {
    color: #9600c1;
    font-weight: 600;
    font-size: 17px;
  }

  .product-info .old-price p {
    color: rgba(0, 0, 0, 0.3);
    text-decoration: line-through;
    font-size: 16px;
    font-weight: 500;
  }

  .sizebtn a {
    border-radius: 38px;
    border: 2px solid #f0eeee;
    width: 68px;
    height: 26px;
    flex-shrink: 0;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #000000;
    font-weight: 600;
  }

  .product-information-details {
    margin-top: 0;
  }
  .main-header-area {
    display: none !important;
  }
}
.add-to{position: relative}
.add-to .success{position: absolute;right: 10px;font-size: 30px;}
.tamara{padding: 15px;
    margin-top: 20px;
    border: 1px solid #ddd;
    border-radius: 10px;}
.tamara a{border-bottom: 1px solid;}
.current-price.product-page p{font-size: 25px;}
.old-price.product-page p{font-size: 25px;}
.product-favourite{    width: 44px;
    position: relative;
    }
.product-favourite img{border: 1px solid #ddd;
    width: 44px;
    padding: 5px;border-radius: 3px;}
    .out-of-stock{border: 1px solid #ddd;
    padding: 10px;
    border-radius: 6px;
    background: #ffc758;
    text-align: center;}
    .sizebtn a {
    width: auto !important;
    height: auto !important;
    padding: 4px 18px !important;
    min-width: 70px;
  }
  </style>




