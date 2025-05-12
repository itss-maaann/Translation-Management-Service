<template>
  <main-layout>
    <el-row :gutter="20" class="home-cards">
      <el-col :xs="24" :sm="12" :md="8" v-for="item in cards" :key="item.title">
        <el-card shadow="hover" class="home-card">
          <div class="card-number">{{ item.count }}</div>
          <div class="card-content">
            <h3>{{ item.title }}</h3>
            <p>{{ item.desc }}</p>
          </div>
          <template #footer>
            <el-button type="primary" @click="go(item.route)">
              Go to {{ item.title }}
            </el-button>
          </template>
        </el-card>
      </el-col>
    </el-row>
  </main-layout>
</template>

<script setup lang="ts">
import { onMounted, reactive } from 'vue'
import { useRouter } from 'vue-router'
import MainLayout from '@/layouts/MainLayout.vue'
import { useLocaleStore } from '@/stores/locale'
import { useTagStore } from '@/stores/tag'
import { useTranslationStore } from '@/stores/translation'

const router = useRouter()

// instantiate stores
const localeStore = useLocaleStore()
const tagStore = useTagStore()
const translationStore = useTranslationStore()

// reactive cards array, we'll fill counts later
const cards = reactive([
  { title: 'Locales', desc: 'Manage your supported languages and regions.', route: '/locales', count: 0 },
  { title: 'Tags', desc: 'Organize translations by tags.', route: '/tags', count: 0 },
  { title: 'Translations', desc: 'Create, edit & export your translation entries.', route: '/translations', count: 0 },
])

onMounted(async () => {
  // fetch only metadata by requesting 1 per page — we only need total
  await localeStore.fetch({ perPage: 1 })
  cards[0].count = localeStore.pagination.total

  await tagStore.fetch({ perPage: 1 })
  cards[1].count = tagStore.pagination.total

  await translationStore.fetch({ perPage: 1 })
  cards[2].count = translationStore.pagination.total
})

function go(path: string) {
  router.push(path)
}
</script>

<style scoped>
.home-cards {
  margin: 20px 0;
}

.home-card {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  height: 200px;
  position: relative;
}

.card-number {
  position: absolute;
  top: 16px;
  right: 16px;
  font-size: 2rem;
  font-weight: bold;
  color: #409EFF; /* Element Plus primary */
}

.card-content {
  margin-top: 24px; /* give space below the count */
}

.card-content h3 {
  margin: 0;
  font-size: 1.25rem;
  font-weight: 600;
}

.card-content p {
  margin: 8px 0 0;
  color: #666;
  font-size: 0.95rem;
}
</style>
