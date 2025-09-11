<template>
  <div class="grid gap-6">
    <!-- Card de asignaturas -->
    <VaCard>
      <VaCardTitle>
        <h1 class="va-h1">Gestión de Asignaturas</h1>
        <VaButton color="primary" icon="add" @click="mostrarModalAsignatura = true"> Nueva Asignatura </VaButton>
      </VaCardTitle>

      <VaCardContent>
        <VaDataTable :items="asignaturas" :columns="columnasAsignatura" :loading="cargandoAsignaturas">
          <template #cell(acciones)="{ row }">
            <VaButton color="danger" icon="delete" @click="eliminarAsignatura(row.itemKey.claveasignatura)" />
            <VaButton color="info" icon="edit" @click="abrirModalEditar(row.itemKey)" />
            <VaButton color="success" icon="visibility" @click="verAsignaturaCompleta(row.itemKey.claveasignatura)" />
          </template>
        </VaDataTable>

        <!-- Modal -->
        <VaModal v-model="mostrarModalAsignatura" title="Nueva Asignatura" size="medium" hide-default-actions>
          <VaForm @submit.prevent="guardarAsignatura">
            <VaInput
              v-model="form.clave_asignatura"
              label="Clave Asignatura"
              class="mb-4"
              :rules="[(v) => !!v || 'Campo requerido']"
            />

            <VaInput v-model="form.nombre" label="Nombre" class="mb-4" :rules="[(v) => !!v || 'Campo requerido']" />

            <VaInput
              v-model="form.creditos"
              label="Créditos"
              type="number"
              class="mb-4"
              :rules="[(v) => !!v || 'Campo requerido']"
            />

            <VaInput
              v-model="form.horas_teoricas"
              label="Horas Teóricas"
              type="number"
              class="mb-4"
              :rules="[(v) => !!v || 'Campo requerido']"
            />

            <VaInput
              v-model="form.horas_practicas"
              label="Horas Prácticas"
              type="number"
              class="mb-4"
              :rules="[(v) => !!v || 'Campo requerido']"
            />

            <VaSelect v-model="form.carreras" :options="opcionesCarreras" label="Carreras" class="mb-4" multiple />

            <VaInput
              v-model="form.semestre"
              label="Semestre"
              type="number"
              class="mb-4"
              :rules="[(v) => !!v || 'Campo requerido']"
            />

            <VaInput
              v-model="form.posicion"
              label="Posición"
              type="number"
              class="mb-4"
              :rules="[(v) => !!v || 'Campo requerido']"
            />

            <div class="flex justify-end gap-2 mt-4">
              <VaButton type="button" color="secondary" @click="mostrarModalAsignatura = false"> Cancelar </VaButton>
              <VaButton type="submit" color="primary"> Guardar </VaButton>
            </div>
          </VaForm>
        </VaModal>
      </VaCardContent>
    </VaCard>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import api from '../services/api'
import { useRouter } from 'vue-router'

const router = useRouter()

const verAsignaturaCompleta = (clave: string) => {
  router.push(`/asignaturas/complete/${clave}`)
}
// Estado
const asignaturas = ref<any[]>([])
const opcionesCarreras = ref<any[]>([])
const columnasAsignatura = [
  { key: 'claveasignatura', label: 'Clave' },
  { key: 'nombreasignatura', label: 'Nombre' },
  { key: 'creditos', label: 'Créditos' },
  { key: 'satca_teoricas', label: 'Teóricas' },
  { key: 'satca_practicas', label: 'Prácticas' },
  { key: 'satca_total', label: 'SATCA Total' },
  { key: 'clavecarrera', label: 'Carrera' },
  { key: 'semestre', label: 'Semestre' },
  { key: 'posicion', label: 'Posición' },
  { key: 'acciones', label: 'Acciones' },
]

const cargandoAsignaturas = ref(false)
const mostrarModalAsignatura = ref(false)
const esEdicion = ref(false)
const claveAsignaturaEditando = ref<string | null>(null)

