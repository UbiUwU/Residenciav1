<template>
  <div class="p-4">
    <VaCard>
      <VaCardTitle class="flex justify-between items-center">
        <h1 class="va-h4">Detalle de Asignatura</h1>
        <VaBadge v-if="asignatura" :text="asignatura.informacionbasica.clave" color="primary" />
      </VaCardTitle>

      <VaCardContent v-if="asignatura">
        <!-- Información básica en cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
          <VaCard>
            <VaCardTitle class="va-h5">Información Básica</VaCardTitle>
            <VaCardContent>
              <VaList>
                <VaListItem>
                  <VaListItemLabel class="font-medium">Nombre:</VaListItemLabel>
                  <VaListItemSection>{{ asignatura.informacionbasica.nombre }}</VaListItemSection>
                </VaListItem>
                <VaListItem>
                  <VaListItemLabel class="font-medium">Créditos:</VaListItemLabel>
                  <VaListItemSection>{{ asignatura.informacionbasica.creditos }}</VaListItemSection>
                </VaListItem>
                <VaListItem>
                  <VaListItemLabel class="font-medium">Horas Teóricas:</VaListItemLabel>
                  <VaListItemSection>{{ asignatura.informacionbasica.satca.teoricas }}</VaListItemSection>
                </VaListItem>
                <VaListItem>
                  <VaListItemLabel class="font-medium">Horas Prácticas:</VaListItemLabel>
                  <VaListItemSection>{{ asignatura.informacionbasica.satca.practicas }}</VaListItemSection>
                </VaListItem>
                <VaListItem>
                  <VaListItemLabel class="font-medium">Total SATCA:</VaListItemLabel>
                  <VaListItemSection>{{ asignatura.informacionbasica.satca.total }}</VaListItemSection>
                </VaListItem>
              </VaList>
            </VaCardContent>
          </VaCard>

          <!-- Carreras asociadas -->
          <VaCard v-if="asignatura.carreras && asignatura.carreras.length">
            <VaCardTitle class="va-h5">Carreras Asociadas</VaCardTitle>
            <VaCardContent>
              <VaList>
                <VaListItem v-for="carrera in asignatura.carreras" :key="carrera.clave">
                  <VaListItemLabel class="font-medium">{{ carrera.clave }}</VaListItemLabel>
                  <VaListItemSection>
                    Semestre {{ carrera.semestre }} (Posición {{ carrera.posicion }})
                  </VaListItemSection>
                </VaListItem>
              </VaList>
            </VaCardContent>
          </VaCard>
        </div>

        <!-- Secciones colapsables mejoradas -->
        <div class="space-y-4">
          <!-- Presentación -->
          <VaCollapse v-if="asignatura.presentacion" v-model="collapseState.presentacion" class="va-card">
            <VaCollapseItem header="Presentación" name="presentacion">
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
            </VaCollapseItem>
          </VaCollapse>

          <!-- Competencias -->
          <VaCollapse v-if="asignatura.competencias?.length" v-model="collapseState.competencias" class="va-card">
            <VaCollapseItem header="Competencias" name="competencias">
              <div class="p-4">
                <VaList>
                  <VaListItem v-for="comp in asignatura.competencias" :key="comp.id" class="mb-2">
                    <VaListItemLabel class="font-medium capitalize">{{ comp.tipo }}:</VaListItemLabel>
                    <VaListItemSection>{{ comp.descripcion }}</VaListItemSection>
                  </VaListItem>
                </VaList>
              </div>
            </VaCollapseItem>
          </VaCollapse>

          <!-- Contenidos -->
          <VaCollapse v-if="asignatura.contenidos?.length" v-model="collapseState.contenidos" class="va-card">
            <VaCollapseItem header="Contenidos Temáticos" name="contenidos">
              <div class="p-4 space-y-4">
                <div v-for="tema in asignatura.contenidos" :key="tema.id" class="border-b pb-4 last:border-b-0">
                  <h4 class="va-h6 font-semibold">{{ tema.numero }}. {{ tema.nombre }}</h4>
                  <div v-if="tema.subtemas?.length" class="mt-2 ml-4">
                    <VaList dense>
                      <VaListItem v-for="sub in tema.subtemas" :key="sub.id" class="py-1">
                        <VaIcon name="chevron_right" size="small" class="mr-2 text-gray-500" />
                        <VaListItemSection>{{ sub.nombre }}</VaListItemSection>
                      </VaListItem>
                    </VaList>
                  </div>
                  <p v-else class="text-gray-500 mt-2">No hay subtemas.</p>
                </div>
              </div>
            </VaCollapseItem>
          </VaCollapse>

          <!-- Actividades -->
          <VaCollapse v-if="asignatura.actividades?.length" v-model="collapseState.actividades" class="va-card">
            <VaCollapseItem header="Actividades de Aprendizaje" name="actividades">
              <div class="p-4 space-y-4">
                <div
                  v-for="actividadTema in asignatura.actividades"
                  :key="actividadTema.tema_id"
                  class="border-b pb-4 last:border-b-0"
                >
                  <h4 class="va-h6 font-semibold mb-3">Tema {{ actividadTema.tema_id }}</h4>
                  <div class="space-y-3">
                    <div v-for="act in actividadTema.actividades" :key="act.id" class="bg-gray-50 p-3 rounded">
                      <p class="font-medium">{{ act.descripcion }}</p>
                      <div v-if="act.competencias?.length" class="mt-2">
                        <VaChip v-for="comp in act.competencias" :key="comp.id" size="small" class="mr-2 mb-2">
                          {{ comp.descripcion }} ({{ comp.tipo }})
                        </VaChip>
                      </div>
                      <p v-else class="text-gray-500 text-sm mt-1">No hay competencias asociadas.</p>
                    </div>
                  </div>
                </div>
              </div>
            </VaCollapseItem>
          </VaCollapse>

          <!-- Prácticas -->
          <VaCollapse v-if="asignatura.practicas?.length" v-model="collapseState.practicas" class="va-card">
            <VaCollapseItem header="Prácticas" name="practicas">
              <div class="p-4">
                <VaList>
                  <VaListItem v-for="pract in asignatura.practicas" :key="pract.id" class="mb-2">
                    <VaIcon name="science" size="small" class="mr-2 text-primary" />
                    <VaListItemSection>{{ pract.descripcion }}</VaListItemSection>
                  </VaListItem>
                </VaList>
              </div>
            </VaCollapseItem>
          </VaCollapse>

          <!-- Proyectos -->
          <VaCollapse v-if="asignatura.proyectos?.length" v-model="collapseState.proyectos" class="va-card">
            <VaCollapseItem header="Proyectos" name="proyectos">
              <div class="p-4">
                <VaList>
                  <VaListItem v-for="proj in asignatura.proyectos" :key="proj.id" class="mb-2">
                    <VaIcon name="assignment" size="small" class="mr-2 text-primary" />
                    <VaListItemSection>{{ proj.descripcion }}</VaListItemSection>
                  </VaListItem>
                </VaList>
              </div>
            </VaCollapseItem>
          </VaCollapse>

          <!-- Evaluación -->
          <VaCollapse v-if="asignatura.evaluacion?.length" v-model="collapseState.evaluacion" class="va-card">
            <VaCollapseItem header="Evaluación" name="evaluacion">
              <div class="p-4">
                <VaList>
                  <VaListItem v-for="(evaluacion, idx) in asignatura.evaluacion" :key="idx" class="mb-2">
                    <VaIcon name="grade" size="small" class="mr-2 text-primary" />
                    <VaListItemSection>{{ evaluacion.criterios }}</VaListItemSection>
                  </VaListItem>
                </VaList>
              </div>
            </VaCollapseItem>
          </VaCollapse>

          <!-- Fuentes -->
          <VaCollapse v-if="asignatura.fuentes?.length" v-model="collapseState.fuentes" class="va-card">
            <VaCollapseItem header="Fuentes de Información" name="fuentes">
              <div class="p-4">
                <VaList>
                  <VaListItem v-for="fuente in asignatura.fuentes" :key="fuente.id" class="mb-2">
                    <VaIcon name="menu_book" size="small" class="mr-2 text-primary" />
                    <VaListItemSection>{{ fuente.referencia }}</VaListItemSection>
                  </VaListItem>
                </VaList>
              </div>
            </VaCollapseItem>
          </VaCollapse>
        </div>
      </VaCardContent>

      <VaCardContent v-else class="flex justify-center items-center py-8">
        <div class="text-center">
          <VaSpinner size="large" />
          <p class="mt-4">Cargando información de la asignatura...</p>
        </div>
      </VaCardContent>
    </VaCard>
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
  fuentes: false,
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
