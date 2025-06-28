<script setup>
import EasyDataTable from 'vue3-easy-data-table'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import axios from '@/libs/axios';
import toastManager from '@/libs/toast';
import TestApi from '@/Api/TestApi';
import ImageSlider from '@/Components/Client/ImageSlider.vue';
// Dùng layout riêng cho trang này
defineOptions({
  layout: AuthenticatedLayout,
})

</script>

<template>
  <div class="p-3">
    <v-card>
        <!-- <v-card-title>Xin chào!</v-card-title> -->
        <v-card-text>
          <ImageSlider />
          
        </v-card-text>
    </v-card>
  </div>
</template>
