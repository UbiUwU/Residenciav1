<template>
  <div class="p-4">
    <va-card>
      <va-card-title>
        <h2 class="va-h5">Registrar Comisión</h2>
      </va-card-title>
      <va-card-content>
        <form @submit.prevent="submitForm" class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Fecha -->
          <va-date-input
            v-model="form.eventDate"
            label="Fecha del Evento"
            clearable
            required
          />

          <!-- Evento -->
          <va-input
            v-model="form.eventName"
            label="Nombre del Evento"
            required
          />

          <!-- Tipo -->
          <va-select
            v-model="form.eventType"
            :options="eventTypeOptions"
            label="Tipo de Evento"
            filterable
            clearable
            required
            />

          <!-- Estado -->
          <va-select
            v-model="form.status"
            :options="statusOptions"
            label="Estado"
            required
          />

          <!-- Asignar Maestro(s) -->
          <va-select
            v-model="form.selectedMaestro"
            :options="maestroOptions"
            label="Asignar Maestro(s)"
            multiple
            clearable
            required
            />


          <!-- Botones -->
          <div class="col-span-1 md:col-span-2 flex justify-end gap-2 mt-4">
            <va-button preset="secondary" @click="resetForm">
              Cancelar
            </va-button>
            <va-button type="submit" color="primary">
              Guardar
            </va-button>
          </div>
        </form>
      </va-card-content>
    </va-card>
  </div>
  
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useToast } from 'vuestic-ui'
import api from '../../../services/api.js'

const maestros = ref<any[]>([])
const isLoading = ref(false)
const error = ref<Error | null>(null)

async function fetchMaestros() {
  isLoading.value = true
  error.value = null
  try {
    const response = await api.getMaestros()
    maestros.value = response.data.data
  } catch (err) {
    error.value = err as Error
  } finally {
    isLoading.value = false
  }
}
fetchMaestros()

const maestroOptions = computed(() =>
  maestros.value.map(m => ({
    text: `${m.nombre} ${m.apellidopaterno} ${m.apellidomaterno}`,
    value: m.tarjeta
  }))
)

const { init } = useToast()

const form = ref({
  eventDate: null,
  eventName: '',
  eventType: '',
  status: 'Pendiente',
  selectedMaestro: [] as any[] // Se define como any[] porque va-select devuelve objetos {text, value}
})

const eventTypeOptions = [
  { text: 'Jurado de congreso', value: 'jurado_congreso' },
  { text: 'Evento Titulación', value: 'evento_titulacion' },
  { text: 'Conferencia', value: 'conferencia' },
  { text: 'Reunión Académica', value: 'reunion_academica' },
  { text: 'Taller', value: 'taller' },
  { text: 'Evaluación', value: 'evaluacion' },
]

const statusOptions = [
  { text: 'Pendiente', value: 'Pendiente' },
  { text: 'Completado', value: 'Completado' },
]

const submitForm = async () => {
  // Procesar selectedMaestro para extraer solo los valores
  const selectedMaestroValues = form.value.selectedMaestro.map(
    (item: any) => item.value ?? item // si item es directamente el value o un objeto {text, value}
  )

  // Construir el payload limpio
  const payload = {
    eventDate: form.value.eventDate,
    eventName: form.value.eventName,
    eventType: form.value.eventType, // por si el select devuelve el objeto completo
    status: form.value.status,
    maestros: selectedMaestroValues
  }

  console.log('Datos del formulario a enviar:', JSON.stringify(payload, null, 2))

  // Envío simulado
  init({ message: 'Comisión registrada exitosamente.', color: 'success' })
  resetForm()
}

const resetForm = () => {
  form.value = {
    eventDate: null,
    eventName: '',
    eventType: '',
    status: 'Pendiente',
    selectedMaestro: []
  }
}
</script>
