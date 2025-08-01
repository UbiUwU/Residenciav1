<template>
  <!-- Título de la vista -->
  <h1 class="va-h4 mb-4">Formulario de Avance Programatico</h1>

  <!-- Encabezado institucional -->
  <VaCard class="mb-4">
    <VaCardContent class="text-center">
      <h1 class="va-h3">INSTITUTO TECNOLÓGICO DE CHETUMAL</h1>
      <h2 class="va-h5">SUBDIRECCIÓN ACADÉMICA</h2>
      <h3 class="va-h6">
        DEPARTAMENTO:
        <VaInput v-model="departamento" placeholder="Nombre del departamento" class="d-inline" readonly />
      </h3>
      <h3 class="va-h6">
        PLANEACIÓN DEL CURSO Y AVANCE PROGRAMÁTICO DEL PERIODO:
        <VaInput v-model="periodo" placeholder="Ej. ENE-JUN 2025" class="d-inline" />
      </h3>
    </VaCardContent>
  </VaCard>

  <!-- Datos básicos del curso -->
  <VaCard class="mb-4">
    <VaCardContent>
      <!-- Selector de asignatura -->
      <div class="mb-6">
        <VaSelect
          v-model="asignaturaSeleccionada"
          label="Seleccionar Asignatura"
          :options="opcionesAsignaturas"
          :loading="cargandoAsignaturas"
          searchable
          clearable
          @update:modelValue="cargarDatosAsignatura"
        >
          <template #content="{ text }">
            <div class="flex justify-between">
              <span>{{ text }}</span>
              <span v-if="asignaturaSeleccionada" class="text-gray-500 ml-2">
                ({{ asignaturaSeleccionada.creditos }} créditos)
              </span>
            </div>
          </template>
        </VaSelect>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
        <VaInput v-model="materia" label="MATERIA" readonly />
        <VaInput v-model="horasTeoricas" label="HT" type="number" readonly />
        <VaInput v-model="horasPracticas" label="HP" type="number" readonly />
        <VaInput v-model="creditos" label="CR" type="number" readonly />
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <VaInput v-model="grupo" label="GRUPO" />
        <VaInput v-model="carrera" label="CARRERA" readonly />
        <VaInput v-model="aula" label="AULA" />
      </div>

      <VaInput v-model="profesor" label="PROFESOR(A)" class="mb-4" readonly />

      <VaInput
        v-model="competenciaAsignatura"
        type="textarea"
        label="COMPETENCIA ESPECÍFICA DE LA ASIGNATURA:"
        autosize
        class="mb-4"
        readonly
      />

      <VaInput v-model="numTemas" label="No. DE TEMAS:" type="number" readonly />
    </VaCardContent>
  </VaCard>

  <!-- Tabla principal de programación -->
  <VaCard>
    <VaCardContent>
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr>
              <th class="text-left p-2 border">Temas</th>
              <th class="text-left p-2 border">Subtemas</th>
              <th class="text-center p-2 border" colspan="2">Fechas (Periodo)</th>
              <th class="text-center p-2 border" colspan="2">Evaluación</th>
              <th class="text-center p-2 border">% de aprobación</th>
              <th class="text-center p-2 border">Firma Docente</th>
              <th class="text-center p-2 border">Firma Jefe Académico</th>
              <th class="text-center p-2 border">Observaciones</th>
            </tr>
            <tr>
              <th colspan="2"></th>
              <th class="text-center p-2 border">Programado</th>
              <th class="text-center p-2 border">Real</th>
              <th class="text-center p-2 border">Programada</th>
              <th class="text-center p-2 border">Real</th>
              <th colspan="4"></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(tema, index) in temas" :key="index">
              <td class="p-2 border">
                <VaInput v-model="tema.nombre" placeholder="Tema" class="w-full" readonly />
              </td>
              <td class="p-2 border">
                <VaInput v-model="tema.subtemas" placeholder="Subtemas" class="w-full" readonly />
              </td>
              <td class="p-2 border">
                <VaDateInput v-model="tema.fechaProgramada" placeholder="Programado" class="w-full" />
              </td>
              <td class="p-2 border">
                <VaDateInput v-model="tema.fechaReal" placeholder="Real" class="w-full" />
              </td>
              <td class="p-2 border">
                <VaInput v-model="tema.evaluacionProgramada" placeholder="Evaluación prog." class="w-full" readonly />
              </td>
              <td class="p-2 border">
                <VaInput v-model="tema.evaluacionReal" placeholder="Evaluación real" class="w-full" readonly />
              </td>
              <td class="p-2 border">
                <VaInput v-model="tema.porcentajeAprobacion" placeholder="%" type="number" class="w-full" readonly />
              </td>
              <td class="p-2 border"></td>
              <td class="p-2 border"></td>
              <td class="p-2 border">
                <VaInput v-model="tema.observaciones" placeholder="Observaciones" class="w-full" readonly />
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <VaButton class="mt-4" preset="secondary" disabled @click="agregarTema">
        <VaIcon name="add" class="mr-2" />
        Agregar tema
      </VaButton>
    </VaCardContent>
  </VaCard>

  <!-- Fechas de seguimiento -->
  <VaCard class="mt-4">
    <VaCardContent>
      <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <VaDateInput v-model="fechaEntregaProgramacion" label="Fecha de entrega de programación" class="mb-4" />
        <VaDateInput v-model="seguimiento1.fecha" :label="`Seguimiento 1 (${periodo})`" />
        <VaDateInput v-model="seguimiento2.fecha" :label="`Seguimiento 2 (${periodo})`" />
        <VaDateInput v-model="seguimiento3.fecha" :label="`Seguimiento 3 (${periodo})`" />
        <VaDateInput v-model="seguimiento4.fecha" :label="`Seguimiento 4 (${periodo})`" />
      </div>
    </VaCardContent>
  </VaCard>

  <!-- Firmas y validación -->
  <VaCard class="mt-4">
    <VaCardContent>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <VaInput v-model="firmaDocente" label="Firma del Docente" class="mb-4" readonly />
          <VaInput v-model="firmaJefeAcademico" label="Vo.Bo. del Jefe(a) de Departamento" readonly />
        </div>
        <VaInput v-model="observacionesGenerales" type="textarea" label="Observaciones generales" autosize readonly />
      </div>
    </VaCardContent>
  </VaCard>

  <!-- Botones de acción -->
  <div class="flex justify-end gap-4 mt-6">
    <VaButton preset="secondary" @click="guardarBorrador">
      <VaIcon name="save" class="mr-2" />
      Guardar borrador
    </VaButton>
    <VaButton @click="generarPDFEstricto">
      <VaIcon name="print" class="mr-2" />
      Generar PDF
    </VaButton>
    <VaButton preset="primary" @click="enviarFormulario">
      <VaIcon name="send" class="mr-2" />
      Enviar
    </VaButton>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'

