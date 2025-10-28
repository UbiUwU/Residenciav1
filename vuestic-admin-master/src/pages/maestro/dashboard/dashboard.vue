<template>
  <div class="dashboard-container">
    <!-- Encabezado -->
    <VaCard class="mb-4">
      <VaCardContent class="flex items-center justify-between">
        <div>
          <h1 class="va-h3">Bienvenido, Profesor {{ nombreProfesor }}</h1>
          <p class="text-gray-500">Resumen de tus actividades académicas</p>
<<<<<<< HEAD
        </div>
        <div class="flex items-center gap-4">
          <VaSelect
            v-model="periodoSeleccionado"
            :options="periodosAcademicos"
            label="Período académico"
            class="w-48"
          />
=======
          <h1>{{ periodoSeleccionado }}</h1>
>>>>>>> 50f1347702246fae5a2ded8433859d2dd8f2f12c
        </div>
      </VaCardContent>
    </VaCard>

    <!-- Tarjetas resumen prioritarias -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
      <VaCard color="background-secondary" @click="filtrarPorEstado('Pendiente')">
        <VaCardContent>
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 mb-1">Documentación pendiente</p>
              <h3 class="va-h3">{{ documentosPendientes }}</h3>
            </div>
            <VaIcon name="pending_actions" size="large" color="warning" />
          </div>
          <VaProgressBar :model-value="porcentajeDocumentacion" color="warning" class="mt-2" />
          <p class="text-xs mt-1">{{ documentosCompletados }} de {{ totalDocumentos }} documentos entregados</p>
        </VaCardContent>
      </VaCard>

      <VaCard color="background-secondary" @click="navegarAEvidencias">
        <VaCardContent>
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 mb-1">Carpetas de evidencia</p>
              <h3 class="va-h3">{{ carpetasEvidencia }}</h3>
            </div>
            <VaIcon name="folder" size="large" color="info" />
          </div>
          <p class="text-xs mt-2">{{ evidenciasCompletadas }} evidencias completas</p>
        </VaCardContent>
      </VaCard>

      <VaCard color="background-secondary" @click="filtrarPorEstado('Comision')">
        <VaCardContent>
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 mb-1">Comisiones activas</p>
              <h3 class="va-h3">{{ comisionesActivas }}</h3>
            </div>
            <VaIcon name="groups" size="large" color="primary" />
          </div>
          <p class="text-xs mt-2">{{ comisionesPendientes }} pendientes de revisión</p>
        </VaCardContent>
      </VaCard>

      <VaCard color="background-secondary" @click="filtrarPorEstado('Atrasado')">
        <VaCardContent>
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 mb-1">Tareas atrasadas</p>
              <h3 class="va-h3">{{ tareasAtrasadas }}</h3>
            </div>
            <VaIcon name="notification_important" size="large" color="danger" />
          </div>
          <p class="text-xs mt-2">Vencen hoy: {{ tareasPorVencerHoy }}</p>
        </VaCardContent>
      </VaCard>
    </div>

    <!-- Sección principal dividida -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
      <!-- Calendario de entregas -->
      <div class="lg:col-span-1">
        <VaCard>
          <VaCardTitle>Próximas entregas</VaCardTitle>
          <VaCardContent>
            <div class="space-y-4">
              <div
                v-for="evento in proximosEventos"
                :key="evento.id"
                class="flex items-start gap-3 p-2 hover:bg-gray-50 rounded"
              >
                <div class="flex-shrink-0 mt-1">
                  <VaIcon :name="evento.icono" :color="evento.color" size="small" />
                </div>
                <div>
                  <p class="font-medium">{{ evento.titulo }}</p>
                  <p class="text-sm text-gray-500">{{ evento.fecha }} - {{ evento.hora }}</p>
                  <VaBadge v-if="evento.urgente" text="Urgente" color="danger" class="mt-1" />
                </div>
              </div>
              <VaButton v-if="proximosEventos.length === 0" preset="plain" icon="event_available" class="w-full">
                No hay entregas próximas
              </VaButton>
            </div>
          </VaCardContent>
        </VaCard>

        <!-- Comisiones activas -->
        <VaCard class="mt-6">
          <VaCardTitle>Mis comisiones</VaCardTitle>
          <VaCardContent>
            <div class="space-y-3">
              <div v-for="comision in comisiones" :key="comision.id" class="p-3 border rounded">
                <div class="flex justify-between items-start">
                  <p class="font-medium">{{ comision.nombre }}</p>
                  <VaBadge :text="comision.estado" :color="getBadgeColor(comision.estado)" />
                </div>
                <p class="text-sm text-gray-500 mt-1">{{ comision.descripcion }}</p>
                <p class="text-xs mt-2">Fecha límite: {{ comision.fechaLimite }}</p>
              </div>
              <VaButton v-if="comisiones.length === 0" preset="plain" icon="groups" class="w-full">
                No tienes comisiones asignadas
              </VaButton>
            </div>
          </VaCardContent>
        </VaCard>
      </div>

      <!-- Contenido principal -->
      <div class="lg:col-span-2">
        <!-- Progreso de asignaturas -->
        <VaCard>
          <VaCardTitle>Progreso de mis asignaturas</VaCardTitle>
          <VaCardContent>
            <div class="flex justify-between items-center mb-4">
              <VaButtonToggle v-model="filtroAsignaturas" :options="opcionesFiltroAsignaturas" size="small" />
              <VaInput v-model="busquedaAsignaturas" placeholder="Buscar asignatura..." class="w-48" />
            </div>

            <div class="h-64">
              <canvas ref="progresoAsignaturasChart"></canvas>
            </div>

            <VaDataTable
              :items="asignaturasFiltradas"
              :columns="columnasAsignaturas"
              :per-page="5"
              selectable
              selected-color="primary"
              class="mt-4"
              @selectionChange="seleccionAsignaturaCambiada"
            >
              <template #cell(estado)="{ value }">
                <VaBadge :text="value" :color="getBadgeColor(value)" />
              </template>

              <template #cell(acciones)="{ row }">
                <VaButton
                  preset="plain"
                  icon="description"
                  size="small"
                  class="mr-2"
                  color="info"
                  @click="verDocumentacion(row.id)"
                />
                <VaButton preset="plain" icon="visibility" size="small" @click="verDetalle(row.id)" />
              </template>
            </VaDataTable>
          </VaCardContent>
        </VaCard>

        <!-- Documentación requerida -->
        <VaCard class="mt-6">
          <VaCardTitle>Documentación requerida</VaCardTitle>
          <VaCardContent>
            <VaTabs v-model="tabDocumentacion" grow>
              <template #tabs>
                <VaTab v-for="tab in tabsDocumentacion" :key="tab.label">
                  {{ tab.label }}
                </VaTab>
              </template>
            </VaTabs>

            <div class="mt-4">
              <VaDataTable :items="documentosFiltrados" :columns="columnasDocumentacion" :per-page="5">
                <template #cell(estado)="{ value }">
                  <VaBadge :text="value" :color="getBadgeColor(value)" />
                </template>

                <template #cell(acciones)="{ row }">
                  <VaButton
                    v-if="row.estado !== 'Completado'"
                    preset="plain"
                    icon="upload"
                    size="small"
                    color="warning"
                    class="mr-2"
                    @click="subirDocumento(row.id)"
                  />
                  <VaButton preset="plain" icon="visibility" size="small" @click="verDocumento(row.id)" />
                </template>
              </VaDataTable>
            </div>
          </VaCardContent>
        </VaCard>
      </div>
    </div>

    <!-- Alertas y notificaciones urgentes -->
    <VaCard class="mt-6">
      <VaCardTitle class="flex items-center">
        <VaIcon name="notifications" class="mr-2" color="danger" />
        Alertas urgentes
      </VaCardTitle>
      <VaCardContent>
        <VaAlert
          v-for="(alert, index) in alertasUrgentes"
          :key="index"
          :color="alert.color"
          class="mb-3"
          border="left"
          @click="manejarAlerta(alert.accion)"
        >
          <div class="flex items-center">
            <VaIcon :name="alert.icon" class="mr-2" />
            {{ alert.message }}
          </div>
          <template #close>
            <VaButton preset="plain" icon="close" size="small" @click.stop="eliminarAlerta(index)" />
          </template>
        </VaAlert>

        <VaAlert v-if="alertasUrgentes.length === 0" color="info" border="left">
          No hay alertas urgentes en este momento
        </VaAlert>
      </VaCardContent>
    </VaCard>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import Chart from 'chart.js/auto'

