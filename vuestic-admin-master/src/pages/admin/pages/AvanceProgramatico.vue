<template>
  <div class="boton-regresar">
    <button @click="handleRegresar">← Regresar</button>
  </div>

  <div v-if="avanceFiltrado">
    <VaCard>
      <VaCardTitle>
        <div class="flex justify-between">
          <span class="font-bold text-lg">Avance programático</span>
          <span class="text-sm text-gray-500">Fecha: {{ avanceFiltrado.fecha_creacion }}</span>
        </div>
      </VaCardTitle>

      <VaCardContent>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <p><strong>Nombre del profesor:</strong> {{ avanceFiltrado.nombre_profesor }}</p>
            <p><strong>Asignatura:</strong> {{ avanceFiltrado.nombre_asignatura }}</p>
            <p><strong>Clave:</strong> {{ avanceFiltrado.clave_asignatura }}</p>
          </div>
          <div>
            <p><strong>Periodo:</strong> {{ avanceFiltrado.periodo }}</p>
            <p><strong>Firma profesor:</strong> {{ avanceFiltrado.firma_profesor }}</p>
            <p><strong>Firma jefe:</strong> {{ avanceFiltrado.firma_jefe || 'No firmada' }}</p>
          </div>
        </div>

        <div class="mt-4">
          <p><strong>Estado:</strong> <VaBadge :text="avanceFiltrado.estado" /></p>
        </div>

        <div class="mt-6">
          <h3 class="text-lg font-semibold mb-2">Detalles del avance</h3>
          <table class="table-auto w-full">
            <thead>
              <tr>
                <th class="px-4 py-2 text-left">Unidad</th>
                <th class="px-4 py-2 text-left">Temas</th>
                <th class="px-4 py-2 text-left">Fecha inicio</th>
                <th class="px-4 py-2 text-left">Fecha fin</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="detalle in avanceFiltrado.detalles" :key="detalle.id_avance_detalle">
                <td class="border px-4 py-2">{{ detalle.unidad }}</td>
                <td class="border px-4 py-2">{{ detalle.temas }}</td>
                <td class="border px-4 py-2">{{ detalle.fecha_inicio }}</td>
                <td class="border px-4 py-2">{{ detalle.fecha_fin }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="mt-6 flex justify-center gap-4">
          <VaButton color="secondary" @click="volver">
            <VaIcon name="arrow_back" class="mr-2" />
            Volver
          </VaButton>
          <VaButton color="info" @click="marcarRevisado">
            <VaIcon name="check_circle" class="mr-2" />
            Revisado
          </VaButton>
          <VaButton color="success" @click="marcarCorrecto">
            <VaIcon name="check" class="mr-2" />
            Correcto
          </VaButton>
          <VaButton color="warning" @click="toggleObservaciones">
            <VaIcon name="edit_note" class="mr-2" />
            Enviar para corrección
          </VaButton>
        </div>

        <!-- Observaciones -->
        <VaCard v-if="mostrarObservaciones" class="mt-4">
          <VaCardTitle class="flex items-center">
            <VaIcon name="note" class="mr-2" />
            <span>Observaciones</span>
          </VaCardTitle>
          <VaCardContent>
            <VaInput
              v-model="observaciones"
              type="textarea"
              label="Observaciones"
              placeholder="Escribe las observaciones aquí..."
              autosize
              class="mb-4"
            />
            <div class="flex justify-end">
              <VaButton color="warning" @click="enviarCorrecciones">
                <VaIcon name="send" class="mr-2" />
                Confirmar envío
              </VaButton>
            </div>
          </VaCardContent>
        </VaCard>
      </VaCardContent>
    </VaCard>
  </div>

  <VaCard v-else class="p-4 text-center">
    <p class="text-lg font-semibold text-gray-700">No tiene avance programático</p>
  </VaCard>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../../../services/api'
import { useToast } from 'vuestic-ui'

interface AvanceDetalle {
  id_avance_detalle: number
  unidad: string | number
  temas: string
  fecha_inicio: string
  fecha_fin: string
}

interface Avance {
  detalles: AvanceDetalle[]
  nombre_profesor: string
  nombre_asignatura: string
  clave_asignatura: string
  periodo: string
  firma_profesor: string
  firma_jefe: string
  estado: string
  fecha_creacion: string
  id_avance?: number
}

const route = useRoute()
const router = useRouter()
const tarjeta = route.params.tarjeta as string
const grupo = (route.params.grupo as string)?.replaceAll('%20', ' ').trim()
const claveAsignatura = (route.params.clave_asignatura as string)?.replaceAll('%20', ' ').trim()
const idPeriodoEscolar = Number(route.params.periodo)
const toast = useToast()

const avanceFiltrado = ref<Avance | null>(null)
const observaciones = ref('')
const mostrarObservaciones = ref(false)
const idAvance = ref<number | null>(null)

const handleRegresar = () => router.back()

onMounted(async () => {
  try {
    const response = await api.getAvance()
    const avances = response.data as Array<any>
    console.log('Respuesta completa del backend:', response.data)

    const encontrado = avances.find(
      (avance) =>
        avance.tarjeta_profesor === Number(tarjeta) &&
        avance.horario.clavegrupo === grupo &&
        avance.clave_asignatura === claveAsignatura &&
        avance.id_periodo_escolar === idPeriodoEscolar,
    )

    if (!encontrado) {
      toast.notify({ message: 'No se encontró avance para este grupo/asignatura.', color: 'warning' })
      return
    }

    // Mapear los datos correctamente
    avanceFiltrado.value = {
      detalles: (encontrado.detalles || []).map((d: any) => ({
        id_avance_detalle: d.id_avance_detalle,
        unidad: d.id_tema,
        temas: d.observaciones,
        fecha_inicio: d.created_at?.split('T')[0] || 'N/A',
        fecha_fin: d.updated_at?.split('T')[0] || 'N/A',
      })),
      nombre_profesor: `${encontrado.profesor.nombre} ${encontrado.profesor.apellidopaterno} ${encontrado.profesor.apellidomaterno}`,
      nombre_asignatura: encontrado.asignatura.NombreAsignatura,
      periodo: encontrado.periodo_escolar.nombre_periodo,
      firma_jefe: encontrado.firma_jefe_carrera,
      firma_profesor: encontrado.firma_profesor,
      clave_asignatura: encontrado.clave_asignatura,
      estado: encontrado.estado,
      fecha_creacion: encontrado.fecha_creacion,
      id_avance: encontrado.id_avance,
    }

    console.log('Avance filtrado:', avanceFiltrado.value)
    idAvance.value = encontrado.id_avance || null
  } catch (error) {
    console.error('Error al obtener avances:', error)
    toast.notify({ message: 'Error al obtener datos del avance programático', color: 'danger' })
  }
})

// Funciones para botones
const volver = () => router.back()

const marcarRevisado = async () => {
  const id = idAvance.value
  if (!id) return alert('No se encontró el ID del avance.')
  try {
    await api.actualizarAvance(id, { estado: 'enviado', detalles: [] })
    toast.notify({ message: 'Documento marcado como REVISADO.', color: 'success' })
  } catch (error) {
    console.error(error)
    toast.notify({ message: 'Error al marcar como revisado.', color: 'danger' })
  }
}

const marcarCorrecto = async () => {
  const id = idAvance.value
  if (!id) return alert('No se encontró el ID del avance.')
  try {
    await api.actualizarAvance(id, { estado: 'firmado', detalles: [] })
    toast.notify({ message: 'Documento marcado como CORRECTO.', color: 'success' })
  } catch (error) {
    console.error(error)
    toast.notify({ message: 'Error al marcar como correcto.', color: 'danger' })
  }
}

const enviarCorrecciones = async () => {
  const id = idAvance.value
  if (!id) return alert('No se encontró el ID del avance.')
  if (!observaciones.value.trim()) return alert('Por favor, escribe las observaciones.')

  try {
    await api.actualizarAvance(id, {
      estado: 'rechazado',
      detalles: [{ id_avance_detalle: id, observaciones: observaciones.value }],
    })
    toast.notify({ message: 'Observaciones enviadas para corrección.', color: 'success' })
    observaciones.value = ''
    mostrarObservaciones.value = false
  } catch (error) {
    console.error(error)
    toast.notify({ message: 'Error al enviar las observaciones.', color: 'danger' })
  }
}

const toggleObservaciones = () => {
  mostrarObservaciones.value = !mostrarObservaciones.value
}
</script>

<style>
.boton-regresar {
  position: sticky;
  top: 1rem;
  left: 1rem;
  z-index: 10;
  margin: 10px;
}
.boton-regresar button {
  background-color: #2a5dff;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.2s ease;
}
.boton-regresar button:hover {
  background-color: #1e45cc;
}
</style>
