<template>
  <div class="relative bg-white p-4 rounded-xl shadow-md mb-4">
    <h2 class="text-xl font-bold mb-4 text-uppercase">{{ props.title }}</h2>

    <!-- Slide Group -->
    <v-sheet >
        <v-slide-group show-arrows>
          <!-- Danh sách sản phẩm -->
          <v-slide-group-item
            v-for="(product, index) in products"
            :key="index"
          >
            <div class="min-w-[250px] max-w-[250px] p-2">
              <ProductCard :product="product" />
            </div>
          </v-slide-group-item>
        </v-slide-group>
    </v-sheet>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import ProductCard from '@/Components/Client/ProductCard.vue';

const props = defineProps({
  products: {
    type: Array,
    default: () => []
  },
  title: {
    type: String,
    default: ''
  }
});
const slider = ref(null)

// Sản phẩm demo
const products = props.products || [
  {
    name: 'Ốp lưng iPhone 15 Pro Max',
    image_avatar: 'https://cdn2.cellphones.com.vn/insecure/rs:fill:358:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/o/p/op-lung-iphone-15-pro-max-araree-flexield.png',
    price: 315000,
    original_price: 350000
  },
  
]





</script>

