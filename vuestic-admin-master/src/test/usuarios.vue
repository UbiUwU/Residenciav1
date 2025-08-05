<template>
  <VaCard class="users-container">
    <VaCardTitle class="header-container">
      <h1 class="va-h1 title-text">Gestión de Usuarios</h1>
      <VaButton color="primary" icon="add" class="add-button" @click="mostrarModalCrear"> Nuevo Usuario </VaButton>
    </VaCardTitle>

    <VaCardContent class="content-container">
      <!-- Tabla de usuarios -->
      <VaDataTable :items="usuarios" :columns="columnas" :loading="cargando" striped hoverable class="users-table">
        <template #cell(rol)="{ value }">
          <VaChip :color="getRolColor(value)" size="small">
            {{ getRolNombre(value) }}
          </VaChip>
        </template>

        <template #cell(actions)="{ row }">
          <div class="actions-container">
            <VaButton
              size="small"
              color="danger"
              icon="delete"
              class="action-button"
              @click="confirmarEliminar(row.idusuario)"
            />
          </div>
        </template>
      </VaDataTable>

      <!-- Modal para crear nuevo usuario -->
      <VaModal v-model="mostrarModal" title="Nuevo Usuario" size="small" hide-default-actions class="user-modal">
        <VaForm class="modal-form" @submit.prevent="crearUsuario">
          <VaInput
            v-model="form.correo"
            label="Correo electrónico"
            type="email"
            class="mb-4"
            :rules="[(v) => !!v || 'Campo requerido', emailRule]"
          />

          <VaInput
            v-model="form.password"
            label="Contraseña"
            type="password"
            class="mb-4"
            :rules="[(v) => !!v || 'Campo requerido', (v) => v.length >= 6 || 'Mínimo 6 caracteres']"
          />

          <VaSelect
            v-model="form.id_rol"
            label="Rol"
            class="mb-4"
            :options="rolesOptions"
            :rules="[(v) => !!v || 'Campo requerido']"
          />

          <div class="modal-actions">
            <VaButton type="button" color="secondary" class="cancel-button" @click="mostrarModal = false">
              Cancelar
            </VaButton>
            <VaButton type="submit" color="primary" class="save-button" :disabled="!formValid">
              Crear Usuario
            </VaButton>
          </div>
        </VaForm>
      </VaModal>

      <!-- Modal de confirmación para eliminar -->
      <VaModal v-model="mostrarConfirmacionEliminar" hide-default-actions class="confirm-modal">
        <template #header>
          <h3 class="va-h3">Confirmar eliminación</h3>
        </template>

        <p>¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.</p>

        <template #footer>
          <div class="flex justify-end gap-2">
            <VaButton color="secondary" @click="mostrarConfirmacionEliminar = false"> Cancelar </VaButton>
            <VaButton color="danger" @click="eliminarUsuarioConfirmado"> Eliminar </VaButton>
          </div>
        </template>
      </VaModal>
    </VaCardContent>
  </VaCard>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useToast } from 'vuestic-ui'
import api from '../services/apiJ'

const { init } = useToast()

// Estado
const usuarios = ref([])
const cargando = ref(false)
const mostrarModal = ref(false)
const mostrarConfirmacionEliminar = ref(false)
const usuarioAEliminar = ref(null)

// Formulario
const form = ref({
  correo: '',
  password: '',
  id_rol: 1,
})

// Roles disponibles (puedes obtenerlos de la API si es dinámico)
const rolesOptions = [
  { text: 'Administrador', value: 1 },
  { text: 'Maestro', value: 2 },
  { text: 'Alumno', value: 3 },
]

// Columnas de la tabla
const columnas = [
  { key: 'idusuario', label: 'ID', sortable: true, width: '80px' },
  { key: 'correo', label: 'Correo', sortable: true },
  { key: 'rol', label: 'Rol', sortable: true },
  { key: 'actions', label: 'Acciones', width: '100px' },
]

// Reglas de validación
const emailRule = (value) => {
  const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return pattern.test(value) || 'Email no válido'
}

// Computed
const formValid = computed(() => {
  return form.value.correo && form.value.password && form.value.password.length >= 6 && form.value.id_rol
})

// Métodos
onMounted(() => {
  obtenerUsuarios()
})

const obtenerUsuarios = async () => {
  try {
    cargando.value = true
    const response = await api.getUsuarios()
    usuarios.value = response.data || []
  } catch (error) {
    console.error('Error al obtener usuarios:', error)
    init({
      message: 'Error al cargar los usuarios',
      color: 'danger',
    })
  } finally {
    cargando.value = false
  }
}

const mostrarModalCrear = () => {
  form.value = {
    correo: '',
    password: '',
    id_rol: 1,
  }
  mostrarModal.value = true
}

const crearUsuario = async () => {
  try {
    const response = await api.crearUsuario(form.value)
    init({
      message: response.data?.mensaje || 'Usuario creado con éxito',
      color: 'success',
    })
    mostrarModal.value = false
    await obtenerUsuarios()
  } catch (error) {
    console.error('Error al crear usuario:', error)
    init({
      message: error.response?.data?.message || 'Error al crear el usuario',
      color: 'danger',
    })
  }
}

const confirmarEliminar = (id) => {
  usuarioAEliminar.value = id
  mostrarConfirmacionEliminar.value = true
}

const eliminarUsuarioConfirmado = async () => {
  try {
    const response = await api.eliminarUsuario(usuarioAEliminar.value)
    init({
      message: response.data?.mensaje || 'Usuario eliminado con éxito',
      color: 'success',
    })
    mostrarConfirmacionEliminar.value = false
    await obtenerUsuarios()
  } catch (error) {
    console.error('Error al eliminar usuario:', error)
    init({
      message: error.response?.data?.message || 'Error al eliminar el usuario',
      color: 'danger',
    })
  }
}

const getRolNombre = (idRol) => {
  const rol = rolesOptions.find((r) => r.value === idRol)
  return rol ? rol.text : 'Desconocido'
}

const getRolColor = (idRol) => {
  const colors = {
    1: 'danger', // Admin
    2: 'primary', // Maestro
    3: 'info', // Alumno
  }
  return colors[idRol] || 'secondary'
}
</script>

<style scoped>
.users-container {
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

.users-table {
  border-radius: 6px;
  overflow: hidden;
}

.users-table :deep(.va-data-table__table) {
  min-width: 100%;
}

.users-table :deep(.va-data-table__table th) {
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

.user-modal :deep(.va-modal__inner) {
  border-radius: 8px;
}

.user-modal :deep(.va-modal__title) {
  font-size: 1.25rem;
  font-weight: 600;
  color: #1e293b;
  padding: 1rem 1.5rem;
  border-bottom: 1px solid #e2e8f0;
}

.confirm-modal :deep(.va-modal__inner) {
  max-width: 500px;
}
</style>
