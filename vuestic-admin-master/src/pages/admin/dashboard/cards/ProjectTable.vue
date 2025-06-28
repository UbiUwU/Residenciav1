<script setup lang="ts">
import { ref } from 'vue'

// Definimos las interfaces para las materias y maestros
interface Materia {
  [key: string]: boolean
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

// Datos de los maestros (todos entregados en true)
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
        'Evidencias de asesorias y atención a alumnos': true
      }
    }
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
        'Listas de calificaciones parciales': true,
        'Evidencias de asesorias y atención a alumnos': true
      }
    }
  }
])

// Calcula el estado del maestro (Completo, Parcial o Incompleto)
const maestroEstado = (maestro: Maestro): string => {
  let total = 0
  let entregados = 0
  for (const materia in maestro.materias) {
    const materiaEntregada = maestro.materias[materia]
    for (const documento in materiaEntregada) {
      total++
      if (materiaEntregada[documento]) {
        entregados++
      }
    }
  }
  if (entregados === total) return 'Completo'
  if (entregados > 0) return 'Parcial'
  return 'Incompleto'
}

// Asigna colores de fondo al estado
const estadoClase = (maestro: Maestro): string => {
  const estado = maestroEstado(maestro)
  switch (estado) {
    case 'Completo':
      return 'bg-green-500 text-white'
    case 'Parcial':
      return 'bg-yellow-500 text-white'
    case 'Incompleto':
      return 'bg-red-500 text-white'
    default:
      return ''
  }
}
</script>

<template>
  <div class="container mx-auto px-4 py-6">
    <h1 class="text-3xl font-bold mb-6 text-center text-gray-800">
      Estado de Entrega de Documentos por Maestro
    </h1>
    <div class="overflow-x-auto">
      <table class="min-w-full table-auto border-collapse shadow-lg rounded-lg overflow-hidden">
        <thead class="bg-gray-200">
          <tr>
            <th class="p-4 border text-left font-semibold text-gray-700">Nombre del Maestro</th>
            <th class="p-4 border text-left font-semibold text-gray-700">Estado</th>
            <th class="p-4 border text-left font-semibold text-gray-700">Materias y Documentos</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(maestro, index) in maestros"
            :key="index"
            class="hover:bg-gray-100 transition-colors"
          >
            <td class="p-4 border font-medium text-gray-800">{{ maestro.nombre }}</td>
            <td :class="[estadoClase(maestro), 'p-4 border text-center font-bold rounded']">
              {{ maestroEstado(maestro) }}
            </td>
            <td class="p-4 border">
              <div
                v-for="(materia, materiaNombre) in maestro.materias"
                :key="materiaNombre"
                class="mb-4"
              >
                <h3 class="font-semibold text-lg text-indigo-700 mb-2">{{ materiaNombre }}</h3>
                <ul class="list-inside space-y-1">
                  <li
                    v-for="(estado, documento) in materia"
                    :key="documento"
                    class="flex justify-between items-center"
                  >
                    <span class="font-medium">{{ documento }}:</span>
                    <span class="text-green-600 font-semibold">Entregado</span>
                  </li>
                </ul>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style scoped>
.bg-green-500 {
  background-color: #48bb78;
}
.bg-yellow-500 {
  background-color: #ecc94b;
}
.bg-red-500 {
  background-color: #f56565;
}
.text-white {
  color: white;
}
.table-auto {
  border-radius: 8px;
  overflow: hidden;
}
.shadow-lg {
  box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
}
.rounded-lg {
  border-radius: 8px;
}
</style>
