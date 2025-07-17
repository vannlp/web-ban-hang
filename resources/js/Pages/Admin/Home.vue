<script setup>
import EasyDataTable from 'vue3-easy-data-table'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import axios from '@/libs/axios';
import toastManager from '@/libs/toast';
import TestApi from '@/Api/TestApi';
import ImageSlider from '@/Components/Client/ImageSlider.vue';
import { useForm, usePage } from '@inertiajs/vue3';
// Dùng layout riêng cho trang này
defineOptions({
  layout: AuthenticatedLayout,
})
const page = usePage()


const form = useForm({
  'a': 123,
  'b': 'jaha'
});

const test = () => {
  form.use_form = true;
  form.post(route('testPost'));
}
</script>

<template>
  <div class="p-3">
    <v-card>
        <!-- <v-card-title>Xin chào!</v-card-title> -->
        <v-card-text>
          <v-btn @click="test">Click</v-btn>
          
        </v-card-text>
    </v-card>
  </div>
</template>
