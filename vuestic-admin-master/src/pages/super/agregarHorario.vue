<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import apiClient from '../../services/api'

const route = useRoute()
const tarjeta = route.params.tarjeta as string

// Datos del horario
const horario = ref({
  tarjeta,
  claveaula: '',
  clavegrupo: '',
  claveasignatura: '',
  idperiodoescolar: null,
  lunes_hi: '',
  lunes_hf: '',
  martes_hi: '',
  martes_hf: '',
  miercoles_hi: '',
  miercoles_hf: '',
  jueves_hi: '',
  jueves_hf: '',
  viernes_hi: '',
  viernes_hf: '',
  sabado_hi: '',
  sabado_hf: '',
})

// Aulas y grupos
const aulas = ref<any[]>([])
const grupos = ref<any[]>([])
const asignaturas = ref<any[]>([])

// Traer aulas y grupos
onMounted(async () => {
  try {
    const resAulas = await apiClient.getAula()
    aulas.value = resAulas.data

    const resGrupos = await apiClient.getGrupo()
    grupos.value = resGrupos.data

    const resAsignaturas = await apiClient.getAsignaturas()
    asignaturas.value = resAsignaturas.data
  } catch (err) {
    console.error('Error al cargar aulas o grupos:', err)
  }
})

// Opciones para selects
const aulasOptions = computed(() =>
  aulas.value.map((aula) => ({
    label: `${aula.nombre} (${aula.edificio?.nombre || 'Sin Edificio'})`,
    value: aula.claveaula,
  })),
)
const gruposOptions = computed(() =>
  grupos.value.map((grupo) => ({
    label: grupo.nombre,
    value: grupo.clavegrupo,
  })),
)
const asignaturasOptions = computed(() =>
  asignaturas.value.map((asignatura) => ({
    label: asignatura.NombreAsignatura,
    value: asignatura.ClaveAsignatura,
  })),
)

console.log(asignaturasOptions)

// Horas cerradas de 8 a 20
const horas = Array.from({ length: 13 }, (_, i) => {
  const hour = i + 8
  return hour < 10 ? `0${hour}:00` : `${hour}:00`
})

// Enviar horario
const submitHorario = () => {
  if (!horario.value.claveaula || !horario.value.clavegrupo || !horario.value.claveasignatura) {
    alert('Completa todos los campos obligatorios')
    return
  }
  console.log('Horario a guardar:', horario.value)
  // Aquí llamarías a tu API
}
const handleRegresar = () => {
  window.history.back()
}
</script>

<template>
  <h2 class="mb-4">Agregar horario para maestro {{ tarjeta }}</h2>

  <form class="space-y-4" @submit.prevent="submitHorario">
    <!-- Aula y Grupo -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <select v-model="horario.claveaula" class="border rounded p-2">
        <option value="" disabled>Selecciona Aula</option>
        <option v-for="aula in aulasOptions" :key="aula.value" :value="aula.value">
          {{ aula.label }}
        </option>
      </select>

      <select v-model="horario.clavegrupo" class="border rounded p-2">
        <option value="" disabled>Selecciona Grupo</option>
        <option v-for="grupo in gruposOptions" :key="grupo.value" :value="grupo.value">
          {{ grupo.label }}
        </option>
      </select>
    </div>

    <!-- Clave Asignatura -->
    <select v-model="horario.claveasignatura" class="border rounded p-2">
      <option value="" disabled>Selecciona Asignatura</option>
      <option v-for="asignatura in asignaturasOptions" :key="asignatura.value" :value="asignatura.value">
        {{ asignatura.label }}
      </option>
    </select>

    <!-- Horarios por día -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <div>
        <h4 class="font-semibold">Lunes</h4>
        <select v-model="horario.lunes_hi" class="border rounded p-2 w-full mb-2">
          <option value="" disabled>Hora Inicio</option>
          <option v-for="h in horas" :key="h" :value="h">{{ h }}</option>
        </select>
        <select v-model="horario.lunes_hf" class="border rounded p-2 w-full">
          <option value="" disabled>Hora Fin</option>
          <option v-for="h in horas" :key="h" :value="h">{{ h }}</option>
        </select>
      </div>

      <div>
        <h4 class="font-semibold">Martes</h4>
        <select v-model="horario.martes_hi" class="border rounded p-2 w-full mb-2">
          <option value="" disabled>Hora Inicio</option>
          <option v-for="h in horas" :key="h" :value="h">{{ h }}</option>
        </select>
        <select v-model="horario.martes_hf" class="border rounded p-2 w-full">
          <option value="" disabled>Hora Fin</option>
          <option v-for="h in horas" :key="h" :value="h">{{ h }}</option>
        </select>
      </div>

      <div>
        <h4 class="font-semibold">Miércoles</h4>
        <select v-model="horario.miercoles_hi" class="border rounded p-2 w-full mb-2">
          <option value="" disabled>Hora Inicio</option>
          <option v-for="h in horas" :key="h" :value="h">{{ h }}</option>
        </select>
        <select v-model="horario.miercoles_hf" class="border rounded p-2 w-full">
          <option value="" disabled>Hora Fin</option>
          <option v-for="h in horas" :key="h" :value="h">{{ h }}</option>
        </select>
      </div>
    </div>

    <!-- Jueves, Viernes, Sábado -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <div>
        <h4 class="font-semibold">Jueves</h4>
        <select v-model="horario.jueves_hi" class="border rounded p-2 w-full mb-2">
          <option value="" disabled>Hora Inicio</option>
          <option v-for="h in horas" :key="h" :value="h">{{ h }}</option>
        </select>
        <select v-model="horario.jueves_hf" class="border rounded p-2 w-full">
          <option value="" disabled>Hora Fin</option>
          <option v-for="h in horas" :key="h" :value="h">{{ h }}</option>
        </select>
      </div>

      <div>
        <h4 class="font-semibold">Viernes</h4>
        <select v-model="horario.viernes_hi" class="border rounded p-2 w-full mb-2">
          <option value="" disabled>Hora Inicio</option>
          <option v-for="h in horas" :key="h" :value="h">{{ h }}</option>
        </select>
        <select v-model="horario.viernes_hf" class="border rounded p-2 w-full">
          <option value="" disabled>Hora Fin</option>
          <option v-for="h in horas" :key="h" :value="h">{{ h }}</option>
        </select>
      </div>

      <div>
        <h4 class="font-semibold">Sábado</h4>
        <select v-model="horario.sabado_hi" class="border rounded p-2 w-full mb-2">
          <option value="" disabled>Hora Inicio</option>
          <option v-for="h in horas" :key="h" :value="h">{{ h }}</option>
        </select>
        <select v-model="horario.sabado_hf" class="border rounded p-2 w-full">
          <option value="" disabled>Hora Fin</option>
          <option v-for="h in horas" :key="h" :value="h">{{ h }}</option>
        </select>
      </div>
    </div>

    <!-- Por esto: -->
    <div class="flex gap-4 mt-4">
      <button
        type="button"
        class="flex-1 bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-7000"
        @click="handleRegresar"
      >
        ← Regresar
      </button>

      <button type="submit" class="flex-1 bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
        Guardar Horario
      </button>
    </div>
  </form>
</template>

<style scoped>
h2 {
  font-weight: 600;
}
h4 {
  margin-bottom: 0.5rem;
}
</style>
