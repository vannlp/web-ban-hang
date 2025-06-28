<script setup>
import axios from '@/libs/axios';
import toastManager from '@/libs/toast';
import TestApi from '@/Api/TestApi';
import ClientLayout from '@/Layouts/ClientLayout.vue';
import ImageSlider from '@/Components/Client/ImageSlider.vue';
import ProductCard from '@/Components/Client/ProductCard.vue';
import ProductSlider from '@/Components/Client/ProductSlider.vue';
import { Head } from '@inertiajs/vue3';
// Dùng layout riêng cho trang này
defineOptions({
  layout: ClientLayout,
})

const productTest = {
  name: 'MacBook Air M4 13 inch 2025 10CPU',
  image_avatar: 'https://cdn2.cellphones.com.vn/insecure/rs:fill:358:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/m/a/macbook_11_1_2.png',
  price: 100000,
  original_price: 200000
};

const props = defineProps({
  categories: {
    type: Array,
    default: () => []
  },
})

const images = [
  'https://cdn2.cellphones.com.vn/insecure/rs:fill:690:300/q:90/plain/https://dashboard.cellphones.com.vn/storage/deal-km-b2s-2025.jpg',
  'https://cdn2.cellphones.com.vn/insecure/rs:fill:690:300/q:90/plain/https://dashboard.cellphones.com.vn/storage/iphone-16-pro-max-sliding-thang-5.png',
]

</script>

<template>
  <div class="p-3">
    <Head :title="props?.title" /> 
    <ImageSlider :images="images" />
    
    <v-container>
      <template v-for="category in props.categories">
        <ProductSlider :title="category.name" :products="category.products" />
      </template>
    </v-container>
  </div>
</template>
