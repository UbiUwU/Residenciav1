import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from './api'
import { useRouter } from 'vue-router'
import { Maestro, Usuario, AuthData } from '../services/types/auth'
import { ROLES } from '../constants/roles' // Importación de roles

export const useAuthStore = defineStore('auth', () => {
  const token = ref<string | null>(null)
  const user = ref<Usuario | null>(null)
  const maestro = ref<Maestro | null>(null)
  const router = useRouter()

  // Computed properties
  const isAuthenticated = computed(() => !!token.value)
  const userRole = computed(() => user.value?.idrol || null)
  const isAdmin = computed(() => userRole.value === ROLES.ADMIN)
  const isTeacher = computed(() => userRole.value === ROLES.TEACHER)

  // Carga los datos del almacenamiento local o de sesión
  const loadFromStorage = () => {
    const stored = localStorage.getItem('userData') || sessionStorage.getItem('userData')

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

  // Iniciar sesión (versión mejorada)
  const login = async (authData: AuthData, keepLoggedIn: boolean) => {
    token.value = authData.token
    user.value = authData.user
    maestro.value = authData.maestro

    persistSession(authData, keepLoggedIn)

    // Retornamos el rol del usuario para que el componente pueda manejar la redirección
    return authData.user.idrol
  }

  // Versión alternativa de login que maneja la redirección internamente
  const loginAndRedirect = async (authData: AuthData, keepLoggedIn: boolean) => {
    const role = await login(authData, keepLoggedIn)
    
    // Redirección basada en el rol
    switch (role) {
      case ROLES.ADMIN:
        router.push({ name: 'dashboard' })
        break
      case ROLES.TEACHER:
        router.push({ name: 'dashboard-teacher' })
        break
      default:
        router.push({ name: 'dashboard' })
    }
    
    return role
  }

  // Cerrar sesión
  const logout = () => {
    token.value = null
    user.value = null
    maestro.value = null
    localStorage.removeItem('userData')
    sessionStorage.removeItem('userData')
    router.push('/login')
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
    userRole,
    isAdmin,
    isTeacher,
    initialize,
    restoreSession,
    login,          // Versión que retorna el rol
    loginAndRedirect, // Versión que maneja redirección
    logout,
  }
})