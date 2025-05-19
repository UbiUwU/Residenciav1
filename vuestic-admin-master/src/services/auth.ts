import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from './api'
import { useRouter } from 'vue-router'

import { Maestro, Usuario, AuthData } from '../services/types/auth' // Ajusta según tu estructura

export const useAuthStore = defineStore('auth', () => {
  const token = ref<string | null>(null)
  const user = ref<Usuario | null>(null)
  const maestro = ref<Maestro | null>(null)

  const router = useRouter()

  // Computed para saber si está autenticado
  const isAuthenticated = computed(() => !!token.value)

  // Carga los datos del almacenamiento local o de sesión
  const loadFromStorage = () => {
    const stored =
      localStorage.getItem('userData') || sessionStorage.getItem('userData')

    if (stored) {
      try {
        const { token: storedToken, user: storedUser, maestro: storedMaestro } = JSON.parse(stored)
        token.value = storedToken
        user.value = storedUser
        maestro.value = storedMaestro
      } catch (error) {
        console.error('Error al cargar datos de almacenamiento:', error)
        logout()
      }
    }
  }

  // Restaurar sesión existente
  const restoreSession = () => {
    loadFromStorage()
    return isAuthenticated.value
  }

  // Guardar sesión en localStorage o sessionStorage
  const persistSession = (data: AuthData, keepLoggedIn: boolean) => {
    const storage = keepLoggedIn ? localStorage : sessionStorage
    const payload = JSON.stringify({
      token: data.token,
      user: data.user,
      maestro: data.maestro,
    })
    storage.setItem('userData', payload)
  }

  // Iniciar sesión
  const login = async (authData: AuthData, keepLoggedIn: boolean) => {
    token.value = authData.token
    user.value = authData.user
    maestro.value = authData.maestro

    persistSession(authData, keepLoggedIn)
  }

  // Cerrar sesión
  const logout = () => {
    token.value = null
    user.value = null
    maestro.value = null
    localStorage.removeItem('userData')
    sessionStorage.removeItem('userData')
    router.push('/login') // ajusta la ruta si es diferente
  }

  // Inicializar store al cargar
  const initialize = () => {
    loadFromStorage()
  }

  return {
    token,
    user,
    maestro,
    isAuthenticated,
    initialize,
    restoreSession,
    login,
    logout,
  }
})
