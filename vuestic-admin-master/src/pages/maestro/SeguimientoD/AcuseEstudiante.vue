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
          <p class="periodo-escolar">PERIODO ESCOLAR: ENERO-JUNIO 2025</p>
        </div>

        <va-card class="mt-4" color="secondary">
          <va-card-content class="format-info">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>Nombre del formato: Acuse del estudiante.</div>
              <div>
                Sistema Integral de Gestión: ISO 9001:2015 8.1, 8.5.1, 8.5.2, 8.5.6, 8.6 14001:2015 ISO 45001:2018
              </div>
              <div class="text-right">Página 1 de 1</div>
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

    <!-- Selector de grupo -->
    <va-card class="mt-4">
      <va-card-content>
        <va-select
          v-model="grupoSeleccionado"
          label="Seleccionar Grupo"
          :options="gruposDisponibles"
          @update:modelValue="cargarEstudiantesPorGrupo"
        />
      </va-card-content>
    </va-card>

    <!-- Tabla de asignaturas y estudiantes -->
    <va-card class="mt-4">
      <va-card-content>
        <div class="overflow-x-auto">
          <table class="w-full asignaturas-table">
            <thead>
              <tr>
                <th>CLAVE</th>
                <th>GRUPO</th>
                <th>MATERIA</th>
                <th>NOMBRE DEL ESTUDIANTE</th>
                <th>NÚMERO DE CONTROL</th>
                <th>FIRMA</th>
                <th>FECHA</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(estudiante, index) in estudiantes" :key="index">
                <td>
                  <va-input v-model="estudiante.clave" readonly />
                </td>
                <td>
                  <va-input v-model="estudiante.grupo" readonly />
                </td>
                <td>
                  <va-input v-model="estudiante.materia" readonly />
                </td>
                <td>
                  <va-input v-model="estudiante.nombre" readonly />
                </td>
                <td>
                  <va-input v-model="estudiante.numeroControl" readonly />
                </td>
                <td>
                  <div class="firma-placeholder">
                    <!-- Espacio para firma física -->
                  </div>
                </td>
                <td>
                  <va-date-input 
                    v-model="estudiante.fecha" 
                    placeholder="Seleccione fecha" 
                    :max-date="new Date()" 
                  />
                </td>
              </tr>
            </tbody>
          </table>
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
  </div>
</template>

<script setup>
import { ref } from 'vue'
import html2pdf from 'html2pdf.js'

// Datos de ejemplo para estudiantes por grupo
const estudiantesPorGrupo = {
  'I3A': [
    { clave: 'SCC-1005', grupo: 'I3A', materia: 'CULTURA EMPRESARIAL', nombre: 'JUAN PÉREZ LÓPEZ', numeroControl: '20230001' },
    { clave: 'SCC-1005', grupo: 'I3A', materia: 'CULTURA EMPRESARIAL', nombre: 'MARÍA GARCÍA HERNÁNDEZ', numeroControl: '20230002' },
    { clave: 'SCC-1005', grupo: 'I3A', materia: 'CULTURA EMPRESARIAL', nombre: 'CARLOS SÁNCHEZ MARTÍNEZ', numeroControl: '20230003' }
  ],
  'I3B': [
    { clave: 'SCC-1005', grupo: 'I3B', materia: 'CULTURA EMPRESARIAL', nombre: 'ANA RODRÍGUEZ GÓMEZ', numeroControl: '20230004' },
    { clave: 'SCC-1005', grupo: 'I3B', materia: 'CULTURA EMPRESARIAL', nombre: 'LUIS HERNÁNDEZ DÍAZ', numeroControl: '20230005' },
    { clave: 'SCC-1005', grupo: 'I3B', materia: 'CULTURA EMPRESARIAL', nombre: 'SOFÍA RAMÍREZ CASTRO', numeroControl: '20230006' }
  ],
  'K9U': [
    { clave: 'TID-1010', grupo: 'K9U', materia: 'DESARROLLO DE EMPRENDEDORES', nombre: 'PEDRO GONZÁLEZ VARGAS', numeroControl: '20230007' },
    { clave: 'TID-1010', grupo: 'K9U', materia: 'DESARROLLO DE EMPRENDEDORES', nombre: 'LAURA MORALES SANTIAGO', numeroControl: '20230008' },
    { clave: 'TID-1010', grupo: 'K9U', materia: 'DESARROLLO DE EMPRENDEDORES', nombre: 'JORGE TORRES FLORES', numeroControl: '20230009' }
  ]
}

// Datos del formulario
const profesorSeleccionado = ref('')
const profesores = ref(['ZARINA MARYELA BASULTO ÁLVAREZ', 'CARLOS EDUARDO AZUETA LEÓN', 'MARÍA GONZÁLEZ LÓPEZ'])
const grupoSeleccionado = ref('')
const gruposDisponibles = ref(['I3A', 'I3B', 'K9U'])
const estudiantes = ref([])

const documentosRecibidos = ref({
  programaEstudios: false,
  planeacionCurso: false,
})

// Cargar estudiantes según el grupo seleccionado
const cargarEstudiantesPorGrupo = () => {
  if (grupoSeleccionado.value && estudiantesPorGrupo[grupoSeleccionado.value]) {
    estudiantes.value = estudiantesPorGrupo[grupoSeleccionado.value].map(est => ({
      ...est,
      fecha: new Date().toISOString().split('T')[0] // Fecha actual por defecto
    }))
  } else {
    estudiantes.value = []
  }
}

// Funciones del formulario
const limpiarFormulario = () => {
  profesorSeleccionado.value = ''
  grupoSeleccionado.value = ''
  documentosRecibidos.value = {
    programaEstudios: false,
    planeacionCurso: false,
  }
  estudiantes.value = []
}

const guardarAcuse = () => {
  console.log('Acuse guardado:', {
    profesor: profesorSeleccionado.value,
    grupo: grupoSeleccionado.value,
    documentos: documentosRecibidos.value,
    estudiantes: estudiantes.value,
  })
}

const generarPDF = () => {
  const element = document.querySelector('.acuse-estudiante')
  const opt = {
    margin: 10,
    filename: `Acuse_Estudiante_${grupoSeleccionado.value || 'grupo'}.pdf`,
    image: { type: 'jpeg', quality: 0.98 },
    html2canvas: { scale: 2 },
    jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
  }

  html2pdf().from(element).set(opt).save()
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
  width: 100%;
}

.asignaturas-table th,
.asignaturas-table td {
  border: 1px solid var(--va-background-border);
  padding: 0.5rem;
  text-align: center;
}

.asignaturas-table th {
  background-color: var(--va-background-element);
  font-weight: 600;
  white-space: nowrap;
}

.firma-placeholder {
  border: 1px dashed var(--va-background-border);
  height: 50px;
  width: 150px;
  margin: 0 auto;
}

@media (max-width: 768px) {
  .format-info .grid-cols-3 {
    grid-template-columns: 1fr;
    gap: 0.5rem;
  }

  .inline-select {
    width: 200px;
  }

  .asignaturas-table {
    font-size: 0.8rem;
  }
}
</style>