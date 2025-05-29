<template>
  <!-- Título de la vista -->
    <h1 class="va-h4 mb-4">Formato de asesorias</h1>
  <div class="asesoria-ceal-container">
    <!-- Selección inicial de grupo y materia -->
    <va-card class="mb-4">
      <va-card-title>Selección de Grupo y Materia</va-card-title>
      <va-card-content>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <va-select
            v-model="grupoSeleccionado"
            :options="gruposDisponibles"
            label="Grupo"
            placeholder="Seleccione un grupo"
          />
          <va-select
            v-model="materiaSeleccionada"
            :options="materiasDisponibles"
            label="Materia"
            placeholder="Seleccione una materia"
            :disabled="!grupoSeleccionado"
          />
          <va-button 
            @click="cargarFormato" 
            :disabled="!materiaSeleccionada"
            class="self-end"
          >
            Cargar Formato
          </va-button>
        </div>
      </va-card-content>
    </va-card>

    <!-- Formato de Asesoría (visible solo después de seleccionar) -->
    <va-card v-if="mostrarFormato">
      <va-card-content>
        <!-- Encabezado del formato -->
        <div class="text-center mb-6" id="encabezado-pdf">
          <h1 class="va-h4">Asesorías del</h1>
          <h2 class="va-h5">Semestre Enero-Junio</h2>
          <h3 class="va-h6">2025</h3>
        </div>

        <!-- Información del docente -->
        <div class="docente-info mb-6">
          <h4 class="va-subtitle">Nombre del Docente/Instructor:</h4>
          <va-input 
            v-model="docenteNombre" 
            placeholder="Ej: Dr. Carlos Eduardo Azueta León"
            class="text-center font-bold"
          />
        </div>

        <!-- Tabla de asesorías -->
        <div class="overflow-x-auto" id="tabla-asesorias">
          <table class="w-full asesorias-table">
            <thead>
              <tr>
                <th>No.</th>
                <th>No. Control</th>
                <th>Nombre</th>
                <th>Carrera</th>
                <th>Asignatura/Tema</th>
                <th class="firma-header">Firma</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(asesoria, index) in asesorias" :key="index">
                <td>{{ index + 1 }}</td>
                <td>
                  <va-input v-model="asesoria.numeroControl" placeholder="Número de control" />
                </td>
                <td>
                  <va-input v-model="asesoria.nombreEstudiante" placeholder="Nombre completo" />
                </td>
                <td>
                  <va-input v-model="asesoria.carrera" :placeholder="carreraGrupo" />
                </td>
                <td>
                  <va-input v-model="asesoria.tema" :placeholder="materiaSeleccionada" />
                </td>
                <td>
                  <div class="firma-placeholder">
                    <!-- Espacio para firma física -->
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Controles para agregar filas -->
        <div class="flex justify-between mt-4">
          <va-button 
            preset="plain" 
            size="small" 
            @click="agregarAsesoria"
          >
            <va-icon name="add" class="mr-2" />
            Agregar otra asesoría
          </va-button>
          
          <va-button 
            @click="eliminarUltima" 
            preset="plain" 
            size="small"
            :disabled="asesorias.length <= 1"
          >
            <va-icon name="delete" class="mr-2" />
            Eliminar última
          </va-button>
        </div>

        <!-- Botones de acción final -->
        <div class="flex justify-end gap-4 mt-6">
          <va-button preset="secondary" @click="limpiarFormulario">
            <va-icon name="delete" class="mr-2" />
            Limpiar
          </va-button>
          <va-button @click="guardarAsesorias">
            <va-icon name="save" class="mr-2" />
            Guardar Asesorías
          </va-button>
          <va-button preset="secondary" @click="generarPDF">
            <va-icon name="picture_as_pdf" class="mr-2" />
            Generar PDF
          </va-button>
        </div>
      </va-card-content>
    </va-card>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import html2pdf from 'html2pdf.js'

// Datos de selección inicial
const grupoSeleccionado = ref('')
const materiaSeleccionada = ref('')
const mostrarFormato = ref(false)

// Datos simulados - en una aplicación real vendrían de una API
const gruposDisponibles = ref([
  'I3A', 'I3B', 'I5C', 'K9U', 'L2D'
])

const materiasDisponibles = computed(() => {
  if (!grupoSeleccionado.value) return []
  
  // Simulación: diferentes materias por grupo
  const materiasComunes = [
    'CULTURA EMPRESARIAL', 
    'TALLER DE BASE DE DATOS',
    'TÓPICOS AVANZADOS DE PROGRAMACIÓN'
  ]
  
  if (grupoSeleccionado.value.startsWith('I')) {
    return [...materiasComunes, 'BASE DE DATOS', 'INGENIERÍA DE SOFTWARE']
  } else {
    return [...materiasComunes, 'REDES DE COMPUTADORAS', 'SISTEMAS OPERATIVOS']
  }
})

// Datos del formato
const docenteNombre = ref('')
const asesorias = ref([
  { numeroControl: '', nombreEstudiante: '', carrera: '', tema: '' }
])

// Computed para la carrera basada en el grupo seleccionado
const carreraGrupo = computed(() => {
  if (!grupoSeleccionado.value) return 'Carrera'
  
  return grupoSeleccionado.value.startsWith('I') 
    ? 'INGENIERÍA EN SISTEMAS COMPUTACIONALES' 
    : 'LICENCIATURA EN INFORMÁTICA'
})

// Funciones para manejar el formato
const cargarFormato = () => {
  mostrarFormato.value = true
}

const agregarAsesoria = () => {
  asesorias.value.push({ 
    numeroControl: '', 
    nombreEstudiante: '', 
    carrera: carreraGrupo.value, 
    tema: materiaSeleccionada.value
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
    asesorias: asesorias.value
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
  
  thCells.forEach(cell => {
    cell.style.border = '1px solid #000'
    cell.style.padding = '4px'
    cell.style.textAlign = 'center'
  })
  
  tdCells.forEach(cell => {
    cell.style.border = '1px solid #000'
    cell.style.padding = '4px'
    cell.style.textAlign = 'center'
  })
  
  // Ajustes específicos para la columna de firma
  const firmaHeaders = tablaClone.querySelectorAll('.firma-header')
  const firmaPlaceholders = tablaClone.querySelectorAll('.firma-placeholder')
  
  firmaHeaders.forEach(header => {
    header.style.width = '80px' // Ancho fijo para la columna de firma
  })
  
  firmaPlaceholders.forEach(placeholder => {
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
      useCORS: true
    },
    jsPDF: { 
      unit: 'mm', 
      format: 'a4', 
      orientation: 'landscape'
    }
  }
  
  // Generar el PDF
  html2pdf().from(element).set(opt).save()
}
</script>

<style scoped>
.asesoria-ceal-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 1rem;
}

.docente-info {
  text-align: center;
}

.asesorias-table {
  border-collapse: collapse;
  width: 100%;
}

.asesorias-table th, 
.asesorias-table td {
  border: 1px solid var(--va-background-border);
  padding: 0.75rem;
  text-align: center;
}

.asesorias-table th {
  background-color: var(--va-background-element);
  font-weight: 600;
}

.firma-placeholder {
  border: 1px dashed var(--va-background-border);
  padding: 0.5rem;
  height: 40px;
  min-width: 80px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto;
}

.firma-header {
  width: 80px;
}

@media (max-width: 768px) {
  .asesorias-table {
    display: block;
    overflow-x: auto;
  }
}
</style>