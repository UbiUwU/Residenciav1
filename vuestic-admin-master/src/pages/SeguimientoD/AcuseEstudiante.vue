<template>
  <!-- Título de la vista -->
  <h1 class="va-h4 mb-4">Acuse del estudiante</h1>
  <div class="acuse-estudiante">
    <!-- Encabezado institucional -->
    <va-card>
      <div class="institutional-header">
        <div class="text-center">
          <h1 class="va-h4">INSTITUTO TECNOLÓGICO DE CHETUMAL</h1>
          <h2 class="va-h6">SUBDIRECCIÓN ACADÉMICA</h2>
          <h3 class="va-subtitle">DEPARTAMENTO DE: SISTEMAS Y COMPUTACIÓN</h3>
          <p class="periodo-escolar">PERIODO ESCOLAR: AGOSTO-DICIEMBRE 2023</p>
        </div>

        <va-card class="mt-4" color="secondary">
          <va-card-content class="format-info">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>Nombre del formato: Acuse del estudiante.</div>
              <div>
                Sistema Integral de Gestión: ISO 9001:2015 8.1, 8.5.1, 8.5.2, 8.5.6, 8.6 14001:2015 ISO 45001:2018
              </div>
              <div class="text-right">Página 1 de 23</div>
            </div>
          </va-card-content>
        </va-card>
      </div>
    </va-card>

    <!-- Contenido principal -->
    <va-card class="mt-4">
      <va-card-content>
        <p class="declaration-text">
          Recibimos del Profesor(a):
          <va-select
            v-model="profesorSeleccionado"
            :options="profesores"
            placeholder="Seleccione profesor"
            class="inline-select"
          />
          al inicio del presente semestre y de forma presencial:
        </p>

        <ul class="document-list">
          <li>
            <va-checkbox v-model="documentosRecibidos.programaEstudios" label="Programa de estudios" />
          </li>
          <li>
            <va-checkbox
              v-model="documentosRecibidos.planeacionCurso"
              label="Planeación del Curso y Avance Programático de la asignatura que imparte en nuestro grupo"
            />
          </li>
        </ul>

        <p class="declaration-text mt-4">
          Asimismo, nos señaló los criterios de evaluación de acuerdo con el Lineamiento para el Proceso de Evaluación y
          Acreditación de Asignaturas.
        </p>
      </va-card-content>
    </va-card>

    <!-- Tabla de asignaturas -->
    <va-card class="mt-4">
      <va-card-content>
        <div class="overflow-x-auto">
          <table class="w-full asignaturas-table">
            <thead>
              <tr>
                <th>CLAVE</th>
                <th>GRUPO</th>
                <th>MATERIA</th>
                <th>NOMBRE Y FIRMA DEL REPRESENTANTE</th>
                <th>FECHA</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(asignatura, index) in asignaturas" :key="index">
                <td>
                  <va-input v-model="asignatura.clave" placeholder="Ej. SCC-1005" />
                </td>
                <td>
                  <va-input v-model="asignatura.grupo" placeholder="Ej. I3A" />
                </td>
                <td>
                  <va-input v-model="asignatura.materia" placeholder="Ej. CULTURA EMPRESARIAL" />
                </td>
                <td>
                  <va-input v-model="asignatura.representante" placeholder="Nombre del representante" />
                  <div class="firma-placeholder mt-2" @click="abrirModalFirma(index)">
                    {{ asignatura.firma ? 'Firma registrada' : 'Haga clic para firmar' }}
                  </div>
                </td>
                <td>
                  <va-date-input v-model="asignatura.fecha" placeholder="Seleccione fecha" :max-date="new Date()" />
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="flex justify-end mt-4">
          <va-button preset="plain" size="small" @click="agregarAsignatura">
            <va-icon name="add" class="mr-2" />
            Agregar otra asignatura
          </va-button>
        </div>
      </va-card-content>
    </va-card>

    <!-- Botones de acción -->
    <div class="flex justify-between mt-6">
      <va-button preset="secondary" @click="generarPDF">
        <va-icon name="picture_as_pdf" class="mr-2" />
        Generar PDF
      </va-button>
      <div class="flex gap-4">
        <va-button preset="secondary" @click="limpiarFormulario">
          <va-icon name="delete" class="mr-2" />
          Limpiar
        </va-button>
        <va-button @click="guardarAcuse">
          <va-icon name="save" class="mr-2" />
          Guardar Acuse
        </va-button>
      </div>
    </div>

    <!-- Modal para firma digital -->
    <va-modal v-model="showModalFirma" hide-default-actions>
      <template #header>
        <h3 class="va-h5">Registrar firma digital</h3>
      </template>

      <div class="firma-modal-content">
        <div class="firma-container">
          <canvas
            ref="firmaCanvas"
            @mousedown="iniciarFirma"
            @mousemove="dibujarFirma"
            @mouseup="terminarFirma"
            @mouseleave="terminarFirma"
            @touchstart="iniciarFirma"
            @touchmove="dibujarFirma"
            @touchend="terminarFirma"
          ></canvas>
        </div>

        <div class="flex justify-between mt-4">
          <va-button preset="plain" @click="limpiarFirma"> Limpiar Firma </va-button>
          <va-button @click="guardarFirma"> Guardar Firma </va-button>
        </div>
      </div>
    </va-modal>
  </div>
</template>

<script setup>
import { ref, nextTick } from 'vue'

