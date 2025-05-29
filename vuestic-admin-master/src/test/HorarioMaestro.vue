<template>
    <va-card>
      <va-card-title>Horario Semestral</va-card-title>
      <va-card-content>
        <!-- Selector de semanas -->
        <div class="flex justify-between mb-4">
          <va-button
            icon="chevron_left"
            @click="semanaAnterior"
            :disabled="semanaActual === 1"
          />
          <h3 class="text-center">Semana {{ semanaActual }}</h3>
          <va-button
            icon="chevron_right"
            @click="semanaSiguiente"
            :disabled="semanaActual === totalSemanas"
          />
        </div>
  
        <!-- Calendario visual -->
        <div class="grid grid-cols-8 gap-1 overflow-x-auto">
          <!-- Columna de horas -->
          <div class="sticky left-0 bg-white z-10">
            <div class="h-12 border-b border-gray-200 font-bold text-center py-3">
              Hora
            </div>
            <div
              v-for="hora in horas"
              :key="hora"
              class="h-12 border-b border-gray-200 flex items-center justify-center text-xs"
            >
              {{ hora }}:00
            </div>
          </div>
  
          <!-- Días de la semana -->
          <div
            v-for="dia in diasSemana"
            :key="dia"
            class="border-r border-gray-200"
          >
            <div class="h-12 border-b border-gray-200 font-bold text-center py-3">
              {{ dia }}
            </div>
            <div
              v-for="hora in horas"
              :key="hora"
              class="h-12 border-b border-gray-200 relative"
              @click="toggleHora(dia, hora, semanaActual)"
            >
              <div
                class="absolute inset-0 cursor-pointer transition-colors flex items-center justify-center"
                :class="{
                  'bg-primary-200': isHoraSeleccionada(dia, hora, semanaActual),
                  'bg-green-100': isHoraOcupada(dia, hora, semanaActual) && isHoraDisponible(dia, hora, semanaActual),
                  'bg-red-100': isHoraOcupada(dia, hora, semanaActual) && !isHoraDisponible(dia, hora, semanaActual)
                }"
              >
                <span v-if="isHoraOcupada(dia, hora, semanaActual)" class="text-xs p-1 text-center">
                  {{ getActividad(dia, hora, semanaActual) }}
                </span>
              </div>
            </div>
          </div>
        </div>
  
        <!-- Leyenda -->
        <div class="flex gap-4 mt-4 justify-center">
          <div class="flex items-center">
            <div class="w-4 h-4 bg-primary-200 mr-2"></div>
            <span class="text-xs">Seleccionado</span>
          </div>
          <div class="flex items-center">
            <div class="w-4 h-4 bg-green-100 mr-2"></div>
            <span class="text-xs">Disponible</span>
          </div>
          <div class="flex items-center">
            <div class="w-4 h-4 bg-red-100 mr-2"></div>
            <span class="text-xs">Ocupado</span>
          </div>
        </div>
      </va-card-content>
    </va-card>
  </template>
  
  <script>
  import { ref, computed } from 'vue'
  import axios from 'axios'
  
  export default {
    setup() {
      const diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo']
      const horas = Array.from({ length: 24 }, (_, i) => i)
      const semanaActual = ref(1)
      const totalSemanas = 16 // Semestre típico de 16 semanas
      const horarios = ref([])
      const horarioSeleccionado = ref([])
  
      // Cargar horarios del semestre (simulado)
      const cargarHorarios = () => {
        // En una implementación real, harías una llamada a tu API
        horarios.value = [
          { semana: 1, dia_semana: 'Lunes', hora_inicio: '08:00', hora_fin: '10:00', actividad: 'Matemáticas', disponible: true },
          { semana: 1, dia_semana: 'Miércoles', hora_inicio: '10:00', hora_fin: '12:00', actividad: 'Física', disponible: true },
          { semana: 1, dia_semana: 'Viernes', hora_inicio: '14:00', hora_fin: '16:00', actividad: 'Reunión', disponible: false },
          // Agrega más horarios según necesites
        ]
      }
  
      const toggleHora = (dia, hora, semana) => {
        const horaStr = `${hora.toString().padStart(2, '0')}:00`
        const horaFinStr = `${(hora + 1).toString().padStart(2, '0')}:00`
        
        // Verificar si ya está seleccionada
        const index = horarioSeleccionado.value.findIndex(h => 
          h.dia === dia && h.hora === hora && h.semana === semana
        )
        
        if (index >= 0) {
          horarioSeleccionado.value.splice(index, 1)
        } else {
          horarioSeleccionado.value.push({
            semana,
            dia,
            hora,
            hora_inicio: horaStr,
            hora_fin: horaFinStr
          })
        }
      }
  
      const isHoraSeleccionada = (dia, hora, semana) => {
        return horarioSeleccionado.value.some(h => 
          h.dia === dia && h.hora === hora && h.semana === semana
        )
      }
  
      const isHoraOcupada = (dia, hora, semana) => {
        const horaStr = `${hora.toString().padStart(2, '0')}:00`
        const horaFinStr = `${(hora + 1).toString().padStart(2, '0')}:00`
        
        return horarios.value.some(h => 
          h.semana === semana &&
          h.dia_semana === dia && 
          h.hora_inicio <= horaStr && 
          h.hora_fin >= horaFinStr
        )
      }
  
      const isHoraDisponible = (dia, hora, semana) => {
        const horaStr = `${hora.toString().padStart(2, '0')}:00`
        const horaFinStr = `${(hora + 1).toString().padStart(2, '0')}:00`
        
        const horario = horarios.value.find(h => 
          h.semana === semana &&
          h.dia_semana === dia && 
          h.hora_inicio <= horaStr && 
          h.hora_fin >= horaFinStr
        )
        
        return horario ? horario.disponible : false
      }
  
      const getActividad = (dia, hora, semana) => {
        const horaStr = `${hora.toString().padStart(2, '0')}:00`
        const horaFinStr = `${(hora + 1).toString().padStart(2, '0')}:00`
        
        const horario = horarios.value.find(h => 
          h.semana === semana &&
          h.dia_semana === dia && 
          h.hora_inicio <= horaStr && 
          h.hora_fin >= horaFinStr
        )
        
        return horario ? horario.actividad.substring(0, 3) + '.' : ''
      }
  
      const semanaAnterior = () => {
        if (semanaActual.value > 1) {
          semanaActual.value--
        }
      }
  
      const semanaSiguiente = () => {
        if (semanaActual.value < totalSemanas) {
          semanaActual.value++
        }
      }
  
      // Cargar datos al iniciar
      cargarHorarios()
  
      return {
        diasSemana,
        horas,
        semanaActual,
        totalSemanas,
        horarioSeleccionado,
        toggleHora,
        isHoraSeleccionada,
        isHoraOcupada,
        isHoraDisponible,
        getActividad,
        semanaAnterior,
        semanaSiguiente
      }
    }
  }
  </script>
  
  <style scoped>
  .grid {
    min-height: 800px;
  }
  
  .h-12 {
    min-height: 3rem;
  }
  
  .bg-primary-200 {
    background-color: var(--va-primary);
    opacity: 0.2;
  }
  
  .bg-green-100 {
    background-color: #dcfce7;
  }
  
  .bg-red-100 {
    background-color: #fee2e2;
  }
  </style>
