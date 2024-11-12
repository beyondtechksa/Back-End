<template>

    <section class="products-details" v-if="rate || reviews.length>0">
      <div class="container-cum">
    <div class="product-reviews">
    <div class="title-top">  <h2>{{ __('Product Reviews') }}</h2></div>
      <div v-for="review in reviews" :key="review.id" class="review">
        <div class="review-header">
          <img v-lazy="review.user.avatar ?? $page.props.auth.default_img" alt="User Avatar" class="avatar" />
          <div>
            <h4 class="username">{{ review.user.name }}</h4>
            <div class="review-stars">
              <span v-for="star in 5" :key="star" class="star" :class="{ filled: star <= review.rate }">★</span>
            </div>
          </div>
        </div>
        <div class="d-flex gap-2">
          <img style="width: 200px;" v-if="review.file" v-lazy="review.file">
        <p>{{ review.comment }}</p>
        </div>
      </div>
        <div v-show="rate">
          <h3 class="my-2">{{ __('Add Review') }}</h3>
          <form @submit.prevent="submitReview">
        <div class="review-stars">
          <span v-for="star in 5" :key="star" class="star" :class="{ filled: star <= newReview.rate }" @click="newReview.rate = star">★</span>
        </div>
        <textarea v-model="newReview.comment" :placeholder="__('Write your review here')" required></textarea>
        <div class="py-3">
        <input accept="image/*" class="form-control file-input" type="file" @change="upload">
        <span class="text-danger" v-html="newReview.errors.file" />
        </div>
        <button type="submit">{{__('Submit Review')}}</button>
      </form>
        </div>
    </div>
    </div>
    </section>
  </template>


<script>
import { useForm } from '@inertiajs/vue3';

export default {
    props:{
        product:Object, rate:Boolean
    },
  data() {
    return {
        newReview:useForm({
            rate: 0,
            comment: '',
            product_id : this.product.id,
            file:null
        })
    }
  },
    computed:{
        reviews(){
          return this.product.rates ?? []
      }
    },
  methods: {
    submitReview() {
        this.newReview.post(route('store.rating'))
    },
    upload(e){
      let file = e.target.files[0]
      this.newReview.file=file
    }
  }
}
</script>

<style scoped>


.review {
  border-bottom: 1px solid #ddd;
  padding: 10px 0;
}

.review-header {
  display: flex;
  align-items: center;
  margin-bottom: 10px;
}

.avatar {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  margin-right: 10px;
}

.username {
  margin: 0;
  font-size: 1.2em;
  font-weight: bold;
}

.review-stars .star {
  color: #ddd;
  cursor: pointer;
  font-size: 30px;
}

.review-stars .star.filled {
  color: gold;
}

textarea {
  width: 100%;
  height: 100px;
  margin: 10px 0;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
}

button {
  padding: 10px 20px;
  background-color: #930ab8;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

button:hover {
  background-color: #711d88;
}
.file-input{border: 1px solid #ddd;padding:10px}
</style>
