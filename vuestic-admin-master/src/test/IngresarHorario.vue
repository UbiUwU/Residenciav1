<template>
  <va-card>
    <va-card-title>Establecer Mi Horario Semanal</va-card-title>
    <va-card-content>
      <div class="mb-6">
        <va-alert color="info" class="mb-4">
          Selecciona las horas en las que estarás disponible durante el semestre. Este horario se aplicará a todas las
          semanas.
        </va-alert>

        <va-checkbox v-model="todasLasHoras" label="Seleccionar todas las horas de los días marcados" class="mb-4" />
      </div>

      <!-- Calendario visual -->
      <div class="grid grid-cols-8 gap-1 overflow-x-auto">
        <!-- Columna de horas -->
        <div class="sticky left-0 bg-white z-10">
          <div class="h-12 border-b border-gray-200 font-bold text-center py-3">Hora</div>
          <div
            v-for="hora in horas"
            :key="hora"
            class="h-12 border-b border-gray-200 flex items-center justify-center text-xs"
          >
            {{ hora }}:00
          </div>
        </div>

        <!-- Días de la semana -->
        <div v-for="dia in diasSemana" :key="dia" class="border-r border-gray-200">
          <div class="h-12 border-b border-gray-200 flex items-center justify-center">
            <va-checkbox v-model="diasSeleccionados" :array-value="dia" :label="dia" color="primary" />
          </div>
          <div
            v-for="hora in horas"
            :key="hora"
            class="h-12 border-b border-gray-200 relative"
            @click="toggleHora(dia, hora)"
          >
            <div
              class="absolute inset-0 cursor-pointer transition-colors"
              :class="{
                'bg-primary-200': isHoraSeleccionada(dia, hora),
                'opacity-50': !diasSeleccionados.includes(dia),
              }"
            ></div>
          </div>
        </div>
      </div>

      <!-- Acciones -->
      <div class="flex justify-end gap-4 mt-6">
        <va-button @click="limpiarSeleccion" color="danger"> Limpiar selección </va-button>
        <va-button @click="guardarHorario" :disabled="horarioSeleccionado.length === 0"> Guardar horario </va-button>
      </div>
    </va-card-content>
  </va-card>
</template>

<script>
import { ref, computed, watch } from 'vue'
import { useToast } from 'vuestic-ui'

export default {
  setup() {
    const { init } = useToast()
    const diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo']
    const horas = Array.from({ length: 24 }, (_, i) => i)
    const horarioSeleccionado = ref([])
    const diasSeleccionados = ref([])
    const todasLasHoras = ref(false)

    // Seleccionar/deseleccionar todas las horas de un día
    watch(diasSeleccionados, (nuevosDias) => {
      if (todasLasHoras.value) {
        nuevosDias.forEach((dia) => {
          horas.forEach((hora) => {
            const horaKey = `${dia}-${hora}`
            if (!horarioSeleccionado.value.includes(horaKey)) {
              horarioSeleccionado.value.push(horaKey)
            }
          })
        })

        // Remover horas de días no seleccionados
        horarioSeleccionado.value = horarioSeleccionado.value.filter((horaKey) => {
          const dia = horaKey.split('-')[0]
          return nuevosDias.includes(dia)
        })
      }
    })

    // Alternar selección de una hora específica
    const toggleHora = (dia, hora) => {
      if (!diasSeleccionados.value.includes(dia)) return

      const horaKey = `${dia}-${hora}`
      const index = horarioSeleccionado.value.indexOf(horaKey)

      if (index >= 0) {
        horarioSeleccionado.value.splice(index, 1)
      } else {
        horarioSeleccionado.value.push(horaKey)
      }
    }

    // Verificar si una hora está seleccionada
    const isHoraSeleccionada = (dia, hora) => {
      return horarioSeleccionado.value.includes(`${dia}-${hora}`)
    }

    // Limpiar toda la selección
    const limpiarSeleccion = () => {
      horarioSeleccionado.value = []
      diasSeleccionados.value = []
    }

    // Guardar el horario (simulado)
    const guardarHorario = () => {
      // Aquí normalmente harías una llamada a la API
      // Por ahora solo mostramos un mensaje

      // Formatear el horario para mostrarlo
      const horarioFormateado = {}
      diasSemana.forEach((dia) => {
        const horasDia = []
        horas.forEach((hora) => {
          if (isHoraSeleccionada(dia, hora)) {
            horasDia.push(`${hora}:00-${hora + 1}:00`)
          }
        })
        if (horasDia.length > 0) {
          horarioFormateado[dia] = horasDia
        }
      })

      init({
        message: `Horario guardado correctamente: ${JSON.stringify(horarioFormateado, null, 2)}`,
        color: 'success',
        position: 'bottom-right',
      })

      console.log('Horario seleccionado:', horarioFormateado)
    }

    return {
      diasSemana,
      horas,
      horarioSeleccionado,
      diasSeleccionados,
      todasLasHoras,
      toggleHora,
      isHoraSeleccionada,
      limpiarSeleccion,
      guardarHorario,
    }
  },
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
  opacity: 0.3;
}

.opacity-50 {
  opacity: 0.5;
}
</style>
