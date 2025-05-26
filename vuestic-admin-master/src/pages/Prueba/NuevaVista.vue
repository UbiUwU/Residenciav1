<template>
  <div class="nueva-vista">
    <!-- Título de la vista -->
    <h1 class="va-h4 mb-4">{{ titulo }}</h1>
    
    <!-- Contenido principal -->
    <va-card>
      <va-card-content>
        <p>Esta es mi nueva vista integrada correctamente con el AppLayout.</p>
        
        <!-- Ejemplo de componente Vuestic -->
        <va-button @click="mostrarMensaje" class="mt-4">
          Probar funcionamiento
        </va-button>
      </va-card-content>
    </va-card>
  </div>
</template>

<script setup lang="ts">
// Importaciones básicas (opcional)
import { ref } from 'vue'
import { useRouter } from 'vue-router'

// Datos reactivos
const titulo = ref('Mi Nueva Vista')
const router = useRouter()

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