<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Usuarios</h1>

    <form @submit.prevent="crearNuevoUsuario" class="mb-6 space-y-3">
      <input v-model="nuevoUsuario.correo" placeholder="Correo" class="input" />
      <input v-model="nuevoUsuario.password" placeholder="Contraseña" type="password" class="input" />
      <input v-model="nuevoUsuario.id_rol" placeholder="ID Rol" type="number" class="input" />
      <button type="submit" class="btn">Crear Usuario</button>
    </form>

    <table class="table-auto w-full border">
      <thead>
        <tr class="bg-gray-200">
          <th class="border px-4 py-2">ID</th>
          <th class="border px-4 py-2">Correo</th>
          <th class="border px-4 py-2">Token</th>
          <th class="border px-4 py-2">Rol</th>
          <th class="border px-4 py-2">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="usuario in usuarios" :key="usuario.idusuario">
          <td class="border px-4 py-2">{{ usuario.idusuario }}</td>
          <td class="border px-4 py-2">{{ usuario.correo }}</td>
          <td class="border px-4 py-2">{{ usuario.token }}</td>
          <td class="border px-4 py-2">{{ usuario.idrol }}</td>
          <td class="border px-4 py-2">
            <button @click="eliminarUsuario(usuario.idusuario)" class="text-red-600">Eliminar</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../services/api' // Asegúrate de que esta ruta apunte a tu archivo real

const usuarios = ref([])
const nuevoUsuario = ref({
  correo: '',
  password: '',
  id_rol: 1
})

const obtenerUsuarios = async () => {
  try {
    const res = await api.getUsuarios()
    usuarios.value = res.data
  } catch (error) {
    console.error('Error al obtener usuarios:', error)
  }
}

const crearNuevoUsuario = async () => {
  try {
    const res = await api.crearUsuario(nuevoUsuario.value)
    alert(res.data.mensaje)
    await obtenerUsuarios()
    nuevoUsuario.value = { correo: '', password: '', id_rol: 1 }
  } catch (error) {
    console.error('Error al crear usuario:', error)
  }
}

const eliminarUsuario = async (id) => {
  if (!confirm('¿Estás seguro de eliminar este usuario?')) return
  try {
    const res = await api.eliminarUsuario(id)
    alert(res.data.mensaje)
    await obtenerUsuarios()
  } catch (error) {
    console.error('Error al eliminar usuario:', error)
  }
}

onMounted(obtenerUsuarios)
</script>

<style scoped>
.input {
  @apply border px-3 py-2 rounded w-full;
}
.btn {
  @apply bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700;
}
</style>
