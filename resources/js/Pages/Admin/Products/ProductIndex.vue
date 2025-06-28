<script setup>
import ProductApi from '@/Api/ProductApi';
import DataTable from '@/Components/DataTable.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import toastManager from '@/libs/toast';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

defineOptions({
  layout: AuthenticatedLayout,
});

const filters = ref({
  name: '',
})
const datatableRef = ref(null)

const headers = [
  { text: 'Id', value: 'id' },
  { text: 'Tên danh mục', value: 'name' },
  { text: 'Slug', value: 'slug' },
  { text: 'Ảnh đại diện', value: 'image_avatar' },
  { text: 'Trạng thái', value: 'status' },
  { text: 'Hành động', value: 'actions' },
]

const remove = async (id) => {
  let response = await ProductApi.delete(id);
  let message = response?.data?.message;
  toastManager.success(message);
  reloadTable();
}

const reloadTable = () => {
  datatableRef.value?.fetchData()
}


</script>

<template>
  <div class="p-3">
    <v-card>
      <v-card-title>
        <v-row>
          <v-col cols="6">
            Danh sách sản phẩm
          </v-col>
          <v-col cols="6" class="text-end">
          </v-col>
        </v-row>
      </v-card-title>
      <v-card-item>
        <DataTable :url="route('admin.product.datatable')" :filters="filters" :headers="headers" ref="datatableRef">
          <template #item-status="item">
            <span class="text-green-600" v-if="item.status == 1">Kích hoạt</span>
            <span class="text-red-600" v-else>Chưa kích hoạt</span>
          </template>
          
          <template #item-image_avatar="item"> 
            <div v-if="item.image_avatar" class="p-3">
              <v-img
                cover
                :src="item.image_avatar"
                width="70"
                aspect-ratio="16/9"
              ></v-img>
            </div>
          </template>
          
          <template #item-actions="item">
            <Link :href="route('admin.product.edit', {id: item.id})">
              <v-btn size="small" color="warning"> Edit</v-btn>
            </Link>
            <button @click="remove(item.id)">🗑 Xóa</button>
          </template>
        </DataTable> 
      </v-card-item>
    </v-card>
  </div>
</template>
