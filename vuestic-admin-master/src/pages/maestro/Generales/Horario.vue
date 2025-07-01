<template>
  <h1 class="va-h2 mb-2">Horario</h1>

  <div class="p-4">
    <va-card>
      <va-card-title class="text-xl font-semibold">
        <va-icon name="calendar_today" class="mr-2" />
        Mi Horario - Ingeniería en Sistemas
      </va-card-title>

      <va-card-content>
        <!-- Controles -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
          <div class="flex items-center gap-2">
            <va-button preset="secondary" icon="chevron_left" @click="previousWeek" />
            <va-button preset="secondary" icon="chevron_right" @click="nextWeek" />
            <va-button preset="secondary" @click="goToToday"> Hoy </va-button>
          </div>

          <va-select v-model="viewType" :options="viewOptions" class="min-w-40" />

          <va-select v-model="selectedCampus" label="Horario completo" :options="campusOptions" class="min-w-40" />
        </div>

        <!-- Vista de semana -->
        <div v-if="viewType === 'week'" class="schedule-week-view">
          <!-- Cabecera con días -->
          <div class="schedule-header">
            <div class="schedule-time-column"></div>
            <div
              v-for="day in visibleDays"
              :key="day.date.toString()"
              class="schedule-day-header"
              :class="{ today: isToday(day.date) }"
            >
              <div class="day-name">{{ day.dayName }}</div>
              <div class="day-date">{{ day.date.getDate() }}</div>
            </div>
          </div>

          <!-- Cuerpo del horario -->
          <div class="schedule-body">
            <!-- Columna de horas -->
            <div class="schedule-time-column">
              <div v-for="time in timeSlots" :key="time" class="schedule-time-slot">
                {{ time }}
              </div>
            </div>

            <!-- Columnas de días -->
            <div v-for="day in visibleDays" :key="day.date.toString()" class="schedule-day-column">
              <div v-for="time in timeSlots" :key="time" class="schedule-slot" @click="openAddDialog(day.date, time)">
                <!-- Eventos/materias en este horario -->
                <div
                  v-for="course in getCoursesAt(day.date, time)"
                  :key="course.id"
                  class="schedule-event"
                  :style="{
                    backgroundColor: getCourseColor(course.code),
                    borderLeft: `4px solid ${getCourseDarkColor(course.code)}`,
                  }"
                  @click.stop="openCourseDetails(course)"
                >
                  <div class="event-title">{{ course.name }}</div>
                  <div class="event-details">{{ course.classroom }} • {{ course.teacherShort }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Vista de día -->
        <div v-else class="schedule-day-view">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">
              {{ currentDay.toLocaleDateString('es-ES', { weekday: 'long', day: 'numeric', month: 'long' }) }}
            </h3>
            <div class="flex gap-2">
              <va-button preset="secondary" size="small" icon="add" @click="openAddDialog(currentDay, '08:00')">
                Agregar
              </va-button>
            </div>
          </div>

          <div class="day-schedule">
            <div v-for="time in dayTimeSlots" :key="time" class="day-schedule-row">
              <div class="day-schedule-time">{{ time }}</div>
              <div class="day-schedule-events" @click="openAddDialog(currentDay, time)">
                <div
                  v-for="course in getCoursesForDay(currentDay, time)"
                  :key="course.id"
                  class="day-schedule-event"
                  :style="{
                    backgroundColor: getCourseColor(course.code),
                    borderLeft: `4px solid ${getCourseDarkColor(course.code)}`,
                  }"
                  @click.stop="openCourseDetails(course)"
                >
                  <div class="event-title">{{ course.name }}</div>
                  <div class="event-location">{{ course.classroom }}</div>
                  <div class="event-time">{{ course.time }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </va-card-content>
    </va-card>

    <!-- Modal de detalles del curso -->
    <va-modal
      v-model="showCourseModal"
      :title="selectedCourse?.name || 'Detalles del curso'"
      size="medium"
      hide-default-actions
    >
      <div v-if="selectedCourse" class="course-details">
        <div class="course-header" :style="{ backgroundColor: getCourseColor(selectedCourse.code) }">
          <div class="course-code">{{ selectedCourse.code }}</div>
          <div class="course-title">{{ selectedCourse.name }}</div>
        </div>

        <va-list class="mt-4">
          <va-list-item>
            <va-list-item-label class="font-medium">Profesor:</va-list-item-label>
            <va-list-item-section>
              {{ selectedCourse.teacher }}
              <div class="text-sm text-gray-600">{{ selectedCourse.teacherEmail }}</div>
            </va-list-item-section>
          </va-list-item>

          <va-list-item>
            <va-list-item-label class="font-medium">Horario:</va-list-item-label>
            <va-list-item-section>
              <div v-for="(session, idx) in selectedCourse.schedule" :key="idx">
                {{ session.day }} {{ session.time }} ({{ session.classroom }})
              </div>
            </va-list-item-section>
          </va-list-item>

          <va-list-item>
            <va-list-item-label class="font-medium">Créditos:</va-list-item-label>
            <va-list-item-section>{{ selectedCourse.credits }}</va-list-item-section>
          </va-list-item>
        </va-list>

        <div class="flex justify-end gap-2 mt-4">
          <va-button preset="secondary" @click="showCourseModal = false"> Cerrar </va-button>
          <va-button
            :href="`mailto:${selectedCourse.teacherEmail}?subject=Consulta sobre ${selectedCourse.name}`"
            icon="email"
          >
            Contactar profesor
          </va-button>
        </div>
      </div>
    </va-modal>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useToast } from 'vuestic-ui'

