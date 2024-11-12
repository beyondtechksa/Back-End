<script>
import { Link, Head } from '@inertiajs/vue3';
export default {
  components:{
    Link, Head
  },
  mounted() {

  },
};
</script>

<template>
  <Head title="Comming Soon" />

  <div class="auth-page-wrapper pt-5">
    <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
      <div class="bg-overlay"></div>
      <div class="shape">
        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
          viewBox="0 0 1440 120">
          <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
        </svg>
      </div>
    </div>

    <div class="auth-page-content">
      <BContainer>
        <BRow>
          <BCol lg="12">
            <div class="text-center mt-sm-5 pt-4 mb-4">
              <div class="mb-sm-5 pb-sm-4 pb-5">

              </div>
              <div class="mb-5">
                <h1 class="display-2 coming-soon-text">Coming Soon</h1>
              </div>
              <div>
                <BRow class="justify-content-center mt-5">
                  <BCol lg="8">
                    <div id="countdown" class="countdownlist"></div>
                  </BCol>
                </BRow>

              </div>
            </div>
          </BCol>
        </BRow>
      </BContainer>
    </div>

  </div>
</template>
