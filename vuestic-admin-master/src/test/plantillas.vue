<script setup>
import { ref, onMounted } from 'vue'
import { useToast } from 'vuestic-ui'
import api from '../services/api'

const { init } = useToast()

// Estado del formulario
const form = ref({
  nombre: '',
  descripcion: '',
  tipo: 'avance',
  archivo: null,
})

// Opciones para el select de tipos
const tipos = [
  { text: 'Avance', value: 'avance' },
  { text: 'Instrumentación', value: 'instrumentacion' },
  { text: 'Reporte Final', value: 'reporte_final' },
]

// Estado de la lista de plantillas
const plantillas = ref([])
const loading = ref({
  list: false,
  submit: false,
  download: null,
})

// Validación del formulario
const validate = () => {
  if (!form.value.nombre.trim()) {
    init({ message: 'El nombre es requerido', color: 'danger' })
    return false
  }
  if (!form.value.archivo) {
    init({ message: 'Debe seleccionar un archivo', color: 'danger' })
    return false
  }
  return true
}

// Manejar cambio de archivo
const handleFileChange = (files) => {
  form.value.archivo = files[0] // Tomamos el primer archivo
}

// Resetear formulario
const resetForm = () => {
  form.value = {
    nombre: '',
    descripcion: '',
    tipo: 'avance',
    archivo: null,
  }
}

// Enviar formulario
const submitForm = async () => {
  if (!validate()) return

  loading.value.submit = true

  try {
    const formData = new FormData()
    formData.append('nombre', form.value.nombre)
    formData.append('descripcion', form.value.descripcion)
    formData.append('tipo', form.value.tipo)
    formData.append('archivo', form.value.archivo)

    await api.crearPlantilla(formData)

    init({ message: 'Plantilla creada con éxito', color: 'success' })
    resetForm()
    fetchPlantillas()
  } catch (error) {
    const message = error.response?.data?.message || 'Error al crear plantilla'
    init({ message, color: 'danger' })
  } finally {
    loading.value.submit = false
  }
}

// Obtener plantillas
const fetchPlantillas = async () => {
  loading.value.list = true
  try {
    const response = await api.getPlantillas()
    plantillas.value = response.data.map((p) => ({
      ...p,
      tipoTexto: tipos.find((t) => t.value === p.tipo)?.text || p.tipo,
    }))
  } catch (error) {
    init({ message: 'Error al cargar plantillas', color: 'danger' })
  } finally {
    loading.value.list = false
  }
}

// Descargar plantilla
const downloadPlantilla = async (id) => {
  loading.value.download = id
  try {
    const response = await api.descargarArchivoPlantilla(id)
    const url = window.URL.createObjectURL(new Blob([response.data]))
    const link = document.createElement('a')
    link.href = url
    link.setAttribute('download', `plantilla_${id}.docx`)
    document.body.appendChild(link)
    link.click()
    link.parentNode.removeChild(link)
    window.URL.revokeObjectURL(url)
  } catch (error) {
    init({ message: 'Error al descargar plantilla', color: 'danger' })
  } finally {
    loading.value.download = null
  }
}

// Cargar datos iniciales
onMounted(fetchPlantillas)
</script>

<template>
  <div class="grid gap-6">
    <!-- Card de formulario -->
    <VaCard>
      <VaCardTitle class="mb-4">Nueva Plantilla</VaCardTitle>
      <VaCardContent>
        <VaForm class="space-y-4" @submit.prevent="submitForm">
          <VaInput
            v-model="form.nombre"
            label="Nombre"
            placeholder="Ingrese el nombre de la plantilla"
            :rules="[(value) => !!value.trim() || 'Campo requerido']"
          />

          <VaTextarea
            v-model="form.descripcion"
            label="Descripción"
            placeholder="Descripción de la plantilla"
            autosize
            :min-rows="2"
          />

          <VaSelect v-model="form.tipo" label="Tipo de plantilla" :options="tipos" />

          <VaFileUpload
            v-model="form.archivo"
            label="Archivo DOCX"
            file-types=".docx"
            dropzone
            @update:modelValue="handleFileChange"
          />

          <div class="flex justify-end gap-3 pt-2">
            <VaButton type="button" color="secondary" :disabled="loading.submit" @click="resetForm"> Limpiar </VaButton>

            <VaButton type="submit" :loading="loading.submit" :disabled="loading.submit"> Guardar Plantilla </VaButton>
          </div>
        </VaForm>
      </VaCardContent>
    </VaCard>

    <!-- Card de listado -->
    <VaCard>
      <VaCardTitle class="mb-4">Plantillas Registradas</VaCardTitle>
      <VaCardContent>
        <VaDataTable
          :items="plantillas"
          :columns="[
            { key: 'nombre', label: 'Nombre', sortable: true },
            { key: 'tipoTexto', label: 'Tipo', sortable: true },
            { key: 'descripcion', label: 'Descripción' },
            { key: 'createdAt', label: 'Fecha', sortable: true },
            { key: 'actions', label: 'Acciones' },
          ]"
          :loading="loading.list"
        >
          <template #cell(actions)="{ row }">
            <VaButton size="small" :loading="loading.download === row.id" @click="downloadPlantilla(row.id)">
              Descargar
            </VaButton>
          </template>

          <template #loading>
            <div class="flex items-center justify-center p-4">
              <VaProgressCircle indeterminate size="small" />
              <span class="ml-2">Cargando plantillas...</span>
            </div>
          </template>

          <template #no-data>
            <div class="p-4 text-center text-gray-500">No hay plantillas registradas</div>
          </template>
        </VaDataTable>
      </VaCardContent>
    </VaCard>
  </div>
</template>

<style scoped>
.va-card {
  border-radius: 0.5rem;
}

.va-card-title {
  font-size: 1.25rem;
  font-weight: 600;
}

.va-data-table {
  --va-data-table-thead-background: var(--va-primary);
  --va-data-table-thead-color: white;
}
</style>
