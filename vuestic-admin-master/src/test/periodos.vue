<template>
  <VaCard class="periodos-container">
    <VaCardTitle class="header-container">
      <h1 class="va-h1 title-text">Gestión de Períodos Escolares</h1>
      <VaButton color="primary" icon="add" class="add-button" @click="mostrarModalCrear"> Nuevo Período </VaButton>
    </VaCardTitle>

    <VaCardContent class="content-container">
      <!-- Tabla de períodos con mejor estilo -->
      <VaDataTable :items="periodos" :columns="columnas" :loading="cargando" striped hoverable class="periodos-table">
        <template #cell(fecha_inicio)="{ value }">
          {{ formatDate(value) }}
        </template>

        <template #cell(fecha_fin)="{ value }">
          {{ formatDate(value) }}
        </template>

        <template #cell(actions)="{ row }">
          <div class="actions-container">
            <VaButton size="small" color="info" icon="edit" class="action-button" @click="mostrarModalEditar(row)" />
            <VaButton size="small" color="danger" icon="delete" class="action-button" @click="confirmarEliminar(row)" />
          </div>
        </template>
      </VaDataTable>

      <!-- Modal para crear/editar con mejor diseño -->
      <VaModal
        v-model="mostrarModal"
        :title="modalTitulo"
        size="small"
        hide-default-actions
        class="periodo-modal"
        :message="modalMensaje"
      >
        <VaForm class="modal-form" @submit.prevent="guardarPeriodo">
          <VaInput
            v-model="form.codigoperiodo"
            label="Código del Período"
            class="mb-4"
            :rules="[(v) => !!v || 'Campo requerido']"
            placeholder="Ej: 2023-A"
          />

          <VaDateInput
            v-model="form.fecha_inicio"
            label="Fecha de Inicio"
            class="mb-4"
            :rules="[(v) => !!v || 'Campo requerido']"
            placeholder="Seleccione fecha"
          />

          <VaDateInput
            v-model="form.fecha_fin"
            label="Fecha de Fin"
            class="mb-4"
            :rules="[
              (v) => !!v || 'Campo requerido',
              (v) => v >= form.fecha_inicio || 'Debe ser posterior a fecha inicio',
            ]"
            placeholder="Seleccione fecha"
          />

          <div class="modal-actions">
            <VaButton type="button" color="secondary" class="cancel-button" @click="mostrarModal = false">
              Cancelar
            </VaButton>
            <VaButton type="submit" color="primary" class="save-button" :disabled="!formValid">
              {{ esEdicion ? 'Actualizar' : 'Guardar' }}
            </VaButton>
          </div>
        </VaForm>
      </VaModal>
    </VaCardContent>
  </VaCard>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useToast } from 'vuestic-ui'
import api from '../services/api.js'
const { init } = useToast()

// Estado del componente
const periodos = ref([])
const cargando = ref(false)
const mostrarModal = ref(false)
const esEdicion = ref(false)
const periodoActual = ref(null)

// Formulario
const form = ref({
  codigoperiodo: '',
  fecha_inicio: null,
  fecha_fin: null,
})

// Computed para título del modal
const modalTitulo = computed(() => (esEdicion.value ? 'Editar Período Escolar' : 'Nuevo Período Escolar'))

// Computed para validación del formulario
const formValid = computed(() => {
  return (
    form.value.codigoperiodo &&
    form.value.fecha_inicio &&
    form.value.fecha_fin &&
    form.value.fecha_fin >= form.value.fecha_inicio
  )
})

// Columnas de la tabla
const columnas = [
  { key: 'id_periodo_escolar', label: 'ID', sortable: true, width: '80px' },
  { key: 'codigoperiodo', label: 'Código', sortable: true },
  { key: 'fecha_inicio', label: 'Inicio', sortable: true, width: '120px' },
  { key: 'fecha_fin', label: 'Fin', sortable: true, width: '120px' },
  { key: 'actions', label: 'Acciones', width: '120px' },
]

// Función para formatear fechas
const formatDate = (dateString) => {
  if (!dateString) return ''
  const options = { year: 'numeric', month: 'short', day: 'numeric' }
  return new Date(dateString).toLocaleDateString('es-ES', options)
}

