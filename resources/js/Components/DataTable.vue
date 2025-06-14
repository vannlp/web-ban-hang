<script setup>
import { ref, watch, computed } from 'vue'
import axios from '@/libs/axios'
import EasyDataTable from 'vue3-easy-data-table'
import { debounce } from 'lodash'

import { useSlots } from 'vue'
const slots = useSlots()

// Lọc ra danh sách các slot tên bắt đầu bằng "item-"
const itemSlots = Object.keys(slots).filter(key => key.startsWith('item-'))

// Props
const props = defineProps({
  url: String,
  headers: Array,
  filters: Object, // reactive object
})

// State
const rows = ref([])
const total = ref(0)
const loading = ref(false)
const currentPage = ref(1)
const perPage = ref(10)
const search = ref('')

// Gộp lại thành serverOptions cho đồng bộ với EasyDataTable
const serverOptions = computed({
  get: () => ({
    page: currentPage.value,
    rowsPerPage: perPage.value,
  }),
  set: (val) => {
    currentPage.value = val.page
    perPage.value = val.rowsPerPage
  },
})

// Fetch data từ server
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
        ...props.filters,
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

// Debounce riêng cho search
const debouncedFetch = debounce(fetchData, 2000)

// Watch search
watch(search, () => {
  currentPage.value = 1
  debouncedFetch()
})

// Watch perPage, currentPage, filters
watch([currentPage, perPage, props.filters], fetchData, { immediate: true })

defineExpose({
  fetchData,
})
</script>


<template>
  <!-- Slot lọc phía trên -->
  <slot name="filters" />

  <!-- Ô tìm kiếm -->
  <div class="mb-2 flex">
    <input
      v-model="search"
      @input="currentPage = 1"
      type="text"
      placeholder="Tìm kiếm..."
      class="border rounded max-w-md p-2"
    />
  </div>

  <EasyDataTable
    v-model:server-options="serverOptions"
    :server-items-length="total"
    :loading="loading"
    :headers="props.headers"
    :items="rows"
  >
    <template
      v-for="slotName in itemSlots"
      :key="slotName"
      v-slot:[slotName]="slotProps"
    >
      <slot :name="slotName" v-bind="slotProps" />
    </template>
  </EasyDataTable>
</template>