const { init } = useToast()

// Configuración de vista
const viewType = ref('week')
const viewOptions = [
  { text: 'Vista Semana', value: 'week' },
  { text: 'Vista Día', value: 'day' },
]

const selectedCampus = ref('TECNM ')
const campusOptions = [
  { text: 'Campus Principal', value: 'main' },
  { text: 'Campus Norte', value: 'north' },
  { text: 'Campus Sur', value: 'south' },
]

// Fechas y horarios
const currentDate = ref(new Date())
const currentDay = ref(new Date())

const timeSlots = [
  '07:00',
  '08:00',
  '09:00',
  '10:00',
  '11:00',
  '12:00',
  '13:00',
  '14:00',
  '15:00',
  '16:00',
  '17:00',
  '18:00',
  '19:00',
  '20:00',
]

const dayTimeSlots = [
  '07:00 - 08:30',
  '08:30 - 10:00',
  '10:00 - 11:30',
  '11:30 - 13:00',
  '13:00 - 14:30',
  '14:30 - 16:00',
  '16:00 - 17:30',
  '17:30 - 19:00',
  '19:00 - 20:30',
]

// Datos de ejemplo
const courses = ref([
  {
    id: 1,
    code: 'IS-301',
    name: 'Bases de Datos',
    credits: 6,
    teacher: 'Dr. Carlos Ruiz',
    teacherShort: 'C. Ruiz',
    teacherEmail: 'cruiz@universidad.edu',
    classroom: 'Aula B-205',
    schedule: [
      { day: 'Lunes', time: '10:00-11:30' },
      { day: 'Miércoles', time: '10:00-11:30' },
      { day: 'Viernes', time: '10:00-11:30' },
    ],
    color: '#4CAF50',
  },
  {
    id: 2,
    code: 'IS-305',
    name: 'Redes de Computadoras',
    credits: 6,
    teacher: 'Dra. Ana Martínez',
    teacherShort: 'A. Martínez',
    teacherEmail: 'amartinez@universidad.edu',
    classroom: 'Lab. Redes 3',
    schedule: [
      { day: 'Martes', time: '14:00-15:30' },
      { day: 'Jueves', time: '14:00-15:30' },
    ],
    color: '#2196F3',
  },
  {
    id: 3,
    code: 'IS-410',
    name: 'Inteligencia Artificial',
    credits: 6,
    teacher: 'Dr. Luis Méndez',
    teacherShort: 'L. Méndez',
    teacherEmail: 'lmendez@universidad.edu',
    classroom: 'Aula C-302',
    schedule: [
      { day: 'Lunes', time: '16:00-17:30' },
      { day: 'Miércoles', time: '16:00-17:30' },
    ],
    color: '#9C27B0',
  },
])

// Computed
const visibleDays = computed(() => {
  const days = []
  const current = new Date(currentDate.value)
  const firstDay = current.getDate() - current.getDay() + (current.getDay() === 0 ? -6 : 1) // Ajuste para que la semana empiece en lunes

  for (let i = 0; i < 7; i++) {
    const date = new Date(current)
    date.setDate(firstDay + i)

    days.push({
      date,
      dayName: date.toLocaleDateString('es-ES', { weekday: 'short' }),
      isToday: isToday(date),
    })
  }

  return days
})

// Métodos
const isToday = (date: Date) => {
  const today = new Date()
  return (
    date.getDate() === today.getDate() &&
    date.getMonth() === today.getMonth() &&
    date.getFullYear() === today.getFullYear()
  )
}

const previousWeek = () => {
  const date = new Date(currentDate.value)
  date.setDate(date.getDate() - 7)
  currentDate.value = date
}

const nextWeek = () => {
  const date = new Date(currentDate.value)
  date.setDate(date.getDate() + 7)
  currentDate.value = date
}

const goToToday = () => {
  currentDate.value = new Date()
  currentDay.value = new Date()
}

const getCoursesAt = (date: Date, time: string) => {
  const dayName = date.toLocaleDateString('es-ES', { weekday: 'long' })
  const hour = parseInt(time.split(':')[0])

  return courses.value.filter((course) => {
    return course.schedule.some((session) => {
      const sessionDay = session.day.toLowerCase() === dayName.toLowerCase()
      const sessionHour = parseInt(session.time.split(':')[0])
      const sessionEndHour = parseInt(session.time.split('-')[1].split(':')[0])

      return sessionDay && hour >= sessionHour && hour < sessionEndHour
    })
  })
}

