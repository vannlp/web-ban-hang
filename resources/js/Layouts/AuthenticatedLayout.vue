<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import TopbarSidebar from '@/Components/Layouts/TopbarSidebar.vue';


</script>

<template>
    <v-app>
    <!-- Các component bên trong như navigation-drawer, toolbar, main -->
        <TopbarSidebar />
        <v-main>
            <slot />
        </v-main>
    </v-app>
</template>
