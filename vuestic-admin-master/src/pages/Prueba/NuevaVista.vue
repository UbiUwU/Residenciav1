<template>
  <div class="nueva-vista">
    <!-- Título de la vista -->
    <h1 class="va-h4 mb-4">{{ titulo }}</h1>

    <!-- Contenido principal -->
    <VaCard>
      <VaCardContent>
        <p>Esta es mi nueva vista integrada correctamente con el AppLayout.</p>

        <!-- Ejemplo de componente Vuestic -->
        <VaButton class="mt-4" @click="mostrarMensaje"> Probar funcionamiento </VaButton>
      </VaCardContent>
    </VaCard>
  </div>
</template>

<script setup lang="ts">
// Importaciones básicas (opcional)
import { ref } from 'vue'

// Datos reactivos
const titulo = ref('Mi Nueva Vista')

// Métodos
const mostrarMensaje = () => {
  console.log('La vista funciona correctamente!')

  // Ejemplo de navegación (opcional)
  // router.push({ name: 'dashboard' })
}
</script>

<style scoped>
.nueva-vista {
  padding: 0.5rem;
}

/* Puedes añadir estilos personalizados aquí */
</style>
