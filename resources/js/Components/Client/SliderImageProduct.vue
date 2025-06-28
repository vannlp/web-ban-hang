<template>
  <div class="w-full">
    <!-- Ảnh chính -->
    <v-img
      :src="currentImage"
      class="rounded-md mb-4"
      height="auto"
      cover
    />

    <!-- Slider ảnh nhỏ -->
    <div class="relative">
      <!-- Nút trượt trái -->
      <v-btn
        icon
        size="small"
        class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow"
        @click="scrollThumbnails('left')"
      >
        <v-icon>mdi-chevron-left</v-icon>
      </v-btn>

      <!-- List ảnh nhỏ -->
      <div
        ref="thumbnailContainer"
        class="flex overflow-x-auto gap-2 px-8 justify-center"
      >
        <div
          v-for="(img, index) in props.images"
          :key="index"
          :class="[
            'cursor-pointer border rounded overflow-hidden min-w-[70px] h-[70px]',
            currentImage === img ? 'border-red-500' : 'border-transparent'
          ]"
          @click="currentImage = img"
        >
          <v-img :src="img" height="70" width="70" cover />
        </div>
      </div>

      <!-- Nút trượt phải -->
      <v-btn
        icon
        size="small"
        class="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow"
        @click="scrollThumbnails('right')"
      >
        <v-icon>mdi-chevron-right</v-icon>
      </v-btn>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const props = defineProps({
  images: {
    type: Array,
    required: true
  }
})


const currentImage = ref(props.images[0])
const thumbnailContainer = ref(null)

const scrollThumbnails = (direction) => {
  const el = thumbnailContainer.value
  if (!el) return
  const scrollAmount = 100
  el.scrollBy({ left: direction === 'left' ? -scrollAmount : scrollAmount, behavior: 'smooth' })
}

</script>
