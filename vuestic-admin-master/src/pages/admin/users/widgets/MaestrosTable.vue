<script setup lang="ts">
import { defineVaDataTableColumns } from 'vuestic-ui'
import { useRouter } from 'vue-router'
import { PropType, toRef, ref, computed } from 'vue'

const columns = defineVaDataTableColumns([
  { label: 'N.º de Tarjeta', key: 'tarjeta', sortable: true },
  { label: 'Nombre', key: 'nombre', sortable: true },
  { label: 'Apellido Paterno', key: 'apellidopaterno', sortable: true },
  { label: 'Apellido Materno', key: 'apellidomaterno', sortable: true },
  { label: '', key: 'acciones' },
])

const props = defineProps({
  maestros: {
    type: Array as PropType<any[]>,
    required: true,
  },
  loading: {
    type: Boolean,
    default: false,
  },
})

const router = useRouter()
const maestros = toRef(props, 'maestros')
const search = ref('')

// Computed para filtrar maestros por nombre (ignora mayúsculas/minúsculas)
const filteredMaestros = computed(() =>
  maestros.value.filter((maestro) => {
    const query = search.value.toLowerCase()
    return (
      maestro.tarjeta?.toString().toLowerCase().includes(query) ||
      maestro.nombre?.toLowerCase().includes(query) ||
      maestro.apellidopaterno?.toLowerCase().includes(query) ||
      maestro.apellidomaterno?.toLowerCase().includes(query)
    )
  }),
)

const verMaestro = (tarjeta: number) => {
  router.push({ name: 'materiasMaestro', params: { tarjeta } })
}

const generarReporteFinal = (tarjeta: number) => {
  router.push({ name: 'reporteFinal', params: { tarjeta } })
}
</script>

<template>
  <div class="mb-4 flex justify-between items-center">
    <VaInput
      v-model="search"
      placeholder="Buscar por nombre..."
      clearable
      prepend-inner-icon="search"
      class="mr-4 w-full sm:w-1/2"
    />
  </div>

  <VaDataTable :columns="columns" :items="filteredMaestros" :loading="loading">
    <template #cell(acciones)="{ rowData }">
      <div class="flex flex-wrap gap-2">
        <VaButton
          color="primary"
          icon="visibility"
          size="small"
          preset="outline"
          class="rounded-md shadow-sm px-1 py-1 text-sm md:text-base font-semibold"
          @click="verMaestro(rowData.tarjeta)"
        >
          Ver maestro
        </VaButton>

        <VaButton
          color="info"
          icon="description"
          size="small"
          preset="outline"
          class="rounded-md shadow-sm px-1 py-1 text-sm md:text-base font-semibold"
          @click="generarReporteFinal(rowData.tarjeta)"
        >
          Reporte Final
        </VaButton>
      </div>
    </template>
  </VaDataTable>
</template>


<style scoped>
.mb-4 {
  margin-bottom: 1rem;
}
</style>
