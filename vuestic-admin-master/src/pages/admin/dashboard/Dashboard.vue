<template>
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
  </div>
</template>

<script setup lang="ts">
// Importaciones básicas (opcional)
import { ref } from 'vue'

// Datos reactivos
const titulo = ref('Mi Nueva Vista')

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
