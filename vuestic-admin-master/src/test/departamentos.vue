<template>
  <div class="p-6 max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Gestión de Departamentos</h1>

    <form class="mb-6 space-y-4 bg-gray-100 p-4 rounded" @submit.prevent="guardarDepartamento">
      <div>
        <label class="block">ID Departamento</label>
        <input v-model="form.id_departamento" type="number" class="input" required />
      </div>
      <div>
        <label class="block">Nombre</label>
        <input v-model="form.nombre" type="text" class="input" required />
      </div>
      <div>
        <label class="block">Abreviación</label>
        <input v-model="form.abreviacion" type="text" class="input" required />
      </div>
      <div>
        <label class="block">ID Maestro (opcional)</label>
        <input v-model="form.maestro_id" type="number" class="input" />
      </div>

      <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        {{ editando ? 'Actualizar' : 'Crear' }}
      </button>
    </form>

    <h2 class="text-xl font-semibold mb-2">Lista de Departamentos</h2>
    <table class="min-w-full bg-white border">
      <thead class="bg-gray-200">
        <tr>
          <th class="px-4 py-2">ID</th>
          <th class="px-4 py-2">Nombre</th>
          <th class="px-4 py-2">Abreviación</th>
          <th class="px-4 py-2">Maestro</th>
          <th class="px-4 py-2">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="d in departamentos" :key="d.id_departamento" class="border-t">
          <td class="px-4 py-2">{{ d.id_departamento }}</td>
          <td class="px-4 py-2">{{ d.nombre }}</td>
          <td class="px-4 py-2">{{ d.abreviacion }}</td>
          <td class="px-4 py-2">{{ d.nombre_maestro || 'N/A' }}</td>
          <td class="px-4 py-2 space-x-2">
            <button class="text-blue-600 hover:underline" @click="editarDepartamento(d)">Editar</button>
            <button class="text-red-600 hover:underline" @click="borrarDepartamento(d.id_departamento)">
              Eliminar
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../services/api' // Asegúrate que el archivo api.js esté en src/api.js o ajusta la ruta

const departamentos = ref([])
const form = ref({
  id_departamento: '',
  nombre: '',
  abreviacion: '',
})
const editando = ref(false)
const idEditando = ref(null)

const cargarDepartamentos = async () => {
  try {
    const { data } = await api.getDepartamentos()
    departamentos.value = data
  } catch (error) {
    alert('Error al cargar los departamentos')
  }
}

const guardarDepartamento = async () => {
  try {
    if (editando.value) {
      await api.actualizarDepartamento(idEditando.value, form.value)
      alert('Departamento actualizado')
    } else {
      await api.crearDepartamento(form.value)
      alert('Departamento creado')
    }
    resetForm()
    cargarDepartamentos()
  } catch (error) {
    alert('Error al guardar')
  }
}

const editarDepartamento = (d) => {
  form.value = { ...d }
  editando.value = true
  idEditando.value = d.id_departamento
}

const borrarDepartamento = async (id) => {
  if (!confirm('¿Estás seguro de eliminar este departamento?')) return
  try {
    await api.eliminarDepartamento(id)
    cargarDepartamentos()
  } catch (error) {
    alert('Error al eliminar')
  }
}

const resetForm = () => {
  form.value = {
    id_departamento: '',
    nombre: '',
    abreviacion: '',
  }
  editando.value = false
  idEditando.value = null
}

onMounted(() => {
  cargarDepartamentos()
})
</script>

<style scoped>
.input {
  width: 100%;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
}
</style>