// Datos de ejemplo para asignaturas (simulando respuesta de API)
const asignaturasEjemplo = [
  {
    claveasignatura: 'ITC-101',
    nombreasignatura: 'Programación Estructurada',
    satca_teoricas: 3,
    satca_practicas: 2,
    creditos: 5,
    clavecarrera: 'ING-SIS',
    competencias: [
      {
        tipo: 'ESPECÍFICA',
        descripcion:
          'Diseña y desarrolla algoritmos eficientes utilizando estructuras de control básicas y técnicas de programación estructurada, aplicando principios de modularidad y reutilización de código.',
      },
      {
        tipo: 'ESPECÍFICA',
        descripcion:
          'Implementa soluciones de software en lenguaje C, aplicando buenas prácticas de programación, manejo de memoria y estructuras de datos básicas para resolver problemas computacionales.',
      },
    ],
    temas: [
      {
        nombre: 'Fundamentos de programación',
        subtemas: 'Algoritmos, pseudocódigo, diagramas de flujo, herramientas de desarrollo',
        evaluacionProgramada: 'Examen teórico-práctico',
        evaluacionReal: '',
        porcentajeAprobacion: 0,
        observaciones: '',
      },
      {
        nombre: 'Estructuras de control',
        subtemas: 'Secuenciales, condicionales (if-else, switch), ciclos (for, while, do-while)',
        evaluacionProgramada: 'Práctica calificada',
        porcentajeAprobacion: 0,
        observaciones: '',
      },
      {
        nombre: 'Funciones y modularidad',
        subtemas: 'Definición de funciones, paso de parámetros, alcance de variables, recursividad',
        evaluacionProgramada: 'Proyecto modular',
        porcentajeAprobacion: 0,
        observaciones: '',
      },
      {
        nombre: 'Arreglos y cadenas',
        subtemas: 'Arreglos unidimensionales y multidimensionales, manejo de cadenas, funciones de string.h',
        evaluacionProgramada: 'Examen parcial',
        porcentajeAprobacion: 0,
        observaciones: '',
      },
      {
        nombre: 'Estructuras y archivos',
        subtemas: 'Tipos de datos definidos por el usuario, manejo de archivos binarios y de texto',
        evaluacionProgramada: 'Proyecto final',
        porcentajeAprobacion: 0,
        observaciones: '',
      },
    ],
    departamento: 'Sistemas y Computación',
  },
  {
    claveasignatura: 'AEF-1031',
    nombreasignatura: 'Fund. de Bases de Datos',
    satca_teoricas: 2,
    satca_practicas: 3,
    creditos: 5,
    clavecarrera: 'ISIC',
    competencias: [
      {
        tipo: 'ESPECÍFICA',
        descripcion:
          'Diseña modelos conceptuales y lógicos de bases de datos aplicando técnicas de normalización y considerando los requerimientos del negocio para garantizar la integridad y consistencia de los datos.',
      },
      {
        tipo: 'ESPECÍFICA',
        descripcion:
          'Implementa bases de datos relacionales utilizando sistemas gestores como MySQL o PostgreSQL, desarrollando consultas SQL complejas, procedimientos almacenados y triggers para aplicaciones empresariales.',
      },
    ],
    temas: [
      {
        nombre: 'Fundamentos de bases de datos',
        subtemas: 'Conceptos básicos, modelos de datos, arquitectura ANSI/SPARC, SGBD',
        evaluacionProgramada: 'Examen teórico',
        porcentajeAprobacion: 0,
        observaciones: '',
      },
      {
        nombre: 'Modelado conceptual',
        subtemas: 'Modelo Entidad-Relación, diagramas ER, restricciones de integridad',
        evaluacionProgramada: 'Práctica de modelado',
        porcentajeAprobacion: 0,
        observaciones: '',
      },
      {
        nombre: 'Modelo relacional',
        subtemas: 'Estructuras relacionales, álgebra relacional, normalización (1FN a 5FN)',
        evaluacionProgramada: 'Examen parcial',
        porcentajeAprobacion: 0,
        observaciones: '',
      },
      {
        nombre: 'SQL básico y avanzado',
        subtemas: 'DDL, DML, consultas complejas, vistas, índices',
        evaluacionProgramada: 'Práctica en laboratorio',
        porcentajeAprobacion: 0,
        observaciones: '',
      },
      {
        nombre: 'Diseño físico e implementación',
        subtemas: 'Procedimientos almacenados, triggers, transacciones, seguridad',
        evaluacionProgramada: 'Proyecto final',
        porcentajeAprobacion: 0,
        observaciones: '',
      },
    ],
    departamento: 'Sistemas y Computación',
  },
  {
    claveasignatura: 'ITC-305',
    nombreasignatura: 'Redes de Computadoras',
    satca_teoricas: 4,
    satca_practicas: 1,
    creditos: 5,
    clavecarrera: 'ING-TICS',
    competencias: [
      {
        tipo: 'ESPECÍFICA',
        descripcion:
          'Diseña e implementa redes de computadoras considerando estándares y protocolos actuales, evaluando requerimientos de ancho de banda, latencia y seguridad para garantizar la conectividad y calidad de servicio.',
      },
      {
        tipo: 'ESPECÍFICA',
        descripcion:
          'Configura dispositivos de red como switches, routers y firewalls aplicando protocolos de enrutamiento y mecanismos de seguridad para construir infraestructuras de red escalables y seguras.',
      },
    ],
    temas: [
      {
        nombre: 'Introducción a redes',
        subtemas: 'Conceptos básicos, modelos OSI y TCP/IP, topologías, medios de transmisión',
        evaluacionProgramada: 'Examen teórico',
        porcentajeAprobacion: 0,
        observaciones: '',
      },
      {
        nombre: 'Capa física y de enlace',
        subtemas: 'Codificación de datos, Ethernet, switches, protocolos MAC',
        evaluacionProgramada: 'Práctica de laboratorio',
        porcentajeAprobacion: 0,
        observaciones: '',
      },
      {
        nombre: 'Capa de red',
        subtemas: 'Direccionamiento IP, enrutamiento estático y dinámico, protocolos RIP, OSPF',
        evaluacionProgramada: 'Examen parcial',
        porcentajeAprobacion: 0,
        observaciones: '',
      },
      {
        nombre: 'Capas de transporte y aplicación',
        subtemas: 'TCP/UDP, DNS, HTTP, FTP, protocolos de seguridad',
        evaluacionProgramada: 'Práctica configuraciones',
        porcentajeAprobacion: 0,
        observaciones: '',
      },
      {
        nombre: 'Seguridad en redes',
        subtemas: 'Firewalls, VPN, detección de intrusos, redes privadas virtuales',
        evaluacionProgramada: 'Proyecto final',
        porcentajeAprobacion: 0,
        observaciones: '',
      },
    ],
    departamento: 'Tecnologías de la Información',
  },
]

