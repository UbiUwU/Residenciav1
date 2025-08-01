<template>
<<<<<<< HEAD
  <div class="nueva-vista">
    <!-- Título de la vista -->
    <h1 class="va-h4 mb-4">{{ titulo }}</h1>

    <!-- Contenido principal -->
    <VaCard>
      <VaCardContent>
        <p>Esta es mi nueva vista integrada correctamente con el AppLayout.</p>

        <!-- Ejemplo de componente Vuestic -->
        <VaButton class="mt-4" @click="mostrarMensaje"> Probar funcionamiento </VaButton>
      </VaCardContent>
    </VaCard>
=======
  <div class="dashboard-container">
    <!-- ENCABEZADO -->
    <VaCard class="mb-4">
      <VaCardContent class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
          <h1 class="va-h3">Panel Jefe de Departamento</h1>
          <p class="text-gray-500">Resumen de avances de los maestros</p>
        </div>
        <VaButton icon="refresh" preset="secondary" @click="fetchMaestrosConAsignaturas"> Actualizar </VaButton>
      </VaCardContent>
    </VaCard>

    <!-- KPIs dinámicos -->
    <VaCard class="mb-4">
      <VaCardContent class="grid grid-cols-1 md:grid-cols-4 gap-4 text-center">
        <div class="kpi-box text-primary border border-primary rounded-xl py-3">
          <h2 class="text-2xl font-bold">{{ totalMaestros }}</h2>
          <p class="text-base">Maestros registrados</p>
        </div>
        <div class="kpi-box text-success border border-success rounded-xl py-3">
          <h2 class="text-2xl font-bold">{{ promedioAvance }}%</h2>
          <p class="text-base">Promedio de avance</p>
        </div>
        <div class="kpi-box text-warning border border-warning rounded-xl py-3">
          <h2 class="text-2xl font-bold">{{ maestrosSinAvance }}</h2>
          <p class="text-base">Sin avance</p>
        </div>
        <div class="kpi-box text-danger border border-danger rounded-xl py-3">
          <h2 class="text-2xl font-bold">{{ alertasActivas }}</h2>
          <p class="text-base">Alertas activas</p>
        </div>
      </VaCardContent>
    </VaCard>

    <!-- FILTROS -->
    <div class="filters-container">
      <VaInput
        v-model="busqueda"
        label="Busqueda"
        placeholder="Buscar maestro..."
        clearable
        prepend-inner-icon="search"
        class="filter-item busqueda"
      />
      <VaSelect
        v-model="filtroEstado"
        :options="opcionesEstado"
        label="Filtrar por estado"
        clearable
        class="filter-item estado"
      />
    </div>

    <!-- LOADER -->
    <div v-if="isLoading" class="flex justify-center items-center p-6">
      <VaLoader size="large" />
      <span class="ml-2 text-gray-600">Cargando maestros...</span>
    </div>

    <!-- ERROR -->
    <div v-else-if="error" class="text-red-600 p-4 text-center">Error: {{ error }}</div>

    <!-- TABLA O MENSAJE VACÍO -->
    <div v-else>
      <VaDataTable
        v-if="maestrosFiltrados.length > 0"
        :columns="columnasMaestros"
        :items="maestrosFiltrados"
        :per-page="5"
      >
        <template #cell(avance)="{ value }">
          <div class="flex items-center gap-2">
            <VaProgressBar :model-value="value" color="primary" />
            <span class="text-sm text-gray-700">{{ value }}%</span>
          </div>
        </template>
      </VaDataTable>

      <div v-else class="p-4 text-center text-gray-500">No hay maestros disponibles.</div>
    </div>
    <!-- ALERTAS -->
    <div v-if="alertas.length" class="alertas-minimalistas p-2 mb-4">
      <h3 class="va-h6 mb-2" style="color: #333">Alertas de progreso bajo</h3>
      <ul style="list-style: none; padding-left: 0; margin: 0">
        <li v-for="(alerta, index) in alertas" :key="index" class="alerta-item flex justify-between items-center mb-1">
          <span style="color: #555">{{ alerta.mensaje }}</span>
          <VaButton size="small" color="primary" aria-label="Ver detalles" @click="manejarAlerta(alerta.accion)">
            Ver detalles
          </VaButton>
        </li>
      </ul>
    </div>
