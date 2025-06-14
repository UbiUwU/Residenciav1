<template>
  <!-- Título de la vista -->
  <h1 class="va-h4 mb-4">Comisiones</h1>
  <div class="p-4">
    <va-card>
      <va-card-content>
        <!-- Filtros -->
        <div class="flex flex-wrap gap-4 mb-6">
          <va-select v-model="selectedStatus" label="Estado" :options="statusOptions" class="min-w-40" />

          <va-select v-model="selectedEventType" label="Tipo de Evento" :options="eventTypeOptions" class="min-w-48" />

          <va-date-input v-model="dateRange" label="Rango de fechas" mode="range" clearable />
        </div>

        <!-- Tabla de comisiones -->
        <va-data-table :items="filteredCommissions" :columns="columns" :loading="loading" hoverable>
          <template #cell(status)="{ value }">
            <va-badge :text="value" :color="getStatusColor(value)" />
          </template>

          <template #cell(daysLeft)="{ value }">
            <span :class="{ 'text-red-500': value <= 3, 'text-orange-500': value > 3 && value <= 7 }">
              {{ value }} día(s)
            </span>
          </template>

          <template #cell(actions)="{ row }">
            <va-button preset="secondary" size="small" icon="visibility" @click="viewDetails(row)" class="mr-2" />
            <va-button preset="secondary" size="small" icon="download" @click="downloadCommissionPDF(row)" />
          </template>
        </va-data-table>

        <!-- Paginación -->
        <div class="flex justify-center mt-4">
          <va-pagination v-model="currentPage" :pages="totalPages" :visible-pages="5" />
        </div>
      </va-card-content>
    </va-card>

    <!-- Modal de detalles -->
    <va-modal
      v-model="showDetailsModal"
      :title="selectedCommission?.eventName || 'Detalles de comisión'"
      size="large"
      hide-default-actions
    >
      <div v-if="selectedCommission" class="p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
          <div>
            <h3 class="text-lg font-semibold mb-3">Información del Evento</h3>
            <va-list>
              <va-list-item>
                <va-list-item-label class="font-medium">Nombre:</va-list-item-label>
                <va-list-item-section>{{ selectedCommission.eventName }}</va-list-item-section>
              </va-list-item>
              <va-list-item>
                <va-list-item-label class="font-medium">Tipo:</va-list-item-label>
                <va-list-item-section>{{ selectedCommission.eventType }}</va-list-item-section>
              </va-list-item>
              <va-list-item>
                <va-list-item-label class="font-medium">Fecha:</va-list-item-label>
                <va-list-item-section>
                  {{ formatDate(selectedCommission.eventDate) }}
                </va-list-item-section>
              </va-list-item>
              <va-list-item>
                <va-list-item-label class="font-medium">Horario:</va-list-item-label>
                <va-list-item-section>{{ selectedCommission.eventTime }}</va-list-item-section>
              </va-list-item>
            </va-list>
          </div>

          <div>
            <h3 class="text-lg font-semibold mb-3">Detalles de Comisión</h3>
            <va-list>
              <va-list-item>
                <va-list-item-label class="font-medium">Estado:</va-list-item-label>
                <va-list-item-section>
                  <va-badge :text="selectedCommission.status" :color="getStatusColor(selectedCommission.status)" />
                </va-list-item-section>
              </va-list-item>
              <va-list-item>
                <va-list-item-label class="font-medium">Días restantes:</va-list-item-label>
                <va-list-item-section>
                  <span
                    :class="{
                      'text-red-500': selectedCommission.daysLeft <= 3,
                      'text-orange-500': selectedCommission.daysLeft > 3 && selectedCommission.daysLeft <= 7,
                    }"
                  >
                    {{ selectedCommission.daysLeft }} día(s)
                  </span>
                </va-list-item-section>
              </va-list-item>
              <va-list-item>
                <va-list-item-label class="font-medium">Lugar:</va-list-item-label>
                <va-list-item-section>{{ selectedCommission.location }}</va-list-item-section>
              </va-list-item>
              <va-list-item>
                <va-list-item-label class="font-medium">Responsable:</va-list-item-label>
                <va-list-item-section>{{ selectedCommission.responsible }}</va-list-item-section>
              </va-list-item>
            </va-list>
          </div>
        </div>

        <div class="mb-6">
          <h3 class="text-lg font-semibold mb-3">Descripción</h3>
          <p class="text-gray-700">{{ selectedCommission.description }}</p>
        </div>

        <div class="flex justify-end gap-2">
          <va-button preset="secondary" @click="showDetailsModal = false"> Cerrar </va-button>
          <va-button color="success" icon="download" @click="downloadCommissionPDF(selectedCommission)">
            Descargar PDF Oficial
          </va-button>
        </div>
      </div>
    </va-modal>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useToast } from 'vuestic-ui'

