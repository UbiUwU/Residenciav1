<script setup>
import { ref, onMounted } from 'vue'
import { useToast } from 'vuestic-ui'
import api from '../services/api'

const { init } = useToast()
const roles = ref([])
const nuevoRol = ref({ idrol: '', nombre: '' })
const rolEditando = ref({ idrol: '', nombre: '' }) // Inicializar con estructura correcta
const mostrarModalEdicion = ref(false)
const mostrarModalConfirmacion = ref(false)
const rolAEliminar = ref(null)

const obtenerRoles = async () => {
  try {
    const res = await api.getRoles()
    console.log('Datos recibidos de roles:', res.data)
    roles.value = res.data.data || res.data
  } catch (error) {
    init({
      message: error.response?.data?.message || 'Error al cargar los roles',
      color: 'danger',
    })
  }
}

const crearRol = async () => {
  if (!nuevoRol.value.idrol || !nuevoRol.value.nombre) {
    init({ message: 'Completa todos los campos', color: 'warning' })
    return
  }

  try {
    const res = await api.crearRol({
      IdRol: nuevoRol.value.idrol,
      Nombre: nuevoRol.value.nombre,
    })

    init({
      message: res.data.message || 'Rol creado exitosamente',
      color: 'success',
    })
    nuevoRol.value = { idrol: '', nombre: '' }
    await obtenerRoles()
  } catch (error) {
    const errorMsg =
      error.response?.data?.message ||
      error.response?.data?.errors?.Nombre?.[0] ||
      error.response?.data?.errors?.IdRol?.[0] ||
      'Error al crear rol'

    init({ message: errorMsg, color: 'danger' })
  }
}

const prepararEdicion = (rowData) => {
  // Extraemos los datos del rol de la estructura compleja (similar a como hicimos con eliminar)
  const rol = rowData.rowData || rowData.source || rowData
  rolEditando.value = {
    idrol: rol.idrol,
    nombre: rol.nombre,
  }
  console.log('Datos preparados para editar:', rolEditando.value) // Para depuración
  mostrarModalEdicion.value = true
}

const actualizarRol = async () => {
  try {
    // Verificación más robusta
    if (!rolEditando.value?.idrol) {
      init({ message: 'ID de rol no encontrado', color: 'danger' })
      return
    }

    console.log('Enviando actualización para rol ID:', rolEditando.value.idrol)

    const res = await api.actualizarRol(rolEditando.value.idrol, { Nombre: rolEditando.value.nombre })

    init({
      message: res.data.message || 'Rol actualizado exitosamente',
      color: 'success',
    })

    mostrarModalEdicion.value = false
    await obtenerRoles() // Refrescar la lista
  } catch (error) {
    console.error('Error al actualizar:', error)
    const errorMsg =
      error.response?.data?.message || error.response?.data?.errors?.Nombre?.[0] || 'Error al actualizar rol'

    init({ message: errorMsg, color: 'danger' })
  }
}

const confirmarEliminacion = (rowData) => {
  // Extraemos los datos del rol de la estructura compleja
  const rol = rowData.rowData || rowData.source || rowData
  rolAEliminar.value = {
    idrol: rol.idrol,
    nombre: rol.nombre,
  }
  console.log('Datos preparados para eliminar:', rolAEliminar.value) // Para depuración
  mostrarModalConfirmacion.value = true
}

const eliminarRol = async () => {
  if (!rolAEliminar.value?.idrol) {
    init({ message: 'No se pudo obtener el ID del rol', color: 'danger' })
    mostrarModalConfirmacion.value = false
    return
  }

  try {
    console.log('Enviando solicitud para eliminar rol ID:', rolAEliminar.value.idrol)
    const res = await api.eliminarRol(rolAEliminar.value.idrol)

    init({
      message: res.data.message || `Rol "${rolAEliminar.value.nombre}" eliminado correctamente`,
      color: 'success',
    })

    await obtenerRoles()
  } catch (error) {
    console.error('Error al eliminar:', error)
    init({
      message: error.response?.data?.message || 'Error al eliminar el rol',
      color: 'danger',
    })
  } finally {
    mostrarModalConfirmacion.value = false
  }
}

onMounted(obtenerRoles)
</script>

<template>
  <div class="p-4">
    <va-card>
      <va-card-title>Gestión de Roles Universitarios</va-card-title>
      <va-card-content>
        <div class="flex flex-wrap gap-4 items-end">
          <va-input
            v-model="nuevoRol.idrol"
            type="number"
            label="ID del Rol"
            class="w-48"
            :rules="[(value) => !!value || 'El ID es requerido']"
          />
          <va-input
            v-model="nuevoRol.nombre"
            label="Nombre del Rol"
            class="flex-1"
            :rules="[(value) => !!value || 'El nombre es requerido']"
          />
          <va-button color="primary" @click="crearRol">Agregar Rol</va-button>
        </div>
      </va-card-content>
    </va-card>

    <va-card class="mt-6">
      <va-card-title>Lista de Roles</va-card-title>
      <va-card-content>
        <va-data-table
          :items="roles"
          :columns="[
            { key: 'idrol', label: 'ID' },
            { key: 'nombre', label: 'Nombre del Rol' },
            { key: 'actions', label: 'Acciones', sortable: false },
          ]"
          bordered
          striped
          hoverable
        >
          <template #cell(actions)="{ row }">
            <div class="flex gap-2">
              <va-button size="small" color="info" icon="edit" @click="prepararEdicion(row.rowData)" />
              <va-button size="small" color="danger" icon="delete" @click="confirmarEliminacion(row)" />
            </div>
          </template>
        </va-data-table>
      </va-card-content>
    </va-card>

    <!-- Modal de Edición -->
    <va-modal v-model="mostrarModalEdicion" title="Editar Rol" hide-default-actions size="small">
      <va-form @submit.prevent="actualizarRol">
        <va-input
          v-model="rolEditando.nombre"
          label="Nombre del Rol"
          class="mb-4"
          :rules="[(value) => !!value || 'El nombre es requerido']"
        />
        <div class="flex justify-end gap-2">
          <va-button type="button" color="secondary" @click="mostrarModalEdicion = false"> Cancelar </va-button>
          <va-button type="submit" color="primary"> Guardar Cambios </va-button>
        </div>
      </va-form>
    </va-modal>

    <!-- Modal de Confirmación de Eliminación -->
    <va-modal v-model="mostrarModalConfirmacion" title="Confirmar Eliminación" hide-default-actions size="small">
      <template v-if="rolAEliminar">
        <p>
          ¿Estás seguro de eliminar el rol <strong>"{{ rolAEliminar.nombre }}"</strong> (ID: {{ rolAEliminar.idrol }})?
        </p>
        <small class="text-warning">Esta acción no se puede deshacer</small>
      </template>
      <div class="flex justify-end gap-2 mt-4">
        <va-button color="secondary" @click="mostrarModalConfirmacion = false"> Cancelar </va-button>
        <va-button color="danger" @click="eliminarRol"> Eliminar </va-button>
      </div>
    </va-modal>
  </div>
</template>

<style scoped>
.flex {
  display: flex;
}

.flex-wrap {
  flex-wrap: wrap;
}

.gap-2 {
  gap: 0.5rem;
}

.gap-4 {
  gap: 1rem;
}

.w-48 {
  width: 12rem;
}

.mt-4 {
  margin-top: 1rem;
}

.mt-6 {
  margin-top: 1.5rem;
}

.justify-end {
  justify-content: flex-end;
}

.items-end {
  align-items: flex-end;
}
</style>
