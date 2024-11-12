<template>
    <Head :title="$page.props.page_name" />
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
          <h1 class="page-title fw-semibold fs-18 mb-0">{{$page.props.page_name}}</h1>
          <div class="ms-md-1 ms-0">
              <nav>
                  <ol class="breadcrumb mb-0">
                      <li class="breadcrumb-item"><Link :href="route('admin.dashboard')"> {{ __('dashboard') }} </Link></li>
                      <li class="breadcrumb-item active" aria-current="page">{{$page.props.page_name}}</li>
                  </ol>
              </nav>
          </div>
      </div>

</template>

    <script>
    import {Head} from '@inertiajs/vue3'
    export default {
        components:{Head},
    }
    </script>