const router = useRouter()

// Datos del profesor
const nombreProfesor = ref('Carlos Eduardo Azueta Leon')

// Periodos académicos
const periodoSeleccionado = ref('ENE-JUN 2025')

// Datos de resumen
const documentosPendientes = ref(4)
const documentosCompletados = ref(12)
const totalDocumentos = computed(() => documentosPendientes.value + documentosCompletados.value)
const porcentajeDocumentacion = computed(() => Math.round((documentosCompletados.value / totalDocumentos.value) * 100))

const carpetasEvidencia = ref(3)
const evidenciasCompletadas = ref(2)

const comisionesActivas = ref(3)
const comisionesPendientes = ref(1)

const tareasAtrasadas = ref(2)
const tareasPorVencerHoy = ref(1)

// Proximos eventos
const proximosEventos = ref([
  {
    id: 1,
    titulo: 'Entrega de planeaciones',
    fecha: '15/06/2024',
    hora: '23:59 hrs',
    icono: 'assignment',
    color: 'primary',
    urgente: false,
  },
  {
    id: 2,
    titulo: 'Revisión de evidencias',
    fecha: '18/06/2024',
    hora: '10:00 hrs',
    icono: 'folder_open',
    color: 'info',
    urgente: true,
  },
  {
    id: 3,
    titulo: 'Junta académica',
    fecha: '20/06/2024',
    hora: '16:00 hrs',
    icono: 'groups',
    color: 'warning',
    urgente: false,
  },
])

