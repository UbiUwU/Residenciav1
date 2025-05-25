<template>
  <RouterView />
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import { useColors } from 'vuestic-ui'
import { useAuthStore } from './services/auth'

onMounted(() => {
  const savedLocale = localStorage.getItem('locale')
  if (savedLocale) {
    const { locale } = useI18n()
    locale.value = savedLocale
  }

  const savedTheme = localStorage.getItem('theme')
  if (savedTheme) {
    const { applyPreset } = useColors()
    applyPreset(savedTheme)
  }
})

const auth = useAuthStore()

onMounted(() => {
  auth.initialize() // Carga token, usuario y maestro desde sessionStorage
})

</script>

<style lang="scss">
#app {
  font-family: 'Inter', Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

body {
  margin: 0;
  min-width: 20rem;
}
</style>
