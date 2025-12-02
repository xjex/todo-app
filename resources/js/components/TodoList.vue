<template>
  <div class="bg-white rounded-lg shadow-md p-6">
    <div v-if="todoStore.loading && todoStore.todos.length === 0" class="text-center py-8">
      <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
      <p class="mt-2 text-gray-600">Loading todos...</p>
    </div>

    <div v-else-if="todoStore.error && todoStore.todos.length === 0" class="text-center py-8">
      <p class="text-red-500">{{ todoStore.error }}</p>
      <button
        @click="todoStore.fetchTodos()"
        class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
      >
        Retry
      </button>
    </div>

    <div v-else-if="todoStore.todos.length === 0" class="text-center py-8">
      <p class="text-gray-500 text-lg">No todos found. Create your first todo!</p>
    </div>

    <div v-else class="space-y-3">
      <TodoItem
        v-for="todo in todoStore.todos"
        :key="todo.id"
        :todo="todo"
        @edit="handleEdit"
        @delete="handleDelete"
        @toggle="handleToggle"
      />
    </div>
  </div>
</template>

<script setup>
import { useTodoStore } from '../stores/todo';
import { useToastStore } from '../stores/toast';
import TodoItem from './TodoItem.vue';

const todoStore = useTodoStore();
const toastStore = useToastStore();

const handleEdit = (todo) => {
  emit('edit', todo);
};

const handleDelete = async (id) => {
  if (confirm('Are you sure you want to delete this todo?')) {
    const result = await todoStore.deleteTodo(id);
    if (result.success) {
      toastStore.success('Todo deleted successfully!');
    } else {
      toastStore.error(todoStore.error || 'Failed to delete todo. Please try again.');
    }
  }
};

const handleToggle = async (id) => {
  const result = await todoStore.toggleTodo(id);
  if (result.success) {
    const todo = todoStore.todos.find(t => t.id === id);
    if (todo) {
      toastStore.success(todo.completed ? 'Todo marked as completed!' : 'Todo marked as incomplete!');
    }
  } else {
    toastStore.error(todoStore.error || 'Failed to update todo status. Please try again.');
  }
};

const emit = defineEmits(['edit', 'delete', 'toggle']);
</script>

