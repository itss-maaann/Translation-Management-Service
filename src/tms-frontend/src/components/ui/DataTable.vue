<template>
  <div>
    <div v-if="loading" class="flex justify-center p-4">
      <svg
        class="animate-spin h-8 w-8 text-gray-500"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
      >
        <circle
          class="opacity-25"
          cx="12"
          cy="12"
          r="10"
          stroke="currentColor"
          stroke-width="4"
        ></circle>
        <path
          class="opacity-75"
          fill="currentColor"
          d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"
        ></path>
      </svg>
    </div>
    <table class="min-w-full divide-y divide-gray-200 bg-white">
      <thead class="bg-gray-50">
        <tr>
          <th
            v-for="col in columns"
            :key="col.field"
            @click="col.sortable && onSort(col.field)"
            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            :class="{ 'cursor-pointer': col.sortable, 'cursor-default': !col.sortable }"
          >
            <div class="flex items-center space-x-1">
              <span>{{ col.label }}</span>
              <svg
                v-if="col.sortable"
                xmlns="http://www.w3.org/2000/svg"
                class="h-3 w-3"
                viewBox="0 0 20 20"
                fill="currentColor"
              >
                <path
                  v-if="sortBy === col.field && !sortDesc"
                  d="M5 10l4-4 4 4H5z"
                />
                <path
                  v-else-if="sortBy === col.field && sortDesc"
                  d="M5 10l4 4 4-4H5z"
                />
                <path
                  v-else
                  d="M5 10h8M5 6h8M5 14h8"
                />
              </svg>
            </div>
          </th>
          <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
            Actions
          </th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
        <tr
          v-for="row in rows"
          :key="row.id"
          class="hover:bg-gray-50"
        >
          <td
            v-for="col in columns"
            :key="col.field"
            class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"
          >
            {{ row[col.field] }}
          </td>
          <td class="px-6 py-4 whitespace-nowrap text-right text-sm space-x-2">
            <slot name="actions" :row="row"></slot>
          </td>
        </tr>
      </tbody>
    </table>

    <div class="flex items-center justify-between py-3">
      <div>
        <span class="text-sm text-gray-700">
          Showing
          <span class="font-medium">{{ from }}</span>
          to
          <span class="font-medium">{{ to }}</span>
          of
          <span class="font-medium">{{ pagination.total }}</span>
          results
        </span>
      </div>
      <div class="flex items-center space-x-2">
        <button
          :disabled="pagination.currentPage === 1"
          @click="onPageChange(pagination.currentPage - 1)"
          class="px-3 py-1 bg-white border rounded disabled:opacity-50"
        >
          Previous
        </button>
        <button
          :disabled="pagination.currentPage === lastPage"
          @click="onPageChange(pagination.currentPage + 1)"
          class="px-3 py-1 bg-white border rounded disabled:opacity-50"
        >
          Next
        </button>
      </div>
      <div>
        <select
          v-model.number="localPerPage"
          @change="onPerPageChange"
          class="border rounded p-1"
        >
          <option
            v-for="n in perPageOptions"
            :key="n"
            :value="n"
          >
            {{ n }} / page
          </option>
        </select>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';

type Column = { label: string; field: string; sortable?: boolean };

interface Pagination {
  currentPage: number;
  perPage: number;
  total: number;
}

const props = defineProps<{
  columns: Column[];
  rows: any[];
  loading?: boolean;
  pagination: Pagination;
  sortBy?: string;
  sortDesc?: boolean;
  perPageOptions?: number[];
}>();

const emit = defineEmits<{
  (e: 'page-change', page: number): void;
  (e: 'per-page-change', perPage: number): void;
  (e: 'sort-change', payload: { field: string; desc: boolean }): void;
}>();

const localPerPage = ref(props.pagination.perPage);

const lastPage = computed(() =>
  Math.ceil(props.pagination.total / props.pagination.perPage)
);
const from = computed(() =>
  (props.pagination.currentPage - 1) * props.pagination.perPage + 1
);
const to = computed(() =>
  Math.min(
    props.pagination.total,
    props.pagination.currentPage * props.pagination.perPage
  )
);

function onPageChange(page: number) {
  emit('page-change', page);
}

function onPerPageChange() {
  emit('per-page-change', localPerPage.value);
}

function onSort(field: string) {
  const desc = props.sortBy === field ? !props.sortDesc : false;
  emit('sort-change', { field, desc });
}

const perPageOptions = computed(() =>
  props.perPageOptions ?? [10, 15, 25, 50]
);
</script>
