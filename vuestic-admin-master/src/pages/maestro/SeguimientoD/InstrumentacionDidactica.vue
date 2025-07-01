<template>
    <!-- Título de la vista -->
  <h1 class="va-h4 mb-4">Formulario de Instrumentación didáctica</h1>
  
  <!-- Encabezado institucional -->
  <va-card class="mb-4">
    <va-card-content class="text-center">
      <h1 class="va-h3">INSTITUTO TECNOLÓGICO DE CHETUMAL</h1>
      <h2 class="va-h5">SUBDIRECCIÓN ACADÉMICA</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
        <va-input v-model="departamento" label="DEPARTAMENTO" />
        <va-input v-model="periodo" label="PERIODO" placeholder="Ej. ENE-JUN 2023" />
        <va-input v-model="planEstudios" label="PLAN DE ESTUDIOS" />
      </div>
    </va-card-content>
  </va-card>

  <!-- Título principal -->
  <h1 class="va-h4 mb-4 text-center">INSTRUMENTACIÓN DIDÁCTICA PARA LA FORMACIÓN Y DESARROLLO DE COMPETENCIAS PROFESIONALES</h1>

  <!-- Selección de asignatura -->
  <va-card class="mb-4">
    <va-card-content>
      <va-select
        v-model="asignaturaSeleccionada"
        label="Seleccionar Asignatura"
        :options="opcionesAsignaturas"
        :loading="cargandoAsignaturas"
        searchable
        clearable
        @update:modelValue="cargarDatosAsignatura"
      />
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
        <va-input v-model="nombreAsignatura" label="NOMBRE DE LA ASIGNATURA" />
        <va-input v-model="horasTeoricas" label="HORAS TEORÍA" type="number" />
        <va-input v-model="horasPracticas" label="HORAS PRÁCTICA" type="number" />
        <va-input v-model="creditos" label="CRÉDITOS" type="number" />
      </div>
    </va-card-content>
  </va-card>

  <!-- Sección 1: Caracterización de la asignatura -->
  <va-card class="mb-4">
    <va-card-content>
      <h2 class="va-h5 mb-2">1. Caracterización de la asignatura</h2>
      <va-textarea
        v-model="caracterizacion"
        label="Describa la aportación al perfil profesional, importancia, contenido y relaciones con otras asignaturas"
        autosize
      />
    </va-card-content>
  </va-card>

  <!-- Sección 2: Intención didáctica -->
  <va-card class="mb-4">
    <va-card-content>
      <h2 class="va-h5 mb-2">2. Intención didáctica</h2>
      <va-textarea
        v-model="intencionDidactica"
        label="Describa el enfoque, profundidad, actividades del estudiante y papel del profesor"
        autosize
      />
    </va-card-content>
  </va-card>

  <!-- Sección 3: Competencia de la asignatura -->
  <va-card class="mb-4">
    <va-card-content>
      <h2 class="va-h5 mb-2">3. Competencia de la asignatura</h2>
      <va-textarea
        v-model="competenciaAsignatura"
        label="¿Qué debe saber y saber hacer el estudiante como resultado de esta asignatura?"
        autosize
      />
    </va-card-content>
  </va-card>

  <!-- Sección 4: Análisis por competencias específicas -->
  <va-card class="mb-4" v-for="(competencia, index) in competencias" :key="index">
    <va-card-content>
      <div class="flex justify-between items-center mb-4">
        <h2 class="va-h5">4. Análisis por competencias específicas</h2>
        <va-button @click="eliminarCompetencia(index)" preset="secondary" size="small" v-if="competencias.length > 1">
          Eliminar
        </va-button>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <va-input v-model="competencia.numero" label="Competencia No." type="number" />
        <va-textarea v-model="competencia.descripcion" label="Descripción" autosize />
      </div>

      <!-- Tabla de temas y actividades -->
      <div class="overflow-x-auto mb-4">
        <table class="w-full">
          <thead>
            <tr>
              <th class="text-left p-2 border">Temas y subtemas (4.3)</th>
              <th class="text-left p-2 border">Actividades de aprendizaje (4.4)</th>
              <th class="text-left p-2 border">Actividades de enseñanza (4.5)</th>
              <th class="text-left p-2 border">Competencias genéricas (4.6)</th>
              <th class="text-left p-2 border">Horas teórico-prácticas (4.7)</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(tema, tIndex) in competencia.temas" :key="tIndex">
              <td class="p-2 border">
                <va-textarea v-model="tema.temasSubtemas" autosize />
              </td>
              <td class="p-2 border">
                <va-textarea v-model="tema.actividadesAprendizaje" autosize />
              </td>
              <td class="p-2 border">
                <va-textarea v-model="tema.actividadesEnsenanza" autosize />
              </td>
              <td class="p-2 border">
                <va-select 
                  v-model="tema.competenciasGenericas"
                  :options="opcionesCompetenciasGenericas"
                  multiple
                  searchable
                />
              </td>
              <td class="p-2 border">
                <va-input v-model="tema.horas" type="number" />
              </td>
            </tr>
          </tbody>
        </table>
        <va-button @click="agregarTema(index)" class="mt-2" preset="secondary" size="small">
          Agregar tema
        </va-button>
      </div>

      <!-- Indicadores y evaluación -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div>
          <h3 class="va-h6 mb-2">Indicadores de alcance (4.8) y valor (4.9)</h3>
          <div v-for="(indicador, iIndex) in competencia.indicadores" :key="iIndex" class="mb-2">
            <div class="flex gap-2">
              <va-input v-model="indicador.descripcion" placeholder="Indicador" />
              <va-input v-model="indicador.valor" placeholder="Valor (%)" type="number" />
              <va-button @click="eliminarIndicador(index, iIndex)" preset="plain" icon="delete" size="small" />
            </div>
          </div>
          <va-button @click="agregarIndicador(index)" preset="secondary" size="small">
            Agregar indicador
          </va-button>
        </div>

        <div>
          <h3 class="va-h6 mb-2">Niveles de desempeño (4.10)</h3>
          <va-data-table
            :items="nivelesDesempeno"
            :columns="[
              { key: 'nivel', label: 'Nivel' },
              { key: 'valoracion', label: 'Valoración numérica' }
            ]"
          />
        </div>
      </div>

      <!-- Matriz de evaluación -->
      <div>
        <h3 class="va-h6 mb-2">4.11 Matriz de evaluación</h3>
        <va-textarea
          v-model="competencia.matrizEvaluacion"
          label="Describa los criterios de evaluación, evidencias de aprendizaje y porcentajes"
          autosize
        />
      </div>
    </va-card-content>
  </va-card>

  <!-- Botón para agregar nueva competencia -->
  <va-button @click="agregarCompetencia" class="mb-4" preset="secondary">
    Agregar competencia específica
  </va-button>

  <!-- Sección 5: Fuentes de información y apoyos didácticos -->
  <va-card class="mb-4">
    <va-card-content>
      <h2 class="va-h5 mb-2">5. Fuentes de información y apoyos didácticos</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <h3 class="va-h6 mb-2">5.1 Fuentes de información</h3>
          <va-textarea
            v-model="fuentesInformacion"
            label="Liste las fuentes según normas APA"
            autosize
          />
        </div>
        <div>
          <h3 class="va-h6 mb-2">5.2 Apoyos didácticos</h3>
          <va-textarea
            v-model="apoyosDidacticos"
            label="Liste los materiales de apoyo"
            autosize
          />
        </div>
      </div>
    </va-card-content>
  </va-card>

  <!-- Sección 6: Calendarización -->
  <va-card class="mb-4">
    <va-card-content>
      <h2 class="va-h5 mb-2">6. Calendarización de evaluación en semanas</h2>
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr>
              <th v-for="semana in 16" :key="semana" class="text-center p-2 border">
                {{ semana }}
              </th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td v-for="semana in 16" :key="semana" class="p-2 border">
                <va-select
                  v-model="calendarizacion[semana-1]"
                  :options="opcionesCalendarizacion"
                  size="small"
                />
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
        <va-date-input v-model="fechaElaboracion" label="Fecha de elaboración" />
      </div>
    </va-card-content>
  </va-card>

  <!-- Firmas -->
  <va-card>
    <va-card-content>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <va-input v-model="profesor" label="PROFESOR(A) - NOMBRE Y FIRMA" />
        </div>
        <div>
          <va-input v-model="jefeAcademico" label="JEFE(A) DEL ÁREA ACADÉMICA - NOMBRE Y FIRMA" />
        </div>
      </div>
    </va-card-content>
  </va-card>

  <!-- Botones de acción -->
  <div class="flex justify-end gap-4 mt-6">
    <va-button preset="secondary" @click="guardarBorrador">
      <va-icon name="save" class="mr-2" />
      Guardar borrador
    </va-button>
    <va-button @click="imprimirFormulario">
      <va-icon name="print" class="mr-2" />
      Imprimir
    </va-button>
    <va-button preset="primary" @click="enviarFormulario">
      <va-icon name="send" class="mr-2" />
      Enviar
    </va-button>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'

