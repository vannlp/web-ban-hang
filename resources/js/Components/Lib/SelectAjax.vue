<template>
  <v-autocomplete
    v-model="model"
    :search="search"
    :items="items"
    :loading="loading"
    :label="label"
    :item-title="itemTitle"
    :item-value="itemValue"
    @focus="onFocus"
    @change="onSelect"
    @update:search="handleSearch"
    no-filter
  />
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import { debounce } from 'lodash'
import axiosInstance from '@/libs/axios'

// Props
const props = defineProps({
  modelValue: {
    type: [Number, null, String],
    default: () => null,
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
  isDefauld: {
    type: Boolean,
    default: false,
  },
})

// Emits
const emit = defineEmits(['update:modelValue'])

// Khởi tạo item data có mục "--Chọn--"
const initItemData = (data = []) => {
  let array = []
  const defaultData = {}
  defaultData[props.itemTitle] = '--Chọn--'
  defaultData[props.itemValue] = null
  array.push(defaultData)

  if (data.length > 0) {
    array = [...array, ...data]
  }

  return array
}

// State
const model = ref(props.modelValue)
const items = ref(initItemData())
const search = ref('')
const loading = ref(false)
const isTyping = ref(false) // Flag xác định người dùng đang gõ

watch(() => props.modelValue, (val) => {
  model.value = val
})


watch(model, (val) => {
  emit('update:modelValue', val)
})


// Gọi API
const fetchOptions = async (query = '', id = null) => {
  loading.value = true
  let params = {}
  
  if (id) {
    params['id'] = id;
  }
  try {
    const { data } = await axiosInstance.get(props.apiUrl, {
      params: {
        search: query,
        
      },
    })
    items.value = initItemData(data.data)
  } catch (err) {
    console.error('Fetch error:', err)
  } finally {
    loading.value = false
  }
}

// Debounce gọi API khi search
const debouncedFetch = debounce((val) => {
  fetchOptions(val)
}, props.debounceDelay)

// Theo dõi input tìm kiếm
// watch(search, (val) => {
//   if (!val || !isTyping.value) return
//   debouncedFetch(val)
// })

const handleSearch = async (val) => {
    search.value = val;
    await fetchOptions(val)
}

// Khi input focus: bật flag gõ
const onFocus = () => {
  isTyping.value = true
}

// Khi chọn item: tắt flag gõ
const onSelect = () => {
  isTyping.value = false
}

// Load dữ liệu lần đầu và đặt giá trị mặc định nếu cần
onMounted(async () => {
  await fetchOptions('', props.modelValue)
})
</script>
