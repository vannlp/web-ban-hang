<template>
    <!-- Top Bar -->
    <v-app-bar app color="#fff" dark>
      <v-app-bar-nav-icon @click="$emit('toggle-drawer')" />
      <v-toolbar-title>Web Bán Hàng</v-toolbar-title>
      <v-spacer />
      <v-btn icon>
        <v-icon>mdi-account</v-icon>
      </v-btn>
    </v-app-bar>
  
    <!-- Sidebar -->
    <v-navigation-drawer app v-model="drawer" color="white">
      <v-list dense>
        <v-list-item
          v-for="(item, index) in menuItems"
          :key="index"
          :to="item.to"
          link
          class="rounded"
        >
          <template #prepend>
            <v-icon>{{ item.icon }}</v-icon>
          </template>
          <v-list-item-title>{{ item.title }}</v-list-item-title>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
  </template>
  
  <script setup>
  import { ref, defineEmits } from 'vue'
  
  const emits = defineEmits(['toggle-drawer'])
  const drawer = ref(true)
  const menuItems = [
    { title: 'Dashboard', icon: 'mdi-view-dashboard', to: '/dashboard' },
    { title: 'Sản phẩm', icon: 'mdi-cube-outline', to: '/products' },
    { title: 'Đơn hàng', icon: 'mdi-cart-outline', to: '/orders' },
    { title: 'Người dùng', icon: 'mdi-account-group-outline', to: '/users' },
  ]
  </script>
  
  <style scoped>
  .v-navigation-drawer {
    border-right: 1px solid #eee;
  }
  </style>