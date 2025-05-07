<!-- eslint-disable prettier/prettier -->
<template>
    <div class="p-4">
      <h2 class="text-xl font-bold mb-4">
        {{ editando ? 'Editar Plantilla' : 'Nueva Plantilla' }}
      </h2>
  
      <form @submit.prevent="handleSubmit" class="space-y-4">
        <input v-model="form.nombre" type="text" placeholder="Nombre" class="border p-2 w-full" required />
        <textarea v-model="form.descripcion" placeholder="Descripción" class="border p-2 w-full"></textarea>
        <input type="file" @change="handleFileChange" class="border p-2 w-full" :required="!editando" />
  
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
          {{ editando ? 'Actualizar' : 'Subir' }}
        </button>
  
        <button v-if="editando" type="button" @click="cancelarEdicion" class="ml-2 bg-gray-400 text-white px-4 py-2 rounded">
          Cancelar
        </button>
      </form>
  
      <div v-if="plantillas.length" class="mt-6">
        <h3 class="text-lg font-semibold mb-2">Plantillas existentes</h3>
        <ul class="space-y-2">
          <li v-for="plantilla in plantillas" :key="plantilla.id" class="border p-2 flex justify-between items-center">
            <div>
              <strong>{{ plantilla.nombre }}</strong><br />
              <small>{{ plantilla.descripcion || 'Sin descripción' }}</small>
            </div>
            <div class="space-x-2">
              <button @click="prepararEdicion(plantilla)" class="bg-yellow-400 text-black px-2 py-1 rounded">
                Editar
              </button>
              <button @click="eliminar(plantilla.id)" class="bg-red-500 text-white px-2 py-1 rounded">
                Eliminar
              </button>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'
  import api from '../../services/api.js'
  
  const form = ref({
    nombre: '',
    descripcion: '',
    archivo: null
  })
  
  const editando = ref(false)
  const idEditando = ref(null)
  
  const plantillas = ref([])
  
  const handleFileChange = (event) => {
    form.value.archivo = event.target.files[0]
  }
  
  const handleSubmit = async () => {
    const data = new FormData()
    data.append('nombre', form.value.nombre)
    data.append('descripcion', form.value.descripcion)
    if (form.value.archivo) {
      data.append('archivo', form.value.archivo)
    }
  
    try {
      if (editando.value) {
        await api.actualizarPlantilla(idEditando.value, data)
        alert('Plantilla actualizada ✅')
      } else {
        await api.crearPlantilla(data)
        alert('Plantilla subida ✅')
      }
      cancelarEdicion()
      fetchPlantillas()
    } catch (error) {
      console.error(error)
      alert('Error al guardar ❌')
    }
  }
  
  const prepararEdicion = (plantilla) => {
    editando.value = true
    idEditando.value = plantilla.id
    form.value.nombre = plantilla.nombre
    form.value.descripcion = plantilla.descripcion
    form.value.archivo = null
  }
  
  const cancelarEdicion = () => {
    editando.value = false
    idEditando.value = null
    form.value = { nombre: '', descripcion: '', archivo: null }
  }
  
  const eliminar = async (id) => {
    if (!confirm('¿Eliminar esta plantilla?')) return
    try {
      await api.eliminarPlantilla(id)
      alert('Plantilla eliminada ✅')
      fetchPlantillas()
    } catch (error) {
      console.error(error)
      alert('Error al eliminar ❌')
    }
  }
  
  const fetchPlantillas = async () => {
    try {
      const res = await api.getPlantillas()
      plantillas.value = res.data
    } catch (error) {
      console.error('Error al cargar:', error)
    }
  }
  
  onMounted(fetchPlantillas)
  </script>
  