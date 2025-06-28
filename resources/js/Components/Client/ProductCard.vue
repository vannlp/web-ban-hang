<template>
  <Link :href="route('client.product.detail', {slug: product.slug})">
    <v-card
      class="rounded-lg overflow-hidden"
      elevation="2"
    >
      <!-- Giảm giá -->
      <v-chip
        v-if="discountPercent"
        color="red"
        class="absolute top-2 left-2 z-10 font-semibold"
        label
      >
        {{ discountPercent }}%
      </v-chip>

      <!-- Ảnh sản phẩm -->
      <v-img
        :src="product.image_avatar"
        height="auto"
        width="100%"
        cover
        class="mb-2"
      />

      <v-card-text class="px-4 pt-0 pb-2">
        <!-- Tên -->
        <div class="text-sm font-medium leading-tight mb-1">
          {{ product.name }}
        </div>
        <div class="mb-1">
          <span class="text-red-600 text-sm font-semibold mr-2">
              {{ formatCurrency(product.price) }}
          </span>
          <span
              v-if="product.original_price"
              class="text-gray-400 text-sm line-through"
          >
              {{ formatCurrency(product.original_price) }}
          </span>
        </div>
      </v-card-text>
    </v-card>
  </Link>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  product: {
    type: Object,
    required: true,
    default: () => ({
      name: '',
      slug: '',
      image_avatar: '',
      price: 0,
      original_price: 0,
    }),
  },
})

const product = props.product;

// Tính phần trăm giảm giá
const discountPercent = computed(() => {
  if (product.original_price && product.price) {
    const discount = ((product.original_price - product.price) / product.original_price) * 100
    return Math.round(discount)
  }
  return null
})

// Format tiền tệ VND
const formatCurrency = (value) => {
  return new Intl.NumberFormat('vi-VN', {
    style: 'currency',
    currency: 'VND',
    maximumFractionDigits: 0,
  }).format(value)
}
</script>
