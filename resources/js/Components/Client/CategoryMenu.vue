<!-- CategoryMenu.vue -->
<template>
  <div>
    <!-- Nút danh mục -->
    <v-btn
      color="white"
      prepend-icon="mdi-menu"
      class="text-red-600"
      @click="isOpen = true"
    >
      Danh mục
    </v-btn>

    <!-- Overlay đen khi mở menu -->
    <div
      v-if="isOpen"
      class="fixed inset-0 bg-black bg-opacity-40 z-40"
      @click="isOpen = false"
    ></div>

    <!-- Menu trượt từ trái ra -->
    <div
      v-if="isOpen"
      class="fixed top-[64px] left-0 z-50 bg-white shadow-lg rounded-r-lg w-[280px] max-h-[calc(100vh-64px)] overflow-y-auto transition-all duration-300"
    >
      <ul class="divide-y divide-gray-100">
        <li
          v-for="item in categories"
          :key="item.title"
          class="flex items-center px-4 py-3 hover:bg-gray-100 cursor-pointer"
        >
          <v-icon class="mr-3" size="22">{{ item.icon }}</v-icon>
          <span class="flex-1 text-sm text-gray-800 font-medium">{{ item.title }}</span>
          <v-icon size="16" class="text-gray-400">mdi-chevron-right</v-icon>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'


</script>
