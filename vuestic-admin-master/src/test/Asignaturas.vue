<!-- eslint-disable prettier/prettier -->
<!-- eslint-disable prettier/prettier -->
<!-- eslint-disable prettier/prettier -->
<!-- eslint-disable prettier/prettier -->
<!-- eslint-disable prettier/prettier -->
<template>
  <div class="asignaturas-container">
    <h1>Lista de Asignaturas</h1>

    <div v-if="loading" class="loading">Cargando asignaturas...</div>
    <div v-else-if="error" class="error">{{ error }}</div>

    <div v-else class="asignaturas-grid">
      <div v-for="asignatura in asignaturas" :key="asignatura.ClaveAsignatura" class="asignatura-card"
        @click="verDetalle(asignatura.ClaveAsignatura)">
        <h3>{{ asignatura.ClaveAsignatura }} - {{ asignatura.NombreAsignatura }}</h3>
        <div class="asignatura-info">
          <span>Créditos: {{ asignatura.Creditos }}</span>
          <span>SATCA: {{ asignatura.Satca_Total }} (T:{{ asignatura.Satca_Teoricas }}, P:{{ asignatura.Satca_Practicas
          }})</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../services/api.js'

const router = useRouter()
const asignaturas = ref([])
const loading = ref(true)
const error = ref(null)

const fetchAsignaturas = async () => {
  try {
    loading.value = true
    const response = await api.getAsignaturas()
    asignaturas.value = response.data.asignaturas.map(a => a.asignatura)
  } catch (err) {
    error.value = 'Error al cargar las asignaturas: ' + (err.response?.data?.error || err.message)
    console.error(err)
  } finally {
    loading.value = false
  }
}

const verDetalle = (clave) => {
  router.push(`/asignaturas/${clave}`)
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
  background-color: #fff;
  border-radius: 8px;
  padding: 15px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  cursor: pointer;
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
.error {
  text-align: center;
  padding: 20px;
  font-size: 1.1em;
}

.error {
  color: #e74c3c;
}
</style>