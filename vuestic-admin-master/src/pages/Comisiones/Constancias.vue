<template>
      <!-- Título de la vista -->
   <h1 class="va-h4 mb-4"> Mis Constancias de Cumplimiento</h1>
  <div class="p-4">
    <va-card>
      <va-card-content>
        <!-- Filtros -->
        <div class="flex flex-wrap gap-4 mb-6">
          <va-select
            v-model="selectedYear"
            label="Año"
            :options="yearOptions"
            class="min-w-32"
          />
          
          <va-select
            v-model="selectedEventType"
            label="Tipo de Evento"
            :options="eventTypeOptions"
            class="min-w-48"
          />
          
          <va-input
            v-model="searchQuery"
            placeholder="Buscar constancias..."
            clearable
            class="flex-grow"
          >
            <template #prependInner>
              <va-icon name="search" size="small" />
            </template>
          </va-input>
        </div>

        <!-- Lista de constancias -->
        <va-data-table
          :items="filteredCertificates"
          :columns="columns"
          :loading="loading"
          hoverable
        >
          <template #cell(status)="{ value }">
            <va-badge :text="value" :color="getStatusColor(value)" />
          </template>

          <template #cell(download)="{ row }">
            <va-button
              preset="secondary"
              size="small"
              icon="download"
              @click="downloadCertificate(row)"
            >
              Descargar
            </va-button>
          </template>
        </va-data-table>

        <!-- Paginación -->
        <div class="flex justify-center mt-4">
          <va-pagination
            v-model="currentPage"
            :pages="totalPages"
            :visible-pages="5"
          />
        </div>
      </va-card-content>
    </va-card>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useToast } from 'vuestic-ui'

const { init } = useToast()

// Estado
const loading = ref(true)
const certificates = ref<any[]>([])
const selectedYear = ref(new Date().getFullYear().toString())
const selectedEventType = ref('all')
const searchQuery = ref('')
const currentPage = ref(1)
const itemsPerPage = 8

// Opciones
const yearOptions = [
  { text: '2023', value: '2023' },
  { text: '2022', value: '2022' },
  { text: '2021', value: '2021' },
]

const eventTypeOptions = [
  { text: 'Todos', value: 'all' },
  { text: 'Jurado de congreso', value: 'jurado_congreso' },
  { text: 'Evento Titulación', value: 'evento_titulacion' },
  { text: 'Conferencia', value: 'conferencia' },
  { text: 'Reunión Académica', value: 'reunion_academica' },
]

// Columnas
const columns = [
  { key: 'eventName', label: 'Evento', sortable: true },
  { key: 'eventType', label: 'Tipo', sortable: true, width: '150px' },
  { key: 'eventDate', label: 'Fecha', sortable: true, width: '120px' },
  { key: 'issueDate', label: 'Expedición', sortable: true, width: '120px' },
  { key: 'status', label: 'Estado', sortable: true, width: '120px' },
  { key: 'download', label: 'Constancia', width: '150px' }
]

// Datos de ejemplo
const sampleCertificates = [
  {
    id: 1,
    eventName: 'Jurado de Congreso de Residencias Profesionales',
    eventType: 'jurado_congreso',
    eventDate: '2023-11-15',
    issueDate: '2023-11-18',
    status: 'disponible',
    pdfUrl: '/certificados/jurado-congreso.pdf',
    reference: 'DOC-2023-11-001'
  },
  {
    id: 2,
    eventName: 'Ceremonia de Titulación Generación 2023-2',
    eventType: 'evento_titulacion',
    eventDate: '2023-12-10',
    issueDate: '2023-12-12',
    status: 'disponible',
    pdfUrl: '/certificados/ceremonia-titulacion.pdf',
    reference: 'DOC-2023-12-005'
  },
  {
    id: 3,
    eventName: 'Conferencia Internacional de Inteligencia Artificial',
    eventType: 'conferencia',
    eventDate: '2023-11-20',
    issueDate: '2023-11-22',
    status: 'pendiente',
    pdfUrl: '',
    reference: 'DOC-2023-11-008'
  },
  {
    id: 4,
    eventName: 'Reunión de Actualización Curricular',
    eventType: 'reunion_academica',
    eventDate: '2023-11-05',
    issueDate: '2023-11-08',
    status: 'disponible',
    pdfUrl: '/certificados/reunion-curricular.pdf',
    reference: 'DOC-2023-11-003'
  }
]

// Computed
const filteredCertificates = computed(() => {
  let result = certificates.value

  // Filtrar por año
  if (selectedYear.value) {
    result = result.filter(cert => cert.issueDate.startsWith(selectedYear.value))
  }

  // Filtrar por tipo de evento
  if (selectedEventType.value !== 'all') {
    result = result.filter(cert => cert.eventType === selectedEventType.value)
  }

  // Filtrar por búsqueda
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    result = result.filter(cert => 
      cert.eventName.toLowerCase().includes(query) ||
      cert.reference.toLowerCase().includes(query)
    )
  }

  return result
})

const totalPages = computed(() => {
  return Math.ceil(filteredCertificates.value.length / itemsPerPage)
})

const paginatedCertificates = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return filteredCertificates.value.slice(start, end)
})

// Métodos
const loadCertificates = async () => {
  loading.value = true
  try {
    // Simular llamada API
    await new Promise(resolve => setTimeout(resolve, 800))
    certificates.value = sampleCertificates.map(cert => ({
      ...cert,
      statusText: getStatusText(cert.status),
      eventDateFormatted: formatDate(cert.eventDate),
      issueDateFormatted: formatDate(cert.issueDate)
    }))
  } catch (error) {
    init({ message: 'Error al cargar constancias', color: 'danger' })
  } finally {
    loading.value = false
  }
}

const formatDate = (dateString: string) => {
  const options: Intl.DateTimeFormatOptions = { year: 'numeric', month: 'short', day: 'numeric' }
  return new Date(dateString).toLocaleDateString('es-ES', options)
}

const getStatusColor = (status: string) => {
  const colors: Record<string, string> = {
    disponible: 'success',
    pendiente: 'warning',
    vencido: 'danger'
  }
  return colors[status] || 'primary'
}

const getStatusText = (status: string) => {
  const texts: Record<string, string> = {
    disponible: 'Disponible',
    pendiente: 'Pendiente',
    vencido: 'Vencido'
  }
  return texts[status] || status
}

const downloadCertificate = (certificate: any) => {
  if (certificate.status !== 'disponible') {
    init({
      message: 'Esta constancia no está disponible para descarga',
      color: 'warning'
    })
    return
  }

  // Simular descarga
  init({
    message: `Descargando constancia ${certificate.reference}`,
    color: 'info'
  })
  
  setTimeout(() => {
    const link = document.createElement('a')
    link.href = certificate.pdfUrl
    link.download = `constancia_${certificate.reference}.pdf`
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
    
    // Mostrar alerta después de descargar
    setTimeout(() => {
      init({
        message: 'Por favor recaba las firmas correspondientes',
        color: 'success',
        duration: 5000,
        position: 'bottom-right'
      })
    }, 1000)
  }, 1000)
}

// Inicialización
onMounted(() => {
  loadCertificates()
})
</script>

<style scoped>
.va-card {
  border-radius: 0.5rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

/* Estilo para certificados no disponibles */
.disabled-certificate {
  opacity: 0.6;
  pointer-events: none;
}
</style>