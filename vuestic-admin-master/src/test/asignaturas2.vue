<template>
  <div class="p-6 max-w-4xl mx-auto">
    <va-card>
      <va-card-title>
        <h2 class="va-h2">Detalle Completo de Asignatura</h2>
      </va-card-title>

      <va-card-content v-if="asignatura">
        <!-- Información básica -->
        <section class="mb-6">
          <h3 class="va-h4 mb-2">Información Básica</h3>
          <p><strong>Clave:</strong> {{ asignatura.informacionbasica.clave }}</p>
          <p><strong>Nombre:</strong> {{ asignatura.informacionbasica.nombre }}</p>
          <p><strong>Créditos:</strong> {{ asignatura.informacionbasica.creditos }}</p>
          <p><strong>Horas Teóricas:</strong> {{ asignatura.informacionbasica.satca.teoricas }}</p>
          <p><strong>Horas Prácticas:</strong> {{ asignatura.informacionbasica.satca.practicas }}</p>
          <p><strong>Total SATCA:</strong> {{ asignatura.informacionbasica.satca.total }}</p>
        </section>

        <!-- Carreras -->
        <section v-if="asignatura.carreras && asignatura.carreras.length" class="mb-6">
          <h3 class="va-h4 mb-2">Carreras Asociadas</h3>
          <ul>
            <li v-for="carrera in asignatura.carreras" :key="carrera.clave">
              <strong>{{ carrera.clave }}</strong> — Semestre: {{ carrera.semestre }}, Posición: {{ carrera.posicion }}
            </li>
          </ul>
        </section>

        <!-- Presentación -->
        <va-collapse v-if="asignatura.presentacion" class="mb-6">
          <va-collapse-item title="Presentación" name="presentacion">
            <p><strong>Caracterización:</strong> {{ asignatura.presentacion.caracterizacion || 'No disponible' }}</p>
            <p><strong>Intención Didáctica:</strong> {{ asignatura.presentacion.intencion_didactica || 'No disponible' }}</p>
          </va-collapse-item>
        </va-collapse>

        <!-- Competencias -->
        <va-collapse v-if="asignatura.competencias && asignatura.competencias.length" class="mb-6">
          <va-collapse-item title="Competencias" name="competencias">
            <ul>
              <li v-for="comp in asignatura.competencias" :key="comp.id">
                <strong>{{ comp.tipo }}:</strong> {{ comp.descripcion }}
              </li>
            </ul>
          </va-collapse-item>
        </va-collapse>

        <!-- Contenidos -->
        <va-collapse v-if="asignatura.contenidos && asignatura.contenidos.length" class="mb-6">
          <va-collapse-item title="Contenidos Temáticos" name="contenidos">
            <div v-for="tema in asignatura.contenidos" :key="tema.id" class="mb-4">
              <h4 class="va-h5">{{ tema.numero }}. {{ tema.nombre }}</h4>
              <ul v-if="tema.subtemas && tema.subtemas.length">
                <li v-for="sub in tema.subtemas" :key="sub.id">{{ sub.nombre }}</li>
              </ul>
              <p v-else class="text-gray-500">No hay subtemas.</p>
            </div>
          </va-collapse-item>
        </va-collapse>

        <!-- Actividades -->
        <va-collapse v-if="asignatura.actividades && asignatura.actividades.length" class="mb-6">
          <va-collapse-item title="Actividades de Aprendizaje" name="actividades">
            <div v-for="actividadTema in asignatura.actividades" :key="actividadTema.tema_id" class="mb-4">
              <h4 class="va-h5">Tema ID: {{ actividadTema.tema_id }}</h4>
              <ul>
                <li v-for="act in actividadTema.actividades" :key="act.id" class="mb-2">
                  <p><strong>Actividad:</strong> {{ act.descripcion }}</p>
                  <ul v-if="act.competencias && act.competencias.length" class="ml-4 list-disc list-inside">
                    <li v-for="comp in act.competencias" :key="comp.id">{{ comp.descripcion }}</li>
                  </ul>
                  <p v-else class="text-gray-500 ml-4">No hay competencias asociadas.</p>
                </li>
              </ul>
            </div>
          </va-collapse-item>
        </va-collapse>

        <!-- Prácticas -->
        <va-collapse v-if="asignatura.practicas && asignatura.practicas.length" class="mb-6">
          <va-collapse-item title="Prácticas" name="practicas">
            <ul>
              <li v-for="pract in asignatura.practicas" :key="pract.id">{{ pract.descripcion }}</li>
            </ul>
          </va-collapse-item>
        </va-collapse>

        <!-- Proyectos -->
        <va-collapse v-if="asignatura.proyectos && asignatura.proyectos.length" class="mb-6">
          <va-collapse-item title="Proyectos" name="proyectos">
            <ul>
              <li v-for="proj in asignatura.proyectos" :key="proj.id">{{ proj.descripcion }}</li>
            </ul>
          </va-collapse-item>
        </va-collapse>

        <!-- Evaluación -->
        <va-collapse v-if="asignatura.evaluacion && asignatura.evaluacion.length" class="mb-6">
          <va-collapse-item title="Evaluación" name="evaluacion">
            <ul>
              <li v-for="evaluacion in asignatura.evaluacion" :key="evaluacion.id">{{ evaluacion.criterios }}</li>
            </ul>
          </va-collapse-item>
        </va-collapse>

        <!-- Fuentes -->
        <va-collapse v-if="asignatura.fuentes && asignatura.fuentes.length" class="mb-6">
          <va-collapse-item title="Fuentes de Información" name="fuentes">
            <ul>
              <li v-for="fuente in asignatura.fuentes" :key="fuente.id">{{ fuente.referencia }}</li>
            </ul>
          </va-collapse-item>
        </va-collapse>
      </va-card-content>

      <va-card-content v-else>
        <va-spinner size="large" /> Cargando asignatura...
      </va-card-content>
    </va-card>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import api from '../services/api'

const route = useRoute()
const clave = route.params.clave as string
const asignatura = ref<any>(null)

const cargarAsignatura = async () => {
  try {
    const response = await api.getAsignaturaCompleta(clave)
    asignatura.value = response.data
  } catch (error) {
    console.error('Error al cargar la asignatura completa:', error)
  }
}

onMounted(() => {
  cargarAsignatura()
})
</script>

<style scoped>
/* Ajustes para mejor espaciado y legibilidad */
.mb-2 {
  margin-bottom: 0.5rem;
}
.mb-4 {
  margin-bottom: 1rem;
}
.mb-6 {
  margin-bottom: 1.5rem;
}
.text-gray-500 {
  color: #6b7280;
}
</style>
