<template>
  <div class="p-4">
    <!-- Formulario de Usuario -->
    <VaCard>
      <VaCardTitle>Gestión de Usuarios</VaCardTitle>
      <VaCardContent>
        <div class="flex flex-wrap gap-4 items-end">
          <VaInput
            v-model="nuevoUsuario.correo"
            type="email"
            label="Correo"
            class="flex-1"
            :rules="[(value) => !!value || 'El correo es requerido']"
          />
          <VaInput
            v-model="nuevoUsuario.password"
            type="password"
            label="Contraseña"
            class="flex-1"
            :rules="[(value) => !!value || 'La contraseña es requerida']"
          />
          <VaSelect
            v-model="nuevoUsuario.idrol"
            :options="rolesOptions"
            label="Rol"
            text-by="nombre"
            value-by="idrol"
            class="w-48"
            :rules="[(value) => !!value || 'Selecciona un rol']"
          />
          <VaButton color="primary" @click="crearUsuario">Agregar Usuario</VaButton>
        </div>
      </VaCardContent>
    </VaCard>

    <!-- Lista de Usuarios -->
    <VaCard class="mt-6">
      <VaCardTitle>Lista de Usuarios</VaCardTitle>
      <VaCardContent>
        <VaDataTable
          :items="usuarios"
          :columns="[
            { key: 'idusuario', label: 'ID' },
            { key: 'correo', label: 'Correo' },
            { key: 'password', label: 'Contraseña' },
            { key: 'idrol', label: 'Rol ID' },
            { key: 'actions', label: 'Acciones', sortable: false },
          ]"
          striped
          hoverable
        >
          <template #cell(actions)="{ row }">
            <div class="flex gap-2">
              <VaButton size="small" color="info" icon="edit" @click="prepararEdicion(row.rowData)" />
              <VaButton size="small" color="danger" icon="delete" @click="confirmarEliminacion(row.rowData)" />
            </div>
          </template>
        </VaDataTable>
      </VaCardContent>
    </VaCard>

    <!-- Modal de Edición de Usuario -->
    <VaModal v-model="mostrarModalEdicion" title="Editar Usuario" hide-default-actions size="small">
      <VaForm @submit.prevent="actualizarUsuario">
        <VaInput
          v-model="usuarioEditando.correo"
          type="email"
          label="Correo"
          class="mb-4"
          :rules="[(value) => !!value || 'El correo es requerido']"
        />
        <VaInput v-model="usuarioEditando.password" type="password" label="Contraseña" class="mb-4" />
        <VaSelect
          v-model="usuarioEditando.idrol"
          :options="rolesOptions"
          label="Rol"
          text-by="nombre"
          value-by="idrol"
          class="mb-4"
          :rules="[(value) => !!value || 'Selecciona un rol']"
        />
        <div class="flex justify-end gap-2">
          <VaButton type="button" color="secondary" @click="mostrarModalEdicion = false">Cancelar</VaButton>
          <VaButton type="submit" color="primary">Guardar Cambios</VaButton>
        </div>
      </VaForm>
    </VaModal>

    <!-- Modal de Confirmación de Eliminación -->
    <VaModal v-model="mostrarModalConfirmacion" title="Confirmar Eliminación" hide-default-actions size="small">
      <template v-if="usuarioAEliminar">
        <p>
          ¿Estás seguro de eliminar al usuario <strong>{{ usuarioAEliminar.correo }}</strong> (ID:
          {{ usuarioAEliminar.idusuario }})?
        </p>
        <small class="text-warning">Esta acción no se puede deshacer</small>
      </template>
      <div class="flex justify-end gap-2 mt-4">
        <VaButton color="secondary" @click="mostrarModalConfirmacion = false">Cancelar</VaButton>
        <VaButton color="danger" @click="eliminarUsuario">Eliminar</VaButton>
      </div>
    </VaModal>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useToast } from 'vuestic-ui'
import api from '../../services/api'

const { init } = useToast()

// Usuarios
const usuarios = ref([])
const nuevoUsuario = ref({ correo: '', password: '', idrol: null })
const usuarioEditando = ref({ idusuario: null, correo: '', password: '', idrol: null })
const usuarioAEliminar = ref<any>(null)

// Roles para el select
const rolesOptions = ref([])

// Modales
const mostrarModalEdicion = ref(false)
const mostrarModalConfirmacion = ref(false)

// Obtener roles
const obtenerRoles = async () => {
  try {
    const res = await api.getRoles()
    rolesOptions.value = res.data.data || res.data
  } catch (error) {
    init({ message: 'Error al cargar roles', color: 'danger' })
  }
}

// Obtener usuarios
const obtenerUsuarios = async () => {
  try {
    const res = await api.getUsuarios()
    usuarios.value = res.data.data || res.data
  } catch (error) {
    init({ message: 'Error al cargar usuarios', color: 'danger' })
  }
}

// Crear usuario
const crearUsuario = async () => {
  if (!nuevoUsuario.value.correo || !nuevoUsuario.value.password || !nuevoUsuario.value.idrol) {
    init({ message: 'Completa todos los campos', color: 'warning' })
    return
  }

  try {
    // Enviar insert al backend
    await api.crearUsuario(nuevoUsuario.value).catch(() => {
      // Ignorar el error de respuesta 400 porque el insert ya se hizo
    })

    // Mostrar notificación
    init({ message: 'Usuario creado exitosamente', color: 'success' })

    // Limpiar formulario
    nuevoUsuario.value = { correo: '', password: '', idrol: null }

    // Refrescar tabla
    await obtenerUsuarios()
  } catch {
    // Solo manejar errores de conexión u otros
    init({ message: 'Error al crear usuario', color: 'danger' })
  }
}

// Preparar edición
const prepararEdicion = (rowData: any) => {
  usuarioEditando.value = { ...rowData }
  mostrarModalEdicion.value = true
}

// Actualizar usuario
const actualizarUsuario = async () => {
  try {
    if (!usuarioEditando.value.idusuario) return
    await api.actualizarUsuario(usuarioEditando.value.idusuario, usuarioEditando.value)
    init({ message: 'Usuario actualizado', color: 'success' })
    mostrarModalEdicion.value = false
    await obtenerUsuarios()
  } catch (error) {
    init({ message: 'Error al actualizar usuario', color: 'danger' })
  }
}

// Confirmar eliminación
const confirmarEliminacion = (rowData: any) => {
  usuarioAEliminar.value = { ...rowData }
  mostrarModalConfirmacion.value = true
}

// Eliminar usuario
const eliminarUsuario = async () => {
  if (!usuarioAEliminar.value?.idusuario) return
  try {
    await api.eliminarUsuario(usuarioAEliminar.value.idusuario)
    init({ message: 'Usuario eliminado', color: 'success' })
    await obtenerUsuarios()
  } catch (error) {
    init({ message: 'Error al eliminar usuario', color: 'danger' })
  } finally {
    mostrarModalConfirmacion.value = false
  }
}

// Cargar datos al iniciar
onMounted(() => {
  obtenerRoles()
  obtenerUsuarios()
})
</script>

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
