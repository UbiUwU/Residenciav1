<template>
  <VaCard>
    <VaCardTitle>Registro de Asesorías Académicas</VaCardTitle>
    <VaCardContent>
      <!-- Selección de grupo y materia -->
      <div class="row">
        <div class="flex md6">
          <VaSelect
            v-model="grupoSeleccionado"
            label="Seleccione el grupo"
            :options="gruposDisponibles"
            clearable
            class="mb-4"
          />
        </div>
        <div class="flex md6">
          <VaSelect
            v-model="materiaSeleccionada"
            label="Seleccione la materia"
            :options="materiasDisponibles"
            :disabled="!grupoSeleccionado"
            clearable
            class="mb-4"
          />
        </div>
      </div>

      <!-- Botón para cargar el formato -->
      <div class="flex justify-center mb-6">
        <VaButton :disabled="!materiaSeleccionada" @click="cargarFormato"> Cargar Formato </VaButton>
      </div>

      <!-- Formulario de asesorías -->
      <div v-if="mostrarFormato">
        <VaCard>
          <VaCardTitle>Formato de Asesorías</VaCardTitle>
          <VaCardContent>
            <VaInput v-model="docenteNombre" label="Nombre del docente/instructor" class="mb-4" />

            <div class="flex gap-2 mb-4">
              <VaButton @click="agregarAsesoria">
                <VaIcon name="add" class="mr-2" />
                Agregar fila
              </VaButton>
              <VaButton color="danger" @click="eliminarUltima">
                <VaIcon name="delete" class="mr-2" />
                Eliminar última
              </VaButton>
            </div>

            <!-- Tabla de asesorías -->
            <div id="encabezado-pdf" class="text-center mb-4">
              <h3>INSTITUTO TECNOLÓGICO DE CHETUMAL</h3>
              <h4>REGISTRO DE ASESORÍAS ACADÉMICAS</h4>
            </div>

            <div id="tabla-asesorias">
              <VaDataTable :items="asesorias" :columns="columnasTabla" striped hoverable>
                <template #cell(numeroControl)="{ rowIndex }">
                  <VaInput v-model="asesorias[rowIndex].numeroControl" />
                </template>
                <template #cell(nombreEstudiante)="{ rowIndex }">
                  <VaInput v-model="asesorias[rowIndex].nombreEstudiante" />
                </template>
                <template #cell(carrera)="{ rowIndex }">
                  <VaInput v-model="asesorias[rowIndex].carrera" />
                </template>
                <template #cell(tema)="{ rowIndex }">
                  <VaInput v-model="asesorias[rowIndex].tema" />
                </template>
                <template #cell(firma)="{ rowIndex }">
                  <div class="firma-placeholder"></div>
                </template>
              </VaDataTable>
            </div>

            <!-- Botones de acción -->
            <div class="flex justify-between mt-6">
              <VaButton color="danger" @click="limpiarFormulario">
                <VaIcon name="delete" class="mr-2" />
                Limpiar formulario
              </VaButton>
              <div class="flex gap-2">
                <VaButton color="success" @click="guardarAsesorias">
                  <VaIcon name="save" class="mr-2" />
                  Guardar
                </VaButton>
                <VaButton color="primary" @click="generarPDF">
                  <VaIcon name="picture_as_pdf" class="mr-2" />
                  Generar PDF
                </VaButton>
              </div>
            </div>
          </VaCardContent>
        </VaCard>
      </div>
    </VaCardContent>
  </VaCard>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import html2pdf from 'html2pdf.js'

// Datos de selección inicial
const grupoSeleccionado = ref('')
const materiaSeleccionada = ref('')
const mostrarFormato = ref(false)

// Columnas para la tabla de asesorías
const columnasTabla = [
  { key: 'numeroControl', label: 'No. Control', sortable: true },
  { key: 'nombreEstudiante', label: 'Nombre del Estudiante', sortable: true },
  { key: 'carrera', label: 'Carrera', sortable: true },
  { key: 'tema', label: 'Tema/Actividad', sortable: true },
  { key: 'firma', label: 'Firma', className: 'firma-header' },
]

// Datos simulados - en una aplicación real vendrían de una API
const gruposDisponibles = ref(['I3A', 'I3B', 'I5C', 'K9U', 'L2D'])

