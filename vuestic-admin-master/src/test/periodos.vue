<template>
    <va-card>
        <va-card-title>
            <h1 class="va-h1">Gestión de Períodos Escolares</h1>
            <va-button color="primary" icon="add" @click="mostrarModalCrear">
                Nuevo Período
            </va-button>
        </va-card-title>

        <va-card-content>
            <!-- Tabla de períodos -->
            <va-data-table :items="periodos" :columns="columnas" :loading="cargando">
                <template #cell(actions)="{ row }">
                    <va-button size="small" color="info" icon="edit" class="mr-2" @click="mostrarModalEditar(row)" />
                    <va-button size="small" color="danger" icon="delete" @click="confirmarEliminar(row)" />
                </template>
            </va-data-table>

            <!-- Modal para crear/editar -->
            <va-modal v-model="mostrarModal" :title="modalTitulo" size="small" hide-default-actions>
                <va-form @submit.prevent="guardarPeriodo">
                    <va-input v-model="form.codigoperiodo" label="Código del Período" class="mb-4"
                        :rules="[v => !!v || 'Campo requerido']" />

                    <va-date-input v-model="form.fecha_inicio" label="Fecha de Inicio" class="mb-4"
                        :rules="[v => !!v || 'Campo requerido']" />

                    <va-date-input v-model="form.fecha_fin" label="Fecha de Fin" class="mb-4" :rules="[
                        v => !!v || 'Campo requerido',
                        v => v >= form.fecha_inicio || 'Debe ser posterior a fecha inicio'
                    ]" />

                    <div class="flex justify-end gap-2 mt-4">
                        <va-button type="button" color="secondary" @click="mostrarModal = false">
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
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useToast } from 'vuestic-ui'
import api from '../services/api.js'
const { init } = useToast()

// Estado del componente
const periodos = ref([])
const cargando = ref(false)
const mostrarModal = ref(false)
const esEdicion = ref(false)
const periodoActual = ref(null)

// Formulario
const form = ref({
    codigoperiodo: '',
    fecha_inicio: null,
    fecha_fin: null
})

// Columnas de la tabla
const columnas = [
    { key: 'id_periodo_escolar', label: 'ID', sortable: true },
    { key: 'codigoperiodo', label: 'Código', sortable: true },
    { key: 'fecha_inicio', label: 'Inicio', sortable: true },
    { key: 'fecha_fin', label: 'Fin', sortable: true },
    { key: 'actions', label: 'Acciones' }
]

// Cargar datos iniciales
onMounted(() => {
    cargarPeriodos()
})

// Métodos con manejo de errores mejorado
const cargarPeriodos = async () => {
    try {
        cargando.value = true
        const response = await api.getPeriodos()
        periodos.value = response.data || []
    } catch (error) {
        console.error('Error al cargar períodos:', error)
        init({
            message: 'Error al cargar los períodos escolares',
            color: 'danger'
        })
    } finally {
        cargando.value = false
    }
}

const mostrarModalCrear = () => {
    esEdicion.value = false
    form.value = {
        codigoperiodo: '',
        fecha_inicio: null,
        fecha_fin: null
    }
    mostrarModal.value = true
}

const mostrarModalEditar = (row) => {
    try {
        const periodo = row.rowData // toma solo los datos puros de la fila
        esEdicion.value = true
        periodoActual.value = periodo.id_periodo_escolar

        // Manejo seguro de fechas
        const fechaInicio = periodo.fecha_inicio ? new Date(periodo.fecha_inicio) : null
        const fechaFin = periodo.fecha_fin ? new Date(periodo.fecha_fin) : null

        form.value = {
            codigoperiodo: periodo.codigoperiodo || '',
            fecha_inicio: fechaInicio,
            fecha_fin: fechaFin
        }
        mostrarModal.value = true
    } catch (error) {
        console.error('Error al preparar edición:', error)
        init({
            message: 'Error al preparar el período para edición',
            color: 'danger'
        })
    }
}

const guardarPeriodo = async () => {
    try {
        // Validación básica
        if (!form.value.codigoperiodo || !form.value.fecha_inicio || !form.value.fecha_fin) {
            throw new Error('Todos los campos son requeridos')
        }

        if (form.value.fecha_fin < form.value.fecha_inicio) {
            throw new Error('La fecha de fin debe ser posterior a la de inicio')
        }

        const payload = {
            codigoperiodo: form.value.codigoperiodo,
            fecha_inicio: form.value.fecha_inicio.toISOString().split('T')[0],
            fecha_fin: form.value.fecha_fin.toISOString().split('T')[0]
        }

        if (esEdicion.value) {
            await api.actualizarPeriodo(periodoActual.value, payload)
            init({ message: 'Período actualizado con éxito', color: 'success' })
        } else {
            await api.crearPeriodo(payload)
            init({ message: 'Período creado con éxito', color: 'success' })
        }

        mostrarModal.value = false
        await cargarPeriodos()
    } catch (error) {
        console.error('Error al guardar período:', error)
        init({
            message: error.message || 'Error al guardar el período',
            color: 'danger'
        })
    }
}

const confirmarEliminar = async (row) => {
    try {
        const periodo = row.rowData 
        if (!periodo?.id_periodo_escolar) {
            throw new Error('ID de período no válido')
        }

        // Aquí podrías agregar un diálogo de confirmación
        await api.eliminarPeriodo(periodo.id_periodo_escolar)
        init({ message: 'Período eliminado con éxito', color: 'success' })
        await cargarPeriodos()
    } catch (error) {
        console.error('Error al eliminar período:', error)
        init({
            message: error.response?.data?.message || 'Error al eliminar el período',
            color: 'danger'
        })
    }
}
</script>

<style scoped>
/* Estilos personalizados si son necesarios */
</style>