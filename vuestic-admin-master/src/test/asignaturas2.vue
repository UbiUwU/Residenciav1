<template>
  <div class="p-4">
    <va-card>
      <va-card-title class="flex justify-between items-center">
        <h1 class="va-h4">Detalle de Asignatura</h1>
        <va-badge v-if="asignatura" :text="asignatura.informacionbasica.clave" color="primary" />
      </va-card-title>

      <va-card-content v-if="asignatura">
        <!-- Información básica en cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
          <va-card>
            <va-card-title class="va-h5">Información Básica</va-card-title>
            <va-card-content>
              <va-list>
                <va-list-item>
                  <va-list-item-label class="font-medium">Nombre:</va-list-item-label>
                  <va-list-item-section>{{ asignatura.informacionbasica.nombre }}</va-list-item-section>
                </va-list-item>
                <va-list-item>
                  <va-list-item-label class="font-medium">Créditos:</va-list-item-label>
                  <va-list-item-section>{{ asignatura.informacionbasica.creditos }}</va-list-item-section>
                </va-list-item>
                <va-list-item>
                  <va-list-item-label class="font-medium">Horas Teóricas:</va-list-item-label>
                  <va-list-item-section>{{ asignatura.informacionbasica.satca.teoricas }}</va-list-item-section>
                </va-list-item>
                <va-list-item>
                  <va-list-item-label class="font-medium">Horas Prácticas:</va-list-item-label>
                  <va-list-item-section>{{ asignatura.informacionbasica.satca.practicas }}</va-list-item-section>
                </va-list-item>
                <va-list-item>
                  <va-list-item-label class="font-medium">Total SATCA:</va-list-item-label>
                  <va-list-item-section>{{ asignatura.informacionbasica.satca.total }}</va-list-item-section>
                </va-list-item>
              </va-list>
            </va-card-content>
          </va-card>

          <!-- Carreras asociadas -->
          <va-card v-if="asignatura.carreras && asignatura.carreras.length">
            <va-card-title class="va-h5">Carreras Asociadas</va-card-title>
            <va-card-content>
              <va-list>
                <va-list-item v-for="carrera in asignatura.carreras" :key="carrera.clave">
                  <va-list-item-label class="font-medium">{{ carrera.clave }}</va-list-item-label>
                  <va-list-item-section>
                    Semestre {{ carrera.semestre }} (Posición {{ carrera.posicion }})
                  </va-list-item-section>
                </va-list-item>
              </va-list>
            </va-card-content>
          </va-card>
        </div>

        <!-- Secciones colapsables mejoradas -->
        <div class="space-y-4">
          <!-- Presentación -->
          <va-collapse v-if="asignatura.presentacion" v-model="collapseState.presentacion" class="va-card">
            <va-collapse-item header="Presentación" name="presentacion">
              <div class="p-4 space-y-4">
                <div>
                  <h4 class="va-h6 font-semibold mb-2">Caracterización</h4>
                  <p class="text-gray-700">{{ asignatura.presentacion.caracterizacion || 'No disponible' }}</p>
                </div>
                <div>
                  <h4 class="va-h6 font-semibold mb-2">Intención Didáctica</h4>
                  <p class="text-gray-700">{{ asignatura.presentacion.intencion_didactica || 'No disponible' }}</p>
                </div>
              </div>
            </va-collapse-item>
          </va-collapse>

          <!-- Competencias -->
          <va-collapse v-if="asignatura.competencias?.length" v-model="collapseState.competencias" class="va-card">
            <va-collapse-item header="Competencias" name="competencias">
              <div class="p-4">
                <va-list>
                  <va-list-item v-for="comp in asignatura.competencias" :key="comp.id" class="mb-2">
                    <va-list-item-label class="font-medium capitalize">{{ comp.tipo }}:</va-list-item-label>
                    <va-list-item-section>{{ comp.descripcion }}</va-list-item-section>
                  </va-list-item>
                </va-list>
              </div>
            </va-collapse-item>
          </va-collapse>

          <!-- Contenidos -->
          <va-collapse v-if="asignatura.contenidos?.length" v-model="collapseState.contenidos" class="va-card">
            <va-collapse-item header="Contenidos Temáticos" name="contenidos">
              <div class="p-4 space-y-4">
                <div v-for="tema in asignatura.contenidos" :key="tema.id" class="border-b pb-4 last:border-b-0">
                  <h4 class="va-h6 font-semibold">{{ tema.numero }}. {{ tema.nombre }}</h4>
                  <div v-if="tema.subtemas?.length" class="mt-2 ml-4">
                    <va-list dense>
                      <va-list-item v-for="sub in tema.subtemas" :key="sub.id" class="py-1">
                        <va-icon name="chevron_right" size="small" class="mr-2 text-gray-500" />
                        <va-list-item-section>{{ sub.nombre }}</va-list-item-section>
                      </va-list-item>
                    </va-list>
                  </div>
                  <p v-else class="text-gray-500 mt-2">No hay subtemas.</p>
                </div>
              </div>
            </va-collapse-item>
          </va-collapse>

          <!-- Actividades -->
          <va-collapse v-if="asignatura.actividades?.length" v-model="collapseState.actividades" class="va-card">
            <va-collapse-item header="Actividades de Aprendizaje" name="actividades">
              <div class="p-4 space-y-4">
                <div v-for="actividadTema in asignatura.actividades" :key="actividadTema.tema_id" class="border-b pb-4 last:border-b-0">
                  <h4 class="va-h6 font-semibold mb-3">Tema {{ actividadTema.tema_id }}</h4>
                  <div class="space-y-3">
                    <div v-for="act in actividadTema.actividades" :key="act.id" class="bg-gray-50 p-3 rounded">
                      <p class="font-medium">{{ act.descripcion }}</p>
                      <div v-if="act.competencias?.length" class="mt-2">
                        <va-chip v-for="comp in act.competencias" :key="comp.id" size="small" class="mr-2 mb-2">
                          {{ comp.descripcion }} ({{ comp.tipo }})
                        </va-chip>
                      </div>
                      <p v-else class="text-gray-500 text-sm mt-1">No hay competencias asociadas.</p>
                    </div>
                  </div>
                </div>
              </div>
            </va-collapse-item>
          </va-collapse>

          <!-- Prácticas -->
          <va-collapse v-if="asignatura.practicas?.length" v-model="collapseState.practicas" class="va-card">
            <va-collapse-item header="Prácticas" name="practicas">
              <div class="p-4">
                <va-list>
                  <va-list-item v-for="pract in asignatura.practicas" :key="pract.id" class="mb-2">
                    <va-icon name="science" size="small" class="mr-2 text-primary" />
                    <va-list-item-section>{{ pract.descripcion }}</va-list-item-section>
                  </va-list-item>
                </va-list>
              </div>
            </va-collapse-item>
          </va-collapse>

          <!-- Proyectos -->
          <va-collapse v-if="asignatura.proyectos?.length" v-model="collapseState.proyectos" class="va-card">
            <va-collapse-item header="Proyectos" name="proyectos">
              <div class="p-4">
                <va-list>
                  <va-list-item v-for="proj in asignatura.proyectos" :key="proj.id" class="mb-2">
                    <va-icon name="assignment" size="small" class="mr-2 text-primary" />
                    <va-list-item-section>{{ proj.descripcion }}</va-list-item-section>
                  </va-list-item>
                </va-list>
              </div>
            </va-collapse-item>
          </va-collapse>

          <!-- Evaluación -->
          <va-collapse v-if="asignatura.evaluacion?.length" v-model="collapseState.evaluacion" class="va-card">
            <va-collapse-item header="Evaluación" name="evaluacion">
              <div class="p-4">
                <va-list>
                  <va-list-item v-for="(evaluacion, idx) in asignatura.evaluacion" :key="idx" class="mb-2">
                    <va-icon name="grade" size="small" class="mr-2 text-primary" />
                    <va-list-item-section>{{ evaluacion.criterios }}</va-list-item-section>
                  </va-list-item>
                </va-list>
              </div>
            </va-collapse-item>
          </va-collapse>

          <!-- Fuentes -->
          <va-collapse v-if="asignatura.fuentes?.length" v-model="collapseState.fuentes" class="va-card">
            <va-collapse-item header="Fuentes de Información" name="fuentes">
              <div class="p-4">
                <va-list>
                  <va-list-item v-for="fuente in asignatura.fuentes" :key="fuente.id" class="mb-2">
                    <va-icon name="menu_book" size="small" class="mr-2 text-primary" />
                    <va-list-item-section>{{ fuente.referencia }}</va-list-item-section>
                  </va-list-item>
                </va-list>
              </div>
            </va-collapse-item>
          </va-collapse>
        </div>
      </va-card-content>

      <va-card-content v-else class="flex justify-center items-center py-8">
        <div class="text-center">
          <va-spinner size="large" />
          <p class="mt-4">Cargando información de la asignatura...</p>
        </div>
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

// Estado de los collapse items
const collapseState = ref({
  presentacion: false,
  competencias: false,
  contenidos: false,
  actividades: false,
  practicas: false,
  proyectos: false,
  evaluacion: false,
  fuentes: false
})

const cargarAsignatura = async () => {
  try {
    const response = await api.getAsignaturaCompleta(clave)
    asignatura.value = response.data
    
    // Abrir automáticamente las secciones más importantes
    collapseState.value.presentacion = true
    collapseState.value.competencias = true
    collapseState.value.contenidos = true
  } catch (error) {
    console.error('Error al cargar la asignatura completa:', error)
  }
}

onMounted(() => {
  cargarAsignatura()
})
</script>

<style scoped>
.va-card {
  border-radius: 0.5rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.va-collapse {
  border: none;
  box-shadow: none;
}

.va-collapse-item__header {
  font-weight: 600;
  padding: 0.75rem 1rem;
}

.va-list-item {
  padding: 0.5rem 0;
}
</style>