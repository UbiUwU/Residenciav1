<template>
  <div>
    <va-calendar
      mode="agenda"
      :events="calendarEvents"
      :event-color="getEventColor"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../services/api'

// Estado para eventos
const calendarEvents = ref([])

// Función para cargar eventos de la API y adaptarlos para va-calendar
const cargarEventos = async () => {
  try {
    const response = await api.getEventos()
    // Mapear eventos al formato que espera va-calendar
    calendarEvents.value = response.data.map(evento => ({
      id: evento.id_evento,
      title: evento.nombre_evento,
      description: evento.descripcion_evento,
      start: new Date(evento.fecha_inicio),
      end: new Date(evento.fecha_fin),
      color: 'primary', // o puedes asignar colores según tipo
    }))
  } catch (error) {
    console.error('Error cargando eventos:', error)
  }
}

onMounted(() => {
  cargarEventos()
})

// Opcional: función para asignar colores según tipo de evento
const getEventColor = (event) => {
  switch (event.color) {
    case 'vacaciones': return 'red'
    case 'servicio_social': return 'green'
    case 'inscripcion': return 'blue'
    default: return 'primary'
  }
}
</script>
