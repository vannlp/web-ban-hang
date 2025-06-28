<script setup>
import ProductApi from '@/Api/ProductApi';
import EditorComponent from '@/Components/Lib/EditorComponent.vue';
import MultiSelectAjax from '@/Components/Lib/MultiSelectAjax.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Helper from '@/libs/Helper';
import loadingManager from '@/libs/LoadingManager';
import toastManager from '@/libs/toast';
import { onMounted, reactive, ref } from 'vue';
import { router } from '@inertiajs/vue3'

defineOptions({
  layout: AuthenticatedLayout,
})

const props = defineProps({
  listBard: Array,
  product: Object,
})

const defaultProduct = () => ({
  name: '',
  short_description: '',
  description: '',
  slug: '',
  price: 0,
  original_price: null,
  image_avatar: '',
  list_image: [],
  bard_id: null,
  status: 0,
  listCategory: [],
});

const initProduct = () => {
    let product = props.product;
    
    return {
        id: product?.id || null,
        name: product?.name || '',
        short_description: product?.short_description || '',
        description: product?.description || '',
        slug: product?.slug || '',
        price: product?.price || 0,
        original_price: product?.original_price || null,
        image_avatar: product?.image_avatar || '',
        list_image: product?.list_image || [],
        bard_id: product?.bard_id || null,
        status: product?.status || 0,
        listCategory: product?.listCategory || [],
    }
};

const tab = ref(null);

const selectedItemBard = reactive([]);

const product = reactive(initProduct());

const submitForm = async () => {
  loadingManager.show();
  
  let response = await ProductApi.update(product.id ,product);
  let message = response?.data?.message;
  toastManager.success(message);
  // refreshProduct();
  loadingManager.hide();  
  router.visit(route('admin.product.index'));
}

const handleSelectImage = () => {
  window.addEventListener('message', receiveMessage);
  
  const route_prefix = '/laravel-filemanager?type=' + 'Images';
  // Load override script trước khi mở popup
  Helper.addScriptLfmOverride();
  
  const win = window.open(route_prefix, 'FileManager', 'width=900,height=600');
  
}

const receiveMessage = (event) => {
  let file = Helper.receiveMessageLFM(event);
  
  if (file) {
    product.image_avatar = file.url;
    
  }
  window.removeEventListener('message', receiveMessage);
};

const receiveMessageListImage = (event) => {
  let files = Helper.receiveMessageLFMMutifile(event);
  if (files) {
    product.list_image = [];
    files.forEach(file => {
      product.list_image.push(file.url);
    });
    // product.image_avatar = files.url;
  }
  window.removeEventListener('message', receiveMessageListImage);
}

const handleSelectListImage = () => {
  window.addEventListener('message', receiveMessageListImage);
  
  const route_prefix = "/laravel-filemanager?type=Images&multiple=true";
  // Load override script trước khi mở popup
  Helper.addScriptLfmOverride();
  
  window.open(route_prefix, 'FileManager123', 'width=900,height=600');
}

const refreshProduct = () => {
  Object.assign(product, defaultProduct());
};

onMounted(() => {
  let listBard = props.listBard;
  listBard = listBard.map((item) => {
    return {
      label: item.name,
      value: item.id,
    };
  });
  
  Object.assign(selectedItemBard, listBard);
})



</script>

<template>
  <div class="p-3">
    <!-- <v-alert class="mb-3 font-medium text-xl">Thêm sản phẩm mới</v-alert> -->
    
    <v-card>
      <v-card-title>
        <v-row>
          <v-col cols="6">
            Thêm sản phẩm mới
          </v-col>
          <v-col cols="6" class="text-end">
          </v-col>
        </v-row>
      </v-card-title>
      <v-card-item>
        <v-tabs
          v-model="tab"
          color="primary"
          class="mb-3"
        >
          <v-tab value="TTCB">Thông tin cơ bản</v-tab>
          <v-tab value="GIAMGIA">Giảm giá</v-tab>
        </v-tabs>
        
        <v-form @submit.prevent="submitForm">
          <v-tabs-window v-model="tab">
            <v-tabs-window-item value="TTCB">
              <v-text-field v-model="product.name" label="Tên sản phẩm" required></v-text-field>
              <v-textarea  v-model="product.short_description" label="Mô tả ngắn"></v-textarea>
              <v-text-field  v-model="product.slug" label="Slug" required></v-text-field>
              <v-row>
                <v-col cols="6">
                  <v-text-field type="number"  v-model="product.price" label="Giá bán" required></v-text-field>
                </v-col>
                <v-col cols="6">  
                  <v-text-field type="number"  v-model="product.original_price" label="Giá ban đầu"></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="6">
                  <label for="">Hình đại diện</label>
                  <!-- <v-text-field type="hidden"  v-model="product.image_avatar"></v-text-field> -->
                  <v-btn color="primary" type="button" @click="handleSelectImage">Chọn ảnh</v-btn>
                  <div class="mt-3"></div>
                  <v-img
                    :width="100"
                    aspect-ratio="16/9"
                    cover
                    :src="product.image_avatar"
                    id="product-image-avatar"
                  ></v-img>
                </v-col>
                <v-col cols="6">  
                  <label for="">Danh sách hình sản phẩm</label>
                  <!-- <v-text-field type="hidden"  v-model="product.list_image"></v-text-field> -->
                  <v-btn color="primary" type="button" @click="handleSelectListImage">Chọn ảnh</v-btn>
                  <div class="mt-3"></div>
                  <v-row>
                    <v-col cols="3" v-for="image in product.list_image">
                      <v-img
                        cover
                        :src="image"
                      ></v-img>
                    </v-col>
                  </v-row>
                </v-col>
              </v-row>
              <!-- <v-textarea  v-model="product.description" label="Mô tả"></v-textarea> -->
              <v-row>
                <v-col cols="6">
                  <v-switch label="Trạng thái" color="primary" v-model="product.status" :false-value="0" :true-value="1" hide-details></v-switch>
                </v-col>
                <v-col cols="6">
                  <v-select
                    label="Hãng"
                    :items="selectedItemBard"
                    v-model="product.bard_id"
                    item-title="label"
                    item-value="value"
                  ></v-select>
                </v-col>
              </v-row>
              
              <MultiSelectAjax
                v-model="product.listCategory"
                :api-url="route('category.getCategories')"
                label="Chọn danh mục"
                item-title="name"
                item-value="id"
                :debounce-delay="400"
              />
              
              <EditorComponent v-model="product.description" label="Mô tả" />
            </v-tabs-window-item>
            
            <v-tabs-window-item value="GIAMGIA">
              
            </v-tabs-window-item>
          </v-tabs-window>
          
          <v-btn color="primary" type="submit" class="mt-3">
              Lưu
          </v-btn>
        </v-form>
      </v-card-item>
    </v-card>
  </div>
</template>
