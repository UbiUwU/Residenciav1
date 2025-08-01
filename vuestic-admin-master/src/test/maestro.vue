<template>
  <VaCard class="maestros-container">
    <VaCardTitle class="header-container">
      <h1 class="va-h1 title-text">Gestión de Maestros</h1>
      <VaButton color="primary" icon="add" class="add-button" @click="abrirFormulario"> Nuevo Maestro </VaButton>
    </VaCardTitle>

    <VaCardContent class="content-container">
      <!-- Tabla de maestros -->
      <VaDataTable :items="maestros" :columns="columnas" :loading="cargando" striped hoverable class="maestros-table">
        <template #cell(nombre_completo)="{ row }">
          {{ `${row.nombre} ${row.apellidopaterno} ${row.apellidomaterno}` }}
        </template>

        <template #cell(actions)="{ row }">
          <div class="actions-container">
            <VaButton size="small" color="info" icon="edit" class="action-button" @click="editarMaestro(row)" />
            <VaButton size="small" color="danger" icon="delete" class="action-button" @click="confirmarEliminar(row)" />
          </div>
        </template>
      </VaDataTable>

      <!-- Modal para crear/editar -->
      <VaModal
        v-model="mostrarFormulario"
        :title="modalTitulo"
        size="large"
        hide-default-actions
        class="maestro-modal"
        maximizable
      >
        <VaForm class="modal-form" @submit.prevent="guardarMaestro">
          <VaTabs v-model="tabActivo" class="mb-4">
            <VaTab name="datosUsuario">Datos de Usuario</VaTab>
            <VaTab name="datosMaestro">Datos del Maestro</VaTab>
            <VaTab name="escolaridad">Escolaridad</VaTab>
          </VaTabs>

          <div v-show="tabActivo === 'datosUsuario'">
            <VaInput
              v-model="form.correo"
              label="Email"
              type="email"
              class="mb-4"
              :rules="[(v) => !!v || 'Campo requerido', emailRule]"
            />

            <VaInput
              v-model="form.password"
              label="Contraseña"
              type="password"
              class="mb-4"
              :rules="[(v) => !editando || v.length >= 6 || 'Mínimo 6 caracteres']"
              :disabled="editando"
            />

            <VaSelect
              v-model="form.id_rol"
              label="Rol"
              class="mb-4"
              :options="roles"
              :rules="[(v) => !!v || 'Campo requerido']"
            />
          </div>

          <div v-show="tabActivo === 'datosMaestro'">
            <VaInput
              v-model="form.tarjeta"
              label="Tarjeta"
              type="number"
              class="mb-4"
              :rules="[(v) => !!v || 'Campo requerido']"
              :disabled="editando"
            />

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
              <VaInput v-model="form.nombre" label="Nombre" class="mb-0" :rules="[(v) => !!v || 'Campo requerido']" />
              <VaInput
                v-model="form.apellidopaterno"
                label="Apellido Paterno"
                class="mb-0"
                :rules="[(v) => !!v || 'Campo requerido']"
              />
              <VaInput
                v-model="form.apellidomaterno"
                label="Apellido Materno"
                class="mb-0"
                :rules="[(v) => !!v || 'Campo requerido']"
              />
            </div>

            <VaInput v-model="form.rfc" label="RFC" class="mb-4" :rules="[(v) => !!v || 'Campo requerido']" />

            <VaSelect
              v-model="form.id_departamento"
              label="Departamento"
              class="mb-4"
              :options="departamentos"
              :rules="[(v) => !!v || 'Campo requerido']"
            />
          </div>

          <div v-show="tabActivo === 'escolaridad'">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <!-- Licenciatura -->
              <VaInput v-model="form.escolaridad_licenciatura" label="Licenciatura" class="mb-4" />
              <VaSelect
                v-model="form.estado_licenciatura"
                label="Estado Licenciatura"
                class="mb-4"
                :options="estadosEscolaridad"
              />

              <!-- Especialización -->
              <VaInput v-model="form.escolaridad_especializacion" label="Especialización" class="mb-4" />
              <VaSelect
                v-model="form.estado_especializacion"
                label="Estado Especialización"
                class="mb-4"
                :options="estadosEscolaridad"
              />

              <!-- Maestría -->
              <VaInput v-model="form.escolaridad_maestria" label="Maestría" class="mb-4" />
              <VaSelect
                v-model="form.estado_maestria"
                label="Estado Maestría"
                class="mb-4"
                :options="estadosEscolaridad"
              />

              <!-- Doctorado -->
              <VaInput v-model="form.escolaridad_doctorado" label="Doctorado" class="mb-4" />
              <VaSelect
                v-model="form.estado_doctorado"
                label="Estado Doctorado"
                class="mb-4"
                :options="estadosEscolaridad"
              />
            </div>
          </div>

          <div class="modal-actions">
            <VaButton type="button" color="secondary" class="cancel-button" @click="cancelar"> Cancelar </VaButton>
            <VaButton type="submit" color="primary" class="save-button" :disabled="!formValid">
              {{ editando ? 'Actualizar' : 'Guardar' }}
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

