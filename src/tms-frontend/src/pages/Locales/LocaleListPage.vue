<template>
  <main-layout>
    <DataTable
      :columns="columns"
      :rows="store.items"
      :loading="store.loading"
      :error="store.error"
      :pagination="store.pagination"
      @sort-change="onSort"
      @page-change="onPage"
    >
      <!-- header -->
      <template #header>
        <el-page-header
          content="Manage your supported languages and regions"
          class="page-header"
        >
          <template #extra>
            <el-button
              type="primary"
              icon="el-icon-plus"
              @click="openCreate"
            >
              New Locale
            </el-button>
          </template>
        </el-page-header>
      </template>

      <!-- actions column -->
      <template #ActionsColumn="{ row }">
        <el-button size="small" @click="openEdit(row)">Edit</el-button>
        <el-button
          size="small"
          type="danger"
          @click="remove(row.id)"
        >
          Delete
        </el-button>
      </template>
    </DataTable>

    <!-- dialog -->
    <el-dialog
      :title="dialogTitle"
      :visible.sync="dialogVisible"
      width="400px"
      @close="closeDialog"
    >
      <el-form :model="form" label-width="100px">
        <el-form-item label="Code" :error="formErrors.code">
          <el-input v-model="form.code" />
        </el-form-item>
        <el-form-item label="Name" :error="formErrors.name">
          <el-input v-model="form.name" />
        </el-form-item>
      </el-form>
      <template #footer>
        <el-button @click="closeDialog">Cancel</el-button>
        <el-button type="primary" @click="save">Save</el-button>
      </template>
    </el-dialog>
  </main-layout>
</template>

<script setup lang="ts">
import { ref, onMounted, reactive } from 'vue'
import { useLocaleStore } from '@/stores/locale'
import MainLayout from '@/layouts/MainLayout.vue'
import DataTable from '@/components/ui/DataTable.vue'

const store = useLocaleStore()
const sortBy = ref('code')
const sortOrder = ref('ascending')

// DataTable columns
const columns = [
  { prop: 'code', label: 'Code', sortable: true },
  { prop: 'name', label: 'Name', sortable: true },
  {
    prop: 'actions',
    label: 'Actions',
    slot: 'ActionsColumn',
    attrs: { width: 180 },
  },
]

// dialog state
const dialogVisible = ref(false)
const dialogTitle = ref('')
const form = reactive({ id: 0, code: '', name: '' })
const formErrors = reactive<{ code?: string; name?: string }>({})

// initial load
onMounted(() => fetchData())

async function fetchData() {
  await store.fetch({
    page: store.pagination.page,
    perPage: store.pagination.perPage,
  })
}

function openCreate() {
  dialogTitle.value = 'Create Locale'
  form.id = 0
  form.code = ''
  form.name = ''
  formErrors.code = formErrors.name = undefined
  dialogVisible.value = true
}

function openEdit(row: any) {
  dialogTitle.value = 'Edit Locale'
  form.id = row.id
  form.code = row.code
  form.name = row.name
  formErrors.code = formErrors.name = undefined
  dialogVisible.value = true
}

function closeDialog() {
  dialogVisible.value = false
}

async function save() {
  formErrors.code = formErrors.name = undefined
  try {
    await store.save({ id: form.id, code: form.code, name: form.name })
    dialogVisible.value = false
    fetchData()
  } catch (err: any) {
    const e = err.response?.data?.errors || {}
    formErrors.code = e.code?.[0]
    formErrors.name = e.name?.[0]
  }
}

async function remove(id: number) {
  await store.remove(id)
  fetchData()
}

function onPage(page: number) {
  store.fetch({ page })
}

function onSort({ prop, order }: { prop: string; order: string }) {
  sortBy.value = prop
  sortOrder.value = order
}
</script>

<style scoped>
.page-header {
  margin-bottom: 16px;
}
</style>
