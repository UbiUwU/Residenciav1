import { ref } from 'vue'
// 👇 Usa el tipo explícito para decirle a TS que tu apiClient tiene esas funciones
import apiClient from '../../../services/apiJ'

interface Maestro {
  tarjeta: string
  nombre: string
  asignaturas: number
  avance: number
  estado: string
}

export function useMaestrosConAsignaturas() {
  const maestros = ref<Maestro[]>([])
  const isLoading = ref(false)
  const error = ref<string | null>(null)

  async function fetchMaestrosConAsignaturas() {
    isLoading.value = true
    error.value = null

    try {
      const response = await apiClient.getMaestros()
      const maestrosRaw = response.data.data as any[]

      const maestrosFiltrados: Maestro[] = []

      for (const m of maestrosRaw) {
        try {
          const asignaturasResp = await apiClient.getAsignaturaByTarjetaCompleta(m.tarjeta)
          const asignaturas = asignaturasResp.data

          if (Array.isArray(asignaturas) && asignaturas.length > 0) {
            maestrosFiltrados.push({
              tarjeta: m.tarjeta,
              nombre: m.nombre,
              asignaturas: asignaturas.length,
              avance: Math.floor(Math.random() * 100),
              estado: ['Pendiente', 'En progreso', 'Completado'][Math.floor(Math.random() * 3)],
            })
          }
        } catch (asigError) {
          console.warn(`No se pudieron obtener asignaturas para ${m.tarjeta}:`, asigError)
        }
      }

      maestros.value = maestrosFiltrados.filter((m: Maestro) => m.asignaturas > 0)
    } catch (err: any) {
      console.error('Error:', err)
      if (err.message?.includes('net::ERR_NETWORK_CHANGED')) {
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

  fetchMaestrosConAsignaturas()

  return {
    maestros,
    isLoading,
    error,
    fetchMaestrosConAsignaturas,
  }
}