// Datos institucionales
const departamento = ref('Sistemas y Computación')
const periodo = ref('ENE-JUN 2025')
const planEstudios = ref('ING-2020')

// Datos de la asignatura
const asignaturaSeleccionada = ref(null)
const opcionesAsignaturas = ref([
  { 
    text: 'FDBD - Fundamentos de Bases de Datos', 
    value: 'FDBD',
    nombre: 'Fundamentos de Bases de Datos',
    creditos: 5,
    horasTeoricas: 3,
    horasPracticas: 2
  },
  { 
    text: 'MAT101 - Matemáticas I', 
    value: 'MAT101',
    nombre: 'Matemáticas I',
    creditos: 5,
    horasTeoricas: 3,
    horasPracticas: 2 
  },
  { 
    text: 'PRO101 - Programación I', 
    value: 'PRO101',
    nombre: 'Programación I',
    creditos: 6,
    horasTeoricas: 2,
    horasPracticas: 4 
  }
])
const cargandoAsignaturas = ref(false)
const nombreAsignatura = ref('')
const horasTeoricas = ref(0)
const horasPracticas = ref(0)
const creditos = ref(0)

// Sección 1
const caracterizacion = ref('')

// Sección 2
const intencionDidactica = ref('')

// Sección 3
const competenciaAsignatura = ref('')

