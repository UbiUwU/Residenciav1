<template>
  <div class="p-4">
    <VaCard>
      <VaCardTitle>
        <h2 class="va-h5">Registrar Comisión</h2>
      </VaCardTitle>
      <VaCardContent>
        <form class="grid grid-cols-1 md:grid-cols-2 gap-6" @submit.prevent="submitForm">
          <!-- Número de Folio -->
          <VaInput v-model="form.folio" label="Número de Folio" required />

          <!-- Remitente Nombre -->
          <VaInput v-model="form.remitenteNombre" label="Remitente - Nombre" required />

          <!-- Remitente Puesto -->
          <VaInput v-model="form.remitentePuesto" label="Remitente - Puesto" required />

          <!-- Lugar -->
          <VaInput v-model="form.lugar" label="Lugar" required />

          <!-- Motivo -->
          <VaInput v-model="form.motivo" label="Motivo" type="textarea" required />

          <!-- Tipo de Comisión -->
          <VaInput v-model="form.tipoComision" label="Tipo de Comisión" required />

          <!-- Permiso de la Constancia -->
          <div class="col-span-1 md:col-span-2">
            <label class="font-medium mb-2 block">Permiso de la Constancia</label>
            <VaCheckbox v-model="form.permisoConstancia" label="Generar constancia" />
          </div>

          <!-- Nombre del Evento -->
          <VaInput v-model="form.nombreEvento" label="Nombre del Evento" required />

          <!-- Tipo de Evento -->
         <VaSelect
            v-model="form.idTipoEvento"
            :options="tipoEventoOptions"
            label="Tipo de Evento"
            required
            searchable
            allow-create
            @create-new="addTipoEvento"
          />

          <!-- Periodo Escolar -->
          <VaSelect
            v-model="form.idPeriodoEscolar"
            :options="periodoOptions"
            label="Periodo Escolar"
            required
          />

          <!-- Estado -->
          <VaSelect v-model="form.estado" :options="statusOptions" label="Estado" required />

          <!-- Fechas de la Comisión -->
          <div class="col-span-1 md:col-span-2">
            <h3 class="font-medium mb-2">Fechas de la Comisión</h3>
            <div
              v-for="(fecha, index) in form.fechas"
              :key="index"
              class="border p-3 rounded mb-2"
            >
              <VaInput v-model="fecha.fecha" type="date" label="Fecha" required />
              <VaInput v-model="fecha.horaInicio" type="time" label="Hora Inicio" required />
              <VaInput v-model="fecha.horaFin" type="time" label="Hora Fin" required />
              <VaInput v-model="fecha.duracion" label="Duración (ej. 4 hours)" required />
              <VaInput v-model="fecha.observaciones" label="Observaciones" />
            </div>
            <VaButton size="small" @click="addFecha">Agregar Fecha</VaButton>
          </div>

          <!-- Asignar Maestro(s) -->
          <VaSelect
            v-model="form.selectedMaestro"
            :options="maestroOptions"
            label="Asignar Maestro(s)"
            multiple
            clearable
            track-by="value"
            required
          />

          <!-- Botones -->
          <div class="col-span-1 md:col-span-2 flex justify-end gap-2 mt-4">
            <VaButton preset="secondary" @click="resetForm">Cancelar</VaButton>
            <VaButton type="submit" color="primary">Guardar</VaButton>
          </div>
        </form>
      </VaCardContent>
    </VaCard>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useToast } from 'vuestic-ui'
import api from '../../../services/api'

const maestros = ref<any[]>([])
const comisiones = ref<any[]>([])
const error = ref<Error | null>(null)

const fetchMaestros = async () => {
  try {
    maestros.value = (await api.getMaestros()).data.data
  } catch (err) {
    error.value = err as Error
  }
}

const fetchComisiones = async () => {
  try {
    comisiones.value = (await api.getComisiones()).data
  } catch (err) {
    error.value = err as Error
  }
}