const getCoursesForDay = (date: Date, timeRange: string) => {
  const dayName = date.toLocaleDateString('es-ES', { weekday: 'long' })
  const [startTime, endTime] = timeRange.split(' - ')

  return courses.value
    .filter((course) => {
      return course.schedule.some((session) => {
        return session.day.toLowerCase() === dayName.toLowerCase() && session.time === `${startTime}-${endTime}`
      })
    })
    .map((course) => {
      const session = course.schedule.find((s) => s.day.toLowerCase() === dayName.toLowerCase())
      return {
        ...course,
        time: session?.time || '',
      }
    })
}

const getCourseColor = (code: string) => {
  const course = courses.value.find((c) => c.code === code)
  return course?.color || '#4CAF50'
}

const getCourseDarkColor = (code: string) => {
  const color = getCourseColor(code)
  // Simplemente oscurecer el color para el borde
  return color // En una implementación real podrías oscurecer el color
}

// Modal
const showCourseModal = ref(false)
const selectedCourse = ref<any>(null)

const openCourseDetails = (course: any) => {
  selectedCourse.value = course
  showCourseModal.value = true
}

const openAddDialog = (date: Date, time: string) => {
  init({
    message: `Agregar evento el ${date.toLocaleDateString()} a las ${time}`,
    color: 'info',
  })
  // Aquí implementarías la lógica para agregar un nuevo evento
}
</script>

<style scoped>
/* Estilos para la vista de semana */
.schedule-week-view {
  display: flex;
  flex-direction: column;
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  overflow: hidden;
}

.schedule-header {
  display: flex;
  background-color: #f7fafc;
  border-bottom: 1px solid #e2e8f0;
}

.schedule-time-column {
  width: 80px;
  min-width: 80px;
  padding: 8px;
  border-right: 1px solid #e2e8f0;
  background-color: #f7fafc;
}

.schedule-day-header {
  flex: 1;
  text-align: center;
  padding: 8px;
  border-right: 1px solid #e2e8f0;
}

.schedule-day-header.today {
  background-color: #ebf8ff;
}

.schedule-day-header .day-name {
  font-weight: 600;
  text-transform: capitalize;
}

.schedule-day-header .day-date {
  font-size: 1.25rem;
  font-weight: 600;
}

.schedule-body {
  display: flex;
  position: relative;
}

.schedule-day-column {
  flex: 1;
  border-right: 1px solid #e2e8f0;
}

.schedule-time-slot {
  height: 60px;
  display: flex;
  align-items: flex-start;
  justify-content: flex-end;
  padding: 4px 8px;
  font-size: 0.75rem;
  color: #718096;
  border-bottom: 1px solid #edf2f7;
}

.schedule-slot {
  height: 60px;
  position: relative;
  border-bottom: 1px solid #edf2f7;
}

.schedule-event {
  position: absolute;
  left: 2px;
  right: 2px;
  padding: 4px;
  border-radius: 4px;
  color: white;
  font-size: 0.75rem;
  cursor: pointer;
  overflow: hidden;
  margin-bottom: 2px;
}

.schedule-event .event-title {
  font-weight: 600;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.schedule-event .event-details {
  font-size: 0.65rem;
  opacity: 0.9;
}

/* Estilos para la vista de día */
.schedule-day-view {
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  overflow: hidden;
}

.day-schedule {
  display: flex;
  flex-direction: column;
}

.day-schedule-row {
  display: flex;
  min-height: 60px;
  border-bottom: 1px solid #e2e8f0;
}

.day-schedule-time {
  width: 80px;
  padding: 8px;
  text-align: right;
  font-size: 0.875rem;
  color: #718096;
  background-color: #f7fafc;
  border-right: 1px solid #e2e8f0;
}

.day-schedule-events {
  flex: 1;
  padding: 4px;
  position: relative;
  cursor: pointer;
}

.day-schedule-event {
  padding: 8px;
  border-radius: 4px;
  color: white;
  margin-bottom: 4px;
  cursor: pointer;
}

.day-schedule-event .event-title {
  font-weight: 600;
  margin-bottom: 2px;
}

.day-schedule-event .event-location {
  font-size: 0.75rem;
  opacity: 0.9;
  margin-bottom: 2px;
}

.day-schedule-event .event-time {
  font-size: 0.65rem;
  opacity: 0.8;
}

/* Estilos para los detalles del curso */
.course-header {
  padding: 16px;
  color: white;
  border-radius: 0.5rem 0.5rem 0 0;
}

.course-code {
  font-size: 0.875rem;
  opacity: 0.9;
}

.course-title {
  font-size: 1.25rem;
  font-weight: 600;
  margin-top: 4px;
}
</style>
