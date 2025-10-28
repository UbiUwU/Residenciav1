<template>
  <div class="p-4">
    <!-- Formulario para agregar maestro -->
    <VaCard class="mb-6">
      <VaCardTitle>Agregar Maestro</VaCardTitle>
      <VaCardContent>
        <div class="flex flex-wrap gap-4 items-end">
          <VaInput v-model="nuevoMaestro.nombre" label="Nombre" class="flex-1" />
          <VaInput v-model="nuevoMaestro.apellidopaterno" label="Apellido Paterno" class="flex-1" />
          <VaInput v-model="nuevoMaestro.apellidomaterno" label="Apellido Materno" class="flex-1" />
          <VaInput v-model="nuevoMaestro.rfc" label="RFC" class="flex-1" />
          <!-- Dentro del formulario de agregar maestro -->
          <VaSelect
            v-model="usuarioSeleccionado"
            :options="usuariosOptions"
            label="Seleccionar Usuario"
            text-by="correo"
            value-by="idusuario"
            class="w-48"
            @update:modelValue="actualizarUsuarioRol"
          />
          <VaInput v-model="nuevoMaestro.escolaridad_licenciatura" label="Escolaridad Licenciatura" />
          <VaInput v-model="nuevoMaestro.estado_licenciatura" label="Estado Licenciatura" />
          <VaInput v-model="nuevoMaestro.escolaridad_especializacion" label="Escolaridad Especialización" />
          <VaInput v-model="nuevoMaestro.estado_especializacion" label="Estado Especialización" />
          <VaInput v-model="nuevoMaestro.escolaridad_maestria" label="Escolaridad Maestría" />
          <VaInput v-model="nuevoMaestro.estado_maestria" label="Estado Maestría" />
          <VaInput v-model="nuevoMaestro.escolaridad_doctorado" label="Escolaridad Doctorado" />
          <VaInput v-model="nuevoMaestro.estado_doctorado" label="Estado Doctorado" />
          <VaSelect
            v-model="nuevoMaestro.id_departamento"
            :options="departamentosOptions"
            label="Departamento"
            text-by="nombre"
            value-by="id_departamento"
            class="w-48"
          />
          <VaButton color="primary" @click="crearMaestro">Agregar Maestro</VaButton>
        </div>
      </VaCardContent>
    </VaCard>

    <!-- Tabla de maestros -->
    <VaCard>
      <VaCardTitle>Maestros</VaCardTitle>
      <VaCardContent>
        <div class="mb-4 flex justify-between items-center">
          <VaInput
            v-model="search"
            placeholder="Buscar..."
            clearable
            prepend-inner-icon="search"
            class="w-full sm:w-1/2"
          />
        </div>

        <VaDataTable :columns="columns" :items="filteredMaestros" :loading="isLoading">
          <template #cell(acciones)="{ rowData }">
            <VaButton
              color="primary"
              icon="schedule"
              size="small"
              preset="outline"
              @click="enviarTarjeta(rowData.tarjeta)"
            >
              Agregar Horario
            </VaButton>
          </template>
        </VaDataTable>
      </VaCardContent>
    </VaCard>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { VaButton, VaInput, VaDataTable, VaSelect, VaCard, VaCardContent, VaCardTitle } from 'vuestic-ui'
import { useRouter } from 'vue-router'
import api from '../../services/api'

import { useToast } from 'vuestic-ui'

const { init } = useToast()
const router = useRouter()

interface Maestro {
  tarjeta: number
  nombre: string
  apellidopaterno: string
  apellidomaterno: string
  [key: string]: any
}

// Lista de maestros
const maestros = ref<Maestro[]>([])
const isLoading = ref(false)
const search = ref('')

interface Usuario {
  idusuario: number
  idrol: number
  correo: string
  [key: string]: any // Por si hay más campos
}

// Tipar el arreglo de opciones
const usuariosOptions = ref<Usuario[]>([])

// Tipar la selección
const usuarioSeleccionado = ref<number | null>(null)

// Columnas de la tabla
const columns = [
  { label: 'N.º de Tarjeta', key: 'tarjeta', sortable: true },
  { label: 'Nombre', key: 'nombre', sortable: true },
  { label: 'Apellido Paterno', key: 'apellidopaterno', sortable: true },
  { label: 'Apellido Materno', key: 'apellidomaterno', sortable: true },
  { label: '', key: 'acciones' },
]

