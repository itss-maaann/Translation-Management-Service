import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

// Views & Pages
import HomeView from '@/views/HomeView.vue'
import LoginPage from '@/pages/LoginPage.vue'
import LocaleListPage from '@/pages/Locales/LocaleListPage.vue'
import TagListPage from '@/pages/Tags/TagListPage.vue'
import TranslationListPage from '@/pages/Translations/TranslationListPage.vue'

const routes = [
  { path: '/login', component: LoginPage },
  { path: '/', component: HomeView, meta: { requiresAuth: true } },
  { path: '/locales', component: LocaleListPage, meta: { requiresAuth: true } },
  { path: '/tags', component: TagListPage, meta: { requiresAuth: true } },
  { path: '/translations', component: TranslationListPage, meta: { requiresAuth: true } },
  { path: '/:pathMatch(.*)*', redirect: '/' },
  // { path: '/docs', component: swagger, meta: { requiresAuth: true } },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to, from, next) => {
  const auth = useAuthStore()
  // Redirect authenticated users away from login
  if (to.path === '/login' && auth.isAuthenticated) {
    return next('/')
  }
  // Protect routes that require authentication
  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    return next('/login')
  }
  next()
})

export default router
