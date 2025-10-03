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
      <!-- Usa clave compuesta para el key -->
      <div
        v-for="asignatura in asignaturas"
        :key="asignatura.ClaveAsignatura + '-' + asignatura.Grupo"
        class="asignatura-card"
      >
        <div class="asignatura-header" @click="toggleDetalle(asignatura.ClaveAsignatura, asignatura.Grupo)">
          <h3>{{ asignatura.ClaveAsignatura }} - {{ asignatura.NombreAsignatura }}</h3>
          <div class="asignatura-info">
            <span>Grupo: {{ asignatura.Grupo }}</span>
            <span>Créditos: {{ asignatura.Creditos }}</span>
            <span>
              SATCA: {{ asignatura.Satca_Total }} (T:{{ asignatura.Satca_Teoricas }}, P:{{
                asignatura.Satca_Practicas
              }})
            </span>
          </div>
        </div>

        <Transition name="fade">
          <!-- Compara con la clave compuesta -->
          <div v-show="detalleAbierto === asignatura.ClaveAsignatura + '-' + asignatura.Grupo" class="submenu">
            <button @click="verPDF('instrumentacion', asignatura)">Instrumentación Didáctica</button>
            <button @click="verPDF('avance', asignatura)">Avance Programático</button>
            <button @click="verAlumnos(asignatura)">Ver alumnos</button>
          </div>
        </Transition>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../../../services/api'

// Tipo para las asignaturas
interface Asignatura {
  ClaveAsignatura: string
  NombreAsignatura: string
  Creditos: number
  Satca_Teoricas: number
  Satca_Practicas: number
  Satca_Total: number
  Grupo: string
  Periodo: number
}

const route = useRoute()
const router = useRouter()

// Refs tipados para evitar never[]
const asignaturas = ref<Asignatura[]>([])
const loading = ref<boolean>(true)
const error = ref<string | null>(null)
const detalleAbierto = ref<string | null>(null)

const tarjeta = route.params.tarjeta as string

// Cargar asignaturas desde el backend
const fetchAsignaturas = async () => {
  try {
    loading.value = true
    const response = await api.getAsignaturaByTarjetaCompleta(tarjeta)

    const periodos = response.data?.data || {}
    const horarios = Object.values(periodos).flat() as any[]

    if (horarios.length > 0) {
      asignaturas.value = horarios.map((item: any) => ({
        ClaveAsignatura: item.asignatura?.ClaveAsignatura ?? 'N/A',
        NombreAsignatura: item.asignatura?.NombreAsignatura ?? 'Sin nombre',
        Creditos: item.asignatura?.Creditos ?? 0,
        Satca_Teoricas: item.asignatura?.Satca_Teoricas ?? 0,
        Satca_Practicas: item.asignatura?.Satca_Practicas ?? 0,
        Satca_Total: item.asignatura?.Satca_Total ?? 0,
        Grupo: item.grupo?.clavegrupo || 'Sin grupo',
        Periodo: item.idperiodoescolar,
      }))
    } else {
      asignaturas.value = []
    }
  } catch (err: any) {
    error.value = 'Error al cargar las asignaturas: ' + (err.response?.data?.error || err.message)
    console.error(err)
  } finally {
    loading.value = false
  }
}

const handleRegresar = () => {
  router.back()
}

const toggleDetalle = (clave: string, grupo: string) => {
  const id = clave + '-' + grupo
  detalleAbierto.value = detalleAbierto.value === id ? null : id
}

const verPDF = (tipo: 'instrumentacion' | 'avance', asignatura: Asignatura) => {
  const params = {
    tarjeta: tarjeta,
    periodo: asignatura.Periodo,
    grupo: asignatura.Grupo,
    clave_asignatura: asignatura.ClaveAsignatura,
  }

  const routeName = tipo === 'instrumentacion' ? 'instrumentacion' : 'avance'
  router.push({ name: routeName, params })
}

const verAlumnos = (asignatura: Asignatura) => {
  router.push({
    name: 'alumnos',
    params: {
      clavegrupo: asignatura.Grupo,
      claveasignatura: asignatura.ClaveAsignatura,
    },
  })
}

onMounted(fetchAsignaturas)
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: all 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: scaleY(0.95);
}

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
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 20px;
}

.asignatura-card {
  background-color: #ffffff;
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
