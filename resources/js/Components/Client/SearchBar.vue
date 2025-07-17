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
    @keydown.enter="submitSearch"
    @click:append-inner="submitSearch"
  ></v-text-field>
</template>

<script setup>
import { ref, watch } from 'vue'
import { router, usePage } from '@inertiajs/vue3'

const search = ref('')


const submitSearch = () => {
  router.visit(route('client.searchPage', { s: search.value }))
  // search.value = '';
}
</script>

<style scoped>
.search-bar input:focus {
  outline: none !important;
  box-shadow: none;
}
</style>