>>>>>>> 9245f27f7b357463a428bdf14ded921c39eb8283
  </div>
</template>

<script setup lang="ts">
<<<<<<< HEAD
// Importaciones básicas (opcional)
import { ref } from 'vue'

// Datos reactivos
const titulo = ref('Mi Nueva Vista')
=======
import { ref, computed, watchEffect } from 'vue'
import { useRouter } from 'vue-router'
import { useMaestrosConAsignaturas } from '../dashboard/maestros'

const router = useRouter()
>>>>>>> 9245f27f7b357463a428bdf14ded921c39eb8283

const { maestros, fetchMaestrosConAsignaturas, isLoading, error } = useMaestrosConAsignaturas()

const columnasMaestros = ref([
  { key: 'nombre', label: 'Nombre del maestro', sortable: true },
  { key: 'asignaturas', label: 'Asignaturas asignadas', sortable: true },
  { key: 'avance', label: 'Avance general (%)' },
  { key: 'estado', label: 'Estado', sortable: true },
])

const busqueda = ref('')
const filtroEstado = ref(null)
const opcionesEstado = ref(['Pendiente', 'En progreso', 'Completado'])

const maestrosConEstado = computed(() => {
  return maestros.value.map((m) => {
    const avanceReal = m.avance ?? 0
    return {
      ...m,
      avance: avanceReal,
      estado: avanceReal >= 100 ? 'Completado' : avanceReal > 0 ? 'En progreso' : 'Pendiente',
    }
  })
})

const maestrosFiltrados = computed(() => {
  let lista = maestrosConEstado.value

  if (filtroEstado.value) {
    lista = lista.filter((m) => m.estado === filtroEstado.value)
  }

  if (busqueda.value) {
    const termino = busqueda.value.toLowerCase()
    lista = lista.filter((m) => m.nombre.toLowerCase().includes(termino))
  }

  return lista
})

// KPIs dinámicos calculados
const totalMaestros = computed(() => maestros.value.length)

const promedioAvance = computed(() => {
  if (maestros.value.length === 0) return 0
  const suma = maestros.value.reduce((acc, m) => acc + (m.avance ?? 0), 0)
  return Math.round(suma / maestros.value.length)
})

const maestrosSinAvance = computed(() => {
  return maestros.value.filter((m) => (m.avance ?? 0) === 0).length
})

const alertasActivas = computed(() => {
  return maestros.value.filter((m) => (m.avance ?? 0) <= 50).length
})

// Alertas dinámicas: maestros con 0% de avance
const alertas = ref([])

watchEffect(() => {
  alertas.value = maestrosConEstado.value
    .filter((m) => m.avance <= 50)
    .map((m) => ({
      mensaje: `Maestro ${m.nombre} tiene ${m.avance}% de progreso`,
      color: 'danger',
      icono: 'error',
      accion: `detalle:${m.tarjeta ?? m.id ?? m.nombre}`,
    }))
})

const manejarAlerta = (accion: string) => {
  const [tipo, tarjeta] = accion.split(':')
  if (tipo === 'detalle') {
    verDetalleMaestro(tarjeta)
  }
}

const verDetalleMaestro = (tarjeta: string | number) => {
  router.push({ name: 'materiasMaestro', params: { tarjeta } })
}
</script>

<style scoped>
.dashboard-container {
  max-width: 1400px;
  margin: 0 auto;
  padding: 1rem;
}

.filters-container {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  margin-bottom: 1rem;
}

.filter-item {
  width: 100%;
}

@media (min-width: 768px) {
  .filters-container {
    flex-direction: row;
    align-items: center;
    gap: 1rem;
  }

  .filter-item.busqueda {
    flex-grow: 1;
    flex-shrink: 1;
    flex-basis: 50%;
    min-width: 65%;
  }

  .filter-item.estado {
    flex-grow: 0;
    flex-shrink: 0;
    width: 100px;
    min-width: 100px;
  }
}

.va-card {
  margin-bottom: 1rem;
}

.va-card:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  transform: translateY(-2px);
  transition: all 0.2s;
}

.alertas-minimalistas {
  font-family: Arial, sans-serif;
  font-size: 0.9rem;
}

.alerta-item {
  transition: text-decoration 0.2s ease;
}
</style>