// Estado del componente
const maestros = ref([])
const cargando = ref(false)
const mostrarFormulario = ref(false)
const editando = ref(false)
const tabActivo = ref('datosUsuario')

// Opciones para selects
const departamentos = ref([{ text: 'Sistemas y Computación', value: 1 }])

const roles = ref([{ text: 'Maestro', value: 2 }])

const estadosEscolaridad = ref([
  { text: 'Concluida', value: 'C' },
  { text: 'En curso', value: 'E' },
])

// Formulario
const form = ref({
  correo: '',
  password: '',
  id_rol: 2,
  tarjeta: '',
  nombre: '',
  apellidopaterno: '',
  apellidomaterno: '',
  rfc: '',
  id_departamento: 1,
  escolaridad_licenciatura: '',
  estado_licenciatura: 'C',
  escolaridad_especializacion: '',
  estado_especializacion: 'C',
  escolaridad_maestria: '',
  estado_maestria: 'C',
  escolaridad_doctorado: '',
  estado_doctorado: 'C',
})

// Columnas de la tabla
const columnas = [
  { key: 'tarjeta', label: 'Tarjeta', sortable: true },
  { key: 'nombre_completo', label: 'Nombre', sortable: true },
  { key: 'rfc', label: 'RFC', sortable: true },
  { key: 'nombre_departamento', label: 'Departamento', sortable: true },
  { key: 'correo', label: 'Email', sortable: true },
  { key: 'actions', label: 'Acciones', width: '120px' },
]

// Computed
const modalTitulo = computed(() => (editando.value ? 'Editar Maestro' : 'Nuevo Maestro'))

const formValid = computed(() => {
  return (
    form.value.correo &&
    (!editando.value || form.value.password.length >= 6) &&
    form.value.tarjeta &&
    form.value.nombre &&
    form.value.apellidopaterno &&
    form.value.apellidomaterno &&
    form.value.rfc
  )
})

// Reglas de validación
const emailRule = (value) => {
  const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return pattern.test(value) || 'Email no válido'
}

// Métodos
onMounted(() => {
  cargarMaestros()
})

const cargarMaestros = async () => {
  try {
    cargando.value = true
    const { data } = await api.getMaestros()

    maestros.value = data.data.map((maestro) => {
      maestro.nombre_departamento = 'Sistemas y Computación'
      maestro.id_departamento = 1

      if (!maestro.correo) {
        const nombre = maestro.nombre.toLowerCase().split(' ')[0]
        const apellido = maestro.apellidopaterno.toLowerCase()
        maestro.correo = `${nombre}.${apellido}@tecnm.mx`

        let counter = 1
        let tempEmail = maestro.correo
        while (maestros.value.some((m) => m.correo === tempEmail)) {
          tempEmail = `${nombre}.${apellido}${counter}@tecnm.mx`
          counter++
        }
        maestro.correo = tempEmail
      }

      return maestro
    })
  } catch (error) {
    init({
      message: 'Error al cargar los maestros',
      color: 'danger',
    })
    console.error('Error al cargar maestros:', error)
  } finally {
    cargando.value = false
  }
}

const abrirFormulario = () => {
  resetForm()
  mostrarFormulario.value = true
  editando.value = false
  tabActivo.value = 'datosUsuario'
}

