<template>
    <footer>
        <div class="text-center py-3 bg-white">
            <h4> {{ __('© 2023 copyright Softoio') }} </h4>
        </div>
    </footer>
</template>

<script>
import { Link, useForm } from '@inertiajs/vue3';
import DropdownLink from '@/Components/DropdownLink.vue';
export default {
  components: { Link, DropdownLink },
  setup() {
    const form = useForm({
      email: '',
      password: ''
    });
    return { form }
  },
  methods: {
    logout() {
      this.form.post(route('admin.logout'))
    },
    collapse_menu() {
      let el = document.getElementById('main-wrapper')
      let attribute = el.getAttribute('data-sidebartype')
      if (attribute == 'full') {
        el.setAttribute('data-sidebartype', 'mini-sidebar')
      } else {
        el.setAttribute('data-sidebartype', 'full')
      }
    }
  }
}

</script>

