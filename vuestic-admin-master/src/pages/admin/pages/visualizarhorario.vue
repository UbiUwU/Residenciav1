<template>
  <div class="maestro-horario-container">
    <div class="header">
      <h2>Horario del Maestro</h2>
      <p><strong>ID:</strong> {{ maestro.tarjeta }}</p>
    </div>

    <div class="horario-grid">
      <div v-for="dia in diasSemana" :key="dia" class="dia-column">
        <div class="dia-header">{{ dia }}</div>
        <div v-for="horario in horariosPorDia(dia)" :key="horario.id" class="bloque-horario">
          <div class="hora">{{ formatHora(horario.hora_inicio) }} - {{ formatHora(horario.hora_fin) }}</div>
          <button @click="eliminarHorario(horario.id)" class="eliminar-btn">×</button>
        </div>
        <button @click="abrirModal(dia)" class="agregar-btn">+ Agregar horario</button>
      </div>
    </div>

    <!-- Modal para agregar horario -->
    <div v-if="mostrarModal" class="modal">
      <div class="modal-content">
        <h3>Agregar horario para {{ diaSeleccionado }}</h3>
        <div class="time-pickers">
          <div>
            <label>Hora inicio:</label>
            <input type="time" v-model="nuevoHorario.hora_inicio" required>
          </div>
          <div>
            <label>Hora fin:</label>
            <input type="time" v-model="nuevoHorario.hora_fin" required>
          </div>
        </div>
        <div class="modal-actions">
          <button @click="guardarHorario" class="guardar-btn">Guardar</button>
          <button @click="cerrarModal" class="cancelar-btn">Cancelar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import api from '../services/api' // Importa el módulo completo
import { useAuthStore } from '../services/auth'

export default {
  setup() {
    const auth = useAuthStore()
    const maestro = auth.maestro

    const diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo']
    const horarios = ref([])
    const mostrarModal = ref(false)
    const diaSeleccionado = ref('')
    
    const nuevoHorario = ref({
      maestro_id: maestro.tarjeta,
      dia_semana: '',
      hora_inicio: '',
      hora_fin: ''
    })

    const cargarHorarios = async () => {
      try {
        const response = await api.getHorariosDeMaestro(maestro.tarjeta)
        horarios.value = response.data
      } catch (error) {
        console.error('Error al cargar horarios:', error)
      }
    }

    const horariosPorDia = (dia) => {
      return horarios.value.filter(h => h.dia_semana === dia)
    }

    const formatHora = (hora) => {
      return hora.slice(0, 5) // Formato HH:MM
    }

    const abrirModal = (dia) => {
      diaSeleccionado.value = dia
      nuevoHorario.value.dia_semana = dia
      mostrarModal.value = true
    }

    const cerrarModal = () => {
      mostrarModal.value = false
      nuevoHorario.value = {
        maestro_id: maestro.tarjeta,
        dia_semana: '',
        hora_inicio: '',
        hora_fin: ''
      }
    }

    const guardarHorario = async () => {
      try {
        await api.createHorarioMaestro(nuevoHorario.value)
        await cargarHorarios()
        cerrarModal()
      } catch (error) {
        console.error('Error al guardar horario:', error)
        alert(error.response?.data?.error || 'Error al guardar horario')
      }
    }

    const eliminarHorario = async (id) => {
      if (confirm('¿Estás seguro de eliminar este horario?')) {
        try {
          await api.deleteHorarioMaestro(id)
          await cargarHorarios()
        } catch (error) {
          console.error('Error al eliminar horario:', error)
          alert('Error al eliminar horario')
        }
      }
    }

    onMounted(cargarHorarios)

    return {
      maestro,
      diasSemana,
      horarios,
      horariosPorDia,
      formatHora,
      mostrarModal,
      diaSeleccionado,
      nuevoHorario,
      abrirModal,
      cerrarModal,
      guardarHorario,
      eliminarHorario
    }
  }
}
</script>

<style scoped>
.maestro-horario-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.header {
  margin-bottom: 30px;
  text-align: center;
}

.horario-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 15px;
}

.dia-column {
  background: #f8f9fa;
  border-radius: 8px;
  padding: 15px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.dia-header {
  font-weight: bold;
  text-align: center;
  margin-bottom: 15px;
  padding-bottom: 8px;
  border-bottom: 1px solid #dee2e6;
  color: #2c3e50;
}

.bloque-horario {
  background: #ffffff;
  border-radius: 6px;
  padding: 10px;
  margin-bottom: 10px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.hora {
  font-size: 14px;
}

.eliminar-btn {
  background: #ff6b6b;
  color: white;
  border: none;
  border-radius: 50%;
  width: 24px;
  height: 24px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  font-size: 14px;
}

.eliminar-btn:hover {
  background: #fa5252;
}

.agregar-btn {
  width: 100%;
  padding: 8px;
  background: #4dabf7;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  margin-top: 10px;
  font-size: 14px;
}

.agregar-btn:hover {
  background: #339af0;
}

.modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0,0,0,0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  padding: 25px;
  border-radius: 8px;
  width: 350px;
  max-width: 90%;
}

.time-pickers {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 15px;
  margin: 20px 0;
}

.time-pickers input {
  width: 100%;
  padding: 8px;
  border: 1px solid #ddd;
  border-radius: 4px;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}

.guardar-btn {
  background: #40c057;
  color: white;
  padding: 8px 15px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.guardar-btn:hover {
  background: #37b24d;
}

.cancelar-btn {
  background: #f1f3f5;
  padding: 8px 15px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.cancelar-btn:hover {
  background: #e9ecef;
}
</style>