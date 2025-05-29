<template>
  <!-- Título de la vista -->
  <h1 class="va-h4 mb-4">Formulario de Avance Programático</h1>
  <div class="avance-programatico">
    <!-- Sección 1: Caracterización -->
    <va-card class="mt-4">
      <va-card-title class="flex items-center">
        <va-icon name="description" class="mr-2" />
        <span>1. Caracterización de la asignatura</span>
      </va-card-title>
      <va-card-content>
        <va-input
          v-model="caracterizacion"
          type="textarea"
          label="Caracterización de la asignatura"
          placeholder="Ingrese la caracterización aquí..."
          autosize
          class="mb-4"
        />
      </va-card-content>
    </va-card>

    <!-- Sección 2: Intención didáctica -->
    <va-card class="mt-4">
      <va-card-title class="flex items-center">
        <va-icon name="school" class="mr-2" />
        <span>2. Intención didáctica</span>
      </va-card-title>
      <va-card-content>
        <va-input
          v-model="intencionDidactica"
          type="textarea"
          label="Intención didáctica"
          placeholder="Ingrese la intención didáctica aquí..."
          autosize
          class="mb-4"
        />
      </va-card-content>
    </va-card>

    <!-- Sección 3: Competencia -->
    <va-card class="mt-4">
      <va-card-title class="flex items-center">
        <va-icon name="workspace_premium" class="mr-2" />
        <span>3. Competencia de la asignatura</span>
      </va-card-title>
      <va-card-content>
        <va-input
          v-model="competenciaAsignatura"
          type="textarea"
          label="Competencia de la asignatura"
          placeholder="Ingrese la competencia aquí..."
          autosize
          class="mb-4"
        />
      </va-card-content>
    </va-card>

    <!-- Sección 4: Competencias específicas -->
    <va-card class="mt-4">
      <va-card-title class="flex items-center">
        <va-icon name="analytics" class="mr-2" />
        <span>4. Análisis por competencias específicas</span>
      </va-card-title>
      <va-card-content>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <va-select v-model="competenciaSeleccionada" :options="competencias" label="Seleccione una competencia" />
          <va-input
            v-model="descripcionCompetencia"
            label="Descripción de la competencia"
            placeholder="Ingrese la descripción"
          />
        </div>

        <va-card class="mb-4">
          <va-card-content>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <va-input
                v-model="temasSubtemas"
                type="textarea"
                label="Temas y subtemas"
                placeholder="Ingrese temas y subtemas"
                autosize
              />
              <va-input
                v-model="actividadesAprendizaje"
                type="textarea"
                label="Actividades de aprendizaje"
                placeholder="Ingrese actividades"
                autosize
              />
              <va-input
                v-model="actividadesEnsenanza"
                type="textarea"
                label="Actividades de enseñanza"
                placeholder="Ingrese actividades"
                autosize
              />
            </div>
          </va-card-content>
        </va-card>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <va-input
            v-model="desarrolloCompetencias"
            type="textarea"
            label="Desarrollo de competencias genéricas"
            placeholder="Ingrese el desarrollo"
            autosize
          />
          <va-input
            v-model="horasPracticasTeoricas"
            type="textarea"
            label="Horas-Prácticas-Teóricas"
            placeholder="Ingrese las horas"
            autosize
          />
        </div>
      </va-card-content>
    </va-card>

    <!-- Sección 5: Fuentes de información -->
    <va-card class="mt-4">
      <va-card-title class="flex items-center">
        <va-icon name="menu_book" class="mr-2" />
        <span>5. Fuentes de información y apoyos didácticos</span>
      </va-card-title>
      <va-card-content>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <va-input
            v-model="fuentesInformacion"
            type="textarea"
            label="Fuentes de información"
            placeholder="Ingrese fuentes de información"
            autosize
          />
          <va-input
            v-model="apoyosDidacticos"
            type="textarea"
            label="Apoyos didácticos"
            placeholder="Ingrese apoyos didácticos"
            autosize
          />
        </div>
      </va-card-content>
    </va-card>

    <!-- Sección 6: Calendarización -->
    <va-card class="mt-4">
      <va-card-title class="flex items-center">
        <va-icon name="calendar_today" class="mr-2" />
        <span>6. Calendarización de evaluación en semanas</span>
      </va-card-title>
      <va-card-content>
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr>
                <th class="text-left p-2">Semanas</th>
                <th v-for="semana in semanas" :key="semana" class="text-center p-2">
                  {{ semana }}
                </th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="font-semibold p-2">TP</td>
                <td v-for="(semana, index) in semanas" :key="`TP-${index}`" class="p-2">
                  <va-input v-model="tpSemana[index]" :placeholder="`TP${semana}`" class="w-full" />
                </td>
              </tr>
              <tr>
                <td class="font-semibold p-2">TR</td>
                <td v-for="(semana, index) in semanas" :key="`TR-${index}`" class="p-2">
                  <va-input v-model="trSemana[index]" :placeholder="`TR${semana}`" class="w-full" />
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </va-card-content>
    </va-card>

    <!-- Botones de acción -->
    <div class="flex justify-end gap-4 mt-6">
      <va-button preset="secondary" @click="guardarFormulario">
        <va-icon name="save" class="mr-2" />
        Guardar
      </va-button>
      <va-button @click="enviarFormulario">
        <va-icon name="send" class="mr-2" />
        Enviar
      </va-button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

