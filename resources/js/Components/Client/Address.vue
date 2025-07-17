<template>
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
                        
                        <v-btn variant="outlined" color="gray" class="m-2" size="small" :disabled="address.is_default == 1" @click="updateAddressDefault(address.id)">
                            Thiết lập mặc định
                        </v-btn>
                        
                        <v-btn color="red" @click="deleteAddress(address.id)" class="m-2" size="small" v-if="address.is_default == 0">
                            Xóa địa chỉ
                        </v-btn>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
    
})

const modal = ref(false);
const listAddress = ref([]);
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