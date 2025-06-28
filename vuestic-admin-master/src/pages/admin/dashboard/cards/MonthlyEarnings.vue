<template>
  <VaCard class="p-4">
    <!-- Título principal -->
    <VaCardTitle>
      <h1 class="text-2xl font-bold text-center text-secondary uppercase mb-2">
        Documentos de Maestros
      </h1>
    </VaCardTitle>

    <VaCardContent>
      <!-- Estado general -->
      <section class="mb-4 flex justify-between items-center">
        <div>
          <p class="text-xl font-semibold">Estado de Entrega</p>
          <p class="text-sm text-secondary">Total de maestros: {{ totalMaestros }}</p>
        </div>
        <div class="flex items-center gap-2">
          <VaBadge color="success" :content="maestrosConDocumentos">
            <span class="text-xs">Entregaron</span>
          </VaBadge>
          <VaBadge color="danger" :content="maestrosSinDocumentos">
            <span class="text-xs">No Entregaron</span>
          </VaBadge>
        </div>
      </section>

      <!-- Filtros -->
      <div class="mb-4 flex gap-2">
        <VaButton
          v-for="(filtro, index) in filtros"
          :key="index"
          :color="selectedFilter === filtro ? 'primary' : 'secondary'"
          @click="selectedFilter = filtro"
          size="small"
        >
          {{ filtro }}
        </VaButton>
      </div>

      <!-- Gráfica resumen -->
      <div class="w-full mb-4">
        <VaChart :data="chartData" type="bar" :options="options" class="h-48 w-full" />
      </div>

      <!-- Gráfica detallada -->
      <div class="w-full mb-4">
        <VaChart :data="detalleData" type="bar" :options="detalleOptions" class="h-72 w-full" />
      </div>

      <!-- Lista de maestros filtrados -->
      <div class="mt-4">
        <h2 class="text-lg font-semibold mb-2">Maestros filtrados:</h2>
        <ul class="divide-y divide-gray-200">
          <li
            v-for="maestro in maestrosFiltrados"
            :key="maestro.id"
            class="flex justify-between items-center py-2"
          >
            <span>{{ maestro.nombre }}</span>
            <div class="flex items-center gap-2">
              <VaBadge
                :color="maestro.entrego ? 'success' : 'danger'"
                :content="maestro.entrego ? 'Entregó' : 'Pendiente'"
              />
              <VaButton
                v-if="!maestro.entrego"
                color="info"
                size="small"
                @click="contactarMaestro(maestro)"
              >
                Contactar
              </VaButton>
            </div>
          </li>
        </ul>
      </div>
    </VaCardContent>
  </VaCard>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { VaCard, VaCardTitle, VaCardContent, VaBadge, VaButton } from 'vuestic-ui'
import VaChart from '../../../../components/va-charts/VaChart.vue'
import { ChartData, ChartOptions } from 'chart.js'

// Datos de maestros
const maestros = [
  { id: 1001, nombre: 'Juan Pérez Gómez', entrego: true },
  { id: 1002, nombre: 'Lucía Ramírez Flores', entrego: true },
  { id: 1003, nombre: 'Carlos González Ruiz', entrego: false },
  { id: 1004, nombre: 'María Torres Mendoza', entrego: true },
  { id: 1005, nombre: 'David Moreno López', entrego: false },
  { id: 1006, nombre: 'Sofía Martínez Castro', entrego: true },
  { id: 1007, nombre: 'Andrés Luna Pérez', entrego: true },
  { id: 1008, nombre: 'Valeria Hernández Jiménez', entrego: false },
  { id: 1009, nombre: 'Raúl Santos Morales', entrego: true },
  { id: 1010, nombre: 'Carmen Flores Ramírez', entrego: true },
  { id: 1011, nombre: 'Tomás Ortiz Díaz', entrego: false },
  { id: 1012, nombre: 'Beatriz Silva Núñez', entrego: true },
  { id: 1013, nombre: 'Eduardo Reyes Gallardo', entrego: true },
  { id: 1014, nombre: 'Patricia Mendoza Rosales', entrego: false },
  { id: 1015, nombre: 'José Alvarez Camacho', entrego: true },
  { id: 1016, nombre: 'Mónica Navarro Ortiz', entrego: true },
  { id: 1017, nombre: 'Daniel Cruz Ramírez', entrego: false },
  { id: 1018, nombre: 'Alejandra Sánchez Pineda', entrego: true },
]

// Filtros
const filtros = ['Todos', 'Entregaron', 'No Entregaron']
const selectedFilter = ref('Todos')

// Maestros filtrados
const maestrosFiltrados = computed(() => {
  if (selectedFilter.value === 'Entregaron') {
    return maestros.filter((m) => m.entrego)
  } else if (selectedFilter.value === 'No Entregaron') {
    return maestros.filter((m) => !m.entrego)
  } else {
    return maestros
  }
})

// Totales
const maestrosConDocumentos = computed(() => maestros.filter((m) => m.entrego).length)
const maestrosSinDocumentos = computed(() => maestros.filter((m) => !m.entrego).length)
const totalMaestros = computed(() => maestros.length)

// Gráfica de resumen (dinámica)
const chartData = computed<ChartData<'bar'>>(() => ({
  labels: ['Entregaron', 'No Entregaron'],
  datasets: [
    {
      label: 'Maestros',
      data: [
        maestrosFiltrados.value.filter((m) => m.entrego).length,
        maestrosFiltrados.value.filter((m) => !m.entrego).length,
      ],
      backgroundColor: ['#4CAF50', '#F44336'],
      borderRadius: 8,
    },
  ],
}))

const options: ChartOptions<'bar'> = {
  responsive: true,
  plugins: {
    legend: { display: false },
    tooltip: { enabled: true },
  },
  scales: {
    y: {
      beginAtZero: true,
      ticks: { stepSize: 1 },
    },
  },
}

// Gráfica detallada (dinámica)
const detalleData = computed<ChartData<'bar'>>(() => ({
  labels: maestrosFiltrados.value.map((m) => m.nombre),
  datasets: [
    {
      label: 'Entregó',
      data: maestrosFiltrados.value.map((m) => (m.entrego ? 1 : 0)),
      backgroundColor: maestrosFiltrados.value.map((m) => (m.entrego ? '#4CAF50' : '#F44336')),
      borderRadius: 6,
    },
  ],
}))

const detalleOptions: ChartOptions<'bar'> = {
  indexAxis: 'y',
  responsive: true,
  plugins: {
    legend: { display: false },
    tooltip: { enabled: true },
  },
  scales: {
    x: {
      beginAtZero: true,
      ticks: { stepSize: 1, precision: 0 },
    },
  },
}

// Acción para contactar a un maestro (simulada)
function contactarMaestro(maestro: { id: number; nombre: string }) {
  alert(`¡Contactando al maestro ${maestro.nombre} (ID: ${maestro.id})!`)
}
</script>


<style scoped>
.text-secondary {
  color: #6c757d;
}
</style>