// Datos del formulario
const profesorSeleccionado = ref('')
const profesores = ref(['ZARINA MARYELA BASULTO ÁLVAREZ', 'JUAN PÉREZ MARTÍNEZ', 'MARÍA GONZÁLEZ LÓPEZ'])

const documentosRecibidos = ref({
  programaEstudios: false,
  planeacionCurso: false,
})

const asignaturas = ref([
  {
    clave: 'SCC-1005',
    grupo: 'I3A',
    materia: 'CULTURA EMPRESARIAL',
    representante: '',
    firma: null,
    fecha: '01/09/2023',
  },
  {
    clave: 'SCC-1005',
    grupo: 'I3B',
    materia: 'CULTURA EMPRESARIAL',
    representante: '',
    firma: null,
    fecha: '30/08/2023',
  },
  {
    clave: 'TID-1010',
    grupo: 'K9U',
    materia: 'DESARROLLO DE EMPRENDEDORES',
    representante: '',
    firma: null,
    fecha: '30/08/2023',
  },
])

// Lógica para firma digital
const showModalFirma = ref(false)
const firmaCanvas = ref(null)
const isFirmando = ref(false)
const asignaturaAFirmar = ref(null)
const ctx = ref(null)

const abrirModalFirma = (index) => {
  asignaturaAFirmar.value = index
  showModalFirma.value = true

  nextTick(() => {
    const canvas = firmaCanvas.value
    canvas.width = canvas.offsetWidth
    canvas.height = canvas.offsetHeight
    ctx.value = canvas.getContext('2d')
    ctx.value.lineWidth = 2
    ctx.value.lineCap = 'round'
    ctx.value.strokeStyle = '#000'
  })
}

const iniciarFirma = (e) => {
  isFirmando.value = true
  const canvas = firmaCanvas.value
  const rect = canvas.getBoundingClientRect()
  const x = (e.clientX || e.touches[0].clientX) - rect.left
  const y = (e.clientY || e.touches[0].clientY) - rect.top

  ctx.value.beginPath()
  ctx.value.moveTo(x, y)
}

const dibujarFirma = (e) => {
  if (!isFirmando.value) return

  const canvas = firmaCanvas.value
  const rect = canvas.getBoundingClientRect()
  const x = (e.clientX || e.touches[0].clientX) - rect.left
  const y = (e.clientY || e.touches[0].clientY) - rect.top

  ctx.value.lineTo(x, y)
  ctx.value.stroke()
}

const terminarFirma = () => {
  isFirmando.value = false
}

const limpiarFirma = () => {
  const canvas = firmaCanvas.value
  ctx.value.clearRect(0, 0, canvas.width, canvas.height)
}

const guardarFirma = () => {
  const canvas = firmaCanvas.value
  asignaturas.value[asignaturaAFirmar.value].firma = canvas.toDataURL()
  showModalFirma.value = false
}

// Funciones del formulario
const agregarAsignatura = () => {
  asignaturas.value.push({
    clave: '',
    grupo: '',
    materia: '',
    representante: '',
    firma: null,
    fecha: '',
  })
}

const limpiarFormulario = () => {
  profesorSeleccionado.value = ''
  documentosRecibidos.value = {
    programaEstudios: false,
    planeacionCurso: false,
  }
  asignaturas.value = asignaturas.value.map((a) => ({
    ...a,
    representante: '',
    firma: null,
  }))
}

const guardarAcuse = () => {
  console.log('Acuse guardado:', {
    profesor: profesorSeleccionado.value,
    documentos: documentosRecibidos.value,
    asignaturas: asignaturas.value,
  })

  // Aquí iría la lógica para guardar en base de datos
}

const generarPDF = () => {
  console.log('Generando PDF...')
  // Aquí iría la lógica para generar el PDF
}
</script>

<style scoped>
.acuse-estudiante {
  padding: 1rem;
  max-width: 1200px;
  margin: 0 auto;
}

.institutional-header {
  text-align: center;
}

.periodo-escolar {
  font-weight: bold;
  margin-top: 0.5rem;
}

.format-info {
  font-size: 0.8rem;
}

.declaration-text {
  margin-bottom: 1rem;
  line-height: 1.6;
}

.inline-select {
  display: inline-block;
  width: 300px;
  margin: 0 0.5rem;
}

.document-list {
  margin: 1rem 0 1rem 2rem;
}

.document-list li {
  margin-bottom: 0.5rem;
}

.asignaturas-table {
  border-collapse: collapse;
}

.asignaturas-table th,
.asignaturas-table td {
  border: 1px solid var(--va-background-border);
  padding: 0.75rem;
  text-align: center;
}

.asignaturas-table th {
  background-color: var(--va-background-element);
  font-weight: 600;
}

.firma-placeholder {
  border: 1px dashed var(--va-background-border);
  padding: 0.5rem;
  cursor: pointer;
  border-radius: 4px;
  min-height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.firma-placeholder:hover {
  background-color: var(--va-background-primary);
}

.firma-modal-content {
  padding: 1rem;
}

.firma-container {
  border: 1px solid var(--va-background-border);
  border-radius: 4px;
  overflow: hidden;
}

.firma-container canvas {
  width: 100%;
  height: 200px;
  background-color: white;
  display: block;
}

@media (max-width: 768px) {
  .format-info .grid-cols-3 {
    grid-template-columns: 1fr;
    gap: 0.5rem;
  }

  .inline-select {
    width: 200px;
  }
}
</style>
