<script setup>
import axios from '@/libs/axios'
import toastManager from '@/libs/toast'
import ClientLayout from '@/Layouts/ClientLayout.vue'
import { computed, onMounted, ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import ModalAddress from '@/Components/Lib/ModalAddress.vue'
import AddressApi from '@/Api/AddressApi'
import loadingManager from '@/libs/LoadingManager'

// Dùng layout riêng cho trang này
defineOptions({
  layout: ClientLayout,
})

const page = usePage()
const user = computed(() => page.props?.auth?.user || null)
const modal = ref(false);
const listAddress = ref([]);

const tab = ref("work");
const idAddress = ref(null);

const getListAddress = async () => {
  let params = {
    user_id: user.value.id
  }
  
  let res = await AddressApi.getListAddress(params);
  listAddress.value = res.data.data;
}

const submitModalAddress = async (val) => {
  await getListAddress();
}


onMounted(async () => {
  
  if (!user) {
    router.visit(route('client.login'))
  }
  
  await getListAddress();
})

const handleUpdateAddressModel = (id) => {
  idAddress.value = id;
  modal.value = true;
}

const updateAddressDefault = async (id) => {
  loadingManager.show();
  let params = {
    'user_id': user.value.id
  }
  let res = await AddressApi.updateAddressDefault(id, params);
  toastManager.success(res.data.message);
  
  await getListAddress();
  loadingManager.hide();
}

const deleteAddress = async (id) => {
  if (!confirm("Bạn có chắc chắn muốn xóa không?")) {
    // Nếu người dùng nhấn OK
    return;
  }
  
  loadingManager.show();
  let res = await AddressApi.delete(id);
  toastManager.success(res.data.message);
  
  await getListAddress();
  loadingManager.hide();
}
</script>

<template>
  <v-container class="py-10">
    <!-- Profile Header -->
    <v-card class="pa-6 rounded-xl" elevation="2">
      <v-row>
        <!-- Avatar -->
        <v-col cols="12" md="2" class="d-flex justify-center">
          <v-avatar size="120">
            <v-img :src="user?.avatar" />
          </v-avatar>
        </v-col>

        <!-- User Info -->
        <v-col cols="12" md="6">
          <div class="text-h5 font-weight-bold d-flex align-center">
            {{ user?.name }}
            <v-chip class="ml-2" color="blue" text-color="white" size="small">
              PRO
            </v-chip>
          </div>
          
          <div class="">
            <p>
              <b>Email: </b><span>{{ user?.email }}</span>
            </p>
            <p>
              <b>Username: </b><span>{{ user?.username }}</span>
            </p>
          </div>
        </v-col>
        
      </v-row>

      <!-- Tabs -->
      <v-tabs v-model="tab" class="mt-6" color="primary">
        <v-tab value="work">Địa chỉ</v-tab>
        <v-tab value="moodboards">Moodboards</v-tab>
      </v-tabs>
    </v-card>

    <v-card class="mt-3 rounded-xl">
      <v-card-text>
        <!-- Tab content -->
        <v-window v-model="tab" class="mt-6">
          <v-window-item value="work">
            <div>
              <v-btn color="primary" @click="modal = true">
                Thêm địa chỉ mới
              </v-btn>
            </div>
            
            <v-row>
              <v-col cols="12" v-for="address in listAddress">
                <v-card>
                  <v-card-text>
                    <v-row>
                      <v-col cols="9">
                        <p>
                          <b>SDT: </b> <span>{{ address.phone }}</span>
                        </p>
                        <p>
                          <b>Địa chỉ: </b> <span>{{ address.street }}, {{ address?.ward?.full_name }}, {{ address?.district?.full_name }}, {{ address?.city?.full_name }}</span>
                        </p>
                      </v-col>
                      
                      <v-col cols="3">
                        <v-btn variant="text" color="blue" size="small" @click="handleUpdateAddressModel(address.id)">
                          Cập nhật
                        </v-btn>
                        
                        <v-btn variant="outlined" color="gray" class="m-2" size="small" :disabled="address.is_defaut == 1" @click="updateAddressDefault(address.id)">
                          Thiết lập mặc định
                        </v-btn>
                        
                        <v-btn color="red" @click="deleteAddress(address.id)" class="m-2" size="small" v-if="address.is_defaut == 0">
                          Xóa địa chỉ
                        </v-btn>
                      </v-col>
                    </v-row>
                  </v-card-text>
                </v-card>
              </v-col>
              
            </v-row>
          </v-window-item>
          <v-window-item value="moodboards">
            <div>Nội dung tab Moodboards...</div>
          </v-window-item>
        </v-window>
      </v-card-text>
    </v-card>
  </v-container>
  
  <ModalAddress v-model="modal" @submit="submitModalAddress" :id="idAddress"></ModalAddress>
  
</template>
