<template>
  <div class="role-container">
    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" :class="iconClass">
      <circle cx="12" cy="8" r="4"></circle>
      <path d="M6 21v-2a6 6 0 0 1 12 0v2"></path>
    </svg>
     <!-- Añadimos una clase específica para el texto del correo -->
    <span :class="['email-text', textClass]">{{ userEmail }}</span>
  </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { useColors } from 'vuestic-ui'
import { useAuthStore } from '../stores/auth-store' // Asegúrate de que la ruta sea correcta

// Obtén el tema actual
const { currentPresetName } = useColors()
const authStore = useAuthStore()

// Obtener el email del usuario desde el store de autenticación
const userEmail = computed(() => {
  // Accedemos a user.value.correo según tu interfaz User
  return authStore.user?.correo || 'Usuario no autenticado'
})

// Computed para determinar si estamos en modo oscuro o claro
const isDarkMode = computed(() => currentPresetName.value === 'dark')

// Clases dinámicas para los iconos y texto
const iconClass = computed(() => (isDarkMode.value ? 'icon-dark' : 'icon-light'))
const textClass = computed(() => (isDarkMode.value ? 'text-dark' : 'text-light'))
</script>

<style scoped>
/* Mantén tus estilos existentes */
.role-container {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 18px;
  font-weight: bold;
}

.email-text {
  font-size: 15px; /* Tamaño reducido (original era 18px) */
  /* Opcional: para emails largos */
  max-width: 180px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.icon-light, .text-light {
  color: #333;
}

.icon-dark, .text-dark {
  color: #fff;
}
</style>