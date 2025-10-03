import { ref } from 'vue'
import apiClient from '../../../services/api'

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
      const maestrosRaw = response.data as { tarjeta: string; nombre: string }[]

      // Ejecuta todas las peticiones de asignaturas en paralelo
      const maestrosProcesados = await Promise.all(
        maestrosRaw.map(async (m) => {
          try {
            const asignaturasResp = await apiClient.getAsignaturaByTarjetaCompleta(m.tarjeta)

            // Aplana los periodos: { "3": [ ... ] } → [ ... ]
            const periodos = asignaturasResp.data?.data || {}
            const asignaturasArray = Object.values(periodos).flat() as any[]

            return {
              tarjeta: m.tarjeta,
              nombre: m.nombre,
              asignaturas: asignaturasArray.length,
              avance: Math.floor(Math.random() * 100),
              estado: ['Pendiente', 'En progreso', 'Completado'][Math.floor(Math.random() * 3)],
            } as Maestro
          } catch (asigError) {
            console.warn(`No se pudieron obtener asignaturas para ${m.tarjeta}:`, asigError)
            return null
          }
        }),
      )

      // Filtra maestros con al menos una asignatura válida
      maestros.value = maestrosProcesados.filter((m): m is Maestro => m !== null && m.asignaturas > 0)
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

  // Llama al cargar
  fetchMaestrosConAsignaturas()

  return {
    maestros,
    isLoading,
    error,
    fetchMaestrosConAsignaturas,
  }
}
