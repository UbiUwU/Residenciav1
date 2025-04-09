<template>
  <RouterView />
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import { useColors } from 'vuestic-ui'

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
