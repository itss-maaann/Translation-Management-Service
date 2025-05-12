import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import { createPinia } from 'pinia'

import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css'
import { api } from '@/api/axios'

const app = createApp(App)

const token = localStorage.getItem('tms_token')
if (token) api.defaults.headers.common.Authorization = `Bearer ${token}`

app.use(createPinia())
app.use(router)
app.use(ElementPlus)
app.mount('#app')
