<template>
    <v-dialog v-model="isOpen" max-width="500" persistent>
        <v-card>
            <v-card-title>
                <v-row align="center" class="w-100">
                    <v-col cols="6">
                        <span class="text-h6 font-weight-bold">{{ isEdit ? 'Chỉnh sửa người dùng' : 'Thêm người dùng mới' }}</span>
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
                    <v-text-field v-model="form.name" label="Họ tên" required></v-text-field>
                    <v-text-field v-model="form.username" :readonly="isEdit" label="Tên đăng nhập" required></v-text-field>

                    <v-text-field v-model="form.email" :readonly="isEdit" label="Email" type="email" required></v-text-field>

                    <v-text-field v-model="form.password" label="Mật khẩu" type="password"
                        :rules="[isEdit ? () => true : requiredRule]"
                        :placeholder="isEdit ? '(Không thay đổi nếu để trống)' : ''"></v-text-field>

                    <v-text-field v-model="form.password_confirmation" label="Nhập lại mật khẩu" type="password"
                        :rules="[isEdit ? () => true : requiredRule]"></v-text-field>
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

<script setup>
import { ref, watch, computed } from 'vue'
import toastManager from '@/libs/toast' // điều chỉnh path nếu cần

const props = defineProps({
    modelValue: Boolean,
    editData: {
        type: Object,
        default: null
    }
})

const emit = defineEmits(['update:modelValue', 'submit'])

const isOpen = ref(props.modelValue)
watch(() => props.modelValue, val => isOpen.value = val)
watch(isOpen, val => emit('update:modelValue', val))

const isEdit = computed(() => props.editData !== null)

computed(() => {
    console.log(props.editData);
    
})

const form = ref({
    name: '',
    username: '',
    email: '',
    password: '',
    password_confirmation: ''
})

const formRef = ref()
const requiredRule = v => !!v || 'Không được để trống'

// Gán dữ liệu khi edit
watch(() => props.editData, val => {
    if (val) {
        form.value = {
            username: val.username || '',
            email: val.email || '',
            password: '',
            name: val.name || '',
            password_confirmation: ''
        }
    } else {
        resetForm()
    }
})

// Reset form khi mở add
function resetForm() {
    form.value = {
        username: '',
        email: '',
        password: '',
        password_confirmation: '',
        name: ''
    }
}

function submitForm() {
    if (!isEdit.value && form.value.password !== form.value.password_confirmation) {
        toastManager.error('Mật khẩu không khớp')
        return
    }
    

    emit('submit', {
        data: form.value,
        id: props.editData?.id || null,
        isEdit: isEdit.value 
    })
    
    // resetForm();

    close()
}

function close() {
    isOpen.value = false
}
</script>