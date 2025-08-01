import { ref } from 'vue'
import apiClient from '../../../services/api.js' // Ajusta la ruta si es necesario

export function useMaestrosConAsignaturas() {
  const maestros = ref<any[]>([])
  const isLoading = ref(false)
  const error = ref<string | null>(null) // Ahora es string

  async function fetchMaestrosConAsignaturas() {
    isLoading.value = true
    error.value = null

    try {
      const response = await apiClient.getMaestros()
      const maestrosRaw = response.data.data

      const maestrosFiltrados = await Promise.all(
        maestrosRaw.map(async (m: any) => {
          const asignaturasResp = await apiClient.getAsignaturaByTarjetaCompleta(m.tarjeta)
          const asignaturas = asignaturasResp.data

          return {
            tarjeta: m.tarjeta,
            nombre: m.nombre,
            asignaturas: asignaturas.length,
            avance: Math.floor(Math.random() * 100),
            estado: ['Pendiente', 'En progreso', 'Completado'][
              Math.floor(Math.random() * 3)
            ],
          }
        })
      )

      maestros.value = maestrosFiltrados.filter(m => m.asignaturas > 0)

    } catch (err: any) {
      console.error('Error:', err)

      // ⚡ Detecta error de red específico
      if (err.message && err.message.includes('net::ERR_NETWORK_CHANGED')) {
        error.value = 'Se perdió la conexión. Por favor verifica tu conexión e inténtalo de nuevo.'
      } else if (err.code === 'ECONNABORTED') {
        error.value = 'La solicitud tardó demasiado. Intenta de nuevo.'
      } else {
        error.value = err.message || 'Ocurrió un error inesperado.'
      }
    } finally {
      isLoading.value = false
    }
  }

  // Ejecuta al cargar
  fetchMaestrosConAsignaturas()

  return {
    maestros,
    isLoading,
    error,
    fetchMaestrosConAsignaturas,
  }
}
