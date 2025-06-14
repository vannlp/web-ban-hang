<!-- EditorComponent.vue -->
<template>
    <label  >{{ props.label }}</label>
  <editor
    api-key="v7uxagccs26096o8eu0kae4sbg90s9bicobdondox6ybfxen"
    v-model="localContent"
    :init="editorInit"
  />
</template>

<script setup>
import { ref, watch } from 'vue'
import Editor from '@tinymce/tinymce-vue'
import Helper from '@/libs/Helper'

const props = defineProps({
  modelValue: String,
  label: String
})
const emit = defineEmits(['update:modelValue'])

const localContent = ref(props.modelValue || '')
watch(localContent, (newVal) => emit('update:modelValue', newVal))

const editorInit = {
  height: 500,
  menubar: false,
  plugins: 'lists link image code table',
  toolbar:
    'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code',

  file_picker_callback(callback, value, meta) {
    const route_prefix = '/laravel-filemanager?type=' + (meta.filetype === 'image' ? 'Images' : 'Files');
    // Load override script trước khi mở popup
    Helper.addScriptLfmOverride();
    const receiveMessage = (event) => {
      if (event.origin !== window.location.origin) return;
      if (event.data.type === 'lfm-selected') {
        const file = event.data.items?.[0] || { url: event.data.url };
        callback(file.url);
        window.removeEventListener('message', receiveMessage);
      }
    };

    window.addEventListener('message', receiveMessage);
    window.open(route_prefix, 'FileManager', 'width=900,height=600');
  },

  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
};
</script>
