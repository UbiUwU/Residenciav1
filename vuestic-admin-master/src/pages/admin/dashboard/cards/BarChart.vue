<script setup>
import { onMounted, ref } from 'vue'
import { Chart, registerables } from 'chart.js'

Chart.register(...registerables)

const canvasRef = ref(null)

onMounted(() => {
  const ctx = canvasRef.value.getContext('2d')

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Maestro A', 'Maestro B', 'Maestro C', 'Maestro D'],
      datasets: [{
        label: 'Avance (%)',
        data: [80, 55, 90, 30],
        backgroundColor: '#3B82F6',
        borderColor: '#1E40AF',
        borderWidth: 1,
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          max: 100,
        }
      }
    }
  })
})
</script>

<template>
  <canvas ref="canvasRef" class="w-full h-64"></canvas>
</template>
