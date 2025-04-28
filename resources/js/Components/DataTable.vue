<script setup>
import EasyDataTable from 'vue3-easy-data-table'
import axios from '@/libs/axios'
import { ref, watch } from 'vue'

// Props
const props = defineProps({
  url: String,
  headers: Array,
})

// State
const rows = ref([])
const total = ref(0)
const loading = ref(false)
const currentPage = ref(1)
const perPage = ref(10)
const search = ref('')
const filters = ref({})

// Fetch data
const fetchData = async () => {
  loading.value = true
  const start = (currentPage.value - 1) * perPage.value

  try {
    const { data } = await axios.get(props.url, {
      params: {
        draw: 1,
        start,
        length: perPage.value,
        search: search.value,
        ...filters.value,
      },
    })
    rows.value = data.data
    total.value = data.recordsFiltered || data.recordsTotal || 0
  } catch (e) {
    console.error('Lỗi khi load data:', e)
  } finally {
    loading.value = false
  }
}

watch([currentPage, perPage, search, filters], fetchData, { immediate: true })
</script>

<template>
  <div class="mb-4">
    <input
      v-model="search"
      @input="currentPage = 1"
      type="text"
      placeholder="Tìm kiếm..."
      class="border px-3 py-2 rounded w-full max-w-md"
    />
  </div>

  <EasyDataTable
    :headers="props.headers"
    :items="rows"
    :loading="loading"
    :server-items-length="total"
    :rows-per-page="perPage"
    v-model:current-page="currentPage"
  />
</template>
