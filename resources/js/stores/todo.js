import { defineStore } from 'pinia';
import axios from 'axios';
import { useToastStore } from './toast';

export const useTodoStore = defineStore('todo', {
  state: () => ({
    todos: [],
    loading: false,
    error: null,
    filters: {
      search: '',
      category: '',
      completed: null,
      sortBy: 'created_at',
      sortOrder: 'desc',
    },
  }),

  getters: {
    categories: (state) => {
      const cats = state.todos
        .map(todo => todo.category)
        .filter(cat => cat !== null && cat !== '');
      return [...new Set(cats)];
    },
  },

  actions: {
    async fetchTodos() {
      this.loading = true;
      this.error = null;
      
      try {
        const params = {};
        
        if (this.filters.search) {
          params.search = this.filters.search;
        }
        
        if (this.filters.category) {
          params.category = this.filters.category;
        }
        
        if (this.filters.completed !== null) {
          params.completed = this.filters.completed;
        }
        
        params.sort_by = this.filters.sortBy;
        params.sort_order = this.filters.sortOrder;

        const response = await axios.get('/todos', { params });
        this.todos = response.data.data;
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to fetch todos';
        console.error('Error fetching todos:', error);
        const toastStore = useToastStore();
        if (error.response?.status === 401) {
          toastStore.error('Session expired. Please login again.');
        } else {
          toastStore.error(this.error);
        }
      } finally {
        this.loading = false;
      }
    },

    async createTodo(todoData) {
      this.loading = true;
      this.error = null;
      
      try {
        const response = await axios.post('/todos', todoData);
        await this.fetchTodos();
        return { success: true, data: response.data.data };
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to create todo';
        return {
          success: false,
          errors: error.response?.data?.errors || {},
        };
      } finally {
        this.loading = false;
      }
    },

    async updateTodo(id, todoData) {
      this.loading = true;
      this.error = null;
      
      try {
        const response = await axios.put(`/todos/${id}`, todoData);
        await this.fetchTodos();
        return { success: true, data: response.data.data };
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to update todo';
        return {
          success: false,
          errors: error.response?.data?.errors || {},
        };
      } finally {
        this.loading = false;
      }
    },

    async deleteTodo(id) {
      this.loading = true;
      this.error = null;
      
      try {
        await axios.delete(`/todos/${id}`);
        await this.fetchTodos();
        return { success: true };
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to delete todo';
        return { success: false };
      } finally {
        this.loading = false;
      }
    },

    async toggleTodo(id) {
      this.loading = true;
      this.error = null;
      
      try {
        await axios.patch(`/todos/${id}/toggle`);
        await this.fetchTodos();
        return { success: true };
      } catch (error) {
        this.error = error.response?.data?.message || 'Failed to toggle todo';
        return { success: false };
      } finally {
        this.loading = false;
      }
    },

    setFilter(filter, value) {
      this.filters[filter] = value;
      this.fetchTodos();
    },

    clearFilters() {
      this.filters = {
        search: '',
        category: '',
        completed: null,
        sortBy: 'created_at',
        sortOrder: 'desc',
      };
      this.fetchTodos();
    },
  },
});