const materiasDisponibles = computed(() => {
  if (!grupoSeleccionado.value) return []

  // Simulación: diferentes materias por grupo
  const materiasComunes = ['CULTURA EMPRESARIAL', 'TALLER DE BASE DE DATOS', 'TÓPICOS AVANZADOS DE PROGRAMACIÓN']

  if (grupoSeleccionado.value.startsWith('I')) {
    return [...materiasComunes, 'BASE DE DATOS', 'INGENIERÍA DE SOFTWARE']
  } else {
    return [...materiasComunes, 'REDES DE COMPUTADORAS', 'SISTEMAS OPERATIVOS']
  }
})

// Datos del formato
const docenteNombre = ref('')
const asesorias = ref([{ numeroControl: '', nombreEstudiante: '', carrera: '', tema: '' }])

// Computed para la carrera basada en el grupo seleccionado
const carreraGrupo = computed(() => {
  if (!grupoSeleccionado.value) return 'Carrera'

  return grupoSeleccionado.value.startsWith('I')
    ? 'INGENIERÍA EN SISTEMAS COMPUTACIONALES'
    : 'LICENCIATURA EN INFORMÁTICA'
})

// Lista de 13 alumnos precargados
const listaAlumnos = ref([
  { numeroControl: '20230001', nombreEstudiante: 'LÓPEZ HERNÁNDEZ JUAN CARLOS' },
  { numeroControl: '20230002', nombreEstudiante: 'MARTÍNEZ GARCÍA MARÍA FERNANDA' },
  { numeroControl: '20230003', nombreEstudiante: 'RODRÍGUEZ PÉREZ LUIS ALBERTO' },
  { numeroControl: '20230004', nombreEstudiante: 'GÓMEZ SÁNCHEZ ANA KAREN' },
  { numeroControl: '20230005', nombreEstudiante: 'HERNÁNDEZ CRUZ JOSÉ MANUEL' },
  { numeroControl: '20230006', nombreEstudiante: 'DÍAZ FLORES PATRICIA ELIZABETH' },
  { numeroControl: '20230007', nombreEstudiante: 'MORALES VARGAS CARLOS EDUARDO' },
  { numeroControl: '20230008', nombreEstudiante: 'CASTRO ORTIZ LAURA ISABEL' },
  { numeroControl: '20230009', nombreEstudiante: 'ORTIZ RAMÍREZ MIGUEL ÁNGEL' },
  { numeroControl: '20230010', nombreEstudiante: 'SALAZAR GUTIÉRREZ SOFÍA GUADALUPE' },
  { numeroControl: '20230011', nombreEstudiante: 'TORRES MENDOZA JORGE ALBERTO' },
  { numeroControl: '20230012', nombreEstudiante: 'VÁZQUEZ RUIZ ADRIANA LUCERO' },
  { numeroControl: '20230013', nombreEstudiante: 'FLORES CASTILLO DAVID ALEJANDRO' },
])

// Función para precargar alumnos
const precargarAlumnos = () => {
  asesorias.value = listaAlumnos.value.map((alumno) => ({
    numeroControl: alumno.numeroControl,
    nombreEstudiante: alumno.nombreEstudiante,
    carrera: carreraGrupo.value,
    tema: materiaSeleccionada.value,
  }))
}

// Watcher para detectar cuando se selecciona una materia
watch(materiaSeleccionada, (newVal) => {
  if (newVal) {
    // Pequeño retraso para que la UI tenga tiempo de actualizarse
    setTimeout(() => {
      precargarAlumnos()
    }, 100)
  }
})

// Funciones para manejar el formato
const cargarFormato = () => {
  mostrarFormato.value = true
  precargarAlumnos() // Precargar alumnos al cargar el formato
}

const agregarAsesoria = () => {
  asesorias.value.push({
    numeroControl: '',
    nombreEstudiante: '',
    carrera: carreraGrupo.value,
    tema: materiaSeleccionada.value,
  })
}

const eliminarUltima = () => {
  if (asesorias.value.length > 1) {
    asesorias.value.pop()
  }
}

const limpiarFormulario = () => {
  asesorias.value = [{ numeroControl: '', nombreEstudiante: '', carrera: '', tema: '' }]
  docenteNombre.value = ''
}

const guardarAsesorias = () => {
  console.log('Asesorías guardadas:', {
    grupo: grupoSeleccionado.value,
    materia: materiaSeleccionada.value,
    docente: docenteNombre.value,
    asesorias: asesorias.value,
  })
  // Mostrar notificación de éxito
  vaToast.init({
    message: 'Asesorías guardadas correctamente',
    color: 'success',
  })
}

