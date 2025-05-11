<template>
  <MainLayout>
    <div class="flex items-center justify-between mb-4">
      <h1 class="text-xl font-semibold">Translations</h1>
      <div class="space-x-2">
        <input
          v-model="searchTerm"
          @keyup.enter="onSearch"
          placeholder="Search..."
          class="px-3 py-2 border rounded"
        />
        <button @click="onSearch" class="px-3 py-2 bg-gray-200 rounded">Go</button>
        <button @click="exportData" class="px-3 py-2 bg-secondary text-white rounded">Export</button>
        <button @click="openForm()" class="px-3 py-2 bg-secondary text-white rounded">+ New</button>
      </div>
    </div>

    <DataTable
      :columns="columns"
      :rows="itemsFormatted"
      :loading="loading"
      :pagination="{ currentPage: page, perPage, total }"
      @page-change="fetch"
      @per-page-change="changePerPage"
    >
      <template #actions="{ row }">
        <button @click="edit(row.id)" class="text-blue-600 hover:underline">Edit</button>
        <button @click="remove(row.id)" class="text-red-600 hover:underline ml-2">Delete</button>
      </template>
    </DataTable>

    <ModalForm v-model:show="showForm" :title="formTitle" @submit="onSubmit">
      <template #body>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium">Locale</label>
            <select v-model.number="form.locale_id" class="w-full border rounded p-2">
              <option v-for="loc in locales" :key="loc.id" :value="loc.id">{{ loc.code }}</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium">Key</label>
            <input v-model="form.key" class="w-full border rounded p-2" />
          </div>
          <div>
            <label class="block text-sm font-medium">Value</label>
            <textarea v-model="form.value" class="w-full border rounded p-2"></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium">Tags</label>
            <select v-model="form.tags" multiple class="w-full border rounded p-2">
              <option v-for="t in tags" :key="t.id" :value="t.id">{{ t.name }}</option>
            </select>
          </div>
        </div>
      </template>
    </ModalForm>
  </MainLayout>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted, computed } from 'vue';
import { useTranslationStore } from '@/stores/translation';
import { useLocaleStore } from '@/stores/locale';
import { useTagStore } from '@/stores/tag';
import MainLayout from '@/layouts/MainLayout.vue';
import DataTable from '@/components/ui/DataTable.vue';
import ModalForm from '@/components/ui/ModalForm.vue';

const store = useTranslationStore();
const localeStore = useLocaleStore();
const tagStore = useTagStore();

const items = computed(() => store.items);
const loading = computed(() => store.loading);
const page = computed(() => store.page);
const perPage = computed(() => store.perPage);
const total = computed(() => store.total);

const columns = [
  { label: 'ID', field: 'id' },
  { label: 'Locale', field: 'locale.code' },
  { label: 'Key', field: 'key' },
  { label: 'Value', field: 'value' }
];

const locales = computed(() => localeStore.items);
const tags = computed(() => tagStore.items);

const showForm = ref(false);
const isEdit = ref(false);
const searchTerm = ref('');
const form = reactive({ id: null as number | null, locale_id: 0, key: '', value: '', tags: [] as number[] });
const formTitle = computed(() => (isEdit.value ? 'Edit Translation' : 'New Translation'));

onMounted(() => { localeStore.fetch(); tagStore.fetch(); fetch(1); });
function fetch(p = 1) { store.fetch(p); }
function onSearch() { store.search(searchTerm.value, 1); }
function changePerPage(n: number) { store.perPage = n; fetch(1); }
function openForm() { isEdit.value = false; Object.assign(form, { id: null, locale_id: locales.value[0]?.id || 0, key: '', value: '', tags: [] }); showForm.value = true; }
async function edit(id: number) { await store.get(id); if (store.current) { isEdit.value = true; const t = store.current; form.id = t.id; form.locale_id = t.locale.id; form.key = t.key; form.value = t.value; form.tags = t.tags.map(x => x.id); showForm.value = true; }}
async function onSubmit() { if (isEdit.value && form.id) await store.update(form.id, form); else await store.create(form); showForm.value = false; fetch(page.value); }
async function remove(id: number) { if (confirm('Delete this translation?')) { await store.remove(id); fetch(page.value); }}
function exportData() { store.export(); }

const itemsFormatted = computed(() => items.value.map(item => ({
  id: item.id,
  'locale.code': item.locale.code,
  key: item.key,
  value: item.value,
  tags: item.tags.map(t => t.name).join(', ')
})));
</script>
