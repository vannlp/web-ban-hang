<template>
  <v-autocomplete
    v-model="model"
    v-model:search="search"
    :items="items"
    :loading="loading"
    :label="label"
    :item-title="itemTitle"
    :item-value="itemValue"
    multiple
    chips
    clearable
    hide-no-data
    hide-details
  />
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import {debounce} from 'lodash'
import axiosInstance from '@/libs/axios'

// Props
const props = defineProps({
  modelValue: {
    type: Array,
    default: () => [],
  },
  apiUrl: {
    type: String,
    required: true,
  },
  label: {
    type: String,
    default: 'Chọn mục',
  },
  itemTitle: {
    type: String,
    default: 'name',
  },
  itemValue: {
    type: String,
    default: 'id',
  },
  extraParams: {
    type: Object,
    default: () => ({}),
  },
  debounceDelay: {
    type: Number,
    default: 300,
  },
})

// Emits
const emit = defineEmits(['update:modelValue'])

// State
const model = ref([...props.modelValue])
const items = ref([])
const search = ref('')
const loading = ref(false)

// Đồng bộ modelValue vào trong component
watch(() => props.modelValue, (val) => {
  model.value = [...val]
})
watch(model, (val) => {
  emit('update:modelValue', val)
})

// Hàm gọi API
const fetchOptions = async (query = '', preloadIds = []) => {
  loading.value = true
  try {
    const { data } = await axiosInstance.get(props.apiUrl, {
      params: {
        search: query,
        preload: preloadIds.join(','),
        ...props.extraParams,
      },
    })
    items.value = data.data
  } catch (err) {
    console.error('Fetch error:', err)
  } finally {
    
    loading.value = false
  }
}

// Debounced version
const debouncedFetch = debounce((val) => {
  fetchOptions(val)
}, props.debounceDelay)

// Khi search thay đổi => gọi debounce
watch(search, (val) => {
  if (!val) return
  debouncedFetch(val)
})

// Gọi lần đầu khi mounted
onMounted(() => {
  fetchOptions('', props.modelValue)
})
</script>
