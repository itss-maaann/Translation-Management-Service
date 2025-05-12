<template>
  <main-layout>
    <DataTable
      :columns="columns"
      :rows="store.items"
      :loading="store.loading"
      :error="store.error"
      :pagination="store.pagination"
      @page-change="onPage"
    >
      <template #header>
        <el-page-header
          content="Manage your translation tags"
          class="page-header"
        >
          <template #extra>
            <el-button type="primary" icon="el-icon-plus" @click="openCreate">
              New Tag
            </el-button>
          </template>
        </el-page-header>
      </template>

      <template #ActionsColumn="{ row }">
        <el-button size="small" @click="openEdit(row)">Edit</el-button>
        <el-button size="small" type="danger" @click="remove(row.id)">
          Delete
        </el-button>
      </template>
    </DataTable>

    <el-dialog
      :title="dialogTitle"
      :visible.sync="dialogVisible"
      width="400px"
      @close="closeDialog"
    >
      <el-form :model="form" label-width="100px">
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
import { useTagStore } from '@/stores/tag'
import MainLayout from '@/layouts/MainLayout.vue'
import DataTable from '@/components/ui/DataTable.vue'

const store = useTagStore()

const columns = [
  { prop: 'name', label: 'Name', sortable: true },
  {
    prop: 'actions',
    label: 'Actions',
    slot: 'ActionsColumn',
    attrs: { width: 180 },
  },
]

const dialogVisible = ref(false)
const dialogTitle = ref('')
const form = reactive<{ id?: number; name: string }>({ name: '' })
const formErrors = reactive<{ name?: string }>({})

onMounted(fetchData)
async function fetchData() {
  await store.fetch({
    page: store.pagination.page,
    perPage: store.pagination.perPage,
  })
}

function openCreate() {
  dialogTitle.value = 'Create Tag'
  form.id = undefined
  form.name = ''
  formErrors.name = undefined
  dialogVisible.value = true
}

function openEdit(row: any) {
  dialogTitle.value = 'Edit Tag'
  form.id = row.id
  form.name = row.name
  formErrors.name = undefined
  dialogVisible.value = true
}

function closeDialog() {
  dialogVisible.value = false
}

async function save() {
  formErrors.name = undefined
  try {
    await store.save({ id: form.id, name: form.name })
    dialogVisible.value = false
    fetchData()
  } catch (err: any) {
    formErrors.name = err.response?.data?.errors?.name?.[0]
  }
}

async function remove(id: number) {
  await store.remove(id)
  fetchData()
}

function onPage(page: number) {
  store.fetch({ page })
}
</script>

<style scoped>
.page-header {
  margin-bottom: 16px;
}
</style>
