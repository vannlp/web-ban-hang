<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import DataTable from '@/Components/DataTable.vue'
import AddForm from './AddForm.vue'
import { ref } from 'vue'
import UserApi from '@/Api/UserApi'
import toastManager from '@/libs/toast'
import Helper from '@/libs/Helper'
import loadingManager from '@/libs/LoadingManager'

defineOptions({
  layout: AuthenticatedLayout,
})

const headers = [
  { text: 'Họ tên', value: 'name' },
  { text: 'Email', value: 'email' },
  { text: 'Tên đăng nhập', value: 'username' },
  { text: 'Hành động', value: 'actions' },
]

const datatableRef = ref(null)

const user = ref(null);

const openAdd = () => {
  user.value = null;
  isOpen.value = true;
}


const reloadTable = () => {
  datatableRef.value?.fetchData()
}

const isOpen = ref(false);

const filters = ref({
  name: 'gggg',
})

const edit = async (item) => {
  let response = await UserApi.getUser(item.id);
  let data = response?.data?.data;
  // toastManager.success(message);
  user.value = data;
  isOpen.value = true;
}

const remove = async (id) => {
  
  let response = await UserApi.delete(id);
  let message = response?.data?.message;
  toastManager.success(message);
  reloadTable();
}



const  submitAdd = async (val) => {
  let data = val.data;
  let isEdit = val.isEdit;
  let id = val.id;
  loadingManager.show();
  if(isEdit) {
    data = Helper.omit(data, ['username', 'email']);
    let response = await UserApi.update(id, data);
    let message = response?.data?.message;
    loadingManager.hide();
    toastManager.success(message);
    reloadTable();
    
    return;
  }
  
  let response = await UserApi.store(data);
  let message = response?.data?.message;
  loadingManager.hide();
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
            Danh sách người dùng
          </v-col>
          <v-col cols="6" class="text-end">
            <v-btn  @click="openAdd" color="primary">Thêm mới</v-btn>
          </v-col>
        </v-row>
      </v-card-title>
      <v-card-item>
        <DataTable :url="route('admin.user.datatable')" :filters="filters" :headers="headers" ref="datatableRef">
          <template #item-actions="item">
            <v-btn @click="edit(item)" size="small" color="warning"> Edit</v-btn>
            <button @click="remove(item.id)">🗑 Xóa</button>
          </template>
        </DataTable>
      </v-card-item>
    </v-card>
    
    <AddForm :edit-data="user" v-model="isOpen" @submit="submitAdd"></AddForm>
  </div>
</template>
