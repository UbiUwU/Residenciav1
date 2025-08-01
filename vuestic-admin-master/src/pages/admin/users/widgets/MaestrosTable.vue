<script setup lang="ts">
import { defineVaDataTableColumns } from 'vuestic-ui'
import { useRouter } from 'vue-router'
import { PropType, toRef, ref, computed } from 'vue'
import { useToast } from 'vuestic-ui' // Asegúrate de importar useToast

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
  router.push({ name: 'reporteFinalMaestro', params: { tarjeta } })
}

const userToCreate = ref({
  tarjeta: '',
  nombre: '',
  apellidopaterno: '',
  apellidomaterno: '',
  idusuario: '',
  rfc: '',
  escolaridad_licenciatura: '',
  estado_licenciatura: '',
  escolaridad_especializacion: '',
  estado_especializacion: '',
  escolaridad_maestria: '',
  estado_maestria: '',
  escolaridad_doctorado: '',
  estado_doctorado: '',
  id_departamento: '',
})

const doShowUserFormModal = ref(false)

const cancelModal = () => {
  doShowUserFormModal.value = false
}

const onUserSaved = () => {
  // Aquí iría la lógica de guardado del nuevo usuario
  console.log('Usuario guardado:', userToCreate.value)
  doShowUserFormModal.value = false

  // Muestra un mensaje de éxito utilizando notify()
  useToast().notify({
    message: 'Usuario creado con éxito',
    color: 'success',
  })
}
</script>

<template>
  <div class="mb-4 flex justify-between items-center">
    <VaInput v-model="search" placeholder="Buscar por nombre..." clearable prepend-inner-icon="search" class="mr-4" />
  </div>

  <VaDataTable :columns="columns" :items="filteredMaestros" :loading="loading">
    <template #cell(acciones)="{ rowData }">
      <VaButton color="primary" icon="visibility" size="small" @click="verMaestro(rowData.tarjeta)">
        Ver maestro
      </VaButton>
      <VaButton color="info" icon="description" size="small" class="ml-2" @click="generarReporteFinal(rowData.tarjeta)">
        Reporte Final
      </VaButton>
    </template>
  </VaDataTable>

  <!-- Modal de Crear Usuario -->
  <VaModal v-model="doShowUserFormModal" size="small" mobile-fullscreen close-button stateful hide-default-actions>
    <h1 class="va-h5 mb-4">Añadir Usuario</h1>
    <form @submit.prevent="onUserSaved">
      <VaInput v-model="userToCreate.tarjeta" label="Tarjeta" />
      <VaInput v-model="userToCreate.nombre" label="Nombre" />
      <VaInput v-model="userToCreate.apellidopaterno" label="Apellido Paterno" />
      <VaInput v-model="userToCreate.apellidomaterno" label="Apellido Materno" />
      <VaInput v-model="userToCreate.idusuario" label="ID Usuario" />
      <VaInput v-model="userToCreate.rfc" label="RFC" />
      <VaInput v-model="userToCreate.escolaridad_licenciatura" label="Escolaridad Licenciatura" />
      <VaInput v-model="userToCreate.estado_licenciatura" label="Estado Licenciatura" />
      <VaInput v-model="userToCreate.escolaridad_especializacion" label="Escolaridad Especialización" />
      <VaInput v-model="userToCreate.estado_especializacion" label="Estado Especialización" />
      <VaInput v-model="userToCreate.escolaridad_maestria" label="Escolaridad Maestría" />
      <VaInput v-model="userToCreate.estado_maestria" label="Estado Maestría" />
      <VaInput v-model="userToCreate.escolaridad_doctorado" label="Escolaridad Doctorado" />
      <VaInput v-model="userToCreate.estado_doctorado" label="Estado Doctorado" />
      <VaInput v-model="userToCreate.id_departamento" label="ID Departamento" />

      <div class="mt-4">
        <VaButton color="primary" type="submit">Guardar</VaButton>
        <VaButton color="secondary" @click="cancelModal">Cancelar</VaButton>
      </div>
    </form>
  </VaModal>
</template>

<style scoped>
.mb-4 {
  margin-bottom: 1rem;
}
</style>
