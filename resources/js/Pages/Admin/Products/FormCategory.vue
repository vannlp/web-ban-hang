<script setup>
import CategoryApi from '@/Api/CategoryApi';
import SelectAjax from '@/Components/Lib/SelectAjax.vue';
import Helper from '@/libs/Helper';
import loadingManager from '@/libs/LoadingManager';
import { computed, onMounted, reactive, ref, watch } from 'vue';

const props = defineProps({
    modelValue: Boolean,
    id: {
        type: Number,
        default: null
    }
})

const emit = defineEmits(['update:modelValue', 'submit'])

const isOpen = ref(false)
const id = computed(() => props.id);
const listCategory = ref([]);

watch(() => props.modelValue, val => {
  isOpen.value = val
})

watch(isOpen, val => {
  emit('update:modelValue', val)
})

watch(id, async (val) => {
    
    if(props.id) {
        await getCategory();
        isEdit.value = true;
        isOpen.value = true;
    } else {
        resetForm();
        isEdit.value = false;
    }
})

const isEdit = ref(false);


const formData = () => {
    return {
        'name': '',
        'slug': '',
        'status': 0,
        'description': "",
        'parent_id': '',
        'image_avatar': '',
        'parent_id': ''
    }
}

const form = reactive(formData())

function submitForm() {

    emit('submit', {
        data: form,
        isEdit: isEdit.value ,
        id: id.value
    })
    
    resetForm();

    close()
}

function close() {
    isOpen.value = false
}

const resetForm = () => {
    Object.assign(form, formData());
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
    form.image_avatar = file.url;
    
  }
  window.removeEventListener('message', receiveMessage);
};

const getCategory = async () => {
    loadingManager.show();
    try {
        let res = await CategoryApi.getOne(props.id);
        let data = res?.data?.data;
        
        let dataUpdate = {
            'name': data.name,
            'slug': data.slug,
            'status': data.status,
            'description': data.description,
            'parent_id': data.parent_id,
            'image_avatar': data.image_avatar
        };
        Object.assign(form, dataUpdate);
        isEdit.value = true;
    } catch (error) {
    } finally  {
        loadingManager.hide(); 
    }
}

onMounted(() => {
    if(props.id) {
        getCategory();
    }
})

</script>

<template>
  <v-dialog v-model="isOpen" max-width="500" persistent fullscreen>
        <v-card>
            <v-card-title>
                <v-row align="center" class="w-100">
                    <v-col cols="6">
                        <span class="text-h6 font-weight-bold">{{ isEdit ? 'Chỉnh sửa danh mục' : 'Thêm mới danh mục' }}</span>
                    </v-col>
                    <v-col cols="6" class="text-end">
                        <v-btn icon @click="close">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-col>
                </v-row>
            </v-card-title>

            <v-card-text>
                <v-form ref="formRef" @submit.prevent="submitForm">
                    <v-text-field v-model="form.name" label="Tên danh mục" required></v-text-field>
                    <v-text-field v-model="form.slug" label="Slug" required></v-text-field>
                    <v-switch label="Trạng thái" color="primary" v-model="form.status" :false-value="0" :true-value="1" ></v-switch>
                    
                    <SelectAjax
                        v-model="form.parent_id"
                        :api-url="route('category.getCategories')"
                        label="Chọn danh mục cha"
                        item-title="name"
                        item-value="id"
                        :isDefauld="true"
                        :debounce-delay="400"
                    />
                    
                    <v-textarea label="Mô tả" v-model="form.description"></v-textarea>
                    
                    <div>
                        <label for="">Hình đại diện</label>
                        <v-btn color="primary" type="button" @click="handleSelectImage">Chọn ảnh</v-btn>
                        <div class="mt-3"></div>
                        <v-img
                            :width="100"
                            aspect-ratio="16/9"
                            cover
                            :src="form.image_avatar"
                            id="product-image-avatar"
                            v-if="form.image_avatar"
                        ></v-img>
                    </div>
                </v-form>
            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" @click="submitForm">
                    {{ isEdit ? 'Cập nhật' : 'Lưu' }}
                </v-btn>
                <v-btn text @click="close">Hủy</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>