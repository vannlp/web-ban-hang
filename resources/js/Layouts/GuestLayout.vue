<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import SidebarMenu from '@/Components/Layouts/SidebarMenu.vue';

</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <v-row>
            <v-col cols="4">
                <SidebarMenu />
            </v-col>
            
            <v-col cols="8">
                <slot />
            </v-col>
        </v-row>
    </div>
</template>