// Comisiones
const comisiones = ref([
  {
    id: 1,
    nombre: 'Comité de evaluación',
    descripcion: 'Revisión de instrumentos de evaluación',
    estado: 'Activa',
    fechaLimite: '25/06/2024',
  },
  {
    id: 2,
    nombre: 'Revisión de planes de estudio',
    descripcion: 'Actualización del plan de estudios',
    estado: 'Pendiente',
    fechaLimite: '30/07/2024',
  },
])

// Asignaturas
const asignaturas = ref([
  {
    id: 1,
    clave: 'PRO101',
    nombre: 'Programación I',
    grupo: 'I3A',
    estado: 'Completado',
    fechaEntrega: '15/05/2024',
    progreso: 100,
    documentos: 4,
    documentosEntregados: 4,
    evidencias: 3,
    evidenciasCompletas: 3,
  },
  {
    id: 2,
    clave: 'MAT101',
    nombre: 'Matemáticas I',
    grupo: 'I3A',
    estado: 'En progreso',
    fechaEntrega: '30/05/2024',
    progreso: 75,
    documentos: 5,
    documentosEntregados: 3,
    evidencias: 4,
    evidenciasCompletas: 2,
  },
  {
    id: 3,
    clave: 'FIS101',
    nombre: 'Física I',
    grupo: 'I5B',
    estado: 'Pendiente',
    fechaEntrega: '05/06/2024',
    progreso: 30,
    documentos: 3,
    documentosEntregados: 1,
    evidencias: 2,
    evidenciasCompletas: 0,
  },
  {
    id: 4,
    clave: 'CUL101',
    nombre: 'Cultura Empresarial',
    grupo: 'K9U',
    estado: 'Completado',
    fechaEntrega: '10/05/2024',
    progreso: 100,
    documentos: 2,
    documentosEntregados: 2,
    evidencias: 2,
    evidenciasCompletas: 2,
  },
])