// Estado para las asignaturas
const asignaturaSeleccionada = ref<any>(null)
const opcionesAsignaturas = ref<any[]>([])
const cargandoAsignaturas = ref(false)

// Datos institucionales
const departamento = ref('')
const periodo = ref('')

// Datos del curso (algunos se autocompletarán)
const materia = ref('')
const horasTeoricas = ref(0)
const horasPracticas = ref(0)
const creditos = ref(0)
const grupo = ref('')
const aula = ref('')
const carrera = ref('')
const profesor = ref('Dr. Carlos Eduardo Azueta León')
const competenciaAsignatura = ref('')
const numTemas = ref(0)

// Tabla de temas
const temas = ref([
  {
    nombre: '',
    subtemas: '',
    fechaProgramada: null,
    fechaReal: null,
    evaluacionProgramada: '',
    evaluacionReal: '',
    porcentajeAprobacion: 0,
    observaciones: '',
  },
])

// Fechas de seguimiento
const fechaEntregaProgramacion = ref(null)
const seguimiento1 = ref({ fecha: null, completado: false })
const seguimiento2 = ref({ fecha: null, completado: false })
const seguimiento3 = ref({ fecha: null, completado: false })
const seguimiento4 = ref({ fecha: null, completado: false })

// Firmas y validación
const firmaDocente = ref('Dr. Carlos Eduardo Azueta León')
const firmaJefeAcademico = ref('')
const observacionesGenerales = ref('')

