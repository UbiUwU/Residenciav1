<template>
  <div class="grid gap-6">
    <!-- Card para Tipos de Evento -->
    <VaCard>
      <VaCardTitle>
        <h1 class="va-h1">Gestión de Tipos de Evento</h1>
        <VaButton color="primary" icon="add" @click="mostrarModalCrearTipoEvento"> Nuevo Tipo </VaButton>
      </VaCardTitle>

      <VaCardContent>
        <VaDataTable :items="tiposEvento" :columns="columnasTipoEvento" :loading="cargandoTipos">
          <template #cell(actions)="{ row }">
            <VaButton size="small" color="info" icon="edit" class="mr-2" @click="mostrarModalEditarTipoEvento(row)" />
            <VaButton size="small" color="danger" icon="delete" @click="confirmarEliminarTipoEvento(row)" />
          </template>
        </VaDataTable>

        <!-- Modal para Tipo Evento -->
        <VaModal v-model="mostrarModalTipoEvento" :title="modalTituloTipoEvento" size="small" hide-default-actions>
          <VaForm @submit.prevent="guardarTipoEvento">
            <VaInput
              v-model="formTipoEvento.nombre"
              label="Nombre"
              class="mb-4"
              :rules="[(v) => !!v || 'Campo requerido']"
            />

            <VaInput v-model="formTipoEvento.descripcion" label="Descripción" class="mb-4" type="textarea" />

            <div class="flex justify-end gap-2 mt-4">
              <VaButton type="button" color="secondary" @click="mostrarModalTipoEvento = false"> Cancelar </VaButton>
              <VaButton type="submit" color="primary"> Guardar </VaButton>
            </div>
          </VaForm>
        </VaModal>
      </VaCardContent>
    </VaCard>

    <!-- Card para Público Destino -->
    <VaCard>
      <VaCardTitle>
        <h1 class="va-h1">Gestión de Público Destino</h1>
        <VaButton color="primary" icon="add" @click="mostrarModalCrearPublicoDestino"> Nuevo Público </VaButton>
      </VaCardTitle>

      <VaCardContent>
        <VaDataTable :items="publicosDestino" :columns="columnasPublicoDestino" :loading="cargandoPublicos">
          <template #cell(actions)="{ row }">
            <VaButton
              size="small"
              color="info"
              icon="edit"
              class="mr-2"
              @click="mostrarModalEditarPublicoDestino(row)"
            />
            <VaButton size="small" color="danger" icon="delete" @click="confirmarEliminarPublicoDestino(row)" />
          </template>
        </VaDataTable>

        <!-- Modal para Público Destino -->
        <VaModal
          v-model="mostrarModalPublicoDestino"
          :title="modalTituloPublicoDestino"
          size="small"
          hide-default-actions
        >
          <VaForm @submit.prevent="guardarPublicoDestino">
            <VaInput
              v-model="formPublicoDestino.nombre"
              label="Nombre"
              class="mb-4"
              :rules="[(v) => !!v || 'Campo requerido']"
            />

            <div class="flex justify-end gap-2 mt-4">
              <VaButton type="button" color="secondary" @click="mostrarModalPublicoDestino = false">
                Cancelar
              </VaButton>
              <VaButton type="submit" color="primary"> Guardar </VaButton>
            </div>
          </VaForm>
        </VaModal>
      </VaCardContent>
    </VaCard>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useToast } from 'vuestic-ui'
import api from '../services/api.js'
const { init } = useToast()

// Estado para Tipos de Evento
const tiposEvento = ref([])
const cargandoTipos = ref(false)
const mostrarModalTipoEvento = ref(false)
const esEdicionTipoEvento = ref(false)
const tipoEventoActual = ref(null)

// Formulario Tipo Evento
const formTipoEvento = ref({
  nombre: '',
  descripcion: '',
})

// Columnas para Tipos de Evento
const columnasTipoEvento = [
  { key: 'id', label: 'ID', sortable: true },
  { key: 'nombre', label: 'Nombre', sortable: true },
  { key: 'descripcion', label: 'Descripción', sortable: true },
  { key: 'actions', label: 'Acciones' },
]

// Estado para Público Destino
const publicosDestino = ref([])
const cargandoPublicos = ref(false)
const mostrarModalPublicoDestino = ref(false)
const esEdicionPublicoDestino = ref(false)
const publicoDestinoActual = ref(null)

// Formulario Público Destino
const formPublicoDestino = ref({
  nombre: '',
})

// Columnas para Público Destino
const columnasPublicoDestino = [
  { key: 'id', label: 'ID', sortable: true },
  { key: 'nombre', label: 'Nombre', sortable: true },
  { key: 'actions', label: 'Acciones' },
]

// Títulos modales computados
const modalTituloTipoEvento = computed(() =>
  esEdicionTipoEvento.value ? 'Editar Tipo de Evento' : 'Nuevo Tipo de Evento',
)

const modalTituloPublicoDestino = computed(() =>
  esEdicionPublicoDestino.value ? 'Editar Público Destino' : 'Nuevo Público Destino',
)

// Cargar datos iniciales
onMounted(() => {
  cargarTiposEvento()
  cargarPublicosDestino()
})

