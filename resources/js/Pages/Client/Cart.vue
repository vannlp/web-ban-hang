<script setup>
import axios from '@/libs/axios';
import toastManager from '@/libs/toast';
import ClientLayout from '@/Layouts/ClientLayout.vue';
import { reactive, ref } from 'vue';
import CartApi from '@/Api/CartApi';
import loadingManager from '@/libs/LoadingManager';
import { Link, router } from '@inertiajs/vue3';
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

// const cartDetail = ref(props.cart?.cart_detail ?? []);
const cart = ref(props.cart ?? {});

const formatCurrency = (value) =>
  new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value)

const increaseQuantity = async (item) => {
  item.quantity++;
  loadingManager.show();
  await updateCartDetail(item.id, item.quantity);
  await getCart();
  toastManager.success("Cập nhật thành công");
  loadingManager.hide();
}

const decreaseQuantity = async (item) => {
  if (item.quantity > 1){
    item.quantity--;
    loadingManager.show();
    await updateCartDetail(item.id, item.quantity);
    await getCart();
    toastManager.success("Cập nhật thành công");
    loadingManager.hide();
  }
  
}

const updateCartDetail = async (cartDetailId, quantity) => {
    let params = {
        detail_id: cartDetailId,
        quantity: quantity
    }
    
    let res = await CartApi.updateCartDetail(params);

}

const getCart = async () => {
    let res = await CartApi.getCart();
    let data = res.data.data;
    cart.value = data;
}

const deleteCartDetail = async (id) => {
    loadingManager.show();
    let res = await CartApi.deleteCartDetail(id);
    toastManager.success("Xóa thành công");
    loadingManager.hide();
    await getCart();
} 

const hanldleRedirectCheckOut =  () => {
    if(cart.value.cart_detail.length == 0) {
        alert("Giỏ hàng trống!! Vui lòng chọn sản phẩm");
        return;
    }
    
    
    router.visit(route('client.checkout'));
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
                <v-table v-if="cart?.cart_detail?.length ?? [] > 0">
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
                        <tr class="" v-for="item in cart.cart_detail ?? []" :key="item.id">
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
                                        <Link :href="route('client.product.detail', {slug: item?.product?.slug})">
                                            <b>
                                                {{item?.product?.name}}
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
                                <v-btn size="small" variant="text" @click="deleteCartDetail(item.id)" color="error">Xóa</v-btn>
                            </td>
                        </tr>
                    </tbody>
                </v-table>
                <h4 class="text-xl text-red-500" v-else>Không có sản phẩm trong giỏ hàng</h4>
            </v-card-text>
        </v-card>
        
        <v-card class="mt-3">
            <v-card-text>
                <div class="text-right flex justify-end gap-2">
                    <div>
                        Tổng cộng: <b class="text-red-500 font-bold">{{ formatCurrency(cart?.total_price) }}</b>
                        <br>
                        Tổng sau giảm giá: <b class="text-red-500 font-bold">{{ formatCurrency(cart?.final_price) }}</b>
                    </div>
                    
                    <v-btn color="red" variant="flat" @click="hanldleRedirectCheckOut">
                        Mua hàng
                    </v-btn>
                    
                </div>
            </v-card-text>
        </v-card>
    </v-container>
  </div>
</template>
