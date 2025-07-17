<template>
  <!-- Top Bar -->
  <v-app-bar app color="#fff" dark>
    <v-app-bar-nav-icon @click="$emit('toggle-drawer')" />
    <v-toolbar-title>
      <Link href="/">
        Web bán hàng
      </Link>
    </v-toolbar-title>
    <v-spacer />
    <v-btn icon>
      <v-icon>mdi-account</v-icon>
    </v-btn>
  </v-app-bar>

  <!-- Sidebar -->
  <v-navigation-drawer
    app
    v-model="drawer"
    color="white"
    width="300"
    mini-variant
    mini-variant-width="64"
  >
    <v-list density="compact" >
      <template v-for="(item, index) in menuItems" :key="index">
        <!-- Nếu có children -->
        <v-list-group
          v-if="item.children"
          :prepend-icon="item.icon"
          no-action
          density="compact"
        >
          <template #activator="{ props }">
            <v-list-item v-bind="props" density="compact">
              <v-list-item-title>{{ item.title }}</v-list-item-title>
            </v-list-item>
          </template>

          <!-- Danh sách submenu -->
          <template v-for="(child, childIndex) in item.children" :key="childIndex">
            <Link :href="child.to" class="text-decoration-none">
              <v-list-item link density="compact" class="pl-6">
                <v-list-item-title>{{ child.title }}</v-list-item-title>
              </v-list-item>
            </Link>
          </template>
        </v-list-group>

        <!-- Nếu không có children -->
        <Link v-else :href="item.to" class="text-decoration-none">
          <v-list-item link density="compact">
            <template #prepend>
              <v-icon>{{ item.icon }}</v-icon>
            </template>
            <v-list-item-title>{{ item.title }}</v-list-item-title>
          </v-list-item>
        </Link>
      </template>
    </v-list>
  </v-navigation-drawer>
</template>

<script setup>
import { ref, defineEmits } from 'vue'
import { Link } from '@inertiajs/vue3'

const emits = defineEmits(['toggle-drawer'])
const drawer = ref(true)

const menuItems = [
  { title: 'Dashboard', icon: 'mdi-view-dashboard', to: route('admin.dashboard') },
  {
    title: 'Quản lý sản phẩm',
    icon: 'mdi-package-variant',
    children: [
      { title: 'Danh sách sản phẩm', to: '/admin/product' },
      { title: 'Thêm sản phẩm', to: route('admin.product.add') },
      { title: 'Quản lý danh mục', to: route('admin.category.index') },
    ]
  },
  {
    title: 'Người dùng',
    icon: 'mdi-account-group',
    children: [
      { title: 'Danh sách', to: route('admin.user.index') },
    ]
  },
  
  {
    title: 'Đơn hàng',
    icon: 'mdi-receipt',
    to: route('admin.order.index')
  },
]
</script>
