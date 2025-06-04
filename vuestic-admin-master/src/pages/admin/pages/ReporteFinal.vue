<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useToast } from 'vuestic-ui'
import api from '../../../services/api.js'

const route = useRoute()
const reporte = ref([])
const loadingReporte = ref(false)
const toast = useToast()

const tarjeta = route.params.tarjeta

const fetchAsignaturas = async () => {
  try {
    loadingReporte.value = true
    // 👇 Cambio aquí: usar getDetalleGruposPorCarreraByTarjeta
    const response = await api.getDetalleGruposPorCarreraByTarjeta(tarjeta)
    console.log('DATA RECIBIDA:', response.data)
    reporte.value = response.data
  } catch (error) {
    toast.notify({ message: `Error: ${error.message || 'Error al obtener reporte'}`, color: 'danger' })
  } finally {
    loadingReporte.value = false
  }
}

const handleRegresar = () => {
  window.history.back()  // o router.back() si usas Vue Router
}

onMounted(() => {
  fetchAsignaturas()
})
</script>

<template>
   <div class="boton-regresar">
    <button @click="handleRegresar">
      ← Regresar
    </button>
  </div>

  <div v-if="loadingReporte" class="loading-container">
    Cargando reporte...
  </div>
<div v-if="reporte.length" class="reporte-container">
    <!-- Encabezados de asignatura -->
       <h1 class="va-h4 mb-4">Reporte final</h1>

  
    <div
      v-for="(asignatura, index) in reporte"
      :key="index"
      class="asignatura-card"
    >
      <h3>{{ asignatura.informacionbasica.nombre }} ({{ asignatura.informacionbasica.clave }})</h3>
      <p><strong>Créditos:</strong> {{ asignatura.informacionbasica.creditos }}</p>
      <p><strong>Nombre del maestro:</strong> {{ asignatura.informacionbasica.maestro || 'Juan Pérez' }}</p>
      <p><strong>Departamento:</strong> {{ asignatura.informacionbasica.departamento || 'Desconocido' }}</p>
    </div>

    <!-- Tabla de todas las carreras (una sola tabla) -->
    <div class="carreras-table">
      <table>
        <thead>
          <tr>
            <th rowspan="2">Asignatura</th>
            <th rowspan="2">Carrera</th>
            <th rowspan="2">A</th>
            <th colspan="2" style="text-align: center;">B</th>
            <th rowspan="2">C</th>
            <th rowspan="2">D</th>
            <th rowspan="2">E</th>
            <th rowspan="2">F</th>
            <th rowspan="2">G</th>
            <th rowspan="2">H</th>
            <!-- Aquí hacemos el encabezado agrupado -->
            
          </tr>
          <tr>
            <th>O</th>
            <th>Co</th>
          </tr>
        </thead>
        <tbody>
          <template v-for="(asignatura, index) in reporte" :key="'asignatura-' + index">
            <template v-for="(agp, agpIndex) in asignatura.aulas_grupos_periodos" :key="'agp-' + agpIndex">
              <template v-for="(carrera, carreraIndex) in agp.carreras" :key="'carrera-' + carreraIndex">
                <tr>
                  <td>{{ asignatura.informacionbasica.nombre }}</td>
                  <td>{{ carrera.nombre_carrera }}</td>
                  <td>{{ carrera.A || 0 }}</td>
                  <td>{{ carrera.C || 0 }}</td>
                  <td>{{ carrera.D || 0 }}</td>
                  <td>{{ carrera.E || 0 }}</td>
                  <td>{{ carrera.F || 0 }}</td>
                  <td>{{ carrera.G || 0 }}</td>
                  <td>{{ carrera.H || 0 }}</td>
                  <!-- Aquí renderizamos O, Co y B detallado -->
                  <td>{{ carrera.O || 0 }}</td>
                  <td>{{ carrera.Co || 0 }}</td>
                </tr>
              </template>
            </template>
          </template>
        </tbody>
      </table>

    </div>

    <!-- Lista de alumnos al final -->
