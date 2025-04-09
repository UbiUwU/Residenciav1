<template>
  <div class="role-container">
    <template v-if="role === 'admin'">
      <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" :class="iconClass">
        <circle cx="12" cy="8" r="4"></circle>
        <path d="M6 21v-2a6 6 0 0 1 12 0v2"></path>
      </svg>
      <span :class="textClass">Admin</span>
    </template>

    <template v-else-if="role === 'maestro'">
      <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" :class="iconClass">
        <path d="M3 8l9-5 9 5-9 5-9-5z"></path>
        <path d="M3 8v6a9 9 0 0 0 18 0V8"></path>
        <path d="M21 12a9 9 0 0 1-18 0"></path>
      </svg>  
      <span :class="textClass">Maestro</span>
    </template>
  </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { useColors } from 'vuestic-ui'

// Obtén el tema actual
const { currentPresetName } = useColors()

const role = "admin" // Cambia a "maestro" según el caso

// Computed para determinar si estamos en modo oscuro o claro
const isDarkMode = computed(() => currentPresetName.value === 'dark')

// Clases dinámicas para los iconos y texto
const iconClass = computed(() => (isDarkMode.value ? 'icon-dark' : 'icon-light'))
const textClass = computed(() => (isDarkMode.value ? 'text-dark' : 'text-light'))
</script>

<style scoped>
.role-container {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 18px;
  font-weight: bold;
}

/* Clases para modo claro */
.icon-light, .text-light {
  color: #333;
}

/* Clases para modo oscuro */
.icon-dark, .text-dark {
  color: #fff;
}
</style>
