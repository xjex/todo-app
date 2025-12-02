<template>
  <div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-xl font-semibold text-gray-800">Filters</h2>
      <button
        @click="clearFilters"
        class="text-sm text-blue-600 hover:text-blue-800"
      >
        Clear All
      </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
      <!-- Search -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
          Search
        </label>
        <input
          v-model="localFilters.search"
          @input="debounceSearch"
          type="text"
          placeholder="Search todos..."
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        />
      </div>

      <!-- Category Filter -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
          Category
        </label>
        <select
          v-model="localFilters.category"
          @change="applyFilters"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        >
          <option value="">All Categories</option>
          <option v-for="cat in categories" :key="cat" :value="cat">
            {{ cat }}
          </option>
        </select>
      </div>

      <!-- Status Filter -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
          Status
        </label>
        <select
          v-model="localFilters.completed"
          @change="applyFilters"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        >
          <option :value="null">All</option>
          <option :value="true">Completed</option>
          <option :value="false">Incomplete</option>
        </select>
      </div>

      <!-- Sort By -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
          Sort By
        </label>
        <select
          v-model="localFilters.sortBy"
          @change="applyFilters"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        >
          <option value="created_at">Created Date</option>
          <option value="due_date">Due Date</option>
          <option value="title">Title</option>
        </select>
      </div>
    </div>

    <div class="mt-4 flex items-center gap-4">
      <label class="flex items-center">
        <input
          type="radio"
          :value="'desc'"
          v-model="localFilters.sortOrder"
          @change="applyFilters"
          class="mr-2"
        />
        <span class="text-sm text-gray-700">Descending</span>
      </label>
      <label class="flex items-center">
        <input
          type="radio"
          :value="'asc'"
          v-model="localFilters.sortOrder"
          @change="applyFilters"
          class="mr-2"
        />
        <span class="text-sm text-gray-700">Ascending</span>
      </label>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { useTodoStore } from '../stores/todo';

const todoStore = useTodoStore();

const localFilters = ref({
  search: '',
  category: '',
  completed: null,
  sortBy: 'created_at',
  sortOrder: 'desc',
});

const categories = computed(() => todoStore.categories);

let searchTimeout = null;

const debounceSearch = () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    applyFilters();
  }, 500);
};

const applyFilters = () => {
  todoStore.filters = { ...localFilters.value };
  todoStore.fetchTodos();
};

const clearFilters = () => {
  localFilters.value = {
    search: '',
    category: '',
    completed: null,
    sortBy: 'created_at',
    sortOrder: 'desc',
  };
  todoStore.clearFilters();
};

watch(() => todoStore.filters, (newFilters) => {
  localFilters.value = { ...newFilters };
}, { immediate: true });
</script>