// Cargar asignaturas al montar el componente
onMounted(() => {
  cargarAsignaturas()
})

// Función para cargar las asignaturas disponibles (ahora con datos simulados)
const cargarAsignaturas = async () => {
  cargandoAsignaturas.value = true
  try {
    // Simulamos un retraso de red
    await new Promise((resolve) => setTimeout(resolve, 800))

    // Usamos los datos de ejemplo en lugar de la API
    opcionesAsignaturas.value = asignaturasEjemplo.map((asignatura) => ({
      text: `${asignatura.claveasignatura} - ${asignatura.nombreasignatura}`,
      value: asignatura.claveasignatura,
      ...asignatura,
    }))
  } catch (error) {
    console.error('Error al cargar asignaturas:', error)
  } finally {
    cargandoAsignaturas.value = false
  }
}

// Función para cargar los datos cuando se selecciona una asignatura
const cargarDatosAsignatura = (asignatura: any) => {
  if (!asignatura) {
    limpiarDatosAsignatura()
    return
  }

  // Autocompletar campos con datos de la asignatura
  materia.value = asignatura.nombreasignatura || ''
  horasTeoricas.value = asignatura.satca_teoricas || 0
  horasPracticas.value = asignatura.satca_practicas || 0
  creditos.value = asignatura.creditos || 0
  carrera.value = asignatura.clavecarrera || ''
  departamento.value = asignatura.departamento || ''

  // Establecer valores por defecto para grupo y aula
  grupo.value = '1' // Grupo por defecto
  aula.value = 'LAB-1' // Aula por defecto

  // Competencias específicas
  if (asignatura.competencias && asignatura.competencias.length) {
    competenciaAsignatura.value = asignatura.competencias
      .filter((comp: any) => comp.tipo === 'ESPECÍFICA')
      .map((comp: any) => `• ${comp.descripcion}`)
      .join('\n')
  } else {
    competenciaAsignatura.value = '• Competencias no definidas en el programa'
  }

  // Temas de la asignatura (siempre 5 temas)
  if (asignatura.temas && asignatura.temas.length) {
    temas.value = asignatura.temas.map((tema: any) => ({
      ...tema,
      fechaProgramada: null, // El docente llenará esto
      fechaReal: null, // El docente llenará esto
      evaluacionReal: '', // El docente llenará esto
    }))
    numTemas.value = asignatura.temas.length
  } else {
    // Si no hay temas definidos, creamos 5 temas genéricos
    temas.value = Array.from({ length: 5 }, (_, i) => ({
      nombre: `Tema ${i + 1}`,
      subtemas: `Subtema A, Subtema B, Subtema C`,
      fechaProgramada: null,
      fechaReal: null,
      evaluacionProgramada: i === 4 ? 'Proyecto final' : `Evaluación ${i + 1}`,
      evaluacionReal: '',
      porcentajeAprobacion: 0,
      observaciones: '',
    }))
    numTemas.value = 5
  }
}

