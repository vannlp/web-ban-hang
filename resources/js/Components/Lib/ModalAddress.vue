<script setup>
import { computed, onMounted, reactive, ref, watch } from 'vue';
import SelectAjax from './SelectAjax.vue';
import AddressApi from '@/Api/AddressApi';
import { debounce } from 'lodash';
import { get } from 'lodash';
import toastManager from '@/libs/toast';
import loadingManager from '@/libs/LoadingManager';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    modelValue: {
        type: Boolean,
        default: false,
    },
    id: {
        type: Number,
        default: null,
    },
})

const emit = defineEmits(['update:modelValue', 'submit']) // khai báo các event mà component có thể emit


const page = usePage()
const user = computed(() => page.props?.auth?.user || null)


const modal = ref(false);

watch(() => props.modelValue, (val) => {
    modal.value = val;
});

watch(modal, (val) => {
    if (!val) {
        id.value = null;
        Object.assign(address, defaultAddress());    
    }
    emit('update:modelValue', val);
});

const submit = async () => {
    loadingManager.show();
    let res = null;
    if(id.value) {
        res = await AddressApi.update(id.value, address);
    } else {
        address.user_id = user.value.id;
        res = await AddressApi.store(address);
    }
    loadingManager.hide();
    toastManager.success(res.data.message);
    modal.value = false;
    emit('submit', res.data.data);
}

const id = ref(props.id);

const defaultAddress = () => {
    
    return {
        phone: "",
        street: "",
        city_id: null,
        district_id: null,
        ward_id: null,
        user_id: null,
        is_defaut: 0
    };
}

const address = reactive(defaultAddress());

const listCity = ref([]);
const listDistrict = ref([]);
const listWard = ref([]);

const getCities = async (params = {}) => {
    let res = await AddressApi.getCities(params);
    listCity.value = res.data.data;
}

const getDistricts = async (params = {}) => {
    let res = await AddressApi.getDistricts(params);
    listDistrict.value = res.data.data;
}

const getWards = async (params = {}) => {
    let res = await AddressApi.getWards(params);
    listWard.value = res.data.data;
}

const getAddresses = async (id) => {
    let res = await AddressApi.getOne(id);
    Object.assign(address, res.data.data);
}


const handleSearchCity = async (value) => {
    let params = {
        search: value
    }
    await debouncedgetCities(params);
}

const handleSearchDistrict = async (value, type = null) => {
    let params = {
        city_id: address.city_id
    }
    
    
    if(type != 'focus') {
        params.search = value;
    }
    await debouncedgetDistricts(params);
}

const handleSearchWard = async (value, type = null) => {
    let params = {
        district_id: address.district_id
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

watch(() => props.id, async (val) => {
    id.value = val;
    await getAddresses(val);
    getCities({id: address.city_id});
    getDistricts({id: address.district_id});
    getWards({id: address.ward_id});
})


onMounted(async () => {
    loadingManager.show();
    
    if(id.value) {
        await getAddresses(props.id);
        await getCities({id: address.city_id});
        await getDistricts({id: address.district_id});
        await getWards({id: address.ward_id});
    } else {
        await getCities();
    }
    
    loadingManager.hide();
})

const close = () => {
    
    modal.value = false;
}



</script>

<template>
    <v-dialog v-model="modal" max-width="600" >
        <v-card
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
                <form  method="post" @submit.prevent="submit">
                    <v-text-field label="Số điện thoại" v-model="address.phone"></v-text-field>
                    
                    <v-text-field label="Địa chỉ" v-model="address.street"></v-text-field>
                    
                    <v-autocomplete
                        v-model="address.city_id"
                        :items="listCity"
                        label="Chọn tỉnh/thành"
                        item-title="name"
                        item-value="id"
                        @update:search="handleSearchCity"
                        no-filter
                    />
                    
                    <v-autocomplete
                        v-model="address.district_id"
                        :items="listDistrict"
                        label="Chọn Quận/Huyện"
                        item-title="name"
                        item-value="id"
                        :disabled="!address.city_id"
                        @update:search="handleSearchDistrict"
                        @focus="handleSearchDistrict('','focus')"
                        no-filter
                    />
                    
                    <v-autocomplete
                        v-model="address.ward_id"
                        :items="listWard"
                        label="Chọn Phường/Xã"
                        item-title="name"
                        item-value="id"
                        :disabled="!address.district_id"
                        @update:search="handleSearchWard"
                        @focus="handleSearchWard('','focus')"
                        no-filter
                    />
                    
                    
                </form>
                
                
            </v-card-text>
            
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" @click="submit" >Submit</v-btn>
                <v-btn text @click="close">Hủy</v-btn>
            </v-card-actions>
        </v-card>
       
    </v-dialog>
</template>