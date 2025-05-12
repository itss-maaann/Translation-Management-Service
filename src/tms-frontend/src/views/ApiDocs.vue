<template>
  <main-layout>
    <div id="swagger-container" style="height: 100vh;"></div>
  </main-layout>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import SwaggerUI from 'swagger-ui-dist/swagger-ui-es-bundle.js'
import 'swagger-ui-dist/swagger-ui.css'

onMounted(() => {
  SwaggerUI({
    dom_id: '#swagger-container',
    url: import.meta.env.VITE_API_URL + '/documentation/openapi.json',
  })
})
</script>

<style scoped>
</style>