// Función para limpiar los datos cuando no hay asignatura seleccionada
const limpiarDatosAsignatura = () => {
  materia.value = ''
  horasTeoricas.value = 0
  horasPracticas.value = 0
  creditos.value = 0
  carrera.value = ''
  departamento.value = ''
  grupo.value = ''
  aula.value = ''
  competenciaAsignatura.value = ''
  temas.value = [
    {
      nombre: '',
      subtemas: '',
      fechaProgramada: null,
      fechaReal: null,
      evaluacionProgramada: '',
      evaluacionReal: '',
      porcentajeAprobacion: 0,
      observaciones: '',
    },
  ]
  numTemas.value = 0
}

// Funciones existentes del formulario
const agregarTema = () => {
  temas.value.push({
    nombre: '',
    subtemas: '',
    fechaProgramada: null,
    fechaReal: null,
    evaluacionProgramada: '',
    evaluacionReal: '',
    porcentajeAprobacion: 0,
    observaciones: '',
  })
}

const guardarBorrador = () => {
  console.log('Borrador guardado:', {
    departamento: departamento.value,
    periodo: periodo.value,
    materia: materia.value,
    // ... otros campos
  })
}

const enviarFormulario = () => {
  console.log('Formulario enviado:', {
    departamento: departamento.value,
    periodo: periodo.value,
    materia: materia.value,
    // ... otros campos
  })
}
</script>

<style scoped>
.border {
  border: 1px solid var(--va-background-border);
}

table {
  width: 100%;
  border-collapse: collapse;
}

th,
td {
  padding: 0.5rem;
}

@media (max-width: 768px) {
  .grid-cols-5,
  .grid-cols-2 {
    grid-template-columns: 1fr;
  }

  table {
    display: block;
    overflow-x: auto;
    white-space: nowrap;
  }
}
</style>
