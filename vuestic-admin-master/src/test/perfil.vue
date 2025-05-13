<!-- Perfil.vue -->
<template>
  <div class="p-4">
    <h2 class="text-2xl font-bold mb-4">Perfil del Maestro</h2>

    <div v-if="maestro">
        <p><strong>ID:</strong> {{ maestro.tarjeta}}</p>
      <p><strong>Nombre:</strong> {{ maestro.nombre }} {{ maestro.apellidopaterno }} {{ maestro.apellidomaterno }}</p>
      <p><strong>RFC:</strong> {{ maestro.rfc }}</p>
      <p><strong>Licenciatura:</strong> {{ maestro.escolaridad_licenciatura }} ({{ maestro.estado_licenciatura }})</p>
      <p><strong>Especialización:</strong> {{ maestro.escolaridad_especializacion }} ({{ maestro.estado_especializacion }})</p>
      <p><strong>Maestría:</strong> {{ maestro.escolaridad_maestria }} ({{ maestro.estado_maestria }})</p>
      <p><strong>Doctorado:</strong> {{ maestro.escolaridad_doctorado }} ({{ maestro.estado_doctorado }})</p>
    </div>

    <div v-else>
      <p>No se encontró información del maestro.</p>
    </div>
  </div>
</template>

<script setup>
import { useAuthStore } from '../services/auth'

const auth = useAuthStore()
const maestro = auth.maestro
</script>
