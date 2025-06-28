<script setup>
import axios from '@/libs/axios';
import toastManager from '@/libs/toast';
import ClientLayout from '@/Layouts/ClientLayout.vue';
import { computed, reactive, ref } from 'vue';
import ImageSlider from '@/Components/Client/ImageSlider.vue';
import SliderImageProduct from '@/Components/Client/SliderImageProduct.vue';
import { Head, usePage } from '@inertiajs/vue3';
import CartApi from '@/Api/CartApi';
import loadingManager from '@/libs/LoadingManager';
// Dùng layout riêng cho trang này
defineOptions({
  layout: ClientLayout,
})

const page = usePage()
const user = computed(() => page.props?.auth?.user || null)


const props = defineProps({
  product: {
    type: Object,
    required: true,
  },
  breadcrumbs: {
    type: Array,
    default: () => []
  },
  title: {
    type: String,
    default: ''
  }
});

const product = reactive(props.product);

const quantity = ref(1)

const increaseQuantity = () => {
  quantity.value++
}

const decreaseQuantity = () => {
  if (quantity.value > 1) quantity.value--
}

const addToCart = async () => {
    let params = {
        product_id: product.id,
        quantity: quantity.value,
        user_id: user?.value?.id
    }
    
    loadingManager.show();
    let res = await CartApi.addToCart(params);
    toastManager.success(res.data.message);
    
    loadingManager.hide();
    quantity.value = 1;
}

// Format tiền VND
const formatCurrency = (value) =>
  new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value)
  
const tab = ref("MoTa")

</script>

<template>
   <Head :title="props?.title" /> 
    
  <div class="p-3">
    
    <v-container class="py-2">
        <v-breadcrumbs :items="props.breadcrumbs" class="p-0">
            <template #divider>
                <span>/</span>
            </template>
        </v-breadcrumbs>
    </v-container>
    
    <v-container>
        <v-card>
            <v-card-text>
                <h3 class="text-xl font-bold mb-3">{{ product.name }}</h3>
                
                <v-row>
                    <v-col cols="6">
                        <!-- <ImageSlider :images="product.list_image" height="auto" /> -->
                         <SliderImageProduct :images="product.list_image" />
                    </v-col>
                    
                    <v-col cols="6">
                        <div class="p-4 w-full max-w-md">
                            <!-- Giá -->
                            <div class="mb-2"> 
                                <span class="text-2xl font-semibold text-red-600 mr-3">
                                {{ formatCurrency(product.price) }}
                                </span>
                                
                                <!-- Giá gốc -->
                                <span v-if="product.original_price" class="text-gray-400 text-xl line-through">
                                {{ formatCurrency(product.original_price) }}
                                </span>
                            </div>

                            <div class="flex items-center gap-4">
                                <!-- Label -->
                                <span class="text-gray-600 font-medium">Số lượng</span>

                                <!-- Nhóm tăng giảm -->
                                <div class="flex border rounded divide-x divide-gray-300 mb-3">
                                    <v-btn
                                        icon="mdi-minus"
                                        flat
                                        class="!rounded-none"
                                        :disabled="quantity <= 1"
                                        @click="decreaseQuantity"
                                        size="x-small"
                                    >
                                    </v-btn>

                                    <div class="flex items-center justify-center text-base min-w-[40px]">
                                        {{ quantity }}
                                    </div>

                                    <v-btn
                                        icon="mdi-plus"
                                        flat
                                        class="!rounded-none"
                                        @click="increaseQuantity"
                                        size="x-small"
                                    >
                                    </v-btn>
                                </div>

                                <!-- Thông tin tồn kho -->
                                <!-- <span class="text-gray-500 text-sm">{{ stock }} sản phẩm có sẵn</span> -->
                            </div>

                            <!-- Nút thêm vào giỏ hàng -->
                            <v-btn
                                color="red"
                                variant="flat"
                                block
                                size="large"
                                @click="addToCart"
                            >
                            <v-icon start>mdi-cart</v-icon>
                                Thêm vào giỏ hàng
                            </v-btn>
                        </div>
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>
    </v-container>
    
    <v-container>
        <v-card>
            <v-card-text>
                <v-tabs
                    v-model="tab"
                    align-tabs="center"
                    color="red"
                >
                    <v-tab value="MoTa">Mô tả</v-tab>
                    <v-tab value="BinhLuan">Bình luận</v-tab>
                </v-tabs>
                
                <v-tabs-window v-model="tab">
                    <v-tabs-window-item
                        value="MoTa"
                    >
                        <v-container fluid>
                            <div v-html="product.description"></div>
                        </v-container>
                    </v-tabs-window-item>
                </v-tabs-window>
            </v-card-text>
        </v-card>
    </v-container>
  </div>
</template>
