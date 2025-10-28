import { ref } from 'vue'
import apiClient from '../../../../services/api' // Ajusta la ruta según la estructura de tu proyecto

export function useMaestros() {
  const maestros = ref<any[]>([])
  const isLoading = ref(false)
  const error = ref<Error | null>(null)

  async function fetchMaestros() {
    isLoading.value = true
    error.value = null
    try {
      const response = await apiClient.getMaestros()
      // Accede a los datos dentro de la clave 'data'
      maestros.value = response.data
    } catch (err) {
      error.value = err as Error
    } finally {
      isLoading.value = false
    }
  }

  // Ejecutar automáticamente para obtener los datos al cargar el componente
  fetchMaestros()

  return {
    maestros,
    isLoading,
    error,
    fetchMaestros,
  }
}