// Resto de la lógica se mantiene igual...
onMounted(() => {
  cargarPeriodos()
})

const cargarPeriodos = async () => {
  try {
    cargando.value = true
    const response = await api.getPeriodos()
    periodos.value = response.data || []
  } catch (error) {
    console.error('Error al cargar períodos:', error)
    init({
      message: 'Error al cargar los períodos escolares',
      color: 'danger',
    })
  } finally {
    cargando.value = false
  }
}

const mostrarModalCrear = () => {
  esEdicion.value = false
  form.value = {
    codigoperiodo: '',
    fecha_inicio: null,
    fecha_fin: null,
  }
  mostrarModal.value = true
}

const mostrarModalEditar = (row) => {
  try {
    const periodo = row.rowData
    esEdicion.value = true
    periodoActual.value = periodo.id_periodo_escolar

    const fechaInicio = periodo.fecha_inicio ? new Date(periodo.fecha_inicio) : null
    const fechaFin = periodo.fecha_fin ? new Date(periodo.fecha_fin) : null

    form.value = {
      codigoperiodo: periodo.codigoperiodo || '',
      fecha_inicio: fechaInicio,
      fecha_fin: fechaFin,
    }
    mostrarModal.value = true
  } catch (error) {
    console.error('Error al preparar edición:', error)
    init({
      message: 'Error al preparar el período para edición',
      color: 'danger',
    })
  }
}

const guardarPeriodo = async () => {
  try {
    if (!formValid.value) {
      throw new Error('Por favor complete todos los campos correctamente')
    }

    const payload = {
      codigoperiodo: form.value.codigoperiodo,
      fecha_inicio: form.value.fecha_inicio.toISOString().split('T')[0],
      fecha_fin: form.value.fecha_fin.toISOString().split('T')[0],
    }

    if (esEdicion.value) {
      await api.actualizarPeriodo(periodoActual.value, payload)
      init({ message: 'Período actualizado con éxito', color: 'success' })
    } else {
      await api.crearPeriodo(payload)
      init({ message: 'Período creado con éxito', color: 'success' })
    }

    mostrarModal.value = false
    await cargarPeriodos()
  } catch (error) {
    console.error('Error al guardar período:', error)
    init({
      message: error.message || 'Error al guardar el período',
      color: 'danger',
    })
  }
}

const confirmarEliminar = async (row) => {
  try {
    const periodo = row.rowData
    if (!periodo?.id_periodo_escolar) {
      throw new Error('ID de período no válido')
    }

    // Aquí podrías agregar un diálogo de confirmación
    await api.eliminarPeriodo(periodo.id_periodo_escolar)
    init({ message: 'Período eliminado con éxito', color: 'success' })
    await cargarPeriodos()
  } catch (error) {
    console.error('Error al eliminar período:', error)
    init({
      message: error.response?.data?.message || 'Error al eliminar el período',
      color: 'danger',
    })
  }
}
</script>

<style scoped>
.periodos-container {
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

.header-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.5rem;
  background-color: #f8fafc;
  border-bottom: 1px solid #e2e8f0;
}

.title-text {
  color: #2d3748;
  margin: 0;
  font-weight: 600;
}

.add-button {
  font-weight: 500;
}

.content-container {
  padding: 1.5rem;
}

.periodos-table {
  border-radius: 6px;
  overflow: hidden;
}

.periodos-table :deep(.va-data-table__table) {
  min-width: 100%;
}

.periodos-table :deep(.va-data-table__table th) {
  background-color: #f1f5f9;
  color: #334155;
  font-weight: 600;
}

.actions-container {
  display: flex;
  gap: 0.5rem;
  justify-content: center;
}

.action-button {
  min-width: 36px;
}

.modal-form {
  padding: 1rem;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 0.75rem;
  margin-top: 1.5rem;
}

.cancel-button {
  background-color: #e2e8f0;
  color: #475569;
}

.save-button {
  font-weight: 500;
}

.periodo-modal :deep(.va-modal__inner) {
  border-radius: 8px;
}

.periodo-modal :deep(.va-modal__title) {
  font-size: 1.25rem;
  font-weight: 600;
  color: #1e293b;
  padding: 1rem 1.5rem;
  border-bottom: 1px solid #e2e8f0;
}
</style>
