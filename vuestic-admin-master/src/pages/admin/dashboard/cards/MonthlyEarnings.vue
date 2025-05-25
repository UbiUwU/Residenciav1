<template>
  <VaCard>
    <VaCardTitle>
      <h1 class="card-title text-tag text-secondary font-bold uppercase">Documentos de Maestros</h1>
    </VaCardTitle>
    <VaCardContent>
      <div class="p-1 bg-black rounded absolute right-4 top-4">
        <VaIcon name="mso-description" color="#fff" size="large" />
      </div>
      <section class="mb-4">
        <div class="text-xl font-bold">Estado de Entrega</div>
        <p class="text-xs text-secondary">Total de maestros: {{ totalMaestros }}</p>
      </section>
      <div class="w-full flex items-center">
        <VaChart :data="chartData" type="bar" :options="options" class="h-40 w-full" />
      </div>
    </VaCardContent>
  </VaCard>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { VaCard } from 'vuestic-ui'
import VaChart from '../../../../components/va-charts/VaChart.vue'
import { ChartData, ChartOptions } from 'chart.js'

// Datos de ejemplo
const maestrosConDocumentos = 18
const maestrosSinDocumentos = 7

const totalMaestros = computed(() => maestrosConDocumentos + maestrosSinDocumentos)

const chartData: ChartData<'bar'> = {
  labels: ['Entregaron', 'No Entregaron'],
  datasets: [
    {
      label: 'Maestros',
      data: [maestrosConDocumentos, maestrosSinDocumentos],
      backgroundColor: ['#4CAF50', '#F44336'], // Verde y rojo
      borderRadius: 8,
    },
  ],
}

const options: ChartOptions<'bar'> = {
  responsive: true,
  plugins: {
    legend: {
      display: false,
    },
    tooltip: {
      enabled: true,
    },
  },
  scales: {
    y: {
      beginAtZero: true,
      ticks: {
        stepSize: 1,
      },
    },
  },
}
</script>