// Tipos de Evento
const cargarTiposEvento = async () => {
  try {
    cargandoTipos.value = true
    const { data } = await api.getTiposEvento()
    tiposEvento.value = data.data || [] // Accede a data.data
  } catch (error) {
    console.error('Error al cargar tipos de evento:', error)
    init({
      message: error.response?.data?.message || 'Error al cargar los tipos de evento',
      color: 'danger',
    })
  } finally {
    cargandoTipos.value = false
  }
}

const mostrarModalCrearTipoEvento = () => {
  esEdicionTipoEvento.value = false
  formTipoEvento.value = {
    nombre: '',
    descripcion: '',
  }
  mostrarModalTipoEvento.value = true
}

const mostrarModalEditarTipoEvento = (row) => {
  try {
    const tipoEvento = row.rowData
    esEdicionTipoEvento.value = true
    tipoEventoActual.value = tipoEvento.id

    formTipoEvento.value = {
      nombre: tipoEvento.nombre || '',
      descripcion: tipoEvento.descripcion || '',
    }
    mostrarModalTipoEvento.value = true
  } catch (error) {
    console.error('Error al preparar edición:', error)
    init({
      message: 'Error al preparar el tipo de evento para edición',
      color: 'danger',
    })
  }
}

const guardarTipoEvento = async () => {
  try {
    if (!formTipoEvento.value.nombre) {
      throw new Error('El nombre es requerido')
    }

    const payload = {
      nombre: formTipoEvento.value.nombre,
      descripcion: formTipoEvento.value.descripcion,
    }

    if (esEdicionTipoEvento.value) {
      await api.actualizarTipoEvento(tipoEventoActual.value, payload)
      init({ message: 'Tipo de evento actualizado con éxito', color: 'success' })
    } else {
      await api.crearTipoEvento(payload)
      init({ message: 'Tipo de evento creado con éxito', color: 'success' })
    }

    mostrarModalTipoEvento.value = false
    await cargarTiposEvento()
  } catch (error) {
    console.error('Error al guardar tipo de evento:', error)
    init({
      message: error.message || 'Error al guardar el tipo de evento',
      color: 'danger',
    })
  }
}

const confirmarEliminarTipoEvento = async (row) => {
  try {
    const tipoEvento = row.rowData
    if (!tipoEvento?.id) {
      throw new Error('ID de tipo de evento no válido')
    }

    // Aquí podrías agregar un diálogo de confirmación
    await api.eliminarTipoEvento(tipoEvento.id)
    init({ message: 'Tipo de evento eliminado con éxito', color: 'success' })
    await cargarTiposEvento()
  } catch (error) {
    console.error('Error al eliminar tipo de evento:', error)
    init({
      message: error.response?.data?.message || 'Error al eliminar el tipo de evento',
      color: 'danger',
    })
  }
}

// Público Destino
const cargarPublicosDestino = async () => {
  try {
    cargandoPublicos.value = true
    const { data } = await api.getPublicosDestino()
    publicosDestino.value = data.data || [] // Accede a data.data
  } catch (error) {
    console.error('Error al cargar públicos destino:', error)
    init({
      message: error.response?.data?.message || 'Error al cargar los públicos destino',
      color: 'danger',
    })
  } finally {
    cargandoPublicos.value = false
  }
}

const mostrarModalCrearPublicoDestino = () => {
  esEdicionPublicoDestino.value = false
  formPublicoDestino.value = {
    nombre: '',
  }
  mostrarModalPublicoDestino.value = true
}

const mostrarModalEditarPublicoDestino = (row) => {
  try {
    const publicoDestino = row.rowData
    esEdicionPublicoDestino.value = true
    publicoDestinoActual.value = publicoDestino.id

    formPublicoDestino.value = {
      nombre: publicoDestino.nombre || '',
    }
    mostrarModalPublicoDestino.value = true
  } catch (error) {
    console.error('Error al preparar edición:', error)
    init({
      message: 'Error al preparar el público destino para edición',
      color: 'danger',
    })
  }
}

const guardarPublicoDestino = async () => {
  try {
    if (!formPublicoDestino.value.nombre) {
      throw new Error('El nombre es requerido')
    }

    const payload = {
      nombre: formPublicoDestino.value.nombre,
    }

    if (esEdicionPublicoDestino.value) {
      await api.actualizarPublicoDestino(publicoDestinoActual.value, payload)
      init({ message: 'Público destino actualizado con éxito', color: 'success' })
    } else {
      await api.crearPublicoDestino(payload)
      init({ message: 'Público destino creado con éxito', color: 'success' })
    }

    mostrarModalPublicoDestino.value = false
    await cargarPublicosDestino()
  } catch (error) {
    console.error('Error al guardar público destino:', error)
    init({
      message: error.message || 'Error al guardar el público destino',
      color: 'danger',
    })
  }
}

const confirmarEliminarPublicoDestino = async (row) => {
  try {
    const publicoDestino = row.rowData
    if (!publicoDestino?.id) {
      throw new Error('ID de público destino no válido')
    }

    // Aquí podrías agregar un diálogo de confirmación
    await api.eliminarPublicoDestino(publicoDestino.id)
    init({ message: 'Público destino eliminado con éxito', color: 'success' })
    await cargarPublicosDestino()
  } catch (error) {
    console.error('Error al eliminar público destino:', error)
    init({
      message: error.response?.data?.message || 'Error al eliminar el público destino',
      color: 'danger',
    })
  }
}
</script>

<style scoped>
/* Estilos personalizados si son necesarios */
.grid {
  display: grid;
}

.gap-6 {
  gap: 1.5rem;
}
</style>
