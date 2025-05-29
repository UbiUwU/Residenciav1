<template>
  <div class="pdf-viewer">
    <h2>Instrumentación Didáctica</h2>

    <iframe
      src="/public/Portada.pdf"
      width="100%"
      height="600px"
      style="border: none"
    ></iframe>

    <div class="opciones">
      <button @click="descargarPDF">Descargar</button>
      <button @click="enviarPorCorreo">Enviar por correo</button>
      <button @click="volver">Volver</button>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()

const descargarPDF = () => {
  window.open('/pdfs/instrumentacion.pdf', '_blank')
}

const enviarPorCorreo = () => {
  alert('PDF enviado al correo registrado (simulación).')
}

const volver = () => {
  router.back()
}
</script>

<style scoped>
.pdf-viewer {
  padding: 20px;
  max-width: 1000px;
  margin: auto;
}

h2 {
  text-align: center;
  margin-bottom: 20px;
}

.opciones {
  margin-top: 20px;
  display: flex;
  justify-content: center;
  gap: 15px;
}

.opciones button {
  padding: 10px 15px;
  border: none;
  background-color: #2ecc71;
  color: white;
  border-radius: 6px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.opciones button:hover {
  background-color: #27ae60;
}
</style>
