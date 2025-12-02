<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Toast Container -->
    <ToastContainer />

    <!-- Router View -->
    <router-view />
  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from './stores/auth';
import { useTodoStore } from './stores/todo';
import ToastContainer from './components/ToastContainer.vue';

const router = useRouter();
const authStore = useAuthStore();
const todoStore = useTodoStore();

onMounted(async () => {
  // Check if user is authenticated on app load
  if (authStore.token) {
    await authStore.fetchUser();
    if (!authStore.isAuthenticated && router.currentRoute.value.meta.requiresAuth) {
      router.push('/login');
    }
  }
});
</script>

