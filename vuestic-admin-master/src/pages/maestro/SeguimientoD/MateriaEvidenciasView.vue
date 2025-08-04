<template>
  <div class="materia-evidencias-container">
    <VaCard>
      <VaCardTitle class="flex justify-between items-center">
        <div>
          <VaButton preset="plain" icon="arrow_back" class="mr-2" @click="router.go(-1)" />
        </div>
        <VaBreadcrumbs>
          <VaBreadcrumbsItem label="Inicio" to="/" />
          <VaBreadcrumbsItem label="Evidencias" to="/evidencias" />
          <VaBreadcrumbsItem :label="materiaActual.nombre" active />
        </VaBreadcrumbs>
      </VaCardTitle>

      <VaCardContent>
        <!-- Componente para subir archivos -->
        <FileUploader :materia-id="materiaActual.id" @fileUploaded="handleFileUploaded" />

        <!-- Lista de archivos -->
        <VaDataTable :items="archivos" :columns="columns" class="mt-6">
          <template #cell(actions)="{ row }">
            <VaButton icon="download" preset="plain" size="small" @click="descargarArchivo(row)" />
            <VaButton
              icon="delete"
              preset="plain"
              size="small"
              color="danger"
              class="ml-2"
              @click="confirmarEliminar(row)"
            />
          </template>
        </VaDataTable>
      </VaCardContent>
    </VaCard>

    <!-- Modal de confirmación para eliminar -->
    <VaModal v-model="showDeleteModal" hide-default-actions>
      <h3 class="va-h5">¿Eliminar archivo?</h3>
      <p class="my-4">¿Estás seguro de que deseas eliminar {{ archivoToDelete?.nombre }}?</p>
      <div class="flex justify-end gap-4">
        <VaButton preset="plain" @click="showDeleteModal = false"> Cancelar </VaButton>
        <VaButton color="danger" @click="eliminarArchivo"> Eliminar </VaButton>
      </div>
    </VaModal>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import FileUploader from './components/FileUploader.vue'

const router = useRouter()
const route = useRoute()

const materias = ref([
  { id: 1, nombre: 'Taller de Base de Datos', archivos: [] },
  { id: 2, nombre: 'Tópicos de Programación', archivos: [] },
  { id: 3, nombre: 'Fundamentos de Programación', archivos: [] },
  { id: 4, nombre: 'Cultura Empresarial', archivos: [] },
  { id: 5, nombre: 'Ingeniería de Software', archivos: [] },
])

const archivosEjemplo = [
  { id: 1, materiaId: 1, nombre: 'Práctica 1 - Modelo ER.pdf', tipo: 'pdf', fecha: '2023-05-15', size: '2.4 MB' },
  { id: 2, materiaId: 1, nombre: 'Documentación SQL.docx', tipo: 'word', fecha: '2023-05-20', size: '1.8 MB' },
  { id: 3, materiaId: 2, nombre: 'Proyecto Final.pptx', tipo: 'powerpoint', fecha: '2023-06-01', size: '5.2 MB' },
]

const materiaActual = computed(() => {
  return materias.value.find((m) => m.id === parseInt(route.params.id)) || {}
})

const archivos = ref([])
const showDeleteModal = ref(false)
const archivoToDelete = ref(null)

const columns = [
  { key: 'nombre', label: 'Nombre', sortable: true },
  { key: 'tipo', label: 'Tipo', sortable: true },
  { key: 'fecha', label: 'Fecha', sortable: true },
  { key: 'size', label: 'Tamaño', sortable: true },
  { key: 'actions', label: 'Acciones' },
]

onMounted(() => {
  // Simular carga de archivos
  setTimeout(() => {
    archivos.value = archivosEjemplo.filter((a) => a.materiaId === parseInt(route.params.id))
  }, 500)
})

const handleFileUploaded = (nuevoArchivo) => {
  archivos.value.unshift({
    id: Math.max(...archivos.value.map((a) => a.id), 0) + 1,
    materiaId: materiaActual.value.id,
    nombre: nuevoArchivo.name,
    tipo: nuevoArchivo.type.split('/')[1] || 'file',
    fecha: new Date().toISOString().split('T')[0],
    size: `${(nuevoArchivo.size / 1024 / 1024).toFixed(1)} MB`,
  })
}

const descargarArchivo = (archivo) => {
  console.log('Descargando:', archivo.nombre)

  const link = document.createElement('a')
  link.href = '#'
  link.download = archivo.nombre
  link.click()
}

const confirmarEliminar = (archivo) => {
  archivoToDelete.value = archivo
  showDeleteModal.value = true
}

const eliminarArchivo = () => {
  if (archivoToDelete.value) {
    archivos.value = archivos.value.filter((a) => a.id !== archivoToDelete.value.id)
    showDeleteModal.value = false
    archivoToDelete.value = null
  }
}
</script>

<style scoped>
.materia-evidencias-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 1rem;
}
</style>
