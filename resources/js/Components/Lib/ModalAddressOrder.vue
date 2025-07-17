<script setup>
import { computed, onMounted, reactive, ref, watch } from 'vue';
import AddressApi from '@/Api/AddressApi';
import { debounce } from 'lodash';
import toastManager from '@/libs/toast';
import loadingManager from '@/libs/LoadingManager';
import { Link, useForm, usePage } from '@inertiajs/vue3';

const props = defineProps({
    modelValue: {
        type: Boolean,
        default: false,
    },
})

const emit = defineEmits(['update:modelValue', 'update:defautAddress']) // khai báo các event mà component có thể emit



const page = usePage()
const user = computed(() => page.props?.auth?.user || null)
const defaultAddress = () => {
    
    return {
        phone: "",
        street: "",
        city_id: null,
        district_id: null,
        ward_id: null,
        user_id: null,
        is_default: 0,
        use_form: true,
        user_id: user.value.id
    };
}
const isEditOrCreate = ref(false);
const listAddress = ref([]);
const address = reactive(defaultAddress());
const addressForm = useForm(defaultAddress());
const addressId = ref(null);



const modal = ref(props.modelValue);

watch(() => props.modelValue, (val) => {
    modal.value = val;
});

watch(modal, (val) => {
    emit('update:modelValue', val);
});


const listCity = ref([]);
const listDistrict = ref([]);
const listWard = ref([]);

const getCities = async (params = {}) => {
    let res = await AddressApi.getCities(params);
    listCity.value = res.data.data;
}

const getListAddress = async () => {
    let params = {
        user_id: user.value.id
    }
    
    let res = await AddressApi.getListAddress(params);
    listAddress.value = res.data.data;
    
    if(listAddress.value.length > 0) {
        const foundAddress = listAddress.value.find(item => item.is_default == 1);
        Object.assign(address, foundAddress);
        addressId.value = foundAddress.id;
    }
}

const getDistricts = async (params = {}) => {
    let res = await AddressApi.getDistricts(params);
    listDistrict.value = res.data.data;
}

const getWards = async (params = {}) => {
    let res = await AddressApi.getWards(params);
    listWard.value = res.data.data;
}


const handleSearchCity = async (value) => {
    let params = {};
    
    if(typeof value == 'string') {
        params = {
            search: value
        }
    }
    
    
    await debouncedgetCities(params);
}

const handleSearchDistrict = async (value, type = null) => {
    let params = {
        city_id: addressForm.city_id
    }
    
    if(type != 'focus') {
        params.search = value;
    }
    
    await debouncedgetDistricts(params);
}

const handleSearchWard = async (value, type = null) => {
    let params = {
        district_id: addressForm.district_id
    }
    
    if(type != 'focus') {
        params.search = value;
    }
    
    await debouncedgetWards(params);
}

const debouncedgetCities = debounce((params = {}) => {
    getCities(params)
}, 400)

const debouncedgetDistricts = debounce((params = {}) => {
    getDistricts(params)
}, 400)

const debouncedgetWards = debounce((params = {}) => {
    getWards(params)
}, 400);



onMounted(async () => {
    loadingManager.show();
    
    // await getCities({id: address.city_id});
    // await getDistricts({id: address.district_id});
    // await getWards({id: address.ward_id});
    await getListAddress();
    
    loadingManager.hide();
})

const close = () => {
    
    modal.value = false;
}

const submit = async () => {
    addressForm.use_form = true;
    addressForm.post(route('address.store'));
    addressForm.reset();
    await getListAddress();
}

const updateAddressDefault = async (id) => {
    loadingManager.show();
    let params = {
        'user_id': user.value.id
    }
    let res = await AddressApi.updateAddressDefault(id, params);
    // toastManager.success(res.data.message);
    
    // await getListAddress();
    // await getAddresses(props.id);
    addressId.value = id;
    emit('update:defautAddress', id);
    loadingManager.hide();
}


</script>

<template>
    <v-dialog v-model="modal" max-width="800" >
        <v-card
            v-if="isEditOrCreate"
        >
            <v-card-title>
                <v-row align="center" class="w-100">
                    <v-col cols="6">
                        <span class="text-h6 font-weight-bold">Địa chỉ</span>
                    </v-col>
                    <v-col cols="6" class="text-end">
                        <v-btn icon @click="close">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-col>
                </v-row>
            </v-card-title>
            <v-card-text>
                <form  method="post">
                    <v-text-field label="Số điện thoại" v-model="addressForm.phone"></v-text-field>
                    
                    <v-text-field label="Địa chỉ" v-model="addressForm.street"></v-text-field>
                    
                    <v-autocomplete
                        v-model="addressForm.city_id"
                        :items="listCity"
                        label="Chọn tỉnh/thành"
                        item-title="name"
                        item-value="id"
                        @update:search="handleSearchCity"
                        @update:focused="handleSearchCity"
                        no-filter
                    />
                    
                    <v-autocomplete
                        v-model="addressForm.district_id"
                        :items="listDistrict"
                        label="Chọn Quận/Huyện"
                        item-title="name"
                        item-value="id"
                        :disabled="!addressForm?.city_id"
                        @update:search="handleSearchDistrict"
                        @focus="handleSearchDistrict('','focus')"
                        no-filter
                    />
                    
                    <v-autocomplete
                        v-model="addressForm.ward_id"
                        :items="listWard"
                        label="Chọn Phường/Xã"
                        item-title="name"
                        item-value="id"
                        :disabled="!addressForm?.district_id"
                        @update:search="handleSearchWard"
                        @focus="handleSearchWard('','focus')"
                        no-filter
                    />
                    
                </form>
                
                
            </v-card-text>
            
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary"  @click="submit">Submit</v-btn>
                <v-btn text @click="isEditOrCreate = false">Trở lại</v-btn>
            </v-card-actions>
        </v-card>
        <v-card v-else>
            <v-card-title>
                <div class="flex items-center justify-between">
                    <span>
                        Địa chỉ của tôi
                    </span>
                    
                    <div>
                        <v-btn color="primary" class="mr-2" @click="isEditOrCreate = true">
                            Tạo mới địa chỉ
                        </v-btn>
                        <v-btn icon @click="close">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </div>
                </div>
            </v-card-title>
            
            <v-card-text>
                <v-radio-group v-model="addressId">
                    <v-card class="mb-2" v-for="item in listAddress" :key="item.id" @click="updateAddressDefault(item.id)">
                        <v-card-title>
                            <v-row>
                                <v-col cols="10">
                                    <v-radio :label="item.phone" :value="item.id"></v-radio>
                                </v-col>
                                <v-col cols="2">
                                    <Link :href="route('client.profile')">
                                        <v-btn size="small" class="text-none" variant="text" color="primary" >Cập nhật</v-btn>
                                    </Link>
                                </v-col>
                            </v-row>
                        </v-card-title>
                        
                        <v-card-text>
                            <div class="pl-3 text-lg text-gray-500">
                                {{ item?.street }}, {{ item?.ward?.full_name }}, {{ item?.district?.full_name }}, {{ item?.city?.full_name }}
                            </div>
                        </v-card-text>
                    </v-card>
                </v-radio-group>
            </v-card-text>
            
            <v-card-actions>
                <v-spacer></v-spacer>
                <!-- <v-btn color="primary" @click="submit" >Submit</v-btn> -->
                <v-btn text @click="close">Hủy</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>