<template>
  <div class="pdf-viewer">
    <!-- Título de la vista -->
    <h1 class="va-h4 mb-4 text-center">Instrumentación Didáctica</h1>

    <!-- PDF Viewer -->
    <va-card>
      <va-card-title class="flex items-center">
        <va-icon name="picture_as_pdf" class="mr-2" />
        <span>Visualizador de documento PDF</span>
      </va-card-title>
      <va-card-content>
        <iframe
          src="/public/Portada.pdf"
          width="100%"
          height="600px"
          style="border: none"
        ></iframe>
      </va-card-content>
    </va-card>

    <!-- Botones de acción -->
    <va-card class="mt-4">
      <va-card-content>
        <div class="flex justify-center gap-4">
          <va-button color="secondary" @click="volver">
            <va-icon name="arrow_back" class="mr-2" />
            Volver
          </va-button>
          <va-button color="info" @click="marcarRevisado">
            <va-icon name="check_circle" class="mr-2" />
            Revisado
          </va-button>
          <va-button color="success" @click="marcarCorrecto">
            <va-icon name="check" class="mr-2" />
            Correcto
          </va-button>
          <va-button color="warning" @click="toggleObservaciones">
            <va-icon name="edit_note" class="mr-2" />
            Enviar para corrección
          </va-button>
        </div>
      </va-card-content>
    </va-card>

    <!-- Observaciones -->
    <va-card v-if="mostrarObservaciones" class="mt-4">
      <va-card-title class="flex items-center">
        <va-icon name="note" class="mr-2" />
        <span>Observaciones</span>
      </va-card-title>
      <va-card-content>
        <va-input
          v-model="observaciones"
          type="textarea"
          label="Observaciones"
          placeholder="Escribe las observaciones aquí..."
          autosize
          class="mb-4"
        />
        <div class="flex justify-end">
          <va-button color="warning" @click="enviarCorrecciones">
            <va-icon name="send" class="mr-2" />
            Confirmar envío
          </va-button>
        </div>
      </va-card-content>
    </va-card>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const mostrarObservaciones = ref(false)
const observaciones = ref('')

const volver = () => {
  router.back()
}

const marcarRevisado = () => {
  alert('El documento ha sido marcado como REVISADO.')
}

const marcarCorrecto = () => {
  alert('El documento ha sido marcado como CORRECTO.')
}

const toggleObservaciones = () => {
  mostrarObservaciones.value = !mostrarObservaciones.value
}

const enviarCorrecciones = () => {
  if (observaciones.value.trim() === '') {
    alert('Por favor, escribe las observaciones antes de enviar.')
    return
  }
  alert(`Las observaciones se han enviado:\n\n${observaciones.value}`)
  observaciones.value = ''
  mostrarObservaciones.value = false
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
