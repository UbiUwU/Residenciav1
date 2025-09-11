<template>
  <VaCard class="periodos-container">
    <VaCardTitle class="header-container">
      <h1 class="va-h1 title-text">Gestión de Períodos Escolares</h1>
      <VaButton color="primary" icon="add" class="add-button" @click="mostrarModalCrear"> Nuevo Período </VaButton>
    </VaCardTitle>

    <VaCardContent class="content-container">
      <!-- Tabla de períodos -->
      <VaDataTable :items="periodos" :columns="columnas" :loading="cargando" striped hoverable>
        <template #cell(fecha_inicio)="{ value }">
          {{ formatDate(value) }}
        </template>
        <template #cell(fecha_fin)="{ value }">
          {{ formatDate(value) }}
        </template>
        <template #cell(estado)="{ value }">
          <span v-if="value === 'true' || value === true" style="color: green">✔</span>
          <span v-else style="color: red">✖</span>
        </template>

        <template #cell(actions)="{ row }">
          <div class="actions-container">
            <VaButton size="small" color="info" icon="edit" class="action-button" @click="mostrarModalEditar(row)" />
            <VaButton size="small" color="danger" icon="delete" class="action-button" @click="confirmarEliminar(row)" />
          </div>
        </template>
      </VaDataTable>

      <!-- Modal Crear/Editar -->
      <VaModal v-model="mostrarModal" :title="modalTitulo" size="small" hide-default-actions>
        <VaForm class="modal-form" @submit.prevent="guardarPeriodo">
          <VaInput
            v-if="!esEdicion"
            v-model="form.codigoperiodo"
            label="Código del Período"
            class="mb-4"
            :rules="[(v) => !!v || 'Campo requerido']"
            placeholder="Ej: 2025-A"
          />

          <VaInput
            v-if="!esEdicion"
            v-model="form.nombre_periodo"
            label="Nombre del Período"
            class="mb-4"
            :rules="[(v) => !!v || 'Campo requerido']"
            placeholder="Ej: Enero - Junio 2025"
          />

          <VaDateInput
            v-if="!esEdicion"
            v-model="form.fecha_inicio"
            label="Fecha de Inicio"
            class="mb-4"
            :rules="[(v) => !!v || 'Campo requerido']"
          />

          <VaDateInput
            v-if="!esEdicion"
            v-model="form.fecha_fin"
            label="Fecha de Fin"
            class="mb-4"
            :rules="[
              (v) => !!v || 'Campo requerido',
              (v) => v >= form.fecha_inicio || 'Debe ser posterior a fecha inicio',
            ]"
          />

          <!-- Solo para edición: cambiar activo -->
          <VaSwitch v-if="esEdicion" v-model="form.estado" label="Activo" />

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
import api from '../services/api'

const { init } = useToast()

// Estado
const periodos = ref([])
const cargando = ref(false)
const mostrarModal = ref(false)
const esEdicion = ref(false)
const periodoActual = ref(null)

// Formulario
const form = ref({
  codigoperiodo: '',
  nombre_periodo: '',
  fecha_inicio: null,
  fecha_fin: null,
  estado: true,
})

// Columnas de la tabla
const columnas = [
  { key: 'id_periodo_escolar', label: 'ID', sortable: true },
  { key: 'codigoperiodo', label: 'Código', sortable: true },
  { key: 'nombre_periodo', label: 'Nombre', sortable: true },
  { key: 'fecha_inicio', label: 'Inicio', sortable: true },
  { key: 'fecha_fin', label: 'Fin', sortable: true },
  { key: 'estado', label: 'Estado', sortable: true },
  { key: 'actions', label: 'Acciones' },
]

// Computed
const modalTitulo = computed(() => (esEdicion.value ? 'Editar Período Escolar' : 'Nuevo Período Escolar'))

const formValid = computed(() => {
  if (esEdicion.value) {
    // Al editar, solo se necesita que el estado sea booleano
    return typeof form.value.estado === 'boolean'
  } else {
    // Al crear, validar todos los campos
    return (
      form.value.codigoperiodo &&
      form.value.nombre_periodo &&
      form.value.fecha_inicio &&
      form.value.fecha_fin &&
      form.value.fecha_fin >= form.value.fecha_inicio
    )
  }
})

// Función formateo fecha
const formatDate = (dateString) => {
  if (!dateString) return ''
  return new Date(dateString).toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  })
}

// CRUD
onMounted(() => {
  cargarPeriodos()
})

const cargarPeriodos = async () => {
  try {
    cargando.value = true
    const response = await api.getPeriodo()
    periodos.value = response.data || []
  } catch (error) {
    console.error('Error al cargar períodos:', error)
    init({ message: 'Error al cargar los períodos escolares', color: 'danger' })
  } finally {
    cargando.value = false
  }
}

const mostrarModalCrear = () => {
  esEdicion.value = false
  form.value = { codigoperiodo: '', nombre_periodo: '', fecha_inicio: null, fecha_fin: null }
  mostrarModal.value = true
}

const mostrarModalEditar = (row) => {
  const periodo = row.rowData
  esEdicion.value = true
  periodoActual.value = periodo.id_periodo_escolar

  // Asegurarse de que sea booleano
  form.value.estado = Boolean(periodo.estado)

  mostrarModal.value = true
}

const guardarPeriodo = async () => {
  try {
    if (esEdicion.value) {
      const payload = {
        estado: form.value.estado ? 'true' : 'false', // convertir booleano a string
      }
      await api.actualizarPeriodo(periodoActual.value, payload)
      init({ message: 'Estado actualizado con éxito', color: 'success' })
    } else {
      const payload = {
        codigoperiodo: form.value.codigoperiodo,
        nombre_periodo: form.value.nombre_periodo,
        fecha_inicio: form.value.fecha_inicio.toISOString().split('T')[0],
        fecha_fin: form.value.fecha_fin.toISOString().split('T')[0],
        estado: true,
      }
      await api.crearPeriodo(payload)
      init({ message: 'Período creado con éxito', color: 'success' })
    }
    mostrarModal.value = false
    await cargarPeriodos()
  } catch (error) {
    console.error('Error al guardar período:', error)
    init({ message: 'Error al guardar el período', color: 'danger' })
  }
}

const confirmarEliminar = async (row) => {
  try {
    const periodo = row.rowData
    await api.eliminarPeriodo(periodo.id_periodo_escolar)
    init({ message: 'Período eliminado con éxito', color: 'success' })
    await cargarPeriodos()
  } catch (error) {
    console.error('Error al eliminar período:', error)
    init({ message: 'Error al eliminar el período', color: 'danger' })
  }
}
</script>
