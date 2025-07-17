<script setup>
import { Link, router } from '@inertiajs/vue3';
import Toast from '@/Components/Errors/Toast.vue'
import { ref, onMounted, reactive, computed } from 'vue'
import toastManager from '@/libs/toast';
import { usePage } from '@inertiajs/vue3';
import ProductApi from '@/Api/ProductApi';
import CategoryApi from '@/Api/CategoryApi';

import AppLoading from '@/Components/Lib/AppLoading.vue';
import loadingManager from '@/libs/LoadingManager';
import SearchBar from '@/Components/Client/SearchBar.vue';
import Footer from './Footer.vue';

const toastRef = ref(null)
const page = usePage()
const loadingRef = ref(null)

onMounted(() => {
    toastManager.register(toastRef.value)
    loadingManager.register(loadingRef.value)
    if (page.props.title) {
        document.title = page.props.title
    }
    
    innitData();
})

const innitData = async () => {
   await getCategories();
}

const getCategories = async () => {
    let param = {};
    let res = await CategoryApi.getClient(param);
    Object.assign(categories, res.data.data);
}

const isOpen = ref(false)

const categories = reactive([]);

const user = computed(() => page.props?.auth?.user)

const handleOnclickDanhMuc = (item) => {
    isOpen.value = !isOpen.value;
    
    router.visit(route('client.category', {slug: item?.slug}));
}

</script>

<template>
    <v-app background-color="#EAEFEF">
    <!-- Các component bên trong như navigation-drawer, toolbar, main -->
        <!-- <TopbarSidebar /> -->
        <v-app-bar color="red" density="compact" dark class="py-2">
            <!-- Logo bên trái -->
            <v-container>
                <v-row align="center" class="w-100">
                <!-- Cột Logo -->
                    <v-col cols="3" class="d-flex align-center">
                        <v-app-bar-title class="font-weight-bold text-white">
                            <Link href="/">Logo</Link>
                        </v-app-bar-title>
                        
                        <v-btn
                            color="white"
                            prepend-icon="mdi-menu"
                            class="text-red-600"
                            @click="() => isOpen = !isOpen"
                        >
                            Danh mục
                        </v-btn>
                    </v-col>

                    <!-- Cột Search -->
                    <v-col cols="5">
                        <SearchBar />
                    </v-col>

                    <!-- Cột Nút đăng nhập -->
                    <v-col cols="4" class="d-flex justify-end align-center">
                        <template v-if="user">
                            <Link :href="route('client.cart')">
                                <v-btn icon="mdi-cart" class="text-none"></v-btn>
                            </Link>
                            <Link :href="route('client.profile')">
                                <v-avatar :image="user.avatar"></v-avatar>
                            </Link>
                        </template>
                        <template v-else>
                            <v-btn prepend-icon="mdi-account" size="small" class="text-none mr-3" variant="outlined">
                                Đăng Ký
                            </v-btn>
                            <Link :href="route('client.login')" >
                                <v-btn prepend-icon="mdi-account" size="small" class="text-none" variant="outlined">
                                    Đăng nhập
                                </v-btn>
                            </Link>
                        </template>
                        
                    </v-col>
                </v-row>
            </v-container>
        </v-app-bar>
        
        <v-main>
            <slot />
        </v-main>
        
        <Footer />
        
        <!-- Toast để cuối -->
        <Toast ref="toastRef" />
        <AppLoading ref="loadingRef" />
        
        <!-- Overlay đen khi mở menu -->
        <div
            v-if="isOpen"
            class="fixed inset-0 bg-slate-700 bg-opacity-30 z-40"
            @click="isOpen = false"
        ></div>
        <!-- Menu trượt từ trái ra -->
        <div
            v-if="isOpen"
            class="fixed top-[70px] left-40 z-50 bg-white shadow-lg rounded w-[280px] max-h-[calc(100vh-64px)] overflow-y-auto transition-all duration-300"
        >
            <ul class="divide-y divide-gray-100">
                <li
                    v-for="item in categories"
                    :key="item.id"
                    class="flex items-center px-4 py-3 hover:bg-gray-100 cursor-pointer"
                    @click="handleOnclickDanhMuc(item)"
                >
                    <!-- <v-icon class="mr-3" size="22">{{ item.icon }}</v-icon> -->
                    <span class="flex-1 text-sm text-gray-800 font-medium">{{ item.name }}</span>
                </li>
            </ul>
        </div>
    </v-app>
</template>
