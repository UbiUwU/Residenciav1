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

          <!-- Tipo de Evento -->
          <va-input
            v-model="form.eventType"
            label="Tipo de Evento"
            placeholder="Escribe el tipo de evento"
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
            track-by="value"
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
import { ref, computed, onMounted } from 'vue'
import { useToast } from 'vuestic-ui'
import api from '../../../services/api.js'

const maestros = ref<any[]>([])
const comisiones = ref<any[]>([])
const loadingCommissions = ref(false)
const error = ref<Error | null>(null)

// Fetch maestros
const fetchMaestros = async () => {
  try {
    const response = await api.getMaestros()
    maestros.value = response.data.data
  } catch (err) {
    error.value = err as Error
  }
}

// Fetch comisiones
const fetchComisiones = async () => {
  loadingCommissions.value = true
  try {
    const response = await api.getComisiones()
    comisiones.value = response.data
  } catch (err) {
    error.value = err as Error
  } finally {
    loadingCommissions.value = false
  }
}

onMounted(() => {
  fetchMaestros()
  fetchComisiones()
})

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
  selectedMaestro: [] as any[]
})

const statusOptions = [
  { text: 'Pendiente', value: 'Pendiente' },
  { text: 'Completado', value: 'Completado' },
]

// Enviar formulario
const submitForm = async () => {
  try {
    if (!form.value.eventType || form.value.eventType.trim() === '') {
      init({ message: 'Por favor escribe un tipo de evento.', color: 'warning' })
      return
    }

    const eventTypeText = form.value.eventType.trim()

    const selectedMaestroValues = form.value.selectedMaestro.map(item =>
      typeof item === 'object' && item.value ? item.value : item
    )

    const payload = {
      eventName: form.value.eventName,
      eventType: { value: eventTypeText }, // Backend espera objeto
      eventDate: form.value.eventDate,
      status: form.value.status,
      selectedMaestro: selectedMaestroValues.map(value => ({ value }))
    }

    await api.crearComision(payload)

    init({ message: 'Comisión registrada exitosamente.', color: 'success' })
    resetForm()
    fetchComisiones()
  } catch (error) {
    console.error('Error al guardar la comisión:', error)
    init({ message: 'Error al guardar la comisión.', color: 'danger' })
  }
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
