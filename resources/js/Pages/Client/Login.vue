<script setup>
import AuthAdmin from "@/Layouts/AuthAdmin.vue"
import { usePage } from '@inertiajs/vue3'
import { ref } from "vue"

const page = usePage()
const csrfToken = page.props.csrf_token
const email = ref('')
const password = ref('')


</script>

<template>
  <AuthAdmin>
    <form method="POST" :action="route('client.handleLogin')">
      <!-- CSRF Token -->
      <input type="hidden" name="_token" :value="csrfToken">

      <v-text-field
        label="Email"
        name="email"
        type="email"
        v-model="email"
        required
        class="mb-4"
      />

      <v-text-field
        label="Mật khẩu"
        name="password"
        type="password"
        v-model="password"
        required
        class="mb-6"
      />

      <v-btn type="submit" color="primary" block class="mb-2">
        Đăng nhập
      </v-btn>
    </form>
  </AuthAdmin>
</template>