const columnasAsignaturas = ref([
  { key: 'clave', label: 'Clave', sortable: true },
  { key: 'nombre', label: 'Asignatura', sortable: true },
  { key: 'grupo', label: 'Grupo', sortable: true },
  { key: 'estado', label: 'Estado', sortable: true },
  { key: 'fechaEntrega', label: 'Fecha entrega', sortable: true },
  { key: 'documentos', label: 'Docs.', sortable: true },
  { key: 'evidencias', label: 'Evid.', sortable: true },
  { key: 'acciones', label: 'Acciones' },
])

// Filtros para asignaturas
const filtroAsignaturas = ref('todas')
const opcionesFiltroAsignaturas = ref([
  { label: 'Todas', value: 'todas' },
  { label: 'Completadas', value: 'Completado' },
  { label: 'En progreso', value: 'En progreso' },
  { label: 'Pendientes', value: 'Pendiente' },
])

const busquedaAsignaturas = ref('')

const asignaturasFiltradas = computed(() => {
  let filtradas = asignaturas.value

  // Aplicar filtro por estado
  if (filtroAsignaturas.value !== 'todas') {
    filtradas = filtradas.filter((a) => a.estado === filtroAsignaturas.value)
  }

  // Aplicar búsqueda
  if (busquedaAsignaturas.value) {
    const termino = busquedaAsignaturas.value.toLowerCase()
    filtradas = filtradas.filter(
      (a) =>
        a.nombre.toLowerCase().includes(termino) ||
        a.clave.toLowerCase().includes(termino) ||
        a.grupo.toLowerCase().includes(termino),
    )
  }

  return filtradas
})

// Documentación requerida
const tabDocumentacion = ref(0)
const tabsDocumentacion = ref([
  { label: 'Pendientes', estado: 'Pendiente' },
  { label: 'En revisión', estado: 'En revisión' },
  { label: 'Completados', estado: 'Completado' },
])

const documentos = ref([
  {
    id: 1,
    tipo: 'Planeación didáctica',
    asignatura: 'Programación I',
    fechaEntrega: '15/05/2024',
    estado: 'Completado',
    comentarios: '',
  },
  {
    id: 2,
    tipo: 'Rúbricas de evaluación',
    asignatura: 'Matemáticas I',
    fechaEntrega: '30/05/2024',
    estado: 'Pendiente',
    comentarios: 'Falta incluir criterios de evaluación',
  },
  {
    id: 3,
    tipo: 'Evidencias de aprendizaje',
    asignatura: 'Física I',
    fechaEntrega: '05/06/2024',
    estado: 'Pendiente',
    comentarios: '',
  },
  {
    id: 4,
    tipo: 'Reporte de calificaciones',
    asignatura: 'Cultura Empresarial',
    fechaEntrega: '10/05/2024',
    estado: 'Completado',
    comentarios: 'Aprobado',
  },
  {
    id: 5,
    tipo: 'Lista de asistencia',
    asignatura: 'Programación I',
    fechaEntrega: '15/05/2024',
    estado: 'Completado',
    comentarios: '',
  },
])

const columnasDocumentacion = ref([
  { key: 'tipo', label: 'Tipo de documento', sortable: true },
  { key: 'asignatura', label: 'Asignatura', sortable: true },
  { key: 'fechaEntrega', label: 'Fecha entrega', sortable: true },
  { key: 'estado', label: 'Estado', sortable: true },
  { key: 'acciones', label: 'Acciones' },
])

const documentosFiltrados = computed(() => {
  const estadoFiltro = tabsDocumentacion.value[tabDocumentacion.value].estado
  return documentos.value.filter((d) => d.estado === estadoFiltro)
})