// Sección 4: Competencias específicas
const competencias = ref([
  {
    numero: 1,
    descripcion: '',
    temas: [
      {
        temasSubtemas: '',
        actividadesAprendizaje: '',
        actividadesEnsenanza: '',
        competenciasGenericas: [],
        horas: 0
      }
    ],
    indicadores: [
      { descripcion: '', valor: 0 }
    ],
    matrizEvaluacion: ''
  }
])

// Opciones para competencias genéricas
const opcionesCompetenciasGenericas = ref([
  { text: 'Capacidad de análisis y síntesis', value: 'analisis_sintesis' },
  { text: 'Capacidad de organizar y planificar', value: 'organizar_planificar' },
  { text: 'Comunicación oral y escrita', value: 'comunicacion' },
  { text: 'Habilidades de gestión de información', value: 'gestion_informacion' },
  { text: 'Solución de problemas', value: 'solucion_problemas' },
  { text: 'Trabajo en equipo', value: 'trabajo_equipo' },
  { text: 'Habilidades interpersonales', value: 'habilidades_interpersonales' },
  { text: 'Capacidad de aplicar conocimientos', value: 'aplicar_conocimientos' },
  { text: 'Habilidades de investigación', value: 'investigacion' }
])

// Niveles de desempeño predefinidos
const nivelesDesempeno = ref([
  { nivel: 'Excelente', valoracion: '90-100' },
  { nivel: 'Notable', valoracion: '80-89' },
  { nivel: 'Bueno', valoracion: '70-79' },
  { nivel: 'Suficiente', valoracion: '60-69' },
  { nivel: 'Insuficiente', valoracion: '0-59' }
])

// Sección 5
const fuentesInformacion = ref('')
const apoyosDidacticos = ref('')

// Sección 6: Calendarización
const calendarizacion = ref(Array(16).fill(''))
const opcionesCalendarizacion = ref([
  { text: 'TP', value: 'TP', description: 'Tiempo Planeado' },
  { text: 'TR', value: 'TR', description: 'Tiempo Real' },
  { text: 'SD', value: 'SD', description: 'Seguimiento Departamental' },
  { text: 'ED', value: 'ED', description: 'Evaluación Diagnóstica' },
  { text: 'EF', value: 'EF', description: 'Evaluación Formativa' },
  { text: 'ES', value: 'ES', description: 'Evaluación Sumativa' }
])
const fechaElaboracion = ref(new Date())

// Firmas
const profesor = ref('')
const jefeAcademico = ref('')

// Funciones para manejar competencias
const agregarCompetencia = () => {
  competencias.value.push({
    numero: competencias.value.length + 1,
    descripcion: '',
    temas: [
      {
        temasSubtemas: '',
        actividadesAprendizaje: '',
        actividadesEnsenanza: '',
        competenciasGenericas: [],
        horas: 0
      }
    ],
    indicadores: [
      { descripcion: '', valor: 0 }
    ],
    matrizEvaluacion: ''
  })
}

