<script setup lang="ts">
import { ref } from 'vue'

// Definimos las interfaces para las materias y maestros
interface Materia {
  [key: string]: boolean // Esto permite acceder a cualquier clave de tipo string y obtener un valor booleano
  'Instrumentación Didáctica': boolean
  'Planeación del curso': boolean
  'Acuse del estudiante': boolean
  'Evaluación Diagnóstica, resultados y acciones a tomar': boolean
  'Carpetas de evidencias': boolean
  'Listas de calificaciones parciales': boolean
  'Evidencias de asesorias y atención a alumnos': boolean
}

interface Maestro {
  nombre: string
  materias: { [key: string]: Materia }
}

// Datos de los maestros con materias
const maestros = ref<Maestro[]>([
  {
    nombre: 'Ana Sánchez',
    materias: {
      'Matemáticas Discretas': {
        'Instrumentación Didáctica': true,
        'Planeación del curso': true,
        'Acuse del estudiante': true,
        'Evaluación Diagnóstica, resultados y acciones a tomar': true,
        'Carpetas de evidencias': true,
        'Listas de calificaciones parciales': true,
        'Evidencias de asesorias y atención a alumnos': true,
      },
      'Programación I': {
        'Instrumentación Didáctica': false,
        'Planeación del curso': true,
        'Acuse del estudiante': true,
        'Evaluación Diagnóstica, resultados y acciones a tomar': true,
        'Carpetas de evidencias': true,
        'Listas de calificaciones parciales': true,
        'Evidencias de asesorias y atención a alumnos': true,
      },
    },
  },
  {
    nombre: 'Carlos Pérez',
    materias: {
      'Matemáticas Discretas': {
        'Instrumentación Didáctica': true,
        'Planeación del curso': true,
        'Acuse del estudiante': true,
        'Evaluación Diagnóstica, resultados y acciones a tomar': true,
        'Carpetas de evidencias': true,
        'Listas de calificaciones parciales': false,
        'Evidencias de asesorias y atención a alumnos': true,
      },
    },
  },
])

// Función para calcular el estado del maestro (completo, parcial, incompleto)
const maestroEstado = (maestro: Maestro): string => {
  let total = 0
  let entregados = 0
  // Iteramos sobre todas las materias y sus entregas
  for (const materia in maestro.materias) {
    const materiaEntregada = maestro.materias[materia]
    for (const documento in materiaEntregada) {
      total++
      if (materiaEntregada[documento]) {
        entregados++
      }
    }
  }
  // Determinamos el estado basado en las entregas
  if (entregados === total) {
    return 'Completo'
  } else if (entregados > 0) {
    return 'Parcial'
  } else {
    return 'Incompleto'
  }
}

// Definimos la clase para el estado visual
const estadoClase = (maestro: Maestro): string => {
  const estado = maestroEstado(maestro)
  switch (estado) {
    case 'Completo':
      return 'bg-green-500 text-white' // Verde para completo
    case 'Parcial':
      return 'bg-yellow-500 text-white' // Amarillo para parcial
    case 'Incompleto':
      return 'bg-red-500 text-white' // Rojo para incompleto
    default:
      return ''
  }
}
</script>

<template>
  <div class="overflow-x-auto">
    <!-- Tabla de maestros con estado -->
    <table class="min-w-full table-auto border-collapse">
      <thead>
        <tr class="">
          <th class="p-3 border text-left">Nombre del Maestro</th>
          <th class="p-3 border text-left">Estado</th>
          <th class="p-3 border text-left">Materias y Documentos</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(maestro, index) in maestros" :key="index">
          <td class="p-3 border">{{ maestro.nombre }}</td>
          <td :class="estadoClase(maestro)" class="p-3 border text-center font-bold">
            {{ maestroEstado(maestro) }}
          </td>
          <td class="p-3 border">
            <div v-for="(materia, materiaNombre) in maestro.materias" :key="materiaNombre" class="mb-4">
              <h3 class="font-semibold text-lg mb-2">{{ materiaNombre }}</h3>
              <ul class="list-inside">
                <li v-for="(estado, documento) in materia" :key="documento" class="flex justify-between">
                  <span class="font-medium">{{ documento }}:</span>
                  <span :class="estado ? 'text-green-500' : 'text-red-500'" class="font-medium">
                    {{ estado ? 'Entregado' : 'Pendiente' }}
                  </span>
                </li>
              </ul>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<style scoped>
/* Estilos para las celdas de estado */
.bg-green-500 {
  background-color: #48bb78; /* Verde */
}
.bg-yellow-500 {
  background-color: #ecc94b; /* Amarillo */
}
.bg-red-500 {
  background-color: #f56565; /* Rojo */
}
.text-white {
  color: white;
}

.text-green-500 {
  color: #48bb78; /* Verde */
}

.text-red-500 {
  color: #f56565; /* Rojo */
}

.font-semibold {
  font-weight: 600;
}

.font-medium {
  font-weight: 500;
}

/* Bordes y espaciado */
table {
  border-collapse: collapse;
}

th,
td {
  padding: 10px;
  border: 1px solid #e2e8f0;
}

.list-inside {
  list-style-position: inside;
}

.mb-4 {
  margin-bottom: 16px;
}

.bg-gray-100 {
  background-color: #f7fafc;
}
</style>
