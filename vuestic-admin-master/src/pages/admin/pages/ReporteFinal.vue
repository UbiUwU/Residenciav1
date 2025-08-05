<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useToast } from 'vuestic-ui'
import api from '../../../services/api'

const route = useRoute()
const reporte = ref([])
const loadingReporte = ref(false)
const toast = useToast()

const tarjeta = route.params.tarjeta

const fetchAsignaturas = async () => {
  try {
    loadingReporte.value = true
    const response = await api.getDetalleGruposPorCarreraByTarjeta(tarjeta)
    console.log('DATA RECIBIDA:', response.data)

    reporte.value = response.data.map((asignatura) => {
      asignatura.aulas_grupos_periodos.forEach((agp) => {
        agp.carreras.forEach((carrera) => {
          const alumnos = carrera.alumnos || []

          const total = alumnos.length
          let ordinarios = 0,
            complementarios = 0,
            noAcreditados = 0,
            desertores = 0,
            sumaCalif = 0,
            conCalif = 0

          alumnos.forEach((alumno) => {
            const calificaciones = alumno.calificaciones || []

            if (calificaciones.length === 0) {
              desertores++
              return
            }

            let evaluado = false
            let aprobado = false

            for (const cal of calificaciones) {
              if (!cal || typeof cal.calificacion !== 'number') continue

              if (cal.tipo_evaluacion === 'Ordinario') {
                evaluado = true
                if (cal.calificacion >= 70) {
                  ordinarios++
                  aprobado = true
                }
                break
              }
            }

            if (!evaluado) {
              for (const cal of calificaciones) {
                if (cal.tipo_evaluacion === 'Complementario') {
                  evaluado = true
                  if (cal.calificacion >= 70) {
                    complementarios++
                    aprobado = true
                  }
                  break
                }
              }
            }

            if (evaluado && !aprobado) {
              noAcreditados++
            }

            for (const cal of calificaciones) {
              if (typeof cal.calificacion === 'number') {
                sumaCalif += cal.calificacion
                conCalif++
              }
            }
          })

          const acreditados = ordinarios + complementarios

          carrera.A = total
          carrera.B_O = ordinarios
          carrera.B_Co = complementarios
          carrera.C = total ? ((acreditados / total) * 100).toFixed(2) + '%' : '0.00%'
          carrera.D = noAcreditados
          carrera.E = total ? ((noAcreditados / total) * 100).toFixed(2) + '%' : '0.00%'
          carrera.F = desertores
          carrera.G = total ? ((desertores / total) * 100).toFixed(2) + '%' : '0.00%'
          carrera.H = conCalif ? (sumaCalif / conCalif).toFixed(2) : '0.00'
        })
      })
      return asignatura
    })
  } catch (error) {
    toast.notify({ message: `Error: ${error.message || 'Error al obtener reporte'}`, color: 'danger' })
  } finally {
    loadingReporte.value = false
  }
}

const totalFilasPorAsignatura = (asignatura) => {
  return asignatura.aulas_grupos_periodos.reduce((total, agp) => total + agp.carreras.length, 0)
}

const handleRegresar = () => {
  window.history.back()
}

onMounted(() => {
  fetchAsignaturas()
})

const generarReportePDF = () => {
  const pdfBaseUrl = import.meta.env.VITE_PDF_BASE_URL || 'http://localhost/RESIDENCIAV1/PlugginPDF2'
  const url = `${pdfBaseUrl}/download.php?tarjeta=${tarjeta}&tipo=reporte_final`
  window.open(url, '_blank')
}
</script>

