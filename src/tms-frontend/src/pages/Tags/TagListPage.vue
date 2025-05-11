<template>
  <MainLayout>
    <div class="flex items-center justify-between mb-4">
      <h1 class="text-xl font-semibold">Tags</h1>
      <button
        @click="openForm()"
        class="px-4 py-2 bg-secondary text-white rounded hover:bg-secondary/90 transition"
      >
        + New Tag
      </button>
    </div>

    <DataTable
      :columns="columns"
      :rows="items"
      :loading="loading"
      :pagination="{ currentPage: page, perPage, total }"
      @page-change="fetch"
      @per-page-change="changePerPage"
    >
      <template #actions="{ row }">
        <button
          @click="edit(row.id)"
          class="text-blue-600 hover:underline"
        >Edit</button>
        <button
          @click="remove(row.id)"
          class="text-red-600 hover:underline ml-2"
        >Delete</button>
      </template>
    </DataTable>

    <ModalForm
      v-model:show="showForm"
      :title="formTitle"
      @submit="onSubmit"
    >
      <template #body>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <input
              v-model="form.name"
              type="text"
              class="w-full px-3 py-2 border rounded"
            />
          </div>
        </div>
      </template>
    </ModalForm>
  </MainLayout>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted, computed } from 'vue';
import { useTagStore } from '@/stores/tag';
import MainLayout from '@/layouts/MainLayout.vue';
import DataTable from '@/components/ui/DataTable.vue';
import ModalForm from '@/components/ui/ModalForm.vue';

const store = useTagStore();
const items = computed(() => store.items);
const loading = computed(() => store.loading);
const page = computed(() => store.page);
const perPage = computed(() => store.perPage);
const total = computed(() => store.total);

const columns = [
  { label: 'ID', field: 'id', sortable: true },
  { label: 'Name', field: 'name', sortable: true }
];

const showForm = ref(false);
const isEdit = ref(false);
const form = reactive({ id: null as number | null, name: '' });

const formTitle = computed(() => (isEdit.value ? 'Edit Tag' : 'New Tag'));

onMounted(() => fetch(1));
function fetch(p = 1) { store.fetch(p); }
function changePerPage(n: number) { store.perPage = n; fetch(1); }
function openForm() { isEdit.value = false; form.id = null; form.name = ''; showForm.value = true; }
async function edit(id: number) {
  await store.get(id);
  if (store.current) {
    isEdit.value = true;
    form.id = store.current.id;
    form.name = store.current.name;
    showForm.value = true;
  }
}
async function onSubmit() {
  if (isEdit.value && form.id) {
    await store.update(form.id, { name: form.name });
  } else {
    await store.create({ name: form.name });
  }
  showForm.value = false;
  fetch(page.value);
}
async function remove(id: number) { if (confirm('Delete this tag?')) { await store.remove(id); fetch(page.value); } }
</script>
