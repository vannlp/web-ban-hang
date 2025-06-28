<!-- ImageSlider.vue -->
<template>
  <v-container class="px-0">
    <v-carousel
      :height="props.height"
      cycle
      show-arrows
      hide-delimiters
      class="rounded"
    >
      <v-carousel-item
        v-for="(image, i) in images"
        :key="i"
        :src="image"
        cover
      />
    </v-carousel>
  </v-container>
</template>

<script setup>
const props = defineProps({
  images: {
    type: Array,
    default: () => []
  },
  height: {
    type: String,
    default: '400px'
  }
});

</script>
