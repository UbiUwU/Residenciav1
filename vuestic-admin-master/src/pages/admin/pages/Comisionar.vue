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
          <VaSelect
            v-model="form.tipoComision"
            :options="origenOptions"
            label="Tipo de Comisión"
            required
            :track-by="'value'"
            :value-by="'value'"
          />

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
            :options="eventoOptions"
            label="Tipo de Evento"
            required
            searchable
            allow-create
            :get-option-value="getEventoValue"
          />

          <VaSelect
            v-model="form.idPeriodoEscolar"
            :options="periodoOptions"
            label="Periodo Escolar"
            required
            :get-option-value="getPeriodoValue"
          />

          <!-- Estado -->
          <VaInput v-model="form.estado" label="Estado" disabled />

          <!-- Fechas de la Comisión -->
          <div class="col-span-1 md:col-span-2">
            <h3 class="font-medium mb-2">Fechas de la Comisión</h3>
            <div v-for="(fecha, index) in form.fechas" :key="index" class="border p-3 rounded mb-2">
              <VaInput v-model="fecha.fecha" type="date" label="Fecha" required />
              <VaInput
                v-model="fecha.horaInicio"
                type="time"
                label="Hora Inicio"
                required
                @update:modelValue="calcularDuracion(index)"
              />
              <VaInput
                v-model="fecha.horaFin"
                type="time"
                label="Hora Fin"
                required
                @update:modelValue="calcularDuracion(index)"
              />
              <!-- Duración calculada automáticamente -->
              <VaInput v-model="fecha.duracion" label="Duration" readonly />

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
const evento = ref<any[]>([])
const periodo = ref<any[]>([])
const origen = ref<any[]>([])
const comisiones = ref<any[]>([])
const error = ref<Error | null>(null)
const { init } = useToast()

const fetchMaestros = async () => {
  try {
    maestros.value = (await api.getMaestros()).data
  } catch (err) {
    error.value = err as Error
  }
}

const fetchEventos = async () => {
  try {
    evento.value = (await api.getevento()).data
  } catch (err) {
    error.value = err as Error
  }
}

const fetchPeriodos = async () => {
  try {
    periodo.value = (await api.getPeriodo()).data
  } catch (err) {
    error.value = err as Error
  }
}

const fetchOrigen = async () => {
  try {
    origen.value = (await api.getEstado()).data.valores
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
  fetchEventos()
  fetchPeriodos()
  fetchOrigen()
  fetchComisiones()
})

const maestroOptions = computed(() =>
  maestros.value.map((m) => ({
    text: `${m.nombre} ${m.apellidopaterno} ${m.apellidomaterno}`,
    value: m.tarjeta,
  })),
)

const eventoOptions = computed(() =>
  evento.value.map((e) => ({
    text: `${e.nombre}`,
    value: e.id_tipo_evento,
  })),
)

const periodoOptions = computed(() =>
  periodo.value.map((p) => ({
    text: `${p.codigoperiodo}`,
    value: p.id_periodo_escolar,
  })),
)

const origenOptions = computed(() =>
  origen.value.map((v: string) => ({
    text: v,
    value: v,
  })),
)

interface PeriodoOption {
  text: string
  value: number
}

// Declara la función tipada
const getPeriodoValue = (option: PeriodoOption) => option.value

interface EventoOption {
  text: string
  value: number
}

const getEventoValue = (option: EventoOption) => option.value

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
  fechas: [{ fecha: '', horaInicio: '', horaFin: '', duracion: '', observaciones: '' }],
  selectedMaestro: [],
})

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

const calcularDuracion = (index: number) => {
  const fecha = form.value.fechas[index]
  if (!fecha.horaInicio || !fecha.horaFin) return

  const [hIni, mIni] = fecha.horaInicio.split(':').map(Number)
  const [hFin, mFin] = fecha.horaFin.split(':').map(Number)

  const inicio = hIni * 60 + mIni
  const fin = hFin * 60 + mFin

  if (fin > inicio) {
    const diffMin = fin - inicio
    const hours = Math.floor(diffMin / 60)
    const minutes = diffMin % 60

    if (minutes === 0) {
      fecha.duracion = `${hours} Hours`
    } else {
      fecha.duracion = `${hours} Hours ${minutes} min`
    }
  } else {
    fecha.duracion = 'Invalid time'
  }
}

const submitForm = async () => {
  try {
    const payload = {
      folio: form.value.folio,
      nombre_evento: form.value.nombreEvento,
      id_tipo_evento: (form.value.idTipoEvento as any)?.value ?? form.value.idTipoEvento,
      id_periodo_escolar: (form.value.idPeriodoEscolar as any)?.value ?? form.value.idPeriodoEscolar,
      estado: form.value.estado,
      remitente_nombre: form.value.remitenteNombre,
      remitente_puesto: form.value.remitentePuesto,
      lugar: form.value.lugar,
      motivo: form.value.motivo,
      tipo_comision: (form.value.tipoComision as any)?.value ?? form.value.tipoComision,
      permiso_constancia: form.value.permisoConstancia,
      fechas: form.value.fechas.map((f) => ({
        fecha: f.fecha,
        hora_inicio: f.horaInicio,
        hora_fin: f.horaFin,
        duracion: f.duracion,
        observaciones: f.observaciones,
      })),
      maestros: form.value.selectedMaestro.map((m) => (typeof m === 'string' ? m : m.value)),
    }
    console.log('Payload a enviar:', payload)

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
    fechas: [{ fecha: '', horaInicio: '', horaFin: '', duracion: '', observaciones: '' }],
    selectedMaestro: [],
  }
}
</script>
