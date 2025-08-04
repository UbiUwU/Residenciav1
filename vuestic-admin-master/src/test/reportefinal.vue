<!-- Componente Vue completo: ReporteFinal.vue -->
<script setup>
import { ref } from 'vue'

/**
 * Datos estáticos de ejemplo. Puedes editar directamente este arreglo.
 * Solo se usan para mostrar las secciones de “asignaturas” y “carreras”.
 */
const reporte = ref([
  {
    informacionbasica: {
      nombre: 'Matemáticas Discretas',
      clave: 'MAT-201',
      creditos: 4,
      maestro: 'Dr. Ana González',
      departamento: 'Matemáticas',
    },
    aulas_grupos_periodos: [
      {
        carreras: [
          {
            nombre_carrera: 'Ingeniería en Sistemas',
            A: 15,
            C: 5,
            D: 2,
            E: 1,
            F: 0,
            G: 0,
            H: 0,
            O: 3,
            Co: 1,
          },
          {
            nombre_carrera: 'Licenciatura en Matemáticas',
            A: 8,
            C: 2,
            D: 1,
            E: 0,
            F: 0,
            G: 0,
            H: 0,
            O: 0,
            Co: 0,
          },
        ],
      },
    ],
  },
])

/**
 * Función que envía el JSON al backend y recibe un PDF
 */
const generarReportePDF = async () => {
  try {
    // URL configurable desde variables de entorno
    const pdfBaseUrl = import.meta.env.VITE_PDF_BASE_URL || 'http://localhost/Inicio%20de%20sesion/PlugginPDF2'
    const response = await fetch(`${pdfBaseUrl}/download2.php`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        tipo: 'reporte_final',
        datos: reporte.value, // Asegúrate que reporte.value tenga la estructura adecuada
      }),
    })

    if (!response.ok) {
      throw new Error('Error al generar el documento PDF')
    }

    // Descargar el archivo PDF generado
    const blob = await response.blob()
    const url = window.URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = 'reporte_final.pdf'
    document.body.appendChild(a)
    a.click()
    a.remove()
    window.URL.revokeObjectURL(url)
  } catch (error) {
    console.error('Error generando reporte PDF:', error)
    alert('Hubo un problema al generar el PDF. Verifica la consola del navegador.')
  }
}

const handleRegresar = () => {
  window.history.back()
}
</script>

<template>
  <div class="boton-regresar">
    <button @click="handleRegresar">← Regresar</button>
  </div>

  <div class="boton-generar-pdf">
    <button @click="generarReportePDF">Generar Reporte PDF</button>
  </div>

  <div class="reporte-container">
    <h1 class="va-h4 mb-4">Reporte Final (Vista Previa)</h1>

    <div v-for="(asignatura, index) in reporte" :key="index" class="asignatura-card">
      <h3>{{ asignatura.informacionbasica.nombre }} ({{ asignatura.informacionbasica.clave }})</h3>
      <p><strong>Créditos:</strong> {{ asignatura.informacionbasica.creditos }}</p>
      <p><strong>Maestro:</strong> {{ asignatura.informacionbasica.maestro }}</p>
      <p><strong>Departamento:</strong> {{ asignatura.informacionbasica.departamento }}</p>

      <!-- Tabla de todas las carreras (una sola tabla) -->
      <div class="carreras-table">
        <table>
          <thead>
            <tr>
              <th rowspan="2">Asignatura</th>
              <th rowspan="2">Carrera</th>
              <th rowspan="2">A</th>
              <th colspan="2" style="text-align: center">B</th>
              <th rowspan="2">C</th>
              <th rowspan="2">D</th>
              <th rowspan="2">E</th>
              <th rowspan="2">F</th>
              <th rowspan="2">G</th>
              <th rowspan="2">H</th>
              <!-- Aquí hacemos el encabezado agrupado -->
            </tr>
            <tr>
              <th>O</th>
              <th>Co</th>
            </tr>
          </thead>
          <tbody>
            <template v-for="(asignatura, index) in reporte" :key="'asignatura-' + index">
              <template v-for="(agp, agpIndex) in asignatura.aulas_grupos_periodos" :key="'agp-' + agpIndex">
                <template v-for="(carrera, carreraIndex) in agp.carreras" :key="'carrera-' + carreraIndex">
                  <tr>
                    <td>{{ asignatura.informacionbasica.nombre }}</td>
                    <td>{{ carrera.nombre_carrera }}</td>
                    <td>{{ carrera.A || 0 }}</td>
                    <td>{{ carrera.C || 0 }}</td>
                    <td>{{ carrera.D || 0 }}</td>
                    <td>{{ carrera.E || 0 }}</td>
                    <td>{{ carrera.F || 0 }}</td>
                    <td>{{ carrera.G || 0 }}</td>
                    <td>{{ carrera.H || 0 }}</td>
                    <!-- Aquí renderizamos O, Co y B detallado -->
                    <td>{{ carrera.O || 0 }}</td>
                    <td>{{ carrera.Co || 0 }}</td>
                  </tr>
                </template>
              </template>
            </template>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<style scoped>
