<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
          Create your account
        </h2>
      </div>
      <form class="mt-8 space-y-6" @submit.prevent="handleSubmit">
        <div class="rounded-md shadow-sm space-y-4">
          <div>
            <label for="name" class="sr-only">Name</label>
            <input
              id="name"
              v-model="form.name"
              name="name"
              type="text"
              required
              class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
              :class="{ 'border-red-500': errors.name }"
              placeholder="Full name"
            />
            <p v-if="errors.name" class="mt-1 text-sm text-red-500">
              {{ errors.name[0] }}
            </p>
          </div>
          <div>
            <label for="email" class="sr-only">Email address</label>
            <input
              id="email"
              v-model="form.email"
              name="email"
              type="email"
              autocomplete="email"
              required
              class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
              :class="{ 'border-red-500': errors.email }"
              placeholder="Email address"
            />
            <p v-if="errors.email" class="mt-1 text-sm text-red-500">
              {{ errors.email[0] }}
            </p>
          </div>
          <div>
            <label for="password" class="sr-only">Password</label>
            <input
              id="password"
              v-model="form.password"
              name="password"
              type="password"
              autocomplete="new-password"
              required
              class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
              :class="{ 'border-red-500': errors.password }"
              placeholder="Password"
            />
            <p v-if="errors.password" class="mt-1 text-sm text-red-500">
              {{ errors.password[0] }}
            </p>
          </div>
          <div>
            <label for="password_confirmation" class="sr-only">Confirm Password</label>
            <input
              id="password_confirmation"
              v-model="form.password_confirmation"
              name="password_confirmation"
              type="password"
              autocomplete="new-password"
              required
              class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
              placeholder="Confirm password"
            />
          </div>
        </div>

        <div v-if="authStore.error" class="text-red-500 text-sm text-center">
          {{ authStore.error }}
        </div>

        <div>
          <button
            type="submit"
            :disabled="authStore.loading"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
          >
            {{ authStore.loading ? 'Creating account...' : 'Sign up' }}
          </button>
        </div>

        <div class="text-center">
          <p class="text-sm text-gray-600">
            Already have an account?
            <button
              type="button"
              @click="$emit('switch-to-login')"
              class="font-medium text-blue-600 hover:text-blue-500"
            >
              Sign in
            </button>
          </p>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue';
import { useAuthStore } from '../stores/auth';
import { useToastStore } from '../stores/toast';

const emit = defineEmits(['switch-to-login', 'registered']);

const authStore = useAuthStore();
const toastStore = useToastStore();
const errors = ref({});

const form = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
});

const handleSubmit = async () => {
  errors.value = {};
  const result = await authStore.register(form);
  
  if (result.success) {
    toastStore.success('Account created successfully! Welcome!');
    emit('registered');
  } else {
    errors.value = result.errors || {};
    if (result.errors && Object.keys(result.errors).length > 0) {
      const firstError = Object.values(result.errors)[0];
      toastStore.error(Array.isArray(firstError) ? firstError[0] : firstError);
    } else {
      toastStore.error(authStore.error || 'Registration failed. Please try again.');
    }
  }
};
</script>

