<script setup>
import axios from '@/libs/axios';
import toastManager from '@/libs/toast';
import ClientLayout from '@/Layouts/ClientLayout.vue';
import { ref } from 'vue';
// Dùng layout riêng cho trang này
defineOptions({
  layout: ClientLayout,
})

const props = defineProps({
    cart: {
        type: Object,
        default: () => ({})
    }
})

const cartDetail = ref(props.cart?.cart_detail ?? []);

const formatCurrency = (value) =>
  new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value)

const increaseQuantity = (item) => {
  item.quantity++
}

const decreaseQuantity = (item) => {
  if (item.quantity > 1) item.quantity--
}



</script>

<template>
  <div class="p-3">
    <v-container>
        <v-card>
            <v-card-text>
                <h3 class="text-2xl">Giỏ hàng</h3>
            </v-card-text>
        </v-card>
        
        <v-card class="mt-3">
            <v-card-text>
                <v-table v-if="cartDetail.length > 0">
                    <thead>
                        <tr class="position-relative">
                            <th class="text-left min-w-[200px]" >
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
                            <th class="text-left">
                                Thao tác
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="" v-for="item in cartDetail" :key="item.id">
                            <td class="py-3">
                                <v-row>
                                    <v-col cols="4">
                                        <v-img
                                            width="100%"
                                            aspect-ratio="16/9"
                                            cover
                                            :src="item?.product?.image_avatar"
                                        ></v-img>
                                    </v-col>
                                    <v-col cols="8">
                                        <b>
                                            {{item?.product?.name}}
                                        </b>
                                    </v-col>
                                </v-row>
                                
                            </td>
                            <td>{{formatCurrency(item?.price)}}</td>
                            <td>
                                <!-- {{item?.quantity}} -->
                                <!-- Nhóm tăng giảm -->
                                <div class="inline-flex border rounded divide-x divide-gray-300 mb-3">
                                    <v-btn
                                        icon="mdi-minus"
                                        flat
                                        class="!rounded-none"
                                        :disabled="item?.quantity <= 1"
                                        @click="decreaseQuantity(item)"
                                        size="x-small"
                                    >
                                    </v-btn>

                                    <div class="flex items-center justify-center text-base min-w-[40px]">
                                        {{ item?.quantity }}
                                    </div>

                                    <v-btn
                                        icon="mdi-plus"
                                        flat
                                        class="!rounded-none"
                                        @click="increaseQuantity(item)"
                                        size="x-small"
                                    >
                                    </v-btn>
                                </div>
                            </td>
                            <td class="text-red-500 font-bold">{{formatCurrency(item?.final_price)}}</td>
                            <td>
                                <v-btn size="small" variant="text" color="error">Xóa</v-btn>
                            </td>
                        </tr>
                    </tbody>
                </v-table>
                <h4 class="text-xl text-red-500" v-else>Không có sản phẩm trong giỏ hàng</h4>
            </v-card-text>
        </v-card>
    </v-container>
  </div>
</template>