const { init } = useToast()

// Estado
const loading = ref(true)
const commissions = ref<any[]>([])
const selectedStatus = ref('Todos')
const selectedEventType = ref('Todos')
const dateRange = ref()
const currentPage = ref(1)
const itemsPerPage = 10
const showDetailsModal = ref(false)
const selectedCommission = ref<any>(null)

// Opciones de filtros
const statusOptions = [
  { text: 'Todos', value: 'Todos' },
  { text: 'Pendiente', value: 'pending' },
  { text: 'Confirmado', value: 'confirmed' },
  { text: 'Completado', value: 'completed' },
  { text: 'Cancelado', value: 'canceled' },
]

const eventTypeOptions = [
  { text: 'Todos', value: 'Todos' },
  { text: 'Jurado de congreso', value: 'jurado_congreso' },
  { text: 'Evento Titulación', value: 'evento_titulacion' },
  { text: 'Conferencia', value: 'conferencia' },
  { text: 'Reunión Académica', value: 'reunion_academica' },
  { text: 'Taller', value: 'taller' },
  { text: 'Evaluación', value: 'evaluacion' },
]

// Columnas de la tabla
const columns = [
  { key: 'eventDate', label: 'Fecha', sortable: true, width: '120px' },
  { key: 'daysLeft', label: 'Días restantes', sortable: true, width: '120px' },
  { key: 'eventName', label: 'Evento', sortable: true },
  { key: 'eventType', label: 'Tipo', sortable: true },
  { key: 'status', label: 'Estado', sortable: true, width: '120px' },
  { key: 'actions', label: 'Acciones', width: '120px' },
]

// Datos de ejemplo
const sampleCommissions = [
  {
    id: 1,
    eventName: 'Jurado de Congreso de Residencias Profesionales',
    eventType: 'jurado_congreso',
    eventDate: '2025-06-05',
    eventTime: '09:00 - 14:00',
    location: 'Auditorio Principal - Edificio A',
    responsible: 'Dra. María González',
    description:
      'Participación como jurado en la presentación de proyectos de residencias profesionales de estudiantes de 8vo semestre.',
    status: 'confirmed',
    daysLeft: 5,
    pdfUrl: '/comisiones/formatos/jurado-congreso.pdf',
  },
  {
    id: 2,
    eventName: 'Academia Journalls Mayo 2025',
    eventType: 'academia_journalls',
    eventDate: '2025-05-22',
    eventTime: '16:00 - 20:00',
    location: 'Teatro Universitario',
    responsible: 'Lic. Juan Pérez',
    description: 'Ponencia Inteligencia Artifical',
    status: 'pending',
    daysLeft: 30,
    pdfUrl: '/comisiones/formatos/ceremonia-titulacion.pdf',
  },
  {
    id: 3,
    eventName: 'Conferencia Internacional de Inteligencia Artificial',
    eventType: 'conferencia',
    eventDate: '2025-06-09',
    eventTime: '10:00 - 12:00',
    location: 'Sala de Conferencias - Edificio B',
    responsible: 'Dr. Carlos Ruiz',
    description: 'Participación como ponente en la conferencia sobre aplicaciones de IA en la educación superior.',
    status: 'confirmed',
    daysLeft: 10,
    pdfUrl: '/comisiones/formatos/conferencia-ia.pdf',
  },
  {
    id: 4,
    eventName: 'Reunión Academica',
    eventType: 'reunion_academica',
    eventDate: '2025-05-05',
    eventTime: '11:00 - 13:00',
    location: 'Sala de Juntas - Dirección Académica',
    responsible: 'Mtro. Luis Hernández',
    description: 'Revisión y actualización del plan de estudios de Ingeniería en Sistemas.',
    status: 'completed',
    daysLeft: -2,
    pdfUrl: '/comisiones/formatos/reunion-curricular.pdf',
  },
]

