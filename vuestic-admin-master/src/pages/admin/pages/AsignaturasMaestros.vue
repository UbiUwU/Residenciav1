<template>
   <div class="asignaturas-container">
  <h1 class="va-text-primary">Asignaturas del maestro</h1>

    <div v-if="loading" class="loading">Cargando asignaturas...</div>
    <div v-else-if="error" class="error">{{ error }}</div>

    <div v-else-if="asignaturas.length === 0" class="no-data">
      Este maestro no tiene asignaturas registradas.
    </div>

    <div v-else class="asignaturas-grid">
      <div
  v-for="asignatura in asignaturas"
  :key="asignatura.ClaveAsignatura"
  class="asignatura-card"
>
  <div @click="toggleDetalle(asignatura.ClaveAsignatura)">
    <h3>{{ asignatura.ClaveAsignatura }} - {{ asignatura.NombreAsignatura }}</h3>
    <div class="asignatura-info">
      <span>Créditos: {{ asignatura.Creditos }}</span>
      <span>
        SATCA: {{ asignatura.Satca_Total }}
        (T:{{ asignatura.Satca_Teoricas }}, P:{{ asignatura.Satca_Practicas }})
      </span>
    </div>
  </div>

<div v-if="detalleAbierto === asignatura.ClaveAsignatura" class="submenu">
    <button @click="verPDF()">
      Instrumentación Didáctica
    </button>
    <button @click="verPDF(asignatura.ClaveAsignatura, 'avance')">
      Avance Programático
    </button>
  </div>
</div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../../../services/api.js'

const route = useRoute()
const router = useRouter()
const asignaturas = ref([])
const loading = ref(true)
const error = ref(null)

const tarjeta = route.params.tarjeta

const fetchAsignaturas = async () => {
  try {
    loading.value = true
    const response = await api.getAsignaturasPorMaestro(tarjeta)

    console.log('DATA RECIBIDA:', response.data)

    const data = response.data

    // Verifica si data es un arreglo
    if (Array.isArray(data)) {
      asignaturas.value = [
        ...new Map(data.map(a => [a.ClaveAsignatura, a])).values()
      ]
    } 
    // Verifica si es un objeto que contiene la propiedad "asignaturas"
    else if (data && Array.isArray(data.asignaturas)) {
      asignaturas.value = [
        ...new Map(data.asignaturas.map(a => [a.ClaveAsignatura, a])).values()
      ]
    } 
    else {
      throw new Error('Formato inesperado en la respuesta del servidor')
    }
  } catch (err) {
    error.value = 'Error al cargar las asignaturas: ' + (err.response?.data?.error || err.message)
    console.error(err)
  } finally {
    loading.value = false
  }
}



const detalleAbierto = ref(null)

const toggleDetalle = (clave) => {
  detalleAbierto.value = detalleAbierto.value === clave ? null : clave
}

const verPDF = () => {
    router.push({ name: 'pdf'})
}
onMounted(() => {
  fetchAsignaturas()
})
</script>

<style scoped>

.asignaturas-container {
  padding: 20px;
  max-width: 1200px;
  margin: 0 auto;
}

h1 {
  color: #2c3e50;
  margin-bottom: 20px;
}

.asignaturas-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 20px;
}

.asignatura-card {
  background-color: var(--va-background-element);
  color: var(--va-on-background);
  border-radius: 8px;
  padding: 15px;
  box-shadow: var(--va-box-shadow);
  transition: transform 0.2s, box-shadow 0.2s;
}

.asignatura-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.asignatura-info {
  margin-top: 10px;
  display: flex;
  flex-direction: column;
  gap: 5px;
  color: #666;
}

.loading,
.error,
.no-data {
  text-align: center;
  padding: 20px;
  font-size: 1.1em;
}

.error {
  color: #e74c3c;
}
.submenu {
  margin-top: 10px;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.submenu button {
  padding: 8px 12px;
  border: none;
  background-color: #3498db;
  color: white;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.submenu button:hover {
  background-color: #2980b9;
}

</style>
