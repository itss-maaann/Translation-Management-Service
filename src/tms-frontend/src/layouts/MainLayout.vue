<template>
  <div class="min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white shadow">
      <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
        <h1 class="text-lg font-semibold">Translation Manager</h1>
        <button
          @click="handleLogout"
          class="text-red-600 hover:text-red-800 font-medium"
        >
          Logout
        </button>
      </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 container mx-auto p-4">
      <slot />
    </main>
  </div>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

const auth = useAuthStore();
const router = useRouter();

function handleLogout() {
  auth.logout();
  router.push('/login');
}
</script>
