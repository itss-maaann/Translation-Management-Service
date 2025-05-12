<template>
  <main-layout>
    <!-- Page header -->
    <el-page-header
      content="Create, edit & export your translation entries"
      class="page-header"
    >
      <template #extra>
        <el-input
          v-model="searchQuery"
          placeholder="Search..."
          size="small"
          clearable
          @clear="onSearchClear"
          @keyup.enter="onSearch"
          class="search-input"
        >
          <template #append>
            <el-button icon="el-icon-search" @click="onSearch" />
          </template>
        </el-input>
        <el-button type="primary" icon="el-icon-plus" @click="openCreate">
          New Translation
        </el-button>
      </template>
    </el-page-header>

    <!-- Error alert -->
    <el-alert
      v-if="store.error"
      :title="store.error"
      type="error"
      show-icon
      class="mb-4"
    />

    <!-- Table skeleton -->
    <el-skeleton :loading="store.loading" animated>
      <el-table
        :data="store.items"
        stripe
        style="width: 100%"
        @sort-change="onSort"
        :default-sort="{ prop: sortBy, order: sortOrder }"
      >
        <el-table-column prop="locale.code" label="Locale" sortable />
        <el-table-column prop="key" label="Key" sortable />
        <el-table-column prop="value" label="Value" />
        <el-table-column label="Tags">
          <template #default="{ row }">
            <el-tag
              v-for="tag in row.tags"
              :key="tag.id"
              size="small"
              class="mr-1"
            >
              {{ tag.name }}
            </el-tag>
          </template>
        </el-table-column>
        <el-table-column label="Actions" width="200">
          <template #default="{ row }">
            <el-button size="small" @click="openEdit(row)">Edit</el-button>
            <el-button
              size="small"
              type="danger"
              @click="remove(row.id)"
            >
              Delete
            </el-button>
          </template>
        </el-table-column>
      </el-table>

      <!-- Pagination & Export -->
      <div class="pagination-wrapper">
        <el-pagination
          @current-change="onPage"
          :current-page="store.pagination.page"
          :page-size="store.pagination.perPage"
          :total="store.pagination.total"
          background
          layout="prev, pager, next"
        />
        <el-button @click="exportJson" class="export-btn">
          Export JSON
        </el-button>
      </div>
    </el-skeleton>

    <!-- Create/Edit dialog -->
    <el-dialog
      :title="dialogTitle"
      :visible.sync="dialogVisible"
      width="600px"
      @close="closeDialog"
    >
      <el-form :model="form" label-width="120px">
        <el-form-item label="Locale" :error="formErrors.locale_id">
          <el-select v-model="form.locale_id" placeholder="Select locale">
            <el-option
              v-for="loc in locales"
              :key="loc.id"
              :label="loc.code"
              :value="loc.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="Key" :error="formErrors.key">
          <el-input v-model="form.key" />
        </el-form-item>
        <el-form-item label="Value" :error="formErrors.value">
          <el-input type="textarea" v-model="form.value" />
        </el-form-item>
        <el-form-item label="Tags" :error="formErrors.tags">
          <el-select v-model="form.tags" multiple placeholder="Select tags">
            <el-option
              v-for="tag in allTags"
              :key="tag.id"
              :label="tag.name"
              :value="tag.id"
            />
          </el-select>
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
import MainLayout from '@/layouts/MainLayout.vue'
import { useTranslationStore } from '@/stores/translation'
import { useLocaleStore } from '@/stores/locale'
import { useTagStore } from '@/stores/tag'
import type { Locale } from '@/api/locales'
import type { Tag } from '@/api/tags'

const store = useTranslationStore()
const localeStore = useLocaleStore()
const tagStore = useTagStore()

// Search + sort state
const searchQuery = ref<string>('')
const sortBy = ref<string>('key')
const sortOrder = ref<string>('ascending')

// Pagination dialog state
const dialogVisible = ref<boolean>(false)
const dialogTitle = ref<string>('')

// Form data & validation
const form = reactive<{
  id?: number
  locale_id: number
  key: string
  value: string
  tags: number[]
}>({
  locale_id: 0,
  key: '',
  value: '',
  tags: [],
})
const formErrors = reactive<Record<string, string[]>>({})

// Typed arrays of locales & tags
const locales = ref<Locale[]>([])
const allTags = ref<Tag[]>([])

onMounted(async () => {
  await localeStore.fetch({ perPage: 100 })
  locales.value = localeStore.items

  await tagStore.fetch({ perPage: 100 })
  allTags.value = tagStore.items

  fetchData()
})

async function fetchData() {
  if (searchQuery.value) {
    await store.search(searchQuery.value, { page: store.pagination.page })
  } else {
    await store.fetch({ page: store.pagination.page })
  }
}

function onSearch() {
  store.pagination.page = 1
  fetchData()
}
function onSearchClear() {
  searchQuery.value = ''
  fetchData()
}

function openCreate() {
  dialogTitle.value = 'Create Translation'
  form.id = undefined
  form.locale_id = locales.value[0]?.id ?? 0
  form.key = ''
  form.value = ''
  form.tags = []
  Object.keys(formErrors).forEach(k => delete formErrors[k])
  dialogVisible.value = true
}

function openEdit(row: {
  id: number
  locale: { id: number }
  key: string
  value: string
  tags: { id: number }[]
}) {
  dialogTitle.value = 'Edit Translation'
  form.id = row.id
  form.locale_id = row.locale.id
  form.key = row.key
  form.value = row.value
  form.tags = row.tags.map(t => t.id)
  Object.keys(formErrors).forEach(k => delete formErrors[k])
  dialogVisible.value = true
}

function closeDialog() {
  dialogVisible.value = false
}

async function save() {
  Object.keys(formErrors).forEach(k => delete formErrors[k])
  try {
    await store.save(form)
    dialogVisible.value = false
    fetchData()
  } catch (err: any) {
    // Laravel returns { errors: { field: ['msg'] } }
    const e = err.response?.data?.errors || {}
    Object.assign(formErrors, e)
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

function exportJson() {
  store.export()
}
</script>

<style scoped>
.page-header {
  margin-bottom: 16px;
}
.search-input {
  width: 200px;
  margin-right: 12px;
}
.pagination-wrapper {
  margin-top: 16px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.export-btn {
  margin-left: 16px;
}
</style>