const eliminarCompetencia = (index) => {
  if (competencias.value.length > 1) {
    competencias.value.splice(index, 1)
    // Renumerar las competencias
    competencias.value.forEach((comp, i) => {
      comp.numero = i + 1
    })
  }
}

const agregarTema = (competenciaIndex) => {
  competencias.value[competenciaIndex].temas.push({
    temasSubtemas: '',
    actividadesAprendizaje: '',
    actividadesEnsenanza: '',
    competenciasGenericas: [],
    horas: 0
  })
}

const agregarIndicador = (competenciaIndex) => {
  competencias.value[competenciaIndex].indicadores.push({
    descripcion: '',
    valor: 0
  })
}

const eliminarIndicador = (competenciaIndex, indicadorIndex) => {
  if (competencias.value[competenciaIndex].indicadores.length > 1) {
    competencias.value[competenciaIndex].indicadores.splice(indicadorIndex, 1)
  }
}

const cargarDatosAsignatura = (asignatura) => {
  if (!asignatura) {
    resetearFormulario()
    return
  }

  nombreAsignatura.value = asignatura.nombre
  horasTeoricas.value = asignatura.horasTeoricas
  horasPracticas.value = asignatura.horasPracticas
  creditos.value = asignatura.creditos

  // Precargar datos si es Fundamentos de Bases de Datos
  if (asignatura.value === 'FDBD') {
    precargarDatosBasesDatos()
  }
}

const resetearFormulario = () => {
  nombreAsignatura.value = ''
  horasTeoricas.value = 0
  horasPracticas.value = 0
  creditos.value = 0
  caracterizacion.value = ''
  intencionDidactica.value = ''
  competenciaAsignatura.value = ''
  competencias.value = [{
    numero: 1,
    descripcion: '',
    temas: [{ temasSubtemas: '', actividadesAprendizaje: '', actividadesEnsenanza: '', competenciasGenericas: [], horas: 0 }],
    indicadores: [{ descripcion: '', valor: 0 }],
    matrizEvaluacion: ''
  }]
  fuentesInformacion.value = ''
  apoyosDidacticos.value = ''
  calendarizacion.value = Array(16).fill('')
}

