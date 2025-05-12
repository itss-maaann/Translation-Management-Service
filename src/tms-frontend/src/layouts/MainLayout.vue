<template>
  <el-container style="height: 100vh">
    <!-- Header with Navigation -->
    <el-header height="60px" class="header-bar">
      <div class="nav-wrapper">
        <el-menu
          mode="horizontal"
          :default-active="active"
          router
          class="nav-menu"
        >
          <el-menu-item index="/">Home</el-menu-item>
          <el-menu-item index="/locales">Locales</el-menu-item>
          <el-menu-item index="/tags">Tags</el-menu-item>
          <el-menu-item index="/translations">Translations</el-menu-item>
          <el-menu-item index="/docs">
          <a href="/api/documentation" target="_blank" rel="noopener">
            API Docs
          </a>
        </el-menu-item>
        </el-menu>

        <el-button
          type="danger"
          plain
          @click="handleLogout"
          class="logout-btn"
        >
          Logout
        </el-button>
      </div>
    </el-header>

    <!-- Main Content -->
    <el-main class="main-content">
      <slot />
    </el-main>
  </el-container>
</template>

<script setup lang="ts">
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const route = useRoute()
const auth = useAuthStore()

// keep menu in sync with route
const active = route.path

function handleLogout() {
  auth.logout()
}
</script>

<style scoped>
.header-bar {
  background-color: #ffffff;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  padding: 0 24px;
  display: flex;
  align-items: center;
}

.nav-wrapper {
  display: flex;
  width: 100%;
  align-items: center;
  justify-content: space-between;
}

.nav-menu {
  flex: 1;
  border-bottom: none;
}

.logout-btn {
  margin-left: auto;
}

.main-content {
  padding: 24px;
  background-color: #f5f7fa; /* subtle background */
}
</style>
