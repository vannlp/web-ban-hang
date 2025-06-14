<script setup>
import CategoryApi from '@/Api/CategoryApi';
import DataTable from '@/Components/DataTable.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { reactive, ref } from 'vue';
import loadingManager from '@/libs/LoadingManager'
import toastManager from '@/libs/toast';
import FormCategory from './FormCategory.vue';
import TestDataTable from '@/Components/TestDataTable.vue';

defineOptions({
  layout: AuthenticatedLayout,
});
const headers = [
  { text: 'Tên danh mục', value: 'name' },
  { text: 'Slug', value: 'slug' },
  { text: 'Trạng thái', value: 'status' },
  { text: 'Hành động', value: 'actions' },
]

const datatableRef = ref(null)

const filters = ref({
  name: '',
})

const categoryId = ref(null)


const addModal = ref(false)

const handleStatusChange = async (newStatus, item) => {
  try {
    loadingManager.show();
    let res = await CategoryApi.updateStatus(item.id, { status: newStatus });

    toastManager.success('Cập nhật trạng thái thành công!');
    loadingManager.hide();
    // Reload lại DataTable
    datatableRef.value?.fetchData?.(); // hoặc gọi method trong DataTable tùy bạn định nghĩa
  } catch (error) {
    toastManager.error('Cập nhật thất bại');
    loadingManager.hide();
  }
};

const updateStatus  = (item) => {
    
}

const reloadTable = () => {
  datatableRef.value?.fetchData()
}

const handleSubmitForm = async (val) => {
  let data = val.data;
  let isEdit = val.isEdit;
  let id = val.id;
  
  if(isEdit) {
    let res = await CategoryApi.update(id, data);
    let message = res?.data?.message;
    toastManager.success(message);
    reloadTable();
    addModal.value = false;
    return;
  }
  
  let res = await CategoryApi.store(data);
  let message = res?.data?.message;
  toastManager.success(message);
  reloadTable();
  addModal.value = false;
}

const edit = (item) => {
    categoryId.value = item.id;
    addModal.value = true;
}

const add = () => {
    categoryId.value = null;
    addModal.value = true;
}

const remove = async (id) => {
  let response = await CategoryApi.delete(id);
  let message = response?.data?.message;
  toastManager.success(message);
  reloadTable();
}


</script>

<template>
  <div class="p-3">
    <v-card>
      <v-card-title>
        <v-row>
          <v-col cols="6">
            Danh mục sản phẩm
          </v-col>
          <v-col cols="6" class="text-end">
            <v-btn color="primary" @click="add">Thêm mới</v-btn>
          </v-col>
        </v-row>
      </v-card-title>
      <v-card-item>
        <DataTable :url="route('admin.category.datatable')" :filters="filters" :headers="headers" ref="datatableRef">
            <template #item-status="item">
                <v-switch
                    v-model="item.status"
                    :label="item.status == 1 ? 'Kích hoạt' : 'Tắt'"
                    :false-value="0"
                    :true-value="1"
                    color="primary"
                    hide-details
                    @update:modelValue="val => handleStatusChange(val, item)"
                />
            </template> 
            
          <template #item-actions="item">
            <v-btn @click="edit(item)" size="small" color="warning"> Edit</v-btn>
            <button @click="remove(item.id)">🗑 Xóa</button>
          </template>
        </DataTable> 
        
        <!-- <TestDataTable /> -->
      </v-card-item>
    </v-card>
    
    <FormCategory v-model="addModal" @submit="handleSubmitForm" :id="categoryId" />
  </div>
</template>