<template>
  <div class="boton-regresar">
    <button @click="handleRegresar">← Regresar</button>
  </div>
  <div>
    <VaCard v-if="avanceFiltrado">
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
    <VaCard v-else class="p-4 text-center">
      <p class="text-lg font-semibold text-gray-700">No tiene avance programático</p>
    </VaCard>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../../../services/api'
import { useToast } from 'vuestic-ui'

const route = useRoute()
const router = useRouter()
const tarjeta = route.params.tarjeta as string
const grupo = (route.params.grupo as string)?.replaceAll('%20', ' ').trim()
const claveAsignatura = (route.params.clave_asignatura as string)?.replaceAll('%20', ' ').trim()

const toast = useToast()

const avanceFiltrado = ref<any>(null)
const observaciones = ref('')
const mostrarObservaciones = ref(false)
const idAvanceDetalle = ref<number | null>(null)

const handleRegresar = () => {
  router.back()
}

onMounted(async () => {
  try {
    console.log('Tarjeta:', tarjeta)
    const response = await api.getAvancesMaestro(tarjeta)
    const avances = response.data as Array<any>
    avanceFiltrado.value = avances.find(
      (avance) => avance.clave_asignatura === claveAsignatura && avance.clave_grupo === grupo,
    )
    idAvanceDetalle.value = avanceFiltrado.value?.detalles?.[0]?.id_avance_detalle || null
  } catch (error) {
    toast.notify({ message: 'Error al obtener datos del avance programático', color: 'danger' })
    console.error(error)
  }
})

const volver = () => {
  router.back()
}

const marcarRevisado = async () => {
  if (!idAvanceDetalle.value) return alert('No se encontró el ID del detalle de avance.')

  try {
    await api.actualizarAvance(idAvanceDetalle.value, {
      estado: 'En Revisión',
      detalles: [],
    })
    toast.notify({ message: 'Documento marcado como REVISADO.', color: 'success' })
  } catch (error) {
    console.error('Error marcarRevisado:', error)
    toast.notify({ message: 'Error al marcar como revisado.', color: 'danger' })
  }
}

const marcarCorrecto = async () => {
  if (!idAvanceDetalle.value) return alert('No se encontró el ID del detalle de avance.')

  try {
    await api.actualizarAvance(idAvanceDetalle.value, {
      estado: 'Aprobado',
      detalles: [],
    })
    toast.notify({ message: 'Documento marcado como CORRECTO.', color: 'success' })
  } catch (error) {
    console.error('Error marcarCorrecto:', error)
    toast.notify({ message: 'Error al marcar como correcto.', color: 'danger' })
  }
}

const toggleObservaciones = () => {
  mostrarObservaciones.value = !mostrarObservaciones.value
}

const enviarCorrecciones = async () => {
  if (!idAvanceDetalle.value) return alert('No se encontró el ID del detalle de avance.')
  if (observaciones.value.trim() === '') {
    alert('Por favor, escribe las observaciones antes de enviar.')
    return
  }

  try {
    await api.actualizarAvance(idAvanceDetalle.value, {
      estado: 'Rechazado',
      detalles: [
        {
          id_avance_detalle: idAvanceDetalle.value,
          observaciones: observaciones.value,
        },
      ],
    })
    toast.notify({ message: 'Observaciones enviadas para corrección.', color: 'success' })
    observaciones.value = ''
    mostrarObservaciones.value = false
  } catch (error) {
    console.error('Error enviarCorrecciones:', error)
    toast.notify({ message: 'Error al enviar las observaciones.', color: 'danger' })
  }
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
