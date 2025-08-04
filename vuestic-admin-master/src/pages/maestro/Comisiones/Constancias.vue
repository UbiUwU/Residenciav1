<template>
  <!-- Título de la vista -->
  <h1 class="va-h4 mb-4">Mis Constancias de Cumplimiento</h1>
  <div class="p-4">
    <VaCard>
      <VaCardContent>
        <!-- Filtros -->
        <div class="flex flex-wrap gap-4 mb-6">
          <VaSelect v-model="selectedYear" label="Año" :options="yearOptions" class="min-w-32" />

          <VaSelect v-model="selectedEventType" label="Tipo de Evento" :options="eventTypeOptions" class="min-w-48" />

          <VaInput v-model="searchQuery" placeholder="Buscar constancias..." clearable class="flex-grow">
            <template #prependInner>
              <VaIcon name="search" size="small" />
            </template>
          </VaInput>
        </div>

        <!-- Lista de constancias -->
        <VaDataTable :items="paginatedCertificates" :columns="columns" :loading="loading" hoverable>
          <template #cell(status)="{ value }">
            <VaBadge :text="value" :color="getStatusColor(value)" />
          </template>

          <template #cell(download)="{ row }">
            <VaButton preset="secondary" size="small" icon="download" @click="downloadCertificate(row)">
              Descargar
            </VaButton>
          </template>
        </VaDataTable>

        <!-- Paginación -->
        <div class="flex justify-center mt-4">
          <VaPagination v-model="currentPage" :pages="totalPages" :visible-pages="5" />
        </div>
      </VaCardContent>
    </VaCard>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useToast } from 'vuestic-ui'

const { init } = useToast()

// Estado
const loading = ref(false)
const selectedYear = ref('2025') // 2025 seleccionado por defecto
const selectedEventType = ref('Todos')
const searchQuery = ref('')
const currentPage = ref(1)
const itemsPerPage = 8

// Opciones
const yearOptions = [
  { text: 'Todos', value: '' },
  { text: '2025', value: '2025' }, // Añadido 2025 como primera opción
  { text: '2024', value: '2024' },
  { text: '2023', value: '2023' },
  { text: '2022', value: '2022' },
  { text: '2021', value: '2021' },
]

const eventTypeOptions = [
  { text: 'Todos', value: 'Todos' },
  { text: 'Jurado de congreso', value: 'jurado_congreso' },
  { text: 'Evento Titulación', value: 'evento_titulacion' },
  { text: 'Conferencia', value: 'conferencia' },
  { text: 'Reunión Académica', value: 'reunion_academica' },
  { text: 'Taller', value: 'taller' },
  { text: 'Evaluación', value: 'evaluacion' },
  { text: 'Seminario', value: 'seminario' },
  { text: 'Diplomado', value: 'diplomado' },
  { text: 'Foro', value: 'foro' },
  { text: 'Revisión de Artículos', value: 'revision_articulos' },
]

// Columnas
const columns = [
  { key: 'eventName', label: 'Evento', sortable: true },
  { key: 'eventType', label: 'Tipo', sortable: true, width: '150px' },
  { key: 'eventDate', label: 'Fecha', sortable: true, width: '120px' },
  { key: 'issueDate', label: 'Expedición', sortable: true, width: '120px' },
  { key: 'status', label: 'Estado', sortable: true, width: '120px' },
  { key: 'download', label: 'Constancia', width: '150px' },
]

const sampleCertificates = [
  {
    id: 1,
    eventName: 'Jurado de Congreso de Residencias Profesionales',
    eventType: 'jurado_congreso',
    eventDate: '2025-06-05',
    issueDate: '2025-06-06',
    status: 'disponible',
    pdfUrl: '/certificados/jurado-congreso.pdf',
    reference: 'DOC-2024-05-001',
  },
  {
    id: 2,
    eventName: 'Ceremonia de Titulación Generación 2024-1',
    eventType: 'evento_titulacion',
    eventDate: '2025-04-10',
    issueDate: '2025-04-12',
    status: 'disponible',
    pdfUrl: '/certificados/ceremonia-titulacion.pdf',
    reference: 'DOC-2024-06-005',
  },
  {
    id: 22,
    eventName: 'Revisión de Artículos para Academia Journal',
    eventType: 'revision_articulos',
    eventDate: '2025-04-15',
    issueDate: '2025-04-30',
    status: 'disponible',
    pdfUrl: '/certificados/academia-journal-constancia.pdf',
    reference: 'DOC-2024-04-105',
    description:
      'Constancia por participación como revisor de artículos científicos para la edición Q2 2024 de Academia Journal.',
  },
]

// Computed
const filteredCertificates = computed(() => {
  let result = sampleCertificates.map((cert) => ({
    ...cert,
    status: getStatusText(cert.status),
    eventDate: formatDate(cert.eventDate),
    issueDate: formatDate(cert.issueDate),
  }))

  // Filtro por año
  if (selectedYear.value) {
    result = result.filter((cert) => cert.issueDate.includes(selectedYear.value))
  }

  // Filtro por tipo de evento
  if (selectedEventType.value !== 'Todos') {
    result = result.filter((cert) => cert.eventType === selectedEventType.value)
  }

  // Filtro por búsqueda
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    result = result.filter(
      (cert) => cert.eventName.toLowerCase().includes(query) || cert.reference.toLowerCase().includes(query),
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

// Funciones auxiliares
const formatDate = (dateString: string) => {
  const options: Intl.DateTimeFormatOptions = { year: 'numeric', month: 'short', day: 'numeric' }
  return new Date(dateString).toLocaleDateString('es-ES', options)
}

const getStatusColor = (status: string) => {
  const colors: Record<string, string> = {
    disponible: 'success',
    pendiente: 'warning',
    vencido: 'danger',
  }
  return colors[status] || 'primary'
}

const getStatusText = (status: string) => {
  const texts: Record<string, string> = {
    disponible: 'Disponible',
    pendiente: 'Pendiente',
    vencido: 'Vencido',
  }
  return texts[status] || status
}

const downloadCertificate = (certificate: any) => {
  if (certificate.status !== 'Disponible') {
    init({
      message: 'Esta constancia no está disponible para descarga',
      color: 'warning',
    })
    return
  }

  // Simular descarga
  init({
    message: `Descargando constancia ${certificate.reference}`,
    color: 'info',
  })

  setTimeout(() => {
    const link = document.createElement('a')
    link.href = certificate.pdfUrl
    link.download = `constancia_${certificate.reference}.pdf`
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)

    // Mensaje adicional
    setTimeout(() => {
      init({
        message: 'Por favor recaba las firmas correspondientes',
        color: 'success',
        duration: 5000,
        position: 'bottom-right',
      })
    }, 1000)
  }, 1000)
}
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
