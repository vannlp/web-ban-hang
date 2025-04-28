<script setup>
import { ref } from 'vue'

const menuItems = ref([
  { title: 'Dashboard', icon: 'mdi-view-dashboard', to: '/dashboard' },
  { title: 'Products', icon: 'mdi-cube', to: '/products' },
  { title: 'Orders', icon: 'mdi-cart', to: '/orders' },
  { title: 'Users', icon: 'mdi-account-group', to: '/users' },
])
</script>

<template>
  <v-navigation-drawer
    app
    permanent
    color="white"
    class="pa-2"
  >
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

        <v-list-item-title>
          {{ item.title }}
        </v-list-item-title>
      </v-list-item>
    </v-list>
  </v-navigation-drawer>
</template>

<style scoped>
.v-navigation-drawer {
  width: 240px;
  border-right: 1px solid #eee;
}
</style>