onMounted(() => {
  fetchMaestros()
  fetchComisiones()
})

const maestroOptions = computed(() =>
  maestros.value.map((m) => ({
    text: `${m.nombre} ${m.apellidopaterno} ${m.apellidomaterno}`,
    value: m.tarjeta,
  }))
)

const { init } = useToast()

// ---- FORM DATA ----
interface FechaData {
  fecha: string
  horaInicio: string
  horaFin: string
  duracion: string
  observaciones: string
}

interface FormData {
  folio: string
  remitenteNombre: string
  remitentePuesto: string
  lugar: string
  motivo: string
  tipoComision: string
  permisoConstancia: boolean
  nombreEvento: string
  idTipoEvento: number | null
  idPeriodoEscolar: number | null
  estado: string
  fechas: FechaData[]
  selectedMaestro: any[]
}

const form = ref<FormData>({
  folio: '',
  remitenteNombre: '',
  remitentePuesto: '',
  lugar: '',
  motivo: '',
  tipoComision: '',
  permisoConstancia: false,
  nombreEvento: '',
  idTipoEvento: null,
  idPeriodoEscolar: null,
  estado: 'pendiente',
  fechas: [
    { fecha: '', horaInicio: '', horaFin: '', duracion: '', observaciones: '' },
  ],
  selectedMaestro: [],
})

// ---- OPTIONS ----
const tipoEventoOptions = ref([
  { text: 'Académico', value: 1 },
  { text: 'Administrativo', value: 2 },
])

const addTipoEvento = (newOption: string) => {
  // Aquí generas un ID temporal (o pides al backend que lo cree)
  const newId = tipoEventoOptions.value.length + 1
  const option = { text: newOption, value: newId }
  tipoEventoOptions.value.push(option)
  form.value.idTipoEvento = newId
}

const periodoOptions = [
  { text: '2025-1', value: 1 },
  { text: '2025-2', value: 2 },
]

const statusOptions = [
  { text: 'Pendiente', value: 'pendiente' },
  { text: 'Completado', value: 'completado' },
]

// ---- METHODS ----
const addFecha = () => {
  form.value.fechas.push({
    fecha: '',
    horaInicio: '',
    horaFin: '',
    duracion: '',
    observaciones: '',
  })
}

const submitForm = async () => {
  try {
    const payload = {
      folio: form.value.folio,
      nombre_evento: form.value.nombreEvento,
      id_tipo_evento: form.value.idTipoEvento,
      id_periodo_escolar: form.value.idPeriodoEscolar,
      estado: form.value.estado,
      remitente_nombre: form.value.remitenteNombre,
      remitente_puesto: form.value.remitentePuesto,
      lugar: form.value.lugar,
      motivo: form.value.motivo,
      tipo_comision: form.value.tipoComision,
      permiso_constancia: form.value.permisoConstancia,
      fechas: form.value.fechas.map((f) => ({
        fecha: f.fecha,
        hora_inicio: f.horaInicio,
        hora_fin: f.horaFin,
        duracion: f.duracion,
        observaciones: f.observaciones,
      })),
      maestros: form.value.selectedMaestro.map((m) =>
        typeof m === 'string' ? m : m.value
      ),
    }

    await api.crearComision(payload)
    init({ message: 'Comisión registrada exitosamente.', color: 'success' })
    resetForm()
    fetchComisiones()
  } catch (err) {
    console.error(err)
    init({ message: 'Error al guardar la comisión.', color: 'danger' })
  }
}

const resetForm = () => {
  form.value = {
    folio: '',
    remitenteNombre: '',
    remitentePuesto: '',
    lugar: '',
    motivo: '',
    tipoComision: '',
    permisoConstancia: false,
    nombreEvento: '',
    idTipoEvento: null,
    idPeriodoEscolar: null,
    estado: 'pendiente',
    fechas: [
      { fecha: '', horaInicio: '', horaFin: '', duracion: '', observaciones: '' },
    ],
    selectedMaestro: [],
  }
}
</script>