<template>
  <div class="boton-regresar">
    <button @click="handleRegresar">← Regresar</button>
  </div>

  <div class="boton-generar-pdf">
    <button @click="generarReportePDF">Generar Reporte PDF</button>
  </div>

  <div v-if="loadingReporte" class="loading-container">Cargando reporte...</div>

  <div v-if="reporte.length" class="reporte-container">
    <h1 class="va-h4 mb-4">Reporte final</h1>

    <div v-for="(asignatura, index) in reporte" :key="index" class="asignatura-card">
      <h3>{{ asignatura.informacionbasica.nombre }} ({{ asignatura.informacionbasica.clave }})</h3>
      <p><strong>Créditos:</strong> {{ asignatura.informacionbasica.creditos }}</p>
      <p><strong>Nombre del maestro:</strong> {{ asignatura.informacionbasica.maestro || 'Juan Pérez' }}</p>
      <p><strong>Departamento:</strong> {{ asignatura.informacionbasica.departamento || 'Desconocido' }}</p>
    </div>

    <div class="carreras-table">
      <table>
        <thead>
          <tr>
            <th rowspan="2">Asignatura</th>
            <th rowspan="2">Carrera</th>
            <th rowspan="2">Grupo</th>
            <th rowspan="2">A</th>
            <th colspan="2" style="text-align: center">B</th>
            <th rowspan="2">C</th>
            <th rowspan="2">D</th>
            <th rowspan="2">E</th>
            <th rowspan="2">F</th>
            <th rowspan="2">G</th>
            <th rowspan="2">H</th>
          </tr>
          <tr>
            <th>O</th>
            <th>Co</th>
          </tr>
        </thead>

        <tbody>
          <template v-for="(asignatura, asignaturaIndex) in reporte" :key="'asignatura-' + asignaturaIndex">
            <template v-if="asignatura.aulas_grupos_periodos && asignatura.aulas_grupos_periodos.length > 0">
              <template v-for="(agp, agpIndex) in asignatura.aulas_grupos_periodos" :key="'agp-' + agpIndex">
                <template v-for="(carrera, carreraIndex) in agp.carreras" :key="'carrera-' + carreraIndex">
                  <tr>
                    <td v-if="agpIndex === 0 && carreraIndex === 0" :rowspan="totalFilasPorAsignatura(asignatura)">
                      {{ asignatura.informacionbasica.nombre }}
                    </td>
                    <td>{{ carrera.nombre_carrera }}</td>
                    <td>{{ agp.grupo || 'Sin grupo' }}</td>
                    <td>{{ carrera.A || 0 }}</td>
                    <td>{{ carrera.B_O || 0 }}</td>
                    <td>{{ carrera.B_Co || 0 }}</td>
                    <td>{{ carrera.C || '0.00%' }}</td>
                    <td>{{ carrera.D || 0 }}</td>
                    <td>{{ carrera.E || '0.00%' }}</td>
                    <td>{{ carrera.F || 0 }}</td>
                    <td>{{ carrera.G || '0.00%' }}</td>
                    <td>{{ carrera.H || '0.00' }}</td>

                    <td>{{ carrera.C || 0 }}</td>
                    <td>{{ carrera.D || 0 }}</td>
                    <td>{{ carrera.E || 0 }}</td>
                    <td>{{ carrera.F || 0 }}</td>
                    <td>{{ carrera.G || 0 }}</td>
                    <td>{{ carrera.H || 0 }}</td>
                  </tr>
                </template>
              </template>
            </template>
            <template v-else>
              <tr>
                <td>{{ asignatura.informacionbasica.nombre }}</td>
                <td colspan="11" style="text-align: center">No hay datos para esta asignatura</td>
              </tr>
            </template>
          </template>
        </tbody>
      </table>
    </div>

    <div class="leyenda">
      <h3>Leyenda:</h3>
      <ul>
        <li><strong>A</strong> = TOTAL DE ESTUDIANTES POR MATERIA/GRUPO</li>
        <li><strong>B_O</strong> = No. DE ESTUDIANTES ACREDITADOS EN ORDINARIO</li>
        <li><strong>B_Co</strong> = No. DE ESTUDIANTES ACREDITADOS EN COMPLEMENTARIO</li>
        <li><strong>C</strong> = % DE ESTUDIANTES ACREDITADOS</li>
        <li><strong>D</strong> = No. DE ESTUDIANTES NO ACREDITADOS</li>
        <li><strong>E</strong> = % DE ESTUDIANTES NO ACREDITADOS</li>
        <li><strong>F</strong> = No. DE ESTUDIANTES QUE DESERTARON DURANTE EL SEMESTRE EN LA MATERIA</li>
        <li><strong>G</strong> = % DE ESTUDIANTES QUE DESERTARON EN LA MATERIA</li>
        <li><strong>H</strong> = PROMEDIO GENERAL DE LA MATERIA/GRUPO</li>
      </ul>
      =======
      <div class="leyenda">
        <h3>Leyenda:</h3>
        <ul>
          <li><strong>A</strong> = TOTAL DE ESTUDIANTES POR MATERIA/GRUPO</li>
          <li><strong>B_O</strong> = No. DE ESTUDIANTES ACREDITADOS EN ORDINARIO</li>
          <li><strong>B_Co</strong> = No. DE ESTUDIANTES ACREDITADOS EN COMPLEMENTARIO</li>
          <li><strong>C</strong> = % DE ESTUDIANTES ACREDITADOS</li>
          <li><strong>D</strong> = No. DE ESTUDIANTES NO ACREDITADOS</li>
          <li><strong>E</strong> = % DE ESTUDIANTES NO ACREDITADOS</li>
          <li><strong>F</strong> = No. DE ESTUDIANTES QUE DESERTARON DURANTE EL SEMESTRE EN LA MATERIA</li>
          <li><strong>G</strong> = % DE ESTUDIANTES QUE DESERTARON EN LA MATERIA</li>
          <li><strong>H</strong> = PROMEDIO GENERAL DE LA MATERIA/GRUPO</li>
        </ul>
      </div>
      >>>>>>> e98679297c524364d4bb0bff7b23864b0d42dfdb
    </div>

    <div class="alumnos-list-container">
      <h5>Alumnos por Carrera:</h5>
      <template v-for="(asignatura, index) in reporte" :key="'alumnos-asignatura-' + index">
        <div class="asignatura-section">
          <h4>{{ asignatura.informacionbasica.nombre }} ({{ asignatura.informacionbasica.clave }})</h4>
          <template v-for="(agp, agpIndex) in asignatura.aulas_grupos_periodos" :key="'alumnos-agp-' + agpIndex">
            <template v-for="(carrera, carreraIndex) in agp.carreras" :key="'alumnos-carrera-' + carreraIndex">
              <div class="carrera-section">
                <h5>{{ carrera.nombre_carrera }}</h5>
                <div class="alumnos-grid">
                  <div
                    v-for="(alumno, alumnoIndex) in carrera.alumnos"
                    :key="'alumno-' + alumnoIndex"
                    class="alumno-card"
                  >
                    <div class="alumno-header">
                      <span class="badge numero-control">{{ alumno.numero_control }}</span>
                      <span class="alumno-nombre">{{ alumno.nombre_completo }}</span>
                    </div>
                    <div class="alumno-calificaciones">
                      <span class="label">Calificaciones:</span>
                      <span class="calificacion">
                        {{
                          alumno.calificaciones && alumno.calificaciones.length
                            ? alumno.calificaciones.map((c) => c.calificacion).join(', ')
                            : 'Sin calificaciones'
                        }}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </template>
          </template>
        </div>
      </template>
    </div>
  </div>

  <div v-if="!loadingReporte && !reporte.length" class="empty-container">No hay datos para mostrar.</div>
</template>

<style scoped>
/* Estilos (igual que en tu versión original) */
</style>
