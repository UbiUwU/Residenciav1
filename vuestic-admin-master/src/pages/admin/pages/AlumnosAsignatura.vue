<template>
  <div class="alumnos-container">
    <h1 class="title">Alumnos - {{ claveasignatura }} ({{ clavegrupo }})</h1>

    <div v-if="loading" class="loading">Cargando alumnos...</div>
    <div v-else-if="error" class="error">{{ error }}</div>
    <div v-else-if="alumnos.length === 0" class="no-data">No hay alumnos registrados para esta materia y grupo.</div>

    <div v-else class="alumnos-list">
      <div v-for="alumno in alumnos" :key="alumno.numerocontrol" class="alumno-card">
        <div class="alumno-header">
          <h3>{{ alumno.nombre }} {{ alumno.apellidopaterno }} {{ alumno.apellidomaterno }}</h3>
          <p class="control">N° Control: {{ alumno.numerocontrol }}</p>
          <p class="carrera">Grupo: {{ alumno.grupo }} | Aula: {{ alumno.aula }}</p>
          <p class="maestro">Maestro: {{ alumno.maestro }}</p>
        </div>

        <div class="horario">
          <h4>Horario</h4>
          <table>
            <thead>
              <tr>
                <th>Día</th>
                <th>Hora Inicio</th>
                <th>Hora Fin</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(horas, dia) in alumno.horario" :key="dia">
                <td>{{ dia }}</td>
                <td>{{ horas.hi || '-' }}</td>
                <td>{{ horas.hf || '-' }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="boton-regresar">
      <button @click="router.back()">← Regresar</button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../../../services/api'
interface Horario {
  hi: string | null
  hf: string | null
}

interface Alumno {
  numerocontrol: number
  nombre: string
  apellidopaterno: string
  apellidomaterno: string
  grupo: string
  aula: string
  maestro: string
  horario: Record<string, Horario>
}

const route = useRoute()
const router = useRouter()

const clavegrupo = route.params.clavegrupo as string
const claveasignatura = route.params.claveasignatura as string

const alumnos = ref<Alumno[]>([])
const loading = ref(true)
const error = ref<string | null>(null)

const fetchAlumnos = async () => {
  loading.value = true
  error.value = null
  alumnos.value = []

  try {
    const response = await api.getAlumnos()
    const todosAlumnos = response.data.data

    for (const alumno of todosAlumnos) {
      const detalle = await api.getAlumnosPorAsignatura(alumno.numerocontrol)
      const cargas = detalle.data.data.cargas_academicas || []

      for (const carga of cargas) {
        for (const det of carga.detalles) {
          const asignatura = det.horario_asignatura
          if (asignatura.claveasignatura === claveasignatura && asignatura.clavegrupo === clavegrupo) {
            alumnos.value.push({
              numerocontrol: alumno.numerocontrol,
              nombre: alumno.nombre,
              apellidopaterno: alumno.apellidopaterno,
              apellidomaterno: alumno.apellidomaterno,
              grupo: asignatura.clavegrupo,
              aula: asignatura.claveaula,
              maestro: asignatura.maestro.nombre,
              horario: {
                Lunes: asignatura.lunes,
                Martes: asignatura.martes,
                Miércoles: asignatura.miercoles,
                Jueves: asignatura.jueves,
                Viernes: asignatura.viernes,
                Sábado: asignatura.sabado,
              },
            })
          }
        }
      }
    }
  } catch (err: any) {
    error.value = 'Error al cargar alumnos: ' + (err.response?.data?.error || err.message)
  } finally {
    loading.value = false
  }
}

onMounted(fetchAlumnos)
</script>

<style scoped>
.alumnos-container {
  padding: 20px;
  max-width: 900px;
  margin: auto;
}

.title {
  text-align: center;
  margin-bottom: 25px;
  color: #2c3e50;
  font-size: 1.8rem;
}

.alumnos-list {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.alumno-card {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  padding: 20px;
  transition: transform 0.2s ease;
}

.alumno-card:hover {
  transform: translateY(-2px);
}

.alumno-header h3 {
  margin: 0 0 10px;
  color: #2a5dff;
}

.control,
.carrera,
.maestro {
  margin: 3px 0;
  font-size: 0.9rem;
  color: #555;
}

.horario {
  margin-top: 15px;
}

.horario h4 {
  margin-bottom: 8px;
  color: #333;
}

.horario table {
  width: 100%;
  border-collapse: collapse;
}

.horario th,
.horario td {
  border: 1px solid #ddd;
  padding: 6px 10px;
  text-align: center;
}

.horario th {
  background-color: #f0f0f0;
  font-weight: 600;
}

.boton-regresar {
  text-align: center;
  margin-top: 25px;
}

.boton-regresar button {
  background-color: #2a5dff;
  color: white;
  border: none;
  padding: 0.6rem 1.2rem;
  border-radius: 5px;
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
  padding: 15px;
  font-size: 1.1rem;
}

.error {
  color: #e74c3c;
}
</style>
