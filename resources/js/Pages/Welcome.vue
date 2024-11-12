

<template>

  <Head title="Welcome" />

    <app>

      <div class="row authentication coming-soon mx-0">

<div class="col-xxl-8 col-xl-8 col-lg-8 col-12">
    <div class="authentication-cover">
        <div class="aunthentication-cover-content text-center">
            <div class="row justify-content-center align-items-center h-100">
                <div class="col-xxl-6 col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12">
                    <div class="mb-2">
                        <a href="">
                            <img src="/assets/images/logo.png" alt="" class="authentication-brand">
                        </a>
                    </div>
                    <p class="fw-semibold fs-12 mb-1 op-4">STAY TUNED</p>
                    <h1 class="fw-bold fs-25 mb-3">Coming Soon</h1>
                    <p class="mb-4 fs-18">Our website is currently under construction, </p>

                    <div class="row mt-4 mb-5 gy-xxl-0 gy-3 justify-content-center" id="timer">
                    </div>
                    <div class="mt-5">
                        <div class="btn-list">
                            <button class="btn btn-icon btn-primary btn-wave">
                                <i class="ri-facebook-line fw-bold"></i>
                            </button>

                            <button class="btn btn-icon btn-danger btn-wave">
                                <i class="ri-instagram-line fw-bold"></i>
                            </button>

                            <button class="btn btn-icon btn-warning btn-wave">
                                <i class="ri-snapchat-line fw-bold"></i>
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-xxl-4 col-xl-4 col-lg-4 d-lg-block d-none px-0">
    <div class="bg-light w-100 pt-5 pb-4 d-flex align-items-center justify-content-center coming-soom-image-container">
        <img src="/assets/images/media/media-87.svg" alt="" class="imig-fluid d-blck ">
      </div>
      <div class="text-center pt-5">
      <a target="_blank" class="btn btn-primary fs-20" href="/admin"> {{ __('developer area') }} </a>
      </div>
</div>

</div>

    </app>

</template>



<script>
import App from '@/Layouts/App.vue';

export default {
    components: { App },
}

</script>

