<template>
  <div class="login-container">
    <el-card class="mx-auto" style="max-width: 400px;">
      <h2 class="text-center mb-6">Sign In</h2>

      <el-alert
        v-if="errorMessage"
        :title="errorMessage"
        type="error"
        class="mb-4"
        show-icon
      />

      <el-form :model="form" status-icon @submit.prevent="onSubmit">
        <el-form-item prop="email">
          <el-input
            v-model="form.email"
            placeholder="Email"
            autocomplete="username"
          />
        </el-form-item>

        <el-form-item prop="password">
          <el-input
            v-model="form.password"
            type="password"
            placeholder="Password"
            autocomplete="current-password"
          />
        </el-form-item>

        <el-form-item>
          <el-button
            type="primary"
            :loading="loading"
            native-type="submit"
            class="w-full"
          >
            Sign In
          </el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

interface LoginForm {
  email: string
  password: string
}

const form = reactive<LoginForm>({
  email: '',
  password: '',
})

const loading = ref(false)
const errorMessage = ref('')
const auth = useAuthStore()
const router = useRouter()

async function onSubmit() {
  loading.value = true
  errorMessage.value = ''
  try {
    await auth.login(form.email, form.password)
    router.push('/')
  } catch (e: any) {
    errorMessage.value = e.response?.data?.message ?? 'Invalid credentials'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.login-container {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #f5f7fa;
  padding: 2rem;
}
.text-center {
  text-align: center;
}
.mb-6 {
  margin-bottom: 1.5rem;
}
</style>
