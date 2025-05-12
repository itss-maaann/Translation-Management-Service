<template>
  <div class="data-table">
    <!-- Header slot for page-specific actions -->
    <div class="data-table__header">
      <slot name="header" />
    </div>

    <!-- Display errors if any -->
    <el-alert
      v-if="error"
      :title="error"
      type="error"
      show-icon
      class="mb-4"
    />

    <!-- Table and pagination wrapped in skeleton for loading state -->
    <el-skeleton :loading="loading" animated>
      <el-table
        :data="rows"
        stripe
        style="width: 100%"
        @sort-change="onSortChange"
      >
        <el-table-column
          v-for="col in columns"
          :key="col.prop"
          :prop="col.prop"
          :label="col.label"
          :sortable="col.sortable || false"
          v-bind="col.attrs"
        >
          <template v-if="col.slot" #default="scope">
            <slot :name="col.slot" v-bind="scope" />
          </template>
        </el-table-column>
      </el-table>

      <!-- Pagination controls -->
      <div class="data-table__pagination">
        <el-pagination
          @current-change="onPageChange"
          :current-page="pagination.page"
          :page-size="pagination.perPage"
          :total="pagination.total"
          background
          layout="prev, pager, next"
        />
      </div>
    </el-skeleton>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { defineProps, defineEmits } from 'vue'

interface Column {
  prop: string
  label: string
  sortable?: boolean
  slot?: string
  attrs?: Record<string, any>
}

const props = defineProps<{
  columns: Column[]
  rows: any[]
  loading: boolean
  error: string | null
  pagination: { page: number; perPage: number; total: number }
}>()

const emit = defineEmits<{
  (e: 'sort-change', payload: { prop: string; order: string }): void
  (e: 'page-change', page: number): void
}>()

function onSortChange({ prop, order }: { prop: string; order: string }) {
  emit('sort-change', { prop, order })
}

function onPageChange(page: number) {
  emit('page-change', page)
}
</script>

<style scoped>
.data-table__header {
  margin-bottom: 16px;
}
.data-table__pagination {
  margin-top: 16px;
  text-align: right;
}
</style>
