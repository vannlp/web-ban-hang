<script setup>
import ProductCard from '@/Components/Client/ProductCard.vue';
import ClientLayout from '@/Layouts/ClientLayout.vue';
import { ref } from 'vue';

const props = defineProps({
    'category': {
        type: Object,
        required: true
    },
    products: {
        type: Array,
        default: () => []
    }
});

// Dùng layout riêng cho trang này
defineOptions({
  layout: ClientLayout,
})

const category = ref(props.category);
const products = ref(props.products);

</script>

<template>
    <div class="p-3">
        <v-container>
            <v-card>
                <v-card-title>
                    <h2 class="text-xl font-bold">Danh mục {{ category.name }}</h2>
                </v-card-title>
                
                <v-card-text>
                    <v-row>
                        <v-col cols="3" v-for="product in products">
                            <ProductCard :product="product" />
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-container>
    </div>
</template>