<template>
  <div>
    <h1>Asignaturas</h1>
    <ul>
      <li v-for="asig in asignaturas" :key="asig.ClaveAsignatura">
        <button @click="seleccionarAsignatura(asig.ClaveAsignatura)">
          {{ asig.Nombre }}
        </button>
      </li>
    </ul>

    <div v-if="asignaturaSeleccionada">
      <h2>{{ asignaturaSeleccionada.asignatura.Nombre }}</h2>
      <p><strong>Caracterización:</strong> {{ asignaturaSeleccionada.presentacion?.Caracterizacion || 'No disponible' }}</p>
      <p><strong>Créditos:</strong> {{ asignaturaSeleccionada.asignatura.Creditos }}</p>
      <p>
        <strong>SATCA:</strong>
        Teóricas: {{ asignaturaSeleccionada.asignatura.Teoricas || 'N/A' }},
        Prácticas: {{ asignaturaSeleccionada.asignatura.Practicas || 'N/A' }}
      </p>

      <div v-for="tema in asignaturaSeleccionada.temas" :key="tema.id_Tema" style="margin-top: 1em;">
        <h3>Tema {{ tema.Numero }}: {{ tema.Nombre }}</h3>
        <ul>
          <li v-for="subtema in tema.subtemas" :key="subtema.id_Subtema">
            {{ subtema.Nombre }}
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const asignaturas = ref([])
const asignaturaSeleccionada = ref(null)

async function cargarAsignaturas() {
  try {
    const res = await axios.get('/api/asignaturas')
    // Según el controlador, las asignaturas están en res.data.asignaturas
    asignaturas.value = res.data.asignaturas
  } catch (error) {
    console.error('Error al cargar asignaturas:', error)
  }
}

async function seleccionarAsignatura(clave) {
  try {
    const res = await axios.get(`/api/asignaturas/${clave}`)
    asignaturaSeleccionada.value = res.data
  } catch (error) {
    console.error('Error al cargar asignatura:', error)
  }
}

onMounted(() => {
  cargarAsignaturas()
})
</script>

<style scoped>
button {
  cursor: pointer;
  background-color: #eee;
  border: none;
  padding: 0.5em 1em;
  margin-bottom: 0.5em;
  border-radius: 4px;
}
button:hover {
  background-color: #ccc;
}
</style>
