<template>
    <v-container class="fill-height d-flex justify-center align-center">
        <v-card class="pa-6 text-center" max-width="500" elevation="8">
        <v-icon color="success" size="64">mdi-check-circle</v-icon>
        <h2 class="mt-3 mb-2 text-success">Thanh toán thành công!</h2>
        <p>Cảm ơn bạn đã thanh toán. Bạn sẽ được chuyển về trang chủ sau {{ countdown }} giây.</p>
        <v-btn class="mt-4" color="primary" @click="router.visit('/')">Về trang chủ ngay</v-btn>
        </v-card>
    </v-container>
</template>

<script setup>
import ClientLayout from '@/Layouts/ClientLayout.vue';
import { onMounted, ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';


defineOptions({
  layout: ClientLayout,
})
const countdown = ref(4)

onMounted(() => {
  const interval = setInterval(() => {
    countdown.value--
    if (countdown.value === 0) {
      clearInterval(interval)
      router.visit('/') // chuyển hướng về trang chủ
    }
  }, 1000)
})

</script>