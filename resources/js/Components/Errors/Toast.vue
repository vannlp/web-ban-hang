<script setup>
import { ref } from 'vue'

const visible = ref(false)
const message = ref('')
const color = ref('error')

// Hàm để show Toast
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

      <v-btn
        icon
        size="small"
        variant="text"
        @click="visible = false"
      >
        <v-icon>mdi-close</v-icon>  <!-- Nút X đóng -->
      </v-btn>
    </div>
  </v-snackbar>
</template>
