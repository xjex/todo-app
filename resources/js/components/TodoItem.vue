<template>
  <div
    class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow"
    :class="{ 'bg-gray-50 opacity-75': todo.completed }"
  >
    <div class="flex items-start gap-4">
      <input
        type="checkbox"
        :checked="todo.completed"
        @change="handleToggle"
        class="mt-1 w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 cursor-pointer"
      />

      <div class="flex-1">
        <div class="flex items-start justify-between gap-4">
          <div class="flex-1">
            <h3
              class="text-lg font-semibold text-gray-800 mb-1"
              :class="{ 'line-through text-gray-500': todo.completed }"
            >
              {{ todo.title }}
            </h3>
            
            <p
              v-if="todo.description"
              class="text-gray-600 mb-2"
              :class="{ 'line-through text-gray-400': todo.completed }"
            >
              {{ todo.description }}
            </p>

            <div class="flex flex-wrap items-center gap-3 text-sm text-gray-500">
              <span v-if="todo.category" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                {{ todo.category }}
              </span>
              
              <span v-if="todo.due_date" class="inline-flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                {{ formatDate(todo.due_date) }}
                <span
                  v-if="isOverdue(todo.due_date) && !todo.completed"
                  class="ml-1 text-red-500 font-semibold"
                >
                  (Overdue)
                </span>
              </span>

              <span class="text-gray-400">
                Created {{ formatDate(todo.created_at) }}
              </span>
            </div>
          </div>

          <div class="flex gap-2">
            <button
              @click="handleEdit"
              class="p-2 text-blue-600 hover:bg-blue-50 rounded-md transition-colors"
              title="Edit todo"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
            </button>
            
            <button
              @click="handleDelete"
              class="p-2 text-red-600 hover:bg-red-50 rounded-md transition-colors"
              title="Delete todo"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
  todo: {
    type: Object,
    required: true,
  },
});

const emit = defineEmits(['edit', 'delete', 'toggle']);

const handleEdit = () => {
  emit('edit', props.todo);
};

const handleDelete = () => {
  emit('delete', props.todo.id);
};

const handleToggle = () => {
  emit('toggle', props.todo.id);
};

const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  });
};

const isOverdue = (dueDate) => {
  if (!dueDate) return false;
  const today = new Date();
  today.setHours(0, 0, 0, 0);
  const due = new Date(dueDate);
  due.setHours(0, 0, 0, 0);
  return due < today;
};
</script>

