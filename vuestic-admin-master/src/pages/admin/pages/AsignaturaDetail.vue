<template>
  <div class="pdf-viewer">
    <h1 class="va-h4 mb-4 text-center">Instrumentación Didáctica</h1>

    <VaCard>
      <VaCardTitle class="flex items-center">
        <VaIcon name="picture_as_pdf" class="mr-2" />
        <span>Visualizador de documento PDF</span>
      </VaCardTitle>
      <VaCardContent>
        <iframe src="/public/Portada.pdf" width="100%" height="600px" style="border: none"></iframe>
      </VaCardContent>
    </VaCard>

    <VaCard class="mt-4">
      <VaCardContent>
        <div class="flex justify-center gap-4">
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
      </VaCardContent>
    </VaCard>

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
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../../services/api'

const router = useRouter()

const mostrarObservaciones = ref(false)
const observaciones = ref('')

// Si ya conoces los detalles de avance puedes obtener el ID aquí:
const idAvanceDetalle = 1 // Cambia por el ID real si lo tienes

const volver = () => {
  router.back()
}

const marcarRevisado = async () => {
  try {
    await api.actualizarAvance(idAvanceDetalle, {
      estado: 'En Revisión',
      detalles: [], // siempre envía detalles aunque sea vacío
    })
    alert('Documento marcado como REVISADO.')
  } catch (error) {
    console.error('Error marcarRevisado:', error.response?.data || error.message)
    alert('Error al marcar como revisado. Revisa la consola.')
  }
}

const marcarCorrecto = async () => {
  try {
    await api.actualizarAvance(idAvanceDetalle, {
      estado: 'Aprobado',
      detalles: [],
    })
    alert('Documento marcado como CORRECTO.')
  } catch (error) {
    console.error('Error marcarCorrecto:', error.response?.data || error.message)
    alert('Error al marcar como correcto. Revisa la consola.')
  }
}

const toggleObservaciones = () => {
  mostrarObservaciones.value = !mostrarObservaciones.value
}

const enviarCorrecciones = async () => {
  if (observaciones.value.trim() === '') {
    alert('Por favor, escribe las observaciones antes de enviar.')
    return
  }

  try {
    await api.actualizarAvance(idAvanceDetalle, {
      estado: 'Rechazado',
      detalles: [
        {
          id_avance_detalle: idAvanceDetalle,
          observaciones: observaciones.value,
        },
      ],
    })
    alert('Observaciones enviadas para corrección.')
    observaciones.value = ''
    mostrarObservaciones.value = false
  } catch (error) {
    console.error('Error enviarCorrecciones:', error.response?.data || error.message)
    alert('Error al enviar las observaciones. Revisa la consola.')
  }
}
</script>

<style scoped>
.pdf-viewer {
  max-width: 900px;
  margin: auto;
  padding: 20px;
}
iframe {
  display: block;
  margin: 0 auto;
}
</style>
