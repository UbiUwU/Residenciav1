<template>
  <!-- Título de la vista -->
  <h1 class="va-h4 mb-4">Mis evidencias</h1>
  <div class="evidencias-container">
    <va-card>
      <va-card-title class="flex justify-between items-center">
        <va-breadcrumbs>
          <va-breadcrumbs-item label="Inicio" to="/" />
          <va-breadcrumbs-item label="Evidencias" active />
        </va-breadcrumbs>
      </va-card-title>

      <va-card-content>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <va-card
            v-for="materia in materias"
            :key="materia.id"
            class="materia-card"
            @click="navigateToMateria(materia.id)"
          >
            <va-card-content class="text-center">
              <va-icon name="folder" size="4rem" color="var(--va-primary)" />
              <h3 class="va-h5 mt-4">{{ materia.nombre }}</h3>
              <p class="text-sm text-gray-500">{{ materia.archivosCount }} archivos</p>
            </va-card-content>
          </va-card>
        </div>
      </va-card-content>
    </va-card>
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