const precargarDatosBasesDatos = () => {
  // Sección 1: Caracterización
  caracterizacion.value = 'Esta asignatura aporta al perfil profesional la capacidad para diseñar, implementar y administrar bases de datos, considerando la integridad, seguridad y disponibilidad de la información. Es fundamental para el desarrollo de sistemas de información y se relaciona con asignaturas como Programación, Ingeniería de Software y Sistemas Operativos.'
  
  // Sección 2: Intención didáctica
  intencionDidactica.value = 'El enfoque será práctico-teórico, combinando fundamentos conceptuales con ejercicios de diseño e implementación. El profesor actuará como guía en el proceso de aprendizaje, fomentando la investigación y solución de problemas reales. Los estudiantes desarrollarán proyectos incrementales que integren los conocimientos adquiridos.'
  
  // Sección 3: Competencia
  competenciaAsignatura.value = 'Diseña e implementa bases de datos relacionales aplicando normas de calidad y seguridad, utilizando lenguajes de definición y manipulación de datos, considerando las necesidades de información de una organización.'
  
  // Sección 4: Competencias específicas
  competencias.value = [
    {
      numero: 1,
      descripcion: 'Analiza los fundamentos teóricos y conceptuales de los sistemas de bases de datos.',
      temas: [
        {
          temasSubtemas: '1.1 Introducción a las bases de datos\n1.2 Evolución histórica\n1.3 Modelos de datos\n1.4 Arquitectura de tres esquemas',
          actividadesAprendizaje: 'Investigar y presentar casos de estudio sobre evolución de bases de datos\nRealizar comparativa entre modelos de datos',
          actividadesEnsenanza: 'Exposición teórica\nDinámicas grupales para comparar modelos\nEjercicios de identificación de componentes arquitectónicos',
          competenciasGenericas: ['analisis_sintesis', 'gestion_informacion'],
          horas: 4
        }
      ],
      indicadores: [
        { descripcion: 'Identifica correctamente los componentes de la arquitectura de tres esquemas', valor: 30 },
        { descripcion: 'Explica las diferencias entre los modelos de datos', valor: 30 },
        { descripcion: 'Describe casos de aplicación real de bases de datos', valor: 40 }
      ],
      matrizEvaluacion: 'Evaluación mediante cuestionario teórico (40%), presentación de casos de estudio (30%) y participación en dinámicas grupales (30%).'
    },
    {
      numero: 2,
      descripcion: 'Diseña modelos conceptuales y lógicos de bases de datos relacionales aplicando técnicas de normalización.',
      temas: [
        {
          temasSubtemas: '2.1 Modelo Entidad-Relación\n2.2 Diagramas E-R\n2.3 Modelo relacional\n2.4 Normalización (1FN, 2FN, 3FN)',
          actividadesAprendizaje: 'Diseñar diagramas E-R para casos propuestos\nConvertir modelos E-R a relacional\nAplicar normalización a esquemas dados',
          actividadesEnsenanza: 'Talleres prácticos de diseño\nRevisión de casos en grupo\nEjercicios de transformación y normalización',
          competenciasGenericas: ['solucion_problemas', 'aplicar_conocimientos'],
          horas: 8
        }
      ],
      indicadores: [
        { descripcion: 'Diseña diagramas E-R completos y correctos', valor: 40 },
        { descripcion: 'Aplica correctamente las reglas de transformación a modelo relacional', valor: 30 },
        { descripcion: 'Normaliza esquemas hasta 3FN', valor: 30 }
      ],
      matrizEvaluacion: 'Evaluación mediante entrega de ejercicios prácticos (60%) y examen de casos (40%).'
    },
    {
      numero: 3,
      descripcion: 'Implementa bases de datos utilizando lenguajes SQL en sistemas gestores de bases de datos.',
      temas: [
        {
          temasSubtemas: '3.1 Lenguaje SQL (DDL, DML)\n3.2 Creación de tablas y restricciones\n3.3 Consultas básicas y avanzadas\n3.4 Vistas e índices',
          actividadesAprendizaje: 'Crear scripts DDL para implementar esquemas\nEjecutar consultas de diversa complejidad\nOptimizar consultas mediante índices',
          actividadesEnsenanza: 'Demostraciones prácticas en laboratorio\nSesiones de resolución de problemas\nRevisión de código SQL',
          competenciasGenericas: ['aplicar_conocimientos', 'trabajo_equipo'],
          horas: 10
        }
      ],
      indicadores: [
        { descripcion: 'Implementa correctamente esquemas relacionales en SQL', valor: 30 },
        { descripcion: 'Construye consultas SQL complejas', valor: 40 },
        { descripcion: 'Aplica técnicas de optimización', valor: 30 }
      ],
      matrizEvaluacion: 'Evaluación mediante proyecto práctico (50%), ejercicios en laboratorio (30%) y participación (20%).'
    }
  ]
  
  // Sección 5: Fuentes y apoyos
  fuentesInformacion.value = '1. Date, C. J. (2019). Introducción a los sistemas de bases de datos. Pearson.\n2. Elmasri, R., & Navathe, S. B. (2015). Fundamentos de sistemas de bases de datos. Pearson.\n3. Coronel, C., & Morris, S. (2019). Sistemas de bases de datos. Cengage Learning.'
  apoyosDidacticos.value = 'Software de modelado (MySQL Workbench, ERwin)\nSistemas gestores de bases de datos (MySQL, PostgreSQL)\nPresentaciones multimedia\nCasos de estudio reales'
  
  // Sección 6: Calendarización
  calendarizacion.value = [
    'ED', 'TP', 'TP', 'TP', 
    'EF', 'TP', 'TP', 'TP', 
    'EF', 'TP', 'TP', 'TP', 
    'ES', 'SD', 'TR', 'TR'
  ]
  
  // Firmas
  profesor.value = 'Dr. Carlos Eduardo Azueta Leon'
  jefeAcademico.value = 'M. en C. Ana López Martínez'
}

// Funciones de acción
const guardarBorrador = () => {
  console.log('Borrador guardado:', {
    departamento: departamento.value,
    periodo: periodo.value,
    // ... otros campos
  })
}

const imprimirFormulario = () => {
  window.print()
}

const enviarFormulario = () => {
  console.log('Formulario enviado:', {
    departamento: departamento.value,
    periodo: periodo.value,
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

th, td {
  padding: 0.5rem;
  border: 1px solid var(--va-background-border);
}

@media (max-width: 768px) {
  .grid-cols-5, .grid-cols-2 {
    grid-template-columns: 1fr;
  }
  
  table {
    display: block;
    overflow-x: auto;
    white-space: nowrap;
  }
}
</style>