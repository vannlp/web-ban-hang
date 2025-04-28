<script setup>
import EasyDataTable from 'vue3-easy-data-table'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

// Dùng layout riêng cho trang này
defineOptions({
  layout: AuthenticatedLayout,
})

const headers = [
  { text: 'Tên sản phẩm', value: 'name' },
  { text: 'Giá', value: 'price' },
  { text: 'Kho', value: 'stock' },
]

const products = [
  { name: 'Sản phẩm A', price: 100000, stock: 5 },
  { name: 'Sản phẩm B', price: 200000, stock: 10 },
]
</script>

<template>
  <div class="p-3">
    <v-card>
        <v-card-title>Xin chào!</v-card-title>
        <v-card-text>Đây là trang chủ bán hàng đơn giản.</v-card-text>
        <EasyDataTable :headers="headers" :items="products" />
    </v-card>
  </div>
</template>
