<script setup>
import { ref, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'

const visible = ref(false)
const message = ref('')
const color = ref('success')

// Nhận flash từ Inertia
const page = usePage()

// Watch để hiển thị flash khi redirect
watch(() => page.props.flash, (flash) => {
  if (flash.success) {
    showToast(flash.success, 'success')
  } else if (flash.error) {
    showToast(flash.error, 'error')
  }
})

// Hiện lỗi validate
watch(() => page.props.errors, (errors) => {
  if (errors && Object.keys(errors).length > 0) {
    const errorMessages = Object.values(errors).flat().join('\n')
    showToast(errorMessages, 'error')
  }
})

// Hàm để show thủ công nếu muốn
function showToast(msg, colorType = 'error') {
  message.value = msg
  color.value = colorType
  visible.value = true
}

defineExpose({
  showToast
})
</script>

<template>
  <v-snackbar
    v-model="visible"
    :color="color"
    location="top end"
    timeout="3000"
    elevation="8"
    rounded="lg"
  >
    <div class="flex items-center justify-between w-full">
      <span>{{ message }}</span>

      <v-btn icon size="small" variant="text" @click="visible = false">
        <v-icon>mdi-close</v-icon>
      </v-btn>
    </div>
  </v-snackbar>
</template>