const editarMaestro = (row) => {
  const maestro = row.rowData
  form.value = {
    correo: maestro.correo,
    password: '', // No mostramos la contraseña existente
    id_rol: 2,
    tarjeta: maestro.tarjeta,
    nombre: maestro.nombre,
    apellidopaterno: maestro.apellidopaterno,
    apellidomaterno: maestro.apellidomaterno,
    rfc: maestro.rfc,
    id_departamento: 1,
    escolaridad_licenciatura: maestro.escolaridad_licenciatura || '',
    estado_licenciatura: maestro.estado_licenciatura || 'C',
    escolaridad_especializacion: maestro.escolaridad_especializacion || '',
    estado_especializacion: maestro.estado_especializacion || 'C',
    escolaridad_maestria: maestro.escolaridad_maestria || '',
    estado_maestria: maestro.estado_maestria || 'C',
    escolaridad_doctorado: maestro.escolaridad_doctorado || '',
    estado_doctorado: maestro.estado_doctorado || 'C',
  }
  mostrarFormulario.value = true
  editando.value = true
}

const guardarMaestro = async () => {
  try {
    if (!formValid.value) {
      throw new Error('Por favor complete todos los campos requeridos')
    }

    // Generar email si es nuevo y no tiene
    if (!editando.value && !form.value.correo) {
      const nombre = form.value.nombre.toLowerCase().split(' ')[0]
      const apellido = form.value.apellidopaterno.toLowerCase()
      form.value.correo = `${nombre}.${apellido}@tecnm.mx`

      let counter = 1
      let tempEmail = form.value.correo
      while (maestros.value.some((m) => m.correo === tempEmail)) {
        tempEmail = `${nombre}.${apellido}${counter}@tecnm.mx`
        counter++
      }
      form.value.correo = tempEmail
    }

    if (editando.value) {
      await api.actualizarMaestro(form.value.tarjeta, form.value)
      init({ message: 'Maestro actualizado con éxito', color: 'success' })
    } else {
      await api.crearMaestro(form.value)
      init({ message: 'Maestro creado con éxito', color: 'success' })
    }

    await cargarMaestros()
    cancelar()
  } catch (error) {
    console.error('Error al guardar maestro:', error)
    init({
      message: error.message || 'Error al guardar el maestro',
      color: 'danger',
    })
  }
}

const confirmarEliminar = async (row) => {
  try {
    const maestro = row.rowData
    const result = await this.$vaModal.confirm({
      title: 'Confirmar eliminación',
      message: `¿Está seguro de eliminar al maestro ${maestro.nombre} ${maestro.apellidopaterno}?`,
      okText: 'Eliminar',
      cancelText: 'Cancelar',
      color: 'danger',
    })

    if (result) {
      await api.eliminarMaestro(maestro.tarjeta)
      init({ message: 'Maestro eliminado con éxito', color: 'success' })
      await cargarMaestros()
    }
  } catch (error) {
    console.error('Error al eliminar maestro:', error)
    init({
      message: error.response?.data?.message || 'Error al eliminar el maestro',
      color: 'danger',
    })
  }
}

const cancelar = () => {
  mostrarFormulario.value = false
  resetForm()
}

const resetForm = () => {
  form.value = {
    correo: '',
    password: '',
    id_rol: 2,
    tarjeta: '',
    nombre: '',
    apellidopaterno: '',
    apellidomaterno: '',
    rfc: '',
    id_departamento: 1,
    escolaridad_licenciatura: '',
    estado_licenciatura: 'C',
    escolaridad_especializacion: '',
    estado_especializacion: 'C',
    escolaridad_maestria: '',
    estado_maestria: 'C',
    escolaridad_doctorado: '',
    estado_doctorado: 'C',
  }
}
</script>

<style scoped>
.maestros-container {
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

.maestros-table {
  border-radius: 6px;
  overflow: hidden;
}

.maestros-table :deep(.va-data-table__table) {
  min-width: 100%;
}

.maestros-table :deep(.va-data-table__table th) {
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

.maestro-modal :deep(.va-modal__inner) {
  border-radius: 8px;
  max-width: 800px;
}

.maestro-modal :deep(.va-modal__title) {
  font-size: 1.25rem;
  font-weight: 600;
  color: #1e293b;
  padding: 1rem 1.5rem;
  border-bottom: 1px solid #e2e8f0;
}

.grid {
  display: grid;
}
</style>
