<template>
  <button class="export-button" @click="enviarAPHP">📤 Exportar a PHP</button>

  <div class="asignatura-detail">
    <button class="back-button" @click="volverALista">← Volver a la lista</button>

    <div v-if="loading" class="loading">Cargando detalles de la asignatura...</div>
    <div v-else-if="error" class="error">{{ error }}</div>

    <div v-else>
      <div class="header-info">
        <h1>{{ asignatura.ClaveAsignatura }} - {{ asignatura.NombreAsignatura }}</h1>
        <div class="meta-info">
          <span>Créditos: {{ asignatura.Creditos }}</span>
          <span
            >SATCA: Total {{ asignatura.Satca_Total }} (Teóricas: {{ asignatura.Satca_Teoricas }}, Prácticas:
            {{ asignatura.Satca_Practicas }})</span
          >
        </div>
      </div>

      <div v-if="presentacion" class="presentacion-section">
        <h2>Presentación</h2>
        <div class="card">
          <h3>Caracterización</h3>
          <p>{{ presentacion.Caracterizacion || 'No disponible' }}</p>

          <h3>Intención didáctica</h3>
          <p>{{ presentacion.Intencion_didactica || 'No disponible' }}</p>
        </div>
      </div>

      <div v-if="competencias && competencias.length" class="competencias-section">
        <h2>Competencias</h2>
        <div class="card competencias-grid">
          <div v-for="(competencia, index) in competencias" :key="index" class="competencia-item">
            <h3>Competencia {{ index + 1 }}</h3>
            <p>{{ competencia.Descripcion }}</p>
            <div v-for="tipo in competencia.Tipo_Competencia" :key="tipo" class="badge">
              {{ tipo }}
            </div>
          </div>
        </div>
      </div>

      <div v-if="temas && temas.length" class="temas-section">
        <h2>Temario</h2>
        <div class="temas-container">
          <div v-for="tema in temas" :key="tema.id_Tema" class="tema-card">
            <h3>Tema {{ tema.Numero }}: {{ tema.Nombre_Tema }}</h3>

            <div v-if="tema.subtemas && tema.subtemas.length" class="subtemas">
              <h4>Subtemas:</h4>
              <ul>
                <li v-for="subtema in tema.subtemas" :key="subtema.id_Subtema">
                  {{ subtema.Nombre_Subtema }}
                </li>
              </ul>
            </div>

            <div v-if="tema.competencias && tema.competencias.length" class="competencias-tema">
              <h4>Competencias específicas:</h4>
              <div class="competencias-list">
                <div v-for="(competencia, idx) in tema.competencias" :key="idx" class="competencia-tema">
                  <p>{{ competencia.Descripcion_Comp_Tema }}</p>
                  <div v-for="tipo in competencia.Tipo_Competencia" :key="tipo" class="badge">
                    {{ tipo }}
                  </div>
                </div>
              </div>
            </div>

            <div v-if="tema.actividades_aprendizaje && tema.actividades_aprendizaje.length" class="actividades">
              <h4>Actividades de aprendizaje:</h4>
              <ul>
                <li v-for="(actividad, idx) in tema.actividades_aprendizaje" :key="idx">
                  {{ actividad.Descripcion_Act_Aprendizaje }}
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '../services/api.js'

const route = useRoute()
const router = useRouter()
const claveAsignatura = route.params.clave

const asignatura = ref(null)
const presentacion = ref(null)
const competencias = ref([])
const temas = ref([])
const loading = ref(true)
const error = ref(null)

const fetchAsignatura = async () => {
  try {
    loading.value = true
    const response = await api.getAsignatura(claveAsignatura)

    asignatura.value = response.data.asignatura
    presentacion.value = response.data.presentacion
    competencias.value = response.data.competencias || []
    temas.value = response.data.temas || []
  } catch (err) {
    error.value = 'Error al cargar la asignatura: ' + (err.response?.data?.error || err.message)
    console.error(err)
  } finally {
    loading.value = false
  }
}

const volverALista = () => {
  router.push('/asignaturas')
}

onMounted(() => {
  fetchAsignatura()
})

const enviarAPHP = async () => {
  try {
    // URL configurable desde variables de entorno
    const pdfBaseUrl = import.meta.env.VITE_PDF_BASE_URL || 'http://localhost/Inicio%20de%20sesion/PlugginPDF2';
    const response = await fetch(`${pdfBaseUrl}/gestionar_plantillas.php`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        asignatura: asignatura.value,
        presentacion: presentacion.value,
        competencias: competencias.value,
        temas: temas.value,
      }),
    })

    if (!response.ok) {
      throw new Error('Error al enviar los datos al PHP')
    }

    alert('Datos enviados correctamente al PHP')
  } catch (err) {
    console.error(err)
    alert('Hubo un error al enviar los datos')
  }
}
</script>

<style scoped>
.asignatura-detail {
  padding: 20px;
  max-width: 1200px;
  margin: 0 auto;
}

.back-button {
  background: none;
  border: none;
  color: #3498db;
  cursor: pointer;
  font-size: 1em;
  margin-bottom: 20px;
  padding: 5px 10px;
  border-radius: 4px;
}

.back-button:hover {
  background-color: #f0f0f0;
}

.header-info {
  margin-bottom: 30px;
  padding-bottom: 15px;
  border-bottom: 1px solid #eee;
}

.meta-info {
  display: flex;
  gap: 15px;
  color: #666;
  margin-top: 10px;
  font-size: 0.9em;
}

.card {
  background-color: #fff;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  margin-bottom: 20px;
}

h2 {
  color: #2c3e50;
  margin: 25px 0 15px;
}

h3 {
  color: #34495e;
  margin: 15px 0 10px;
}

h4 {
  color: #7f8c8d;
  margin: 10px 0 5px;
}

.competencias-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 20px;
}

.competencia-item {
  border-left: 3px solid #3498db;
  padding-left: 15px;
}

.badge {
  display: inline-block;
  background-color: #e0f7fa;
  color: #00838f;
  padding: 3px 8px;
  border-radius: 12px;
  font-size: 0.8em;
  margin-right: 5px;
  margin-top: 5px;
}

.temas-container {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.tema-card {
  background-color: #fff;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.subtemas ul,
.actividades ul {
  padding-left: 20px;
}

.subtemas li,
.actividades li {
  margin-bottom: 5px;
}

.competencias-list {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.competencia-tema {
  background-color: #f5f5f5;
  padding: 10px;
  border-radius: 5px;
}

.loading,
.error {
  text-align: center;
  padding: 40px;
  font-size: 1.2em;
}

.error {
  color: #e74c3c;
}
.export-button {
  background-color: #2ecc71;
  border: none;
  color: white;
  padding: 10px 15px;
  margin-left: 10px;
  border-radius: 5px;
  cursor: pointer;
}

.export-button:hover {
  background-color: #27ae60;
}
</style>