// Filtrado
const filteredMaestros = computed(() =>
  (maestros.value || []).filter((maestro) => {
    const q = search.value.toLowerCase()
    return (
      maestro.tarjeta?.toString().toLowerCase().includes(q) ||
      maestro.nombre?.toLowerCase().includes(q) ||
      maestro.apellidopaterno?.toLowerCase().includes(q) ||
      maestro.apellidomaterno?.toLowerCase().includes(q)
    )
  }),
)

interface MaestroForm {
  nombre: string
  apellidopaterno: string
  apellidomaterno: string
  idusuario: number | null
  idrol: number | null
  rfc: string
  escolaridad_licenciatura: string
  estado_licenciatura: string
  escolaridad_especializacion: string
  estado_especializacion: string
  escolaridad_maestria: string
  estado_maestria: string
  escolaridad_doctorado: string
  estado_doctorado: string
  id_departamento: number | null
}

const nuevoMaestro = ref<MaestroForm>({
  nombre: '',
  apellidopaterno: '',
  apellidomaterno: '',
  idusuario: null,
  idrol: null,
  rfc: '',
  escolaridad_licenciatura: '',
  estado_licenciatura: '',
  escolaridad_especializacion: '',
  estado_especializacion: '',
  escolaridad_maestria: '',
  estado_maestria: '',
  escolaridad_doctorado: '',
  estado_doctorado: '',
  id_departamento: null,
})

const obtenerUsuarios = async () => {
  try {
    const res = await api.getUsuarios()
    usuariosOptions.value = res.data.data || res.data
  } catch (err) {
    console.error('Error al obtener usuarios', err)
  }
}

// Al seleccionar usuario, actualizar idusuario y idrol
const actualizarUsuarioRol = (idusuario: number) => {
  const user = usuariosOptions.value.find((u) => u.idusuario === idusuario)
  if (user) {
    nuevoMaestro.value.idusuario = user.idusuario
    nuevoMaestro.value.idrol = user.idrol
  }
}

// Opciones de departamento y roles
const departamentosOptions = ref([])
const rolesOptions = ref([])

// Obtener maestros
const obtenerMaestros = async () => {
  isLoading.value = true
  try {
    const res = await api.getMaestros()
    maestros.value = res.data.data || res.data
  } finally {
    isLoading.value = false
  }
}

// Obtener departamentos y roles
const obtenerDepartamentos = async () => {
  try {
    const res = await api.getDepartamentos()
    departamentosOptions.value = res.data.data || res.data
  } catch (err) {
    console.error(err)
  }
}
const obtenerRoles = async () => {
  try {
    const res = await api.getRoles()
    rolesOptions.value = res.data.data || res.data
  } catch (err) {
    console.error(err)
  }
}

// Crear maestro
const crearMaestro = async () => {
  try {
    await api.crearMaestro(nuevoMaestro.value)
    init({ message: 'Maestro creado con éxito', color: 'success' })
    nuevoMaestro.value = {
      nombre: '',
      apellidopaterno: '',
      apellidomaterno: '',
      idusuario: null,
      idrol: null,
      rfc: '',
      escolaridad_licenciatura: '',
      estado_licenciatura: '',
      escolaridad_especializacion: '',
      estado_especializacion: '',
      escolaridad_maestria: '',
      estado_maestria: '',
      escolaridad_doctorado: '',
      estado_doctorado: '',
      id_departamento: null,
    }
    await obtenerMaestros()
  } catch (err: any) {
    console.error('Error al crear maestro', err.response?.data)
  }
}

// Acción para agregar horario
const enviarTarjeta = (tarjeta: number) => {
  router.push({ name: 'agregarHorario', params: { tarjeta } })
}
onMounted(() => {
  obtenerMaestros()
  obtenerDepartamentos()
  obtenerRoles()
  obtenerUsuarios()
})
</script>

<style scoped>
.flex {
  display: flex;
}
.flex-wrap {
  flex-wrap: wrap;
}
.gap-2 {
  gap: 0.5rem;
}
.gap-4 {
  gap: 1rem;
}
.w-48 {
  width: 12rem;
}
.mb-4 {
  margin-bottom: 1rem;
}
.mb-6 {
  margin-bottom: 1.5rem;
}
</style>