<div class="alumnos-list-container">
  <h5>Alumnos por Carrera:</h5>
  <template v-for="(asignatura, index) in reporte" :key="'alumnos-asignatura-' + index">
    <div class="asignatura-section">
      <h4>{{ asignatura.informacionbasica.nombre }} ({{ asignatura.informacionbasica.clave }})</h4>
      <template v-for="(agp, agpIndex) in asignatura.aulas_grupos_periodos" :key="'alumnos-agp-' + agpIndex">
        <template v-for="(carrera, carreraIndex) in agp.carreras" :key="'alumnos-carrera-' + carreraIndex">
          <div class="carrera-section">
            <h5>{{ carrera.nombre_carrera }}</h5>
            <div class="alumnos-grid">
              <div
                v-for="(alumno, alumnoIndex) in carrera.alumnos"
                :key="'alumno-' + alumnoIndex"
                class="alumno-card"
              >
                <div class="alumno-header">
                  <span class="badge numero-control">{{ alumno.numero_control }}</span>
                  <span class="alumno-nombre">{{ alumno.nombre_completo }}</span>
                </div>
                <div class="alumno-calificaciones">
                  <span class="label">Calificaciones:</span>
                  <span class="calificacion">{{ alumno.calificaciones ?? 'Sin calificaciones' }}</span>
                </div>
              </div>
            </div>
          </div>
        </template>
      </template>
    </div>
  </template>
</div>

  </div>

  <div v-if="!loadingReporte && !reporte.length" class="empty-container">
    No hay datos para mostrar.
  </div>
</template>


<style scoped>
.loading-container,
.empty-container {
  text-align: center;
  margin-top: 1.5rem;
  font-weight: bold;
  font-size: 1.1rem;
  color: #555;
}

.reporte-container {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  margin-top: 1rem;
}

.asignatura-card {
  background-color: #ffffff;
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 1rem;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}

.agp-section {
  margin-top: 1rem;
  padding-left: 1rem;
  border-left: 3px solid #007bff20;
}

.agp-header {
  font-weight: 500;
  margin-bottom: 0.5rem;
}

.carrera-card {
  background-color: #f9f9f9;
  border-radius: 6px;
  padding: 0.75rem;
  margin-top: 0.5rem;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 0.75rem;
}

th,
td {
  border: 1px solid #ccc;
  padding: 0.4rem 0.6rem;
  text-align: left;
  font-size: 0.95rem;
}

th {
  background-color: #f0f0f0;
  font-weight: 600;
}

td {
  background-color: #fff;
}

.carreras-table {
  margin-top: 1rem;
  overflow-x: auto;
}
.alumnos-list-container {
  margin-top: 2rem;
  background-color: #f9faff;
  padding: 1rem;
  border-radius: 8px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}

.alumnos-list-container h5 {
  font-size: 1.2rem;
  color: #2a5dff;
  margin-bottom: 0.5rem;
}

.asignatura-section h4 {
  color: #333;
  margin-top: 1rem;
  font-weight: 600;
}

.carrera-section h5 {
  margin-top: 0.5rem;
  color: #444;
  font-size: 1.05rem;
  font-weight: 500;
}

.alumnos-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 0.75rem;
  margin-top: 0.5rem;
}

.alumno-card {
  background-color: #fff;
  border: 1px solid #d0d9ff;
  border-radius: 8px;
  padding: 0.75rem;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  transition: transform 0.2s ease;
}

.alumno-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.alumno-header {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-weight: 600;
  font-size: 1rem;
}

.badge.numero-control {
  background-color: #2a5dff;
  color: #fff;
  padding: 0.2rem 0.5rem;
  border-radius: 4px;
  font-size: 0.85rem;
}

.alumno-nombre {
  color: #333;
  flex: 1;
}

.alumno-calificaciones {
  display: flex;
  align-items: center;
  gap: 0.3rem;
  font-size: 0.9rem;
}

.label {
  font-weight: 500;
  color: #666;
}

.calificacion {
  background-color: #f0f4ff;
  padding: 0.2rem 0.5rem;
  border-radius: 4px;
  font-weight: 500;
}
.boton-regresar {
  position: sticky;
  top: 1rem;
  left: 1rem;
  z-index: 10;
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
</style>
