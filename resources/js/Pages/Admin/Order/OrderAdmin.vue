<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { computed, ref } from 'vue';
import OrderApi from '@/Api/OrderApi';
import loadingManager from '@/libs/LoadingManager';
import { Link } from '@inertiajs/vue3';
import DataTable from '@/Components/DataTable.vue';

defineOptions({
  layout: AuthenticatedLayout,
})

const props = defineProps({
     listStatusOrder: {
        type: Object,
        default: () => {}
    }
})


const headers = [
  { text: 'Id', value: 'id' },
  { text: 'Code', value: 'code' },
  { text: 'SDT', value: 'phone' },
  { text: 'Tổng tiền', value: 'final_price_formatted' },
  { text: 'Trạng thái', value: 'status_label' },
  { text: 'Địa chỉ', value: 'full_address' },
  { text: 'Thao tác', value: 'action' },
]

const colorMapping = {
    'pending': 'default',
    'processing': 'primary',
    'completed': 'success',
    'cancelled': 'error',
};

const order = ref({});

const modal = ref(false);

const filters = ref({
  name: '',
})

const listStatusSelect = computed(() => {
  return Object.entries(props.listStatusOrder).map(([value, label]) => ({
    value,
    label,
  }));
});

// const listStatusSelect = ref([]);

const datatableRef = ref(null);

const getDataOrder = async (id) => {
    let res = await OrderApi.getOrder(id);
    
    order.value = res.data.data;
};

const reloadTable = () => {
  datatableRef.value?.fetchData()
}

const formatCurrency = (value) =>
  new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value)

const hanldeOpenOrderModal = async (id) => {
    loadingManager.show();
    
    await getDataOrder(id);
    
    modal.value = true;
    loadingManager.hide();
};

const handleChangeStatus = async (newStatus) => {
    if (!order.value.id) return;

    loadingManager.show();
    
    let data = {
        id: order.value?.id,
        status: newStatus
    }
    
    await OrderApi.updateOrderStatus(data);  // gọi API cập nhật
    order.value.status = newStatus; // cập nhật lại local
    
    reloadTable();
    
    loadingManager.hide();
}
</script>

<template>
    <v-card>
        <v-card-title>
            <v-row>
            <v-col cols="6">
                Danh sách đơn hàng
            </v-col>
            <v-col cols="6" class="text-end">
            </v-col>
            </v-row>
        </v-card-title>
        
        <v-card-text>
                <DataTable :url="route('client.clientDataTable')" :filters="filters" :headers="headers" ref="datatableRef">
                <template #item-status_label="item">
                    <v-chip :color="colorMapping[item.status]" variant="flat" size="small">{{ item.status_label }}</v-chip>
                </template>
                <template #item-final_price_formatted="item">
                    <span class="font-bold text-red-600">{{ item.final_price_formatted }}</span>
                </template>
                
                <template #item-action="item">
                    <v-btn variant="text" class="text-none" @click="hanldeOpenOrderModal(item.id)" size="small">Xem chi tiết</v-btn>
                </template>
            </DataTable> 
            
            <v-dialog v-model="modal" max-width="800" >
                <v-card>
                    <v-card-text>
                        <div>
                            <p>
                                <span class="font-bold">Mã đơn: </span> {{ order?.code }}
                            </p>
                            <p>
                                <span class="font-bold">SDT: </span> {{ order?.address.phone }}
                            </p>
                            <p>
                                <v-select label="Trạng thái" :items="listStatusSelect" 
                                item-title="label"
                                item-value="value" v-model="order.status" v-if="order?.status" style="max-width: 300px;"  @update:modelValue="handleChangeStatus">
                                    
                                </v-select>
                            </p>
                            <p>
                                <span class="font-bold">Địa chỉ giao hàng: </span> {{ order?.full_address }}
                            </p>
                            <p> 
                                <span>Danh sách sản phẩm: </span>
                            </p>
                    
                            <v-table v-if="order?.order_item?.length ?? [] > 0">
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
                                <tr class="" v-for="item in order?.order_item ?? []" :key="item.id">
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
                    
                            <v-card class="mt-3"> 
                                <v-card-text>
                                    <v-row>
                                        <v-col cols="12">
                                        <span class="font-bold">Tổng cộng:</span> <b class="text-red-500 font-bold">{{ formatCurrency(order?.total_price) }}</b>
                                        </v-col>
                                    </v-row>
                                </v-card-text>
                            </v-card>
                        </div>
                    </v-card-text>
                    
                    <v-card-actions>
                        <v-btn text @click="modal = false">Trở lại</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-card-text>
    </v-card>
    
</template>