// Datos del formulario
const caracterizacion = ref('')
const intencionDidactica = ref('')
const competenciaAsignatura = ref('')
const competencias = ref(['Competencia 1', 'Competencia 2', 'Competencia 3'])
const competenciaSeleccionada = ref(competencias.value[0])
const descripcionCompetencia = ref('')
const temasSubtemas = ref('')
const actividadesAprendizaje = ref('')
const actividadesEnsenanza = ref('')
const desarrolloCompetencias = ref('')
const horasPracticasTeoricas = ref('')
const fuentesInformacion = ref('')
const apoyosDidacticos = ref('')
const semanas = ref(Array.from({ length: 17 }, (_, i) => i + 1))
const tpSemana = ref(Array(17).fill(''))
const trSemana = ref(Array(17).fill(''))

// Funciones para guardar y enviar
const guardarFormulario = () => {
  console.log('Formulario guardado:', {
    caracterizacion: caracterizacion.value,
    intencionDidactica: intencionDidactica.value,
    competenciaAsignatura: competenciaAsignatura.value,
    competenciaSeleccionada: competenciaSeleccionada.value,
    descripcionCompetencia: descripcionCompetencia.value,
    temasSubtemas: temasSubtemas.value,
    actividadesAprendizaje: actividadesAprendizaje.value,
    actividadesEnsenanza: actividadesEnsenanza.value,
    desarrolloCompetencias: desarrolloCompetencias.value,
    horasPracticasTeoricas: horasPracticasTeoricas.value,
    fuentesInformacion: fuentesInformacion.value,
    apoyosDidacticos: apoyosDidacticos.value,
    tpSemana: tpSemana.value,
    trSemana: trSemana.value,
  })

  // Aquí iría la lógica real de guardado
}

const enviarFormulario = () => {
  console.log('Formulario enviado:', {
    caracterizacion: caracterizacion.value,
    intencionDidactica: intencionDidactica.value,
    competenciaAsignatura: competenciaAsignatura.value,
    competenciaSeleccionada: competenciaSeleccionada.value,
    descripcionCompetencia: descripcionCompetencia.value,
    temasSubtemas: temasSubtemas.value,
    actividadesAprendizaje: actividadesAprendizaje.value,
    actividadesEnsenanza: actividadesEnsenanza.value,
    desarrolloCompetencias: desarrolloCompetencias.value,
    horasPracticasTeoricas: horasPracticasTeoricas.value,
    fuentesInformacion: fuentesInformacion.value,
    apoyosDidacticos: apoyosDidacticos.value,
    tpSemana: tpSemana.value,
    trSemana: trSemana.value,
  })

  // Aquí iría la lógica real de envío
}
</script>

<style scoped>
.avance-programatico {
  padding: 1rem;
  max-width: 1400px;
  margin: 0 auto;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th,
td {
  border: 1px solid var(--va-background-border);
  padding: 0.5rem;
  text-align: center;
}

th {
  background-color: var(--va-background-element);
  font-weight: 600;
}

@media (max-width: 768px) {
  .grid-cols-3,
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
