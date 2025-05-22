<template>
  <div>
    <va-card>
      <va-card-title>Calendario de Eventos</va-card-title>
      <va-card-content>
        <va-date-picker
          v-model="fechaSeleccionada"
          :attributes="eventosMarcados"
          mode="calendar"
          is-expanded
        />
      </va-card-content>
    </va-card>

    <va-card v-if="eventosFiltrados.length > 0" class="mt-4">
      <va-card-title>Eventos del {{ fechaSeleccionada }}</va-card-title>
      <va-card-content>
        <va-list>
          <va-list-item
            v-for="evento in eventosFiltrados"
            :key="evento.id"
          >
            <va-list-item-section>
              <strong>{{ evento.nombre }}</strong><br />
              <span>{{ evento.descripcion }}</span><br />
              <small>
                {{ formatDate(evento.fecha_inicio) }}
                -
                {{ formatDate(evento.fecha_fin) }}
              </small>
            </va-list-item-section>
          </va-list-item>
        </va-list>
      </va-card-content>
    </va-card>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useToast } from 'vuestic-ui'
import api from '../services/api' // Asegúrate que tu api esté bien importado

const { init } = useToast()

const fechaSeleccionada = ref(new Date().toISOString().substring(0, 10))
const eventos = ref([])

// Cargar eventos desde API
const cargarEventos = async () => {
  try {
    const response = await api.getEventos()
eventos.value = response.data
  } catch (error) {
    init({ message: 'Error al cargar eventos', color: 'danger' })
    console.error(error)
  }
}

// Eventos visibles por fecha seleccionada
const eventosFiltrados = computed(() => {
  return eventos.value.filter(e =>
    fechaSeleccionada.value >= e.fecha_inicio &&
    fechaSeleccionada.value <= e.fecha_fin
  )
})

// Marcado en el calendario
const eventosMarcados = computed(() => {
  return eventos.value.map(evento => ({
    dates: {
      start: evento.fecha_inicio,
      end: evento.fecha_fin
    },
    contentStyle: {
      backgroundColor: '#E0AF58',
      color: '#000000',
      borderRadius: '6px'
    },
    popover: {
      label: evento.nombre
    }
  }))
})

const formatDate = date => {
  return new Date(date).toLocaleDateString('es-MX', {
    year: 'numeric', month: 'short', day: 'numeric'
  })
}

onMounted(() => {
  cargarEventos()
})
</script>

<style scoped>
.va-date-picker {
  max-width: 100%;
}
</style>
