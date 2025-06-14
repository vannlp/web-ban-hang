<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import TopbarSidebar from '@/Components/Layouts/TopbarSidebar.vue';
import Toast from '@/Components/Errors/Toast.vue'
import { ref, onMounted } from 'vue'
import toastManager from '@/libs/toast';
import { usePage } from '@inertiajs/vue3';

import AppLoading from '@/Components/Lib/AppLoading.vue';
import loadingManager from '@/libs/LoadingManager';

const toastRef = ref(null)
const page = usePage()
const loadingRef = ref(null)

onMounted(() => {
    toastManager.register(toastRef.value)
    loadingManager.register(loadingRef.value)
    if (page.props.title) {
        document.title = page.props.title
    }
})

</script>

<template>
    <v-app>
    <!-- Các component bên trong như navigation-drawer, toolbar, main -->
        <TopbarSidebar />
        <v-main>
            <slot />
        </v-main>
        
        <!-- Toast để cuối -->
        <Toast ref="toastRef" />
        <AppLoading ref="loadingRef" />
    </v-app>
</template>
