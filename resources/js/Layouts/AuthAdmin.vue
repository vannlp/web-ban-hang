<template>
    <v-container fluid="true" class="min-h-screen flex items-center justify-center bg-gray-100">
      <v-card class="p-6 w-full max-w-md">
        <v-card-title class="text-center text-2xl font-bold mb-4">Đăng nhập</v-card-title>
        <v-card-text>
          <slot />
        </v-card-text>
      </v-card>
    </v-container>
</template>
  
<script setup>
import { ref } from 'vue'

const email = ref('')
const password = ref('')


</script>

<style scoped>
.v-card {
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}
</style>
  