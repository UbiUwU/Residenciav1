<template>
  <!-- Título de la vista -->
  <h1 class="va-h4 mb-4">Mis evidencias</h1>
  <div class="evidencias-container">
    <VaCard>
      <VaCardTitle class="flex justify-between items-center">
        <VaBreadcrumbs>
          <VaBreadcrumbsItem label="Inicio" to="/" />
          <VaBreadcrumbsItem label="Evidencias" active />
        </VaBreadcrumbs>
      </VaCardTitle>

      <VaCardContent>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <VaCard
            v-for="materia in materias"
            :key="materia.id"
            class="materia-card"
            @click="navigateToMateria(materia.id)"
          >
            <VaCardContent class="text-center">
              <VaIcon name="folder" size="4rem" color="var(--va-primary)" />
              <h3 class="va-h5 mt-4">{{ materia.nombre }}</h3>
              <p class="text-sm text-gray-500">{{ materia.archivosCount }} archivos</p>
            </VaCardContent>
          </VaCard>
        </div>
      </VaCardContent>
    </VaCard>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const materias = ref([
  { id: 1, nombre: 'Taller de Base de Datos', archivosCount: 0 },
  { id: 2, nombre: 'Tópicos de Programación', archivosCount: 0 },
  { id: 3, nombre: 'Fundamentos de Programación', archivosCount: 0 },
  { id: 4, nombre: 'Cultura Empresarial', archivosCount: 0 },
  { id: 5, nombre: 'Ingeniería de Software', archivosCount: 0 },
])

const navigateToMateria = (materiaId) => {
  router.push({ name: 'MateriaEvidenciasView', params: { id: materiaId } })
}
</script>

<style scoped>
.evidencias-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 1rem;
}

.materia-card {
  cursor: pointer;
  transition:
    transform 0.2s,
    box-shadow 0.2s;
}

.materia-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
}
</style>
