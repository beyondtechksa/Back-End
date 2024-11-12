

<template>

    <Head :title="$page.props.page_title" />
      <app>
        <div class="bread_cam">
      <div class="container-cum">
        <div class="navigation-url">
          <ul>
            <li>
              <Link  :href="route('welcome')">{{ __('Home') }}</Link>
            </li>
            <li>
              <img src="/home/img/arrow.svg" alt="">
            </li>
            <li>
              <Link :href="route('cart')"> {{ __('My Cart') }}</Link>
            </li>
            <li>
              <img src="/home/img/arrow.svg" alt="">
            </li>
            <li>
              <a href="#"> {{ __('Checkout') }} </a>
            </li>
            <li>
              <img src="/home/img/arrow.svg" alt="">
            </li>
            <li>
              <a href="#"> {{ __('Successfully Ordered') }} </a>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- ====== bread Cam  ===== -->
    <main class="Successfully">
      <div class="container-sm">
        <div class="Successfully-container">
          <div class="m-auto text-center">
            <img src="/home/img/Rectangly-success.svg" alt="">
          </div>
          <div class="Successfully-mess">
            <h2>{{ __('Your Order is Confirmed!') }}</h2>
            <p>
              {{ __("We’ll send you a shipping confirmation email as soon as your order ships.") }}
            </p>
            <Link :href="route('profile.track_order',order.id)" class="check"> {{ __('Track Order') }} </Link>

            <!-- <div class="star-rating my-3">
            <span v-for="star in stars" :key="star" class="star" @click="rate(star)">
              <i
                :class="star <= currentRating ? 'fas fa-star' : 'far fa-star'"
                @mouseover="currentHover = star"
                @mouseleave="currentHover = 0"
              ></i>
            </span>
          </div> -->
          </div>
        </div>
      </div>
    </main>


      </app>

  </template>



  <script>
    import App from '@/HomeLayouts/AppLayout.vue';
    import { useForm } from '@inertiajs/vue3';

  export default {
      components: { App, },
      props:{
        order:Object,
        rating: {
          type: Number,
          default: 0,
        },
      },
      mounted(){

      },
      data(){
        return {
          form:useForm({

          }),
          currentRating: this.rating,
          currentHover: 0,
        }
      },
      methods:{
        pay(){
          this.form.post(route('order.create'))
        },
        rate(star) {
        this.currentRating = star;
        this.$emit('update:rating', star);
      },
      },
      computed: {
      stars() {
        return [1, 2, 3, 4, 5];
      },
    },

  }

  </script>

<style>
.emty-card img{margin: 0 auto;}
.input-box{width:100%}
</style>



<style scoped>
.star-rating {
  display: flex;
  flex-direction: row;
}

.star {
  cursor: pointer;
  font-size: 2rem;
  color: #ffd055;
}

.star:hover,
.star:hover ~ .star {
  color: #ffd055;
}

.fas.fa-star {
  color: #ffd055;
}

.far.fa-star {
  color: #dcdcdc;
}
</style>