const generarPDF = () => {
  // Elemento que queremos convertir a PDF
  const element = document.createElement('div')
  element.style.width = '100%'

  // Clonamos las partes relevantes del formulario
  const encabezado = document.getElementById('encabezado-pdf').cloneNode(true)
  const tabla = document.getElementById('tabla-asesorias').cloneNode(true)

  // Aplicamos estilos específicos para el PDF
  encabezado.style.textAlign = 'center'
  encabezado.style.marginBottom = '15px'

  // Creamos un contenedor para la información del docente, grupo y materia
  const infoContainer = document.createElement('div')
  infoContainer.style.display = 'flex'
  infoContainer.style.justifyContent = 'space-between'
  infoContainer.style.marginBottom = '15px'
  infoContainer.style.flexWrap = 'wrap'

  // Información del docente
  const docenteInfo = document.createElement('div')
  docenteInfo.innerHTML = `
    <p style="font-weight: bold; margin-bottom: 5px;">Nombre del Docente/Instructor:</p>
    <p>${docenteNombre.value || '__________________________'}</p>
  `

  // Información del grupo
  const grupoInfo = document.createElement('div')
  grupoInfo.innerHTML = `
    <p style="font-weight: bold; margin-bottom: 5px;">Grupo:</p>
    <p>${grupoSeleccionado.value || '________'}</p>
  `

  // Información de la materia
  const materiaInfo = document.createElement('div')
  materiaInfo.innerHTML = `
    <p style="font-weight: bold; margin-bottom: 5px;">Materia:</p>
    <p>${materiaSeleccionada.value || '__________________________'}</p>
  `

  // Añadimos los elementos al contenedor
  infoContainer.appendChild(docenteInfo)
  infoContainer.appendChild(grupoInfo)
  infoContainer.appendChild(materiaInfo)

  // Ajustamos la tabla para el PDF
  const tablaClone = tabla.querySelector('table')
  tablaClone.style.width = '100%'
  tablaClone.style.fontSize = '10px'
  tablaClone.style.borderCollapse = 'collapse'

  // Añadimos estilos a las celdas
  const thCells = tablaClone.querySelectorAll('th')
  const tdCells = tablaClone.querySelectorAll('td')

  thCells.forEach((cell) => {
    cell.style.border = '1px solid #000'
    cell.style.padding = '4px'
    cell.style.textAlign = 'center'
  })

  tdCells.forEach((cell) => {
    cell.style.border = '1px solid #000'
    cell.style.padding = '4px'
    cell.style.textAlign = 'center'
  })

  // Ajustes específicos para la columna de firma
  const firmaHeaders = tablaClone.querySelectorAll('.firma-header')
  const firmaPlaceholders = tablaClone.querySelectorAll('.firma-placeholder')

  firmaHeaders.forEach((header) => {
    header.style.width = '80px' // Ancho fijo para la columna de firma
  })

  firmaPlaceholders.forEach((placeholder) => {
    placeholder.style.border = '1px dashed #000'
    placeholder.style.height = '30px' // Altura adecuada para firma
    placeholder.style.minWidth = '80px'
    placeholder.style.margin = '0 auto'
  })

  // Añadimos todo al elemento que se convertirá a PDF
  element.appendChild(encabezado)
  element.appendChild(infoContainer)
  element.appendChild(tabla)

  // Configuración para el PDF en horizontal
  const opt = {
    margin: [10, 10, 10, 10],
    filename: `Asesorias_${grupoSeleccionado.value}_${materiaSeleccionada.value}.pdf`,
    image: { type: 'jpeg', quality: 0.98 },
    html2canvas: {
      scale: 2,
      width: 1123,
      height: 794,
      useCORS: true,
    },
    jsPDF: {
      unit: 'mm',
      format: 'a4',
      orientation: 'landscape',
    },
  }

  // Generar el PDF
  html2pdf().from(element).set(opt).save()

  // Mostrar notificación
  vaToast.init({
    message: 'PDF generado con éxito',
    color: 'success',
  })
}
</script>

<style scoped>
.firma-placeholder {
  border: 1px dashed #ccc;
  height: 30px;
  width: 80px;
  margin: 0 auto;
}

/* Estilos responsivos */
.row {
  display: flex;
  flex-wrap: wrap;
  margin: 0 -0.5rem;
}

.flex {
  flex: 1;
  padding: 0 0.5rem;
}

.md6 {
  flex: 0 0 50%;
  max-width: 50%;
}

@media (max-width: 768px) {
  .md6 {
    flex: 0 0 100%;
    max-width: 100%;
  }
}
</style>
