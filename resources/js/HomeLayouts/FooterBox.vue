<template>
      <footer>
      <div class="footer_container">
        <div class="container-cum">
          <div class="row">
            <div class="col-lg-8 col-md-12 rtl-right-content">
              <div class="brand-logos">
                <a href="#">
                  <img width="200" v-lazy="base_logo_setting" alt="" />
                </a>
              </div>
              <div class="contact-information">
                <div class="row">
                  <div class="information col-4">
                    <h3>{{__('COMPANY INFO')}}</h3>
                    <a href="#"> {{__('About BEYOND')}} </a>
                    <!-- <a href="#"> {{__('Affiliate')}} *</a> -->
                    <!-- <a href="#"> {{__('Partner with BEYOND')}} *</a> -->
                    <!-- <a href="#"> {{__('Social Responsibility')}} *</a> -->
                  </div>
                  <div class="information col-4">
                    <h3>{{__('HELP & SUPPORT')}}</h3>
                    <Link :href="route('page',page.slug)" v-for="page,index in pages" :key="index"> {{page.translated_name}} </Link>
                  </div>
                  <div class="information col-4">
                    <h3>{{__('CUSTOMER CARE')}}</h3>
                    <a href="#"> {{__('Contact us')}} </a>
                    <!-- <a href="#"> {{__('Payment Method')}} *</a> -->
                    <!-- <a href="#"> {{__('Klama')}}*</a> -->
                  </div>
                </div>
              </div>
              <div class="usefulllinks">
                <h4>©2024 BEYOND {{__('All Rights Reserved')}}</h4>
                <ul>
                  <li>
                    <Link :href="route('page',page.slug)" v-for="page,index in footer_bar_pages" :key="index"> {{page.translated_name}} </Link>
                </li>
                </ul>
              </div>
            </div>
            <div class="col-lg-4 col-md-12">
              <div class="row rtl-left-conteent">
                <div class="col-6 social-media">
                  <h4>{{__('FIND US ON')}}</h4>
                  <div class="social" v-if="socials_setting">
                    <a :href="social.link" v-for="social,index in socials_setting.value" :key="index">
                      <img v-lazy="social.image" />
                    </a>
                  </div>
                </div>
                <div class="col-6 app-store">
                  <h4>{{__('SHOP FROM THE APP')}}</h4>
                  <a href="#">
                    <img src="/home/img/App.png" alt="app" />
                  </a>
                </div>
              </div>
              <div class="subscribe-form">
                <div class="subscribe">
                  <h4>{{__('SIGN UP FOR NEWSLETTER')}}</h4>
                  <form  @submit.prevent="storeSubscribe">
                    <input type="email" v-model="subscribeForm.email" :placeholder="__('Email Address')" />
                      <button type="submit">{{__('Subscribe')}}</button>
                  </form>
                </div>
              </div>
              <!-- <div class="mathood">
                <div>
                  <img
                    class="w-100"
                    src="/home/img/payment-mathod.png"
                    alt="mathod"
                  />
                </div>
              </div> -->
            </div>
          </div>
        </div>
      </div>
    </footer>

    <!-- ==== footer End ====== -->

    <!-- === Of Canvars  ===  -->



    <!-- =====  Popup Modal ======= -->

    <div class="popup-modal" v-show="popup.status">
      <div class="modal-content">
        <div class="modal-content-box">
          <div class="modal-close">
            <a href="#">
              <img src="/home/img/closeWhite.svg" alt="" />
            </a>
          </div>
          <div v-if="popup.value" class="modal-up-content">
            <h5>{{popup.value.text1[$page.props.locale]}}</h5>
            <h1>{{popup.value.text2[$page.props.locale]}}</h1>
            <p>
              {{popup.value.text3[$page.props.locale]}}
            </p>
            <Link :href="popup.link">{{popup.value.button[$page.props.locale]}}</Link>
          </div>
        </div>
      </div>
    </div>
    <!-- =====  Popup Modal ======= -->
    <!-- Back to top -->
</template>

<script>
import { Link, useForm } from '@inertiajs/vue3';
import Form from "vform";
export default {
  components: {Form, Link },
  setup() {
    const form = useForm({
      email: '',
      password: ''
    });
     const subscribeForm=useForm({
          email:null
      })
    return { form ,subscribeForm}
  },
  mounted(){
    this.get_footer_settings()
  },
  data(){
    return {
      socials_setting:Object,
      pages:Array,
      footer_bar_pages:Array,
      popup:Object,
    }
  },
  methods: {
    get_footer_settings(){
      axios.get('/get_footer_settings')
           .then((resp)=>{
            this.socials_setting=resp.data.socials_setting
            this.pages=resp.data.pages
            this.footer_bar_pages=resp.data.footer_bar_pages
            this.popup=resp.data.popup
           })
    },
      storeSubscribe(){
        this.subscribeForm.post(route('subscribe.store'))
    }
  }
}

</script>

