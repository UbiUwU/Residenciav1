<template>
  <div class="boton-regresar">
    <button @click="handleRegresar">← Regresar</button>
  </div>

  <div class="asignaturas-container">
    <h1 class="va-text-primary">Asignaturas del maestro</h1>

    <div v-if="loading" class="loading">Cargando asignaturas...</div>
    <div v-else-if="error" class="error">{{ error }}</div>

    <div v-else-if="asignaturas.length === 0" class="no-data">Este maestro no tiene asignaturas registradas.</div>

    <div v-else class="asignaturas-grid">
      <div v-for="asignatura in asignaturas" :key="asignatura.ClaveAsignatura" class="asignatura-card">
        <div @click="toggleDetalle(asignatura.ClaveAsignatura)">
          <h3>{{ asignatura.ClaveAsignatura }} - {{ asignatura.NombreAsignatura }}</h3>
          <div class="asignatura-info">
            <span>Créditos: {{ asignatura.Creditos }}</span>
            <span>
              SATCA: {{ asignatura.Satca_Total }} (T:{{ asignatura.Satca_Teoricas }}, P:{{
                asignatura.Satca_Practicas
              }})
            </span>
          </div>
        </div>

        <Transition name="fade">
          <div v-show="detalleAbierto === asignatura.ClaveAsignatura" class="submenu">
            <button @click="verPDF('instrumentacion')">Instrumentación Didáctica</button>
            <button @click="verPDF('avance')">Avance Programático</button>
          </div>
        </Transition>
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
const detalleAbierto = ref(null)

const tarjeta = route.params.tarjeta

const fetchAsignaturas = async () => {
  try {
    loading.value = true
    const response = await api.getAsignaturaByTarjetaCompleta(tarjeta)
    const data = response.data

    if (Array.isArray(data) && data.length > 0) {
      asignaturas.value = data.map((item) => ({
        ClaveAsignatura: item.informacionbasica.clave,
        NombreAsignatura: item.informacionbasica.nombre,
        Creditos: item.informacionbasica.creditos,
        Satca_Teoricas: item.informacionbasica.satca.teoricas,
        Satca_Practicas: item.informacionbasica.satca.practicas,
        Satca_Total: item.informacionbasica.satca.total,
      }))

      // Asignatura ficticia para prueba
      asignaturas.value.push({
        ClaveAsignatura: 'PRUEBA123',
        NombreAsignatura: 'Asignatura de Prueba',
        Creditos: 5,
        Satca_Teoricas: 3,
        Satca_Practicas: 2,
        Satca_Total: 5,
      })
    } else {
      asignaturas.value = []
    }
  } catch (err) {
    error.value = 'Error al cargar las asignaturas: ' + (err.response?.data?.error || err.message)
    console.error(err)
  } finally {
    loading.value = false
  }
}

const toggleDetalle = (clave) => {
  detalleAbierto.value = detalleAbierto.value === clave ? null : clave
}

const verPDF = (tipo) => {
  router.push({ name: 'pdf', params: { tarjeta, tipo } })
}

const handleRegresar = () => {
  window.history.back()
}

onMounted(() => {
  fetchAsignaturas()
})
</script>

<style scoped>
/* Transición */
.fade-enter-active,
.fade-leave-active {
  transition: all 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: scaleY(0.95);
}

/* Layout */
.asignaturas-container {
  padding: 20px;
  max-width: 1200px;
  margin: 0 auto;
}

h1 {
  color: #2c3e50;
  margin-bottom: 20px;
}

/* Grid responsivo */
.asignaturas-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 20px;
}

/* Tarjeta */
.asignatura-card {
  background-color: white;
  border: 1px solid #ddd;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s;
}
.asignatura-card:hover {
  transform: translateY(-3px);
}
.asignatura-header {
  cursor: pointer;
}

.asignatura-info {
  margin-top: 10px;
  display: flex;
  flex-direction: column;
  gap: 4px;
  color: #555;
}

/* Submenú */
.submenu {
  margin-top: 15px;
  padding-top: 12px;
  border-top: 1px solid #ccc;
  display: flex;
  flex-direction: column;
  gap: 10px;
}
.submenu button {
  padding: 10px;
  background-color: #2a5dff;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  text-align: left;
  transition: background-color 0.2s ease;
}
.submenu button:hover {
  background-color: #1e45cc;
}

/* Botón regresar */
.boton-regresar {
  position: sticky;
  top: 1rem;
  left: 1rem;
  z-index: 10;
  margin: 10px;
}
.boton-regresar button {
  background-color: #2a5dff;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.2s ease;
}
.boton-regresar button:hover {
  background-color: #1e45cc;
}

/* Estados */
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
</style>
