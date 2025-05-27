<template>
  <div class="grid gap-6">
    <!-- Card de asignaturas -->
    <va-card>
      <va-card-title>
        <h1 class="va-h1">Gestión de Asignaturas</h1>
        <va-button color="primary" icon="add" @click="mostrarModalAsignatura = true">
          Nueva Asignatura
        </va-button>
      </va-card-title>

      <va-card-content>
        <va-data-table :items="asignaturas" :columns="columnasAsignatura" :loading="cargandoAsignaturas">
          <template #cell(acciones)="{ row }">
            <va-button color="danger" icon="delete" @click="eliminarAsignatura(row.itemKey.claveasignatura)" />
            <va-button color="info" icon="edit" @click="abrirModalEditar(row.itemKey)" />
            <va-button color="success" icon="visibility" @click="verAsignaturaCompleta(row.itemKey.claveasignatura)" />

          </template>
        </va-data-table>

        <!-- Modal -->
        <va-modal v-model="mostrarModalAsignatura" title="Nueva Asignatura" size="medium" hide-default-actions>
          <va-form @submit.prevent="guardarAsignatura">
            <va-input v-model="form.clave_asignatura" label="Clave Asignatura" class="mb-4"
              :rules="[v => !!v || 'Campo requerido']" />

            <va-input v-model="form.nombre" label="Nombre" class="mb-4" :rules="[v => !!v || 'Campo requerido']" />

            <va-input v-model="form.creditos" label="Créditos" type="number" class="mb-4"
              :rules="[v => !!v || 'Campo requerido']" />

            <va-input v-model="form.horas_teoricas" label="Horas Teóricas" type="number" class="mb-4"
              :rules="[v => !!v || 'Campo requerido']" />

            <va-input v-model="form.horas_practicas" label="Horas Prácticas" type="number" class="mb-4"
              :rules="[v => !!v || 'Campo requerido']" />

            <va-select v-model="form.clave_carrera" :options="opcionesCarreras" label="Carrera" class="mb-4" />

            <va-input v-model="form.semestre" label="Semestre" type="number" class="mb-4"
              :rules="[v => !!v || 'Campo requerido']" />

            <va-input v-model="form.posicion" label="Posición" type="number" class="mb-4"
              :rules="[v => !!v || 'Campo requerido']" />

            <div class="flex justify-end gap-2 mt-4">
              <va-button type="button" color="secondary" @click="mostrarModalAsignatura = false">
                Cancelar
              </va-button>
              <va-button type="submit" color="primary">
                Guardar
              </va-button>
            </div>
          </va-form>
        </va-modal>
      </va-card-content>
    </va-card>
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


const form = ref({
  clave_asignatura: '',
  nombre: '',
  creditos: 0,
  horas_teoricas: 0,
  horas_practicas: 0,
  clave_carrera: '',
  semestre: 1,
  posicion: 1
})

// Métodos
const cargarAsignaturas = async () => {
  cargandoAsignaturas.value = true
  try {
    const response = await api.getAsignaturas()
    asignaturas.value = response.data
  } finally {
    cargandoAsignaturas.value = false
  }
}

const cargarCarreras = async () => {
  const response = await api.getCarreras()
  opcionesCarreras.value = response.data.map((carrera: any) => ({
    text: carrera.nombre,
    value: carrera.clavecarrera // <-- este es un string
  }))
}

const guardarAsignatura = async () => {
  try {
    // Asegura que 'clave_carrera' sea string
    const datosProcesados = {
      ...form.value,
      clave_carrera: typeof form.value.clave_carrera === 'object'
        ? form.value.clave_carrera.value
        : form.value.clave_carrera
    }

    if (esEdicion.value && claveAsignaturaEditando.value) {
      await api.actualizarAsignatura(claveAsignaturaEditando.value, datosProcesados)
    } else {
      await api.crearAsignatura(datosProcesados)
    }

    mostrarModalAsignatura.value = false
    await cargarAsignaturas()

    // Reiniciar estado
    esEdicion.value = false
    claveAsignaturaEditando.value = null
  } catch (error: any) {
    console.error('Error al guardar:', error.response?.data?.errors || error)
  }
}


const eliminarAsignatura = async (clave: string) => {
  try {
    await api.eliminarAsignatura(clave)  // Aquí clave es "1", "2", etc.
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
    clave_carrera: asignatura.clavecarrera,
    semestre: asignatura.semestre,
    posicion: asignatura.posicion
  }

  mostrarModalAsignatura.value = true
}


// Inicialización
onMounted(() => {
  cargarAsignaturas()
  cargarCarreras()
})
</script>
