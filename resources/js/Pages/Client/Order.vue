<script setup>
import axios from '@/libs/axios';
import toastManager from '@/libs/toast';
import ProductSlider from '@/Components/Client/ProductSlider.vue';
import ClientLayout from '@/Layouts/ClientLayout.vue';
import { computed, ref } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import ModalAddressOrder from '@/Components/Lib/ModalAddressOrder.vue';
import AddressApi from '@/Api/AddressApi';
import loadingManager from '@/libs/LoadingManager';
import OrderApi from '@/Api/OrderApi';
// Dùng layout riêng cho trang này
defineOptions({
  layout: ClientLayout,
})

const props = defineProps({
  cart: {
    type: Object,
    default: () => ({})
  },
  listAddress: {
    type: Array,
    default: () => []
  },
  address: {
    type: Object,
    default: () => ({})
  }
})

const cart = ref(props.cart ?? {});
const listAddress = ref(props.listAddress ?? []);
const address = ref(props.address);
const modal = ref(false);
const page = usePage()
const user = computed(() => page.props?.auth?.user || null)
// const 
const formatCurrency = (value) =>
  new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value)
  
const getAddresses = async () => {
  let params = {
    user_id: user.value.id,
    is_default: 1
  }
  
  let res = await AddressApi.getAddressDefault(params);
  address.value = res.data.data;
}

const updateAddressDefault = async (val) => {
  await getAddresses();
}

const checkout = async () => {
  loadingManager.show();
  let res = await OrderApi.checkout();
  loadingManager.hide();
  router.visit(route('client.checkoutSuccess'));
}
  
</script>

<template>
  <div class="p-3">
    <v-container>
      <v-card>
        <v-card-title>
          <span class="text-2xl">
            Địa chỉ giao hàng
          </span>
        </v-card-title>
        <v-card-text>
          <v-row>
            <v-col cols="10">
              <p>
                {{ address?.street }}, {{ address?.ward?.full_name }}, {{ address?.district?.full_name }}, {{ address?.city?.full_name }}
              </p>
            </v-col>
            <v-col cols="2">
              <v-btn size="small" variant="text" color="primary" @click="modal = true">
                Chỉnh sửa
              </v-btn>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>

      <v-card class="mt-3">
        <v-card-text>
          <v-table v-if="cart?.cart_detail?.length ?? [] > 0">
            <thead>
              <tr class="position-relative">
                <th class="text-left min-w-[200px]">
                  Sản phẩm
                </th>
                <th class="text-left">
                  Đơn giá
                </th>
                <th class="text-left">
                  Số lượng
                </th>
                <th class="text-left">
                  Số tiền
                </th>
              </tr>
            </thead>
            <tbody>
              <tr class="" v-for="item in cart?.cart_detail ?? []" :key="item.id">
                <td class="py-3">
                  <v-row>
                    <v-col cols="3">
                      <v-img width="100%" aspect-ratio="16/9" cover :src="item?.product?.image_avatar"></v-img>
                    </v-col>
                    <v-col cols="9">
                      <Link :href="route('client.product.detail', { slug: item?.product?.slug })" class="hover:underline hover:text-blue-500">
                      <b>
                        {{ item?.product?.name }}
                      </b>
                      </Link>
                    </v-col>
                  </v-row>

                </td>
                <td>
                  <span class="font-semibold text-red-600 mr-3">
                    {{ formatCurrency(item?.product?.price) }}
                  </span>

                  <!-- Giá gốc -->
                  <span v-if="item?.product?.original_price" class="text-gray-400 line-through">
                    {{ formatCurrency(item?.product?.original_price) }}
                  </span>
                </td>
                <td>
                  {{ item?.quantity }}
                </td>
                <td class="text-red-500 font-bold">{{ formatCurrency(item?.final_price) }}</td>
              </tr>
            </tbody>
          </v-table>
          <h4 class="text-xl text-red-500" v-else>Không có sản phẩm trong giỏ hàng</h4>
        </v-card-text>
      </v-card>
      
      <v-card class="mt-3"> 
        <v-card-title>
          <span class="text-2xl">
              Phương thức thanh toán
          </span>
        </v-card-title>
        <v-card-text>
          <div>
            <v-radio label="Thanh toán khi nhận hàng" value="1" ></v-radio>
          </div>
        </v-card-text>
      </v-card>
      
      <v-card class="mt-3"> 
        <v-card-text>
          <v-row>
            <v-col cols="12">
              <span class="font-bold">Tổng cộng:</span> <b class="text-red-500 font-bold">{{ formatCurrency(cart?.total_price) }}</b>
            </v-col>
            
            <v-col cols="12">
              <v-btn color="red" @click="checkout">
                Thanh toán ngay
              </v-btn>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-container>
    
    <ModalAddressOrder v-model="modal" @update:defautAddress="updateAddressDefault"></ModalAddressOrder>
  </div>
  
</template>