// Computed
const filteredCommissions = computed(() => {
  let result = commissions.value

  // Filtrar por estado
  if (selectedStatus.value !== 'Todos') {
    result = result.filter((commission) => commission.status === selectedStatus.value)
  }

  // Filtrar por tipo de evento
  if (selectedEventType.value !== 'Todos') {
    result = result.filter((commission) => commission.eventType === selectedEventType.value)
  }

  // Filtrar por rango de fechas
  if (dateRange.value && dateRange.value.length === 2) {
    const startDate = new Date(dateRange.value[0])
    const endDate = new Date(dateRange.value[1])

    result = result.filter((commission) => {
      const commissionDate = new Date(commission.eventDate)
      return commissionDate >= startDate && commissionDate <= endDate
    })
  }

  // Calcular días restantes (si no está ya calculado)
  result = result.map((commission) => {
    if (!commission.daysLeft) {
      const today = new Date()
      const eventDate = new Date(commission.eventDate)
      const diffTime = eventDate.getTime() - today.getTime()
      commission.daysLeft = Math.ceil(diffTime / (1000 * 60 * 60 * 24))
    }
    return commission
  })

  return result
})

const totalPages = computed(() => {
  return Math.ceil(filteredCommissions.value.length / itemsPerPage)
})

const paginatedCommissions = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return filteredCommissions.value.slice(start, end)
})

// Métodos
const loadCommissions = async () => {
  loading.value = true
  try {
    // Simular llamada API
    await new Promise((resolve) => setTimeout(resolve, 800))
    commissions.value = sampleCommissions.map((commission) => {
      const today = new Date()
      const eventDate = new Date(commission.eventDate)
      const diffTime = eventDate.getTime() - today.getTime()
      const daysLeft = Math.ceil(diffTime / (1000 * 60 * 60 * 24))

      return {
        ...commission,
        daysLeft,
        statusText: getStatusText(commission.status),
      }
    })
  } catch (error) {
    init({ message: 'Error al cargar comisiones', color: 'danger' })
  } finally {
    loading.value = false
  }
}

const formatDate = (dateString: string) => {
  const options: Intl.DateTimeFormatOptions = { year: 'numeric', month: 'long', day: 'numeric' }
  return new Date(dateString).toLocaleDateString('es-ES', options)
}

const getStatusColor = (status: string) => {
  const colors: Record<string, string> = {
    pending: 'warning',
    confirmed: 'success',
    completed: 'info',
    canceled: 'danger',
  }
  return colors[status] || 'primary'
}

const getStatusText = (status: string) => {
  const texts: Record<string, string> = {
    pending: 'Pendiente',
    confirmed: 'Confirmado',
    completed: 'Completado',
    canceled: 'Cancelado',
  }
  return texts[status] || status
}

const viewDetails = (commission: any) => {
  selectedCommission.value = commission
  showDetailsModal.value = true
}

const downloadCommissionPDF = (commission: any) => {
  init({
    message: `Descargando formato PDF para ${commission.eventName}`,
    color: 'info',
  })

  // Simular descarga
  setTimeout(() => {
    const link = document.createElement('a')
    link.href = commission.pdfUrl
    link.download = `comision_${commission.id}.pdf`
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
  }, 1000)
}

// Inicialización
onMounted(() => {
  loadCommissions()
})
</script>

<style scoped>
/* Estilos personalizados */
.va-card {
  border-radius: 0.5rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.text-red-500 {
  color: #ef4444;
}

.text-orange-500 {
  color: #f97316;
}
</style>