// Alertas urgentes
const alertasUrgentes = ref([
  {
    message: 'La entrega de rúbricas para Matemáticas I vence en 2 días',
    color: 'danger',
    icon: 'warning',
    accion: 'verDocumento:2',
  },
  {
    message: 'Tienes 3 documentos pendientes de revisión',
    color: 'warning',
    icon: 'pending',
    accion: 'filtrarDocumentos:Pendiente',
  },
])

// Referencias para gráficos
const progresoAsignaturasChart = ref(null)

// Funciones
const getBadgeColor = (estado) => {
  const estados = {
    Completado: 'success',
    'En progreso': 'warning',
    Pendiente: 'info',
    Atrasado: 'danger',
    Activa: 'primary',
    'En revisión': 'info',
  }
  return estados[estado] || 'primary'
}

const verDocumentacion = (id) => {
  router.push(`/documentacion/asignatura/${id}`)
}

const verDetalle = (id) => {
  router.push(`/asignaturas/detalle/${id}`)
}

const subirDocumento = (id) => {
  console.log('Subir documento:', id)
  // Lógica para subir documento
}

const verDocumento = (id) => {
  router.push(`/documentacion/ver/${id}`)
}

const navegarAEvidencias = () => {
  router.push('/evidencias')
}

const filtrarPorEstado = (estado) => {
  if (estado === 'Comision') {
    // Lógica para filtrar comisiones
    console.log('Filtrar comisiones')
  } else {
    filtroAsignaturas.value = estado
  }
}

const manejarAlerta = (accion) => {
  const [tipo, parametro] = accion.split(':')
  if (tipo === 'verDocumento') {
    verDocumento(parametro)
  } else if (tipo === 'filtrarDocumentos') {
    tabDocumentacion.value = tabsDocumentacion.value.findIndex((tab) => tab.estado === parametro)
  }
}

const eliminarAlerta = (index) => {
  alertasUrgentes.value.splice(index, 1)
}

const seleccionAsignaturaCambiada = (selectedItems) => {
  console.log('Asignaturas seleccionadas:', selectedItems)
}

// Inicialización de gráficos
onMounted(() => {
  // Gráfico de progreso por asignatura
  new Chart(progresoAsignaturasChart.value.getContext('2d'), {
    type: 'bar',
    data: {
      labels: asignaturas.value.map((a) => `${a.nombre} (${a.grupo})`),
      datasets: [
        {
          label: 'Documentación completada',
          data: asignaturas.value.map((a) => (a.documentosEntregados / a.documentos) * 100),
          backgroundColor: 'rgba(54, 162, 235, 0.7)',
        },
        {
          label: 'Evidencias completadas',
          data: asignaturas.value.map((a) => (a.evidenciasCompletas / a.evidencias) * 100),
          backgroundColor: 'rgba(75, 192, 192, 0.7)',
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          max: 100,
          title: {
            display: true,
            text: 'Porcentaje completado',
          },
        },
        x: {
          title: {
            display: true,
            text: 'Asignaturas',
          },
        },
      },
      plugins: {
        tooltip: {
          callbacks: {
            label: function (context) {
              let label = context.dataset.label || ''
              if (label) {
                label += ': '
              }
              label += `${Math.round(context.raw)}%`
              return label
            },
          },
        },
      },
    },
  })
})

// Actualizar gráficos cuando cambian los datos
watch(
  asignaturas,
  () => {
    // Lógica para actualizar gráficos cuando cambian los datos
  },
  { deep: true },
)
</script>

<style scoped>
.dashboard-container {
  max-width: 1800px;
  margin: 0 auto;
  padding: 1rem;
}

.va-card {
  margin-bottom: 1.5rem;
  transition:
    transform 0.2s,
    box-shadow 0.2s;
}

.va-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.va-card[color='background-secondary'] {
  cursor: pointer;
}

.va-alert {
  margin-bottom: 0.75rem;
  cursor: pointer;
}

.va-alert:hover {
  opacity: 0.9;
}
</style>
