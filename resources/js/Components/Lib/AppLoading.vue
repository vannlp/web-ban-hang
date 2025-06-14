<template>
  <v-overlay
    :model-value="visible"
    class="app-loading"
    persistent
    scrim
    z-index="9999"
  >
    <v-progress-circular
      indeterminate
      color="primary"
      size="64"
      width="6"
    />
  </v-overlay>
</template>

<script setup>
import { ref, defineExpose } from 'vue'

const visible = ref(false)

function show() {
  visible.value = true
}

function hide() {
  visible.value = false
}

defineExpose({
  show,
  hide,
})
</script>

<style scoped>
.app-loading {
  background-color: rgba(255, 255, 255, 0.7);
  display: flex;
  justify-content: center;
  align-items: center;
}
</style>