.loading-container,
.empty-container {
  text-align: center;
  margin-top: 1.5rem;
  font-weight: bold;
  font-size: 1.1rem;
  color: #555;
}

.reporte-container {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  margin-top: 1rem;
}

.asignatura-card {
  background-color: #ffffff;
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 1rem;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}

.agp-section {
  margin-top: 1rem;
  padding-left: 1rem;
  border-left: 3px solid #007bff20;
}

.agp-header {
  font-weight: 500;
  margin-bottom: 0.5rem;
}

.carrera-card {
  background-color: #f9f9f9;
  border-radius: 6px;
  padding: 0.75rem;
  margin-top: 0.5rem;
}

table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 0.75rem;
}

th,
td {
  border: 1px solid #ccc;
  padding: 0.4rem 0.6rem;
  text-align: left;
  font-size: 0.95rem;
}

th {
  background-color: #f0f0f0;
  font-weight: 600;
}

td {
  background-color: #fff;
}

.carreras-table {
  margin-top: 1rem;
  overflow-x: auto;
}

.alumnos-list-container {
  margin-top: 2rem;
  background-color: #f9faff;
  padding: 1rem;
  border-radius: 8px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}

.alumnos-list-container h5 {
  font-size: 1.2rem;
  color: #2a5dff;
  margin-bottom: 0.5rem;
}

.asignatura-section h4 {
  color: #333;
  margin-top: 1rem;
  font-weight: 600;
}

.carrera-section h5 {
  margin-top: 0.5rem;
  color: #444;
  font-size: 1.05rem;
  font-weight: 500;
}

.alumnos-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 0.75rem;
  margin-top: 0.5rem;
}

.alumno-card {
  background-color: #fff;
  border: 1px solid #d0d9ff;
  border-radius: 8px;
  padding: 0.75rem;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  transition: transform 0.2s ease;
}

.alumno-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.alumno-header {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-weight: 600;
  font-size: 1rem;
}

.badge.numero-control {
  background-color: #2a5dff;
  color: #fff;
  padding: 0.2rem 0.5rem;
  border-radius: 4px;
  font-size: 0.85rem;
}

.alumno-nombre {
  color: #333;
  flex: 1;
}

.alumno-calificaciones {
  display: flex;
  align-items: center;
  gap: 0.3rem;
  font-size: 0.9rem;
}

.label {
  font-weight: 500;
  color: #666;
}

.calificacion {
  background-color: #f0f4ff;
  padding: 0.2rem 0.5rem;
  border-radius: 4px;
  font-weight: 500;
}

.boton-regresar {
  position: sticky;
  top: 1rem;
  left: 1rem;
  z-index: 10;
}

.boton-regresar button {
  background-color: #2a5dff;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.boton-regresar button:hover {
  background-color: #1e45cc;
}

/* Nuevos estilos para el botón de PDF */
.boton-generar-pdf {
  position: sticky;
  top: 1rem;
  right: 1rem;
  z-index: 10;
  text-align: right;
  margin-bottom: 1rem;
}

.boton-generar-pdf button {
  background-color: #28a745;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.boton-generar-pdf button:hover {
  background-color: #218838;
}
</style>
