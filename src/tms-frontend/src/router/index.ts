import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

import HomeView from '@/views/HomeView.vue';
import LoginPage from '@/pages/LoginPage.vue';
import LocaleListPage from '@/pages/Locales/LocaleListPage.vue';
import TagListPage from '@/pages/Tags/TagListPage.vue';
import TranslationListPage from '@/pages/Translations/TranslationListPage.vue';

const routes = [
  { path: '/login', component: LoginPage },
  { path: '/', component: HomeView, meta: { requiresAuth: true } },
  { path: '/locales', component: LocaleListPage, meta: { requiresAuth: true } },
  { path: '/tags', component: TagListPage, meta: { requiresAuth: true } },
  { path: '/translations', component: TranslationListPage, meta: { requiresAuth: true } },
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

router.beforeEach((to, from, next) => {
  const auth = useAuthStore();
  if (to.meta.requiresAuth && !auth.token) {
    return next('/login');
  }
  next();
});

export default router;
