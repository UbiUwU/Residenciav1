<template>
  <div class="p-4">
    <va-card>
      <va-card-title>Calendario Interactivo</va-card-title>
      <va-card-content>
        <table class="calendar-table">
          <thead>
            <tr>
              <th>Hora</th>
              <th v-for="day in days" :key="day">{{ day }}</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="hour in hours" :key="hour">
              <td class="time-label">{{ hour }}</td>
              <td
                v-for="day in days"
                :key="day"
                class="calendar-cell"
                :style="getCellStyle(day, hour)"
                @click="openModal(day, hour)"
              >
                {{ getActivityLabel(day, hour) }}
              </td>
            </tr>
          </tbody>
        </table>
      </va-card-content>
    </va-card>

    <va-modal v-model="showModal" hide-default-actions>
      <template #header>
        <h3>Asignar actividad</h3>
      </template>
      <va-input v-model="activityInput" label="Actividad" class="mb-4" />
      <va-button @click="saveActivity" color="primary">Guardar</va-button>
    </va-modal>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'

const days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado']
const hours = Array.from({ length: 16 }, (_, i) => `${i + 7}:00`)

const schedule = ref<Record<string, { activity: string; color: string }>>({})
const showModal = ref(false)
const selectedDay = ref('')
const selectedHour = ref('')
const activityInput = ref('')

const colors: Record<string, string> = {
  Asesoría: '#ff4d4d',
  'Base de Datos': '#4d79ff',
  Taller: '#4dd2ff',
  Programación: '#85e085',
  Reunión: '#ffd24d',
}

const getCellKey = (day: string, hour: string) => `${day}-${hour}`

const getCellStyle = (day: string, hour: string) => {
  const key = getCellKey(day, hour)
  const entry = schedule.value[key]
  return entry ? { backgroundColor: entry.color, color: 'white' } : {}
}

const getActivityLabel = (day: string, hour: string) => {
  const key = getCellKey(day, hour)
  return schedule.value[key]?.activity || ''
}

const openModal = (day: string, hour: string) => {
  selectedDay.value = day
  selectedHour.value = hour
  activityInput.value = schedule.value[getCellKey(day, hour)]?.activity || ''
  showModal.value = true
}

const saveActivity = () => {
  const key = getCellKey(selectedDay.value, selectedHour.value)
  const activity = activityInput.value.trim()
  if (activity) {
    if (!colors[activity]) {
      colors[activity] = '#' + Math.floor(Math.random() * 16777215).toString(16)
    }
    schedule.value[key] = {
      activity,
      color: colors[activity],
    }
  } else {
    delete schedule.value[key]
  }
  showModal.value = false
}

// Cargar el horario guardado al montar el componente
onMounted(() => {
  const saved = localStorage.getItem('horarioGuardado')
  if (saved) {
    try {
      const parsed = JSON.parse(saved)
      // Validar que parsed es objeto con keys string y valores con activity y color
      if (typeof parsed === 'object' && parsed !== null) {
        schedule.value = parsed
      }
      console.log('Horario cargado desde localStorage:', schedule.value)
    } catch (e) {
      console.error('Error al leer localStorage:', e)
    }
  }
})

// Guardar el horario en localStorage cada vez que cambie
watch(
  schedule,
  (newVal) => {
    localStorage.setItem('horarioGuardado', JSON.stringify(newVal))
    console.log('Horario guardado en localStorage:', newVal)
  },
  { deep: true }
)
</script>

<style scoped>
.calendar-table {
  width: 100%;
  border-collapse: collapse;
  text-align: center;
}
.calendar-table th,
.calendar-table td {
  border: 1px solid #ccc;
  padding: 0.5rem;
  min-width: 80px;
  cursor: pointer;
}
.time-label {
  font-weight: bold;
  background-color: #f4f4f4;
}
.calendar-cell {
  transition: background-color 0.2s;
}
</style>
