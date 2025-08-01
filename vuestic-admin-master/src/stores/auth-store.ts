import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '../services/apiJ'
import { useRouter } from 'vue-router'

interface MaestroData {
  tarjeta: number
  nombre: string
  apellidopaterno: string
  apellidomaterno: string
  correo: string
  rfc: string
  escolaridad_licenciatura: string
  estado_licenciatura: string
}

export const useAuthStore = defineStore('auth', () => {
  const user = ref<{
    user_type: string
    correo: string
    data: MaestroData | null
    token?: string
  } | null>(null)

  const token = ref<string | null>(null)
  const router = useRouter()

  const login = async (authData: { token: string }, keepLoggedIn: boolean) => {
    token.value = authData.token

    const storage = keepLoggedIn ? localStorage : sessionStorage
    storage.setItem('authToken', authData.token)

    try {
      const userResponse = await api.getUserData()

      if (userResponse.data.user_type === 'maestro') {
        user.value = {
          user_type: 'maestro',
          correo: userResponse.data.dada.correo,
          data: userResponse.data.data,
          token: authData.token,
        }
        router.push({ name: 'maestro-dashboard' })
      }
    } catch (error) {
      console.error('Error obteniendo datos de usuario:', error)
      throw error
    }
  }

  return {
    user,
    token,
    login,
  }
})
