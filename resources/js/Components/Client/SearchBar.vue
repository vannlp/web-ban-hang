<!-- SearchBar.vue -->
<template>
  <v-text-field
    v-model="search"
    append-inner-icon="mdi-magnify"
    density="compact"
    label="Tìm kiếm"
    variant="solo"
    hide-details
    single-line
    class="search-bar"
    ></v-text-field>
</template>

<script setup>
import { ref } from 'vue'
const search = ref('')
</script>

<style scoped>
.search-bar input:focus{
  outline: none !important;
  box-shadow: none;
}
</style>
