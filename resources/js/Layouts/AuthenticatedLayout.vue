<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import TopbarSidebar from '@/Components/Layouts/TopbarSidebar.vue';
import Toast from '@/Components/Errors/Toast.vue'
import { ref, onMounted } from 'vue'
import toastManager from '@/libs/toast';

const toastRef = ref(null)

onMounted(() => {
    toastManager.register(toastRef.value)
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
    </v-app>
</template>
