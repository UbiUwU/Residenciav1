<script setup>
import { ref } from 'vue'
import api from '../services/api.js'

const plantillas = ref([])
const error = ref(null)

async function cargarPlantillas() {
  try {
    const res = await api.getPlantillas()
    plantillas.value = res.data
  } catch (e) {
    error.value = 'Error cargando plantillas'
  }
}

cargarPlantillas()
</script>

<template>
  <div>
    <h2>Plantillas</h2>
    <ul>
      <li v-for="p in plantillas" :key="p.id">{{ p.nombre }} ({{ p.tipo }}) - Estado: {{ p.estado }}</li>
    </ul>
    <p v-if="error">{{ error }}</p>
  </div>
</template>