interface FormularioAsignatura {
  clave_asignatura: string
  nombre: string
  creditos: number
  horas_teoricas: number
  horas_practicas: number
  carreras: Array<string | { text: string; value: string }> // puede venir como objeto o string
  semestre: number
  posicion: number
}

const form = ref<FormularioAsignatura>({
  clave_asignatura: '',
  nombre: '',
  creditos: 0,
  horas_teoricas: 0,
  horas_practicas: 0,
  carreras: [],
  semestre: 1,
  posicion: 1,
})

// Métodos
const cargarAsignaturas = async () => {
  cargandoAsignaturas.value = true
  try {
    const response = await api.getAsignaturas()
    asignaturas.value = response.data.map((a: any) => ({
      claveasignatura: a.ClaveAsignatura,
      nombreasignatura: a.NombreAsignatura,
      creditos: a.Creditos,
      satca_teoricas: a.Satca_Teoricas,
      satca_practicas: a.Satca_Practicas,
      satca_total: a.Satca_Total,
      // Tomamos la primera carrera si existe
      clavecarrera: a.carreras[0]?.clavecarrera || '',
      semestre: a.carreras[0]?.pivot?.Semestre || 0,
      posicion: a.carreras[0]?.pivot?.Posicion || 0,
      raw: a, // guardamos el objeto completo por si lo necesitas
    }))
  } finally {
    cargandoAsignaturas.value = false
  }
}

const cargarCarreras = async () => {
  const response = await api.getCarreras()
  opcionesCarreras.value = response.data.map((carrera: any) => ({
    text: carrera.nombre,
    value: carrera.clavecarrera, // <-- este es un string
  }))
}

const guardarAsignatura = async () => {
  try {
    const carrerasProcesadas = form.value.carreras.map((c: any) => (typeof c === 'object' ? c.value : c))

    const datosProcesados = {
      ClaveAsignatura: form.value.clave_asignatura,
      NombreAsignatura: form.value.nombre,
      Creditos: form.value.creditos,
      Satca_Teoricas: form.value.horas_teoricas,
      Satca_Practicas: form.value.horas_practicas,
      Satca_Total: form.value.horas_teoricas + form.value.horas_practicas,
      carreras: carrerasProcesadas.map((clave: string) => ({
        clavecarrera: clave,
        Semestre: form.value.semestre,
        Posicion: form.value.posicion,
      })),
    }

    if (esEdicion.value && claveAsignaturaEditando.value) {
      await api.actualizarAsignatura(claveAsignaturaEditando.value, datosProcesados)
    } else {
      await api.crearAsignatura(datosProcesados)
    }

    mostrarModalAsignatura.value = false
    await cargarAsignaturas()
    esEdicion.value = false
    claveAsignaturaEditando.value = null
  } catch (error: any) {
    console.error('Error al guardar:', error.response?.data?.errors || error)
  }
}

const eliminarAsignatura = async (clave: string) => {
  try {
    await api.eliminarAsignatura(clave) // Aquí clave es "1", "2", etc.
    await cargarAsignaturas()
  } catch (error) {
    console.error('Error al eliminar:', error)
  }
}
const abrirModalEditar = (asignatura: any) => {
  esEdicion.value = true
  claveAsignaturaEditando.value = asignatura.claveasignatura

  form.value = {
    clave_asignatura: asignatura.claveasignatura,
    nombre: asignatura.nombreasignatura,
    creditos: asignatura.creditos,
    horas_teoricas: asignatura.satca_teoricas,
    horas_practicas: asignatura.satca_practicas,
    carreras: asignatura.raw.carreras.map((c: any) => c.clavecarrera),
    semestre: asignatura.semestre,
    posicion: asignatura.posicion,
  }

  mostrarModalAsignatura.value = true
}

// Inicialización
onMounted(() => {
  cargarAsignaturas()
  cargarCarreras()
})
</script>
