<template>
  <div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">
      {{ editingTodo ? 'Edit Todo' : 'Add New Todo' }}
    </h2>
    
    <form @submit.prevent="handleSubmit" class="space-y-4">
      <div>
        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
          Title <span class="text-red-500">*</span>
        </label>
        <input
          id="title"
          v-model="form.title"
          type="text"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          :class="{ 'border-red-500': errors.title }"
          placeholder="Enter todo title"
        />
        <p v-if="errors.title" class="mt-1 text-sm text-red-500">
          {{ errors.title[0] }}
        </p>
      </div>

      <div>
        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
          Description
        </label>
        <textarea
          id="description"
          v-model="form.description"
          rows="3"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          placeholder="Enter todo description"
        ></textarea>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label for="category" class="block text-sm font-medium text-gray-700 mb-1">
            Category
          </label>
          <input
            id="category"
            v-model="form.category"
            type="text"
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="e.g., Work, Personal"
          />
        </div>

        <div>
          <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">
            Due Date
          </label>
          <input
            id="due_date"
            v-model="form.due_date"
            type="date"
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          />
        </div>
      </div>

      <div class="flex gap-3">
        <button
          type="submit"
          :disabled="loading"
          class="flex-1 bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
        >
          {{ loading ? 'Saving...' : editingTodo ? 'Update Todo' : 'Add Todo' }}
        </button>
        
        <button
          v-if="editingTodo"
          type="button"
          @click="cancelEdit"
          class="px-6 py-2 border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors"
        >
          Cancel
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, reactive, watch } from 'vue';
import { useTodoStore } from '../stores/todo';
import { useToastStore } from '../stores/toast';

const props = defineProps({
  editingTodo: {
    type: Object,
    default: null,
  },
});

const emit = defineEmits(['todo-created', 'todo-updated', 'cancel-edit']);

const todoStore = useTodoStore();
const toastStore = useToastStore();
const loading = ref(false);
const errors = ref({});

const form = reactive({
  title: '',
  description: '',
  category: '',
  due_date: '',
});

const resetForm = () => {
  form.title = '';
  form.description = '';
  form.category = '';
  form.due_date = '';
  errors.value = {};
};

watch(() => props.editingTodo, (newTodo) => {
  if (newTodo) {
    form.title = newTodo.title || '';
    form.description = newTodo.description || '';
    form.category = newTodo.category || '';
    form.due_date = newTodo.due_date || '';
  } else {
    resetForm();
  }
}, { immediate: true });

const handleSubmit = async () => {
  errors.value = {};
  loading.value = true;

  const todoData = {
    title: form.title,
    description: form.description || null,
    category: form.category || null,
    due_date: form.due_date || null,
  };

  let result;
  
  if (props.editingTodo) {
    result = await todoStore.updateTodo(props.editingTodo.id, todoData);
    if (result.success) {
      toastStore.success('Todo updated successfully!');
      emit('todo-updated');
      resetForm();
    } else {
      errors.value = result.errors;
      if (result.errors && Object.keys(result.errors).length > 0) {
        const firstError = Object.values(result.errors)[0];
        toastStore.error(Array.isArray(firstError) ? firstError[0] : firstError);
      } else {
        toastStore.error('Failed to update todo. Please try again.');
      }
    }
  } else {
    result = await todoStore.createTodo(todoData);
    if (result.success) {
      toastStore.success('Todo created successfully!');
      emit('todo-created');
      resetForm();
    } else {
      errors.value = result.errors;
      if (result.errors && Object.keys(result.errors).length > 0) {
        const firstError = Object.values(result.errors)[0];
        toastStore.error(Array.isArray(firstError) ? firstError[0] : firstError);
      } else {
        toastStore.error('Failed to create todo. Please try again.');
      }
    }
  }

  loading.value = false;
};

const cancelEdit = () => {
  resetForm();
  emit('cancel-edit');
};
</script>

