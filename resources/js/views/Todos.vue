<template>
  <div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
      <header class="mb-8 flex justify-between items-center">
        <div>
          <h1 class="text-4xl font-bold text-gray-800 mb-2">Todo App</h1>
        </div>
        <div class="flex items-center gap-4">
          <span class="text-sm text-gray-600"
            >Hello, {{ authStore.user?.name }}</span
          >
          <button
            @click="handleLogout"
            class="px-4 py-2 text-sm text-red-600 hover:text-red-800 border border-red-300 rounded-md hover:bg-red-50 transition-colors"
          >
            Logout
          </button>
        </div>
      </header>

      <TodoForm
        :editing-todo="editingTodo"
        @todo-created="handleTodoCreated"
        @todo-updated="handleTodoUpdated"
        @cancel-edit="handleCancelEdit"
      />

      <div class="mt-8">
        <TodoFilters />
        <TodoList @edit="handleEdit" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import { useTodoStore } from "../stores/todo";
import { useAuthStore } from "../stores/auth";
import { useToastStore } from "../stores/toast";
import TodoForm from "../components/TodoForm.vue";
import TodoList from "../components/TodoList.vue";
import TodoFilters from "../components/TodoFilters.vue";

const router = useRouter();
const todoStore = useTodoStore();
const authStore = useAuthStore();
const toastStore = useToastStore();
const editingTodo = ref(null);

const handleLogout = async () => {
  await authStore.logout();
  toastStore.success("Logged out successfully!");
  router.push("/login");
};

const handleTodoCreated = () => {
  todoStore.fetchTodos();
};

const handleTodoUpdated = () => {
  editingTodo.value = null;
  todoStore.fetchTodos();
};

const handleEdit = (todo) => {
  editingTodo.value = todo;
};

const handleCancelEdit = () => {
  editingTodo.value = null;
};

onMounted(() => {
  todoStore.fetchTodos();
});
</script>
