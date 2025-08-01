<template>
  <!-- Título de la vista -->
  <h1 class="va-h4 mb-4">Nombramientos</h1>
  <div class="p-4">
    <VaCard>
      <VaCardContent>
        <!-- Barra de búsqueda y filtros -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
          <VaInput v-model="searchQuery" placeholder="Buscar nombramientos..." class="flex-grow" clearable>
            <template #prependInner>
              <VaIcon name="search" size="small" />
            </template>
          </VaInput>

          <VaSelect v-model="selectedPeriod" label="Periodo" :options="periodOptions" class="min-w-40" />

          <VaSelect v-model="selectedStatus" label="Estado" :options="statusOptions" class="min-w-40" />
        </div>

        <!-- Tabla de nombramientos -->
        <VaDataTable :items="filteredAppointments" :columns="columns" :loading="loading" hoverable>
          <template #cell(status)="{ value }">
            <VaBadge :text="getStatusText(value)" :color="getStatusColor(value)" />
          </template>

          <template #cell(actions)="{ row }">
            <VaButton preset="secondary" size="small" icon="info" class="mr-2" @click="openDetails(row)" />
            <VaButton preset="secondary" size="small" icon="download" @click="downloadAppointment(row)" />
          </template>
        </VaDataTable>

        <!-- Paginación -->
        <div class="flex justify-center mt-4">
          <VaPagination v-model="currentPage" :pages="totalPages" :visible-pages="5" />
        </div>
      </VaCardContent>
    </VaCard>

    <!-- Modal de detalles -->
    <VaModal
      v-model="showDetailsModal"
      :title="selectedAppointment?.course || 'Detalles del nombramiento'"
      size="large"
      hide-default-actions
    >
      <div v-if="selectedAppointment" class="p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
          <div>
            <h3 class="text-lg font-semibold mb-3">Información básica</h3>
            <VaList>
              <VaListItem>
                <VaListItemLabel class="font-medium">Código:</VaListItemLabel>
                <VaListItemSection>{{ selectedAppointment.code }}</VaListItemSection>
              </VaListItem>
              <VaListItem>
                <VaListItemLabel class="font-medium">Materia:</VaListItemLabel>
                <VaListItemSection>{{ selectedAppointment.course }}</VaListItemSection>
              </VaListItem>
              <VaListItem>
                <VaListItemLabel class="font-medium">Grupo:</VaListItemLabel>
                <VaListItemSection>{{ selectedAppointment.group }}</VaListItemSection>
              </VaListItem>
            </VaList>
          </div>

          <div>
            <h3 class="text-lg font-semibold mb-3">Horario</h3>
            <VaList>
              <VaListItem v-for="(session, idx) in selectedAppointment.schedule" :key="idx">
                <VaListItemLabel class="font-medium">{{ session.day }}:</VaListItemLabel>
                <VaListItemSection>{{ session.time }}</VaListItemSection>
              </VaListItem>
            </VaList>
          </div>
        </div>

        <div class="flex justify-end">
          <VaButton @click="showDetailsModal = false">Cerrar</VaButton>
        </div>
      </div>
    </VaModal>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useToast } from 'vuestic-ui'

const { init } = useToast()

// Estado
const loading = ref(true)
const appointments = ref([
  {
    id: 1,
    code: 'MAT-2023-001',
    course: 'Matemáticas Avanzadas',
    group: 'A-101',
    period: '2023-2024',
    schedule: [
      { day: 'Lunes', time: '08:00 - 10:00' },
      { day: 'Miércoles', time: '10:00 - 12:00' },
    ],
    status: 'active',
  },
  {
    id: 2,
    code: 'FIS-2023-002',
    course: 'Física Cuántica',
    group: 'B-205',
    period: '2023-2024',
    schedule: [
      { day: 'Martes', time: '14:00 - 16:00' },
      { day: 'Jueves', time: '16:00 - 18:00' },
    ],
    status: 'active',
  },
  {
    id: 3,
    code: 'QUI-2022-015',
    course: 'Química Orgánica',
    group: 'C-302',
    period: '2022-2023',
    schedule: [{ day: 'Viernes', time: '09:00 - 11:00' }],
    status: 'finished',
  },
])

const searchQuery = ref('')
const selectedPeriod = ref('all')
const selectedStatus = ref('all')
const currentPage = ref(1)
const itemsPerPage = 10
const showDetailsModal = ref(false)
const selectedAppointment = ref<any>(null)

// Opciones de filtros
const periodOptions = [
  { text: 'Todos los periodos', value: 'all' },
  { text: '2023-2024', value: '2023-2024' },
  { text: '2022-2023', value: '2022-2023' },
]

const statusOptions = [
  { text: 'Todos', value: 'all' },
  { text: 'Activo', value: 'active' },
  { text: 'Finalizado', value: 'finished' },
  { text: 'Pendiente', value: 'pending' },
]

// Columnas de la tabla
const columns = [
  { key: 'code', label: 'Código', sortable: true },
  { key: 'course', label: 'Materia', sortable: true },
  { key: 'group', label: 'Grupo', sortable: true },
  { key: 'period', label: 'Periodo', sortable: true },
  { key: 'status', label: 'Estado', sortable: true },
  { key: 'actions', label: 'Acciones', width: '120px' },
]

// Datos filtrados
const filteredAppointments = computed(() => {
  let result = appointments.value

  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    result = result.filter(
      (appointment) =>
        appointment.course.toLowerCase().includes(query) || appointment.code.toLowerCase().includes(query),
    )
  }

  if (selectedPeriod.value !== 'all') {
    result = result.filter((appointment) => appointment.period === selectedPeriod.value)
  }

  if (selectedStatus.value !== 'all') {
    result = result.filter((appointment) => appointment.status === selectedStatus.value)
  }

  return result
})

const totalPages = computed(() => {
  return Math.ceil(filteredAppointments.value.length / itemsPerPage)
})

const paginatedAppointments = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return filteredAppointments.value.slice(start, end)
})

// Helpers
const getStatusColor = (status: string) => {
  const colors: Record<string, string> = {
    active: 'success',
    finished: 'info',
    pending: 'warning',
  }
  return colors[status] || 'primary'
}

const getStatusText = (status: string) => {
  const texts: Record<string, string> = {
    active: 'Activo',
    finished: 'Finalizado',
    pending: 'Pendiente',
  }
  return texts[status] || status
}

const openDetails = (appointment: any) => {
  selectedAppointment.value = appointment
  showDetailsModal.value = true
}

const downloadAppointment = (appointment: any) => {
  init({ message: `Descargando ${appointment.code}`, color: 'info' })
  // Lógica para descargar el nombramiento
}

// Simular carga de datos
onMounted(() => {
  setTimeout(() => {
    loading.value = false
  }, 1000)
})
</script>

<style scoped>
/* Estilos personalizados */
.va-card {
  border-radius: 0.5rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}
</style>
