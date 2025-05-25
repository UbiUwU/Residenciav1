<template>
  <div class="materia-evidencias-container">
    <va-card>
      <va-card-title class="flex justify-between items-center">
        <div>
          <va-button 
            preset="plain" 
            icon="arrow_back" 
            @click="router.go(-1)" 
            class="mr-2"
          />
          
        </div>
        <va-breadcrumbs>
          <va-breadcrumbs-item label="Inicio" to="/" />
          <va-breadcrumbs-item label="Evidencias" to="/evidencias" />
          <va-breadcrumbs-item :label="materiaActual.nombre" active />
        </va-breadcrumbs>
      </va-card-title>

      <va-card-content>
        <!-- Componente para subir archivos -->
        <FileUploader 
          :materia-id="materiaActual.id" 
          @file-uploaded="handleFileUploaded"
        />

        <!-- Lista de archivos -->
        <va-data-table
          :items="archivos"
          :columns="columns"
          class="mt-6"
        >
          <template #cell(actions)="{ row }">
            <va-button 
              icon="download" 
              preset="plain" 
              size="small" 
              @click="descargarArchivo(row)"
            />
            <va-button 
              icon="delete" 
              preset="plain" 
              size="small" 
              color="danger" 
              @click="confirmarEliminar(row)"
              class="ml-2"
            />
          </template>
        </va-data-table>
      </va-card-content>
    </va-card>

    <!-- Modal de confirmación para eliminar -->
    <va-modal v-model="showDeleteModal" hide-default-actions>
      <h3 class="va-h5">¿Eliminar archivo?</h3>
      <p class="my-4">¿Estás seguro de que deseas eliminar {{ archivoToDelete?.nombre }}?</p>
      <div class="flex justify-end gap-4">
        <va-button preset="plain" @click="showDeleteModal = false">
          Cancelar
        </va-button>
        <va-button color="danger" @click="eliminarArchivo">
          Eliminar
        </va-button>
      </div>
    </va-modal>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import FileUploader from './components/FileUploader.vue'

const router = useRouter()
const route = useRoute()

// Datos de ejemplo (en una app real vendrían de una API)
const materias = ref([
  { id: 1, nombre: 'Taller de Base de Datos', archivos: [] },
  { id: 2, nombre: 'Tópicos de Programación', archivos: [] },
  { id: 3, nombre: 'Fundamentos de Programación', archivos: [] },
  { id: 4, nombre: 'Cultura Empresarial', archivos: [] },
  { id: 5, nombre: 'Ingeniería de Software', archivos: [] },
])

// Simulación de archivos existentes
const archivosEjemplo = [
  { id: 1, materiaId: 1, nombre: 'Práctica 1 - Modelo ER.pdf', tipo: 'pdf', fecha: '2023-05-15', size: '2.4 MB' },
  { id: 2, materiaId: 1, nombre: 'Documentación SQL.docx', tipo: 'word', fecha: '2023-05-20', size: '1.8 MB' },
  { id: 3, materiaId: 2, nombre: 'Proyecto Final.pptx', tipo: 'powerpoint', fecha: '2023-06-01', size: '5.2 MB' },
]

const materiaActual = computed(() => {
  return materias.value.find(m => m.id === parseInt(route.params.id)) || {}
})

const archivos = ref([])
const showDeleteModal = ref(false)
const archivoToDelete = ref(null)

const columns = [
  { key: 'nombre', label: 'Nombre', sortable: true },
  { key: 'tipo', label: 'Tipo', sortable: true },
  { key: 'fecha', label: 'Fecha', sortable: true },
  { key: 'size', label: 'Tamaño', sortable: true },
  { key: 'actions', label: 'Acciones' }
]

onMounted(() => {
  // Simular carga de archivos
  setTimeout(() => {
    archivos.value = archivosEjemplo.filter(a => a.materiaId === parseInt(route.params.id))
  }, 500)
})

const handleFileUploaded = (nuevoArchivo) => {
  archivos.value.unshift({
    id: Math.max(...archivos.value.map(a => a.id), 0) + 1,
    materiaId: materiaActual.value.id,
    nombre: nuevoArchivo.name,
    tipo: nuevoArchivo.type.split('/')[1] || 'file',
    fecha: new Date().toISOString().split('T')[0],
    size: `${(nuevoArchivo.size / 1024 / 1024).toFixed(1)} MB`
  })
}

const descargarArchivo = (archivo) => {
  // En una app real, aquí iría la lógica para descargar el archivo
  console.log('Descargando:', archivo.nombre)
  // Simulación de descarga
  const link = document.createElement('a')
  link.href = '#' // URL real del archivo
  link.download = archivo.nombre
  link.click()
}

const confirmarEliminar = (archivo) => {
  archivoToDelete.value = archivo
  showDeleteModal.value = true
}

const eliminarArchivo = () => {
  if (archivoToDelete.value) {
    archivos.value = archivos.value.filter(a => a.id !== archivoToDelete.value.id)
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