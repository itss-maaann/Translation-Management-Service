<template>
  <el-dialog
    :title="title"
    :visible.sync="visible"
    width="500px"
    @close="onClose"
    custom-class="modal-form"
  >
    <div class="mt-4">
      <slot name="body"></slot>
    </div>

    <template #footer>
      <el-button @click="onClose">Cancel</el-button>
      <el-button type="primary" @click="onSubmit">{{ okText }}</el-button>
    </template>
  </el-dialog>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue';

const props = defineProps<{
  show: boolean;
  title: string;
  okText?: string;
}>();

const emit = defineEmits<{
  (e: 'update:show', value: boolean): void;
  (e: 'submit'): void;
}>();

const visible = ref(props.show);

watch(
  () => props.show,
  (val) => {
    visible.value = val;
  }
);

function onClose() {
  emit('update:show', false);
}

function onSubmit() {
  emit('submit');
}

const okText = computed(() => props.okText || 'Save');

watch(visible, (val) => {
  if (!val) emit('update:show', false);
});
</script>

<style scoped>
.modal-form ::v-deep .el-dialog__header {
  background: #fafafa;
  border-bottom: 1px solid #ebeef5;
}
.modal-form ::v-deep .el-dialog__footer {
  text-align: right;
  padding: 15px 20px;
  background: #fafafa;
  border-top: 1px solid #ebeef5;
}
</style>
