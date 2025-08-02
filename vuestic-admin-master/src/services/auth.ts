import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { Maestro, Usuario, AuthData } from '../services/types/auth'
import { ROLES } from '../constants/roles'

export const useAuthStore = defineStore('auth', () => {
  const token = ref<string | null>(null)
  const user = ref<Usuario | null>(null)
  const maestro = ref<Maestro | null>(null)
  const router = useRouter()

  // Convertimos el rol a número reactivamente
  const userRole = computed(() => {
    const role = user.value?.idrol
    return role ? Number(role) : null
  })

  const isAuthenticated = computed(() => !!token.value)
  const isAdmin = computed(() => userRole.value === ROLES.ADMIN)
  const isTeacher = computed(() => userRole.value === ROLES.TEACHER)
  const isSuper = computed(() => userRole.value === ROLES.SUPER)

  const loadFromStorage = () => {
    const stored = localStorage.getItem('userData') || sessionStorage.getItem('userData')

    if (stored) {
      try {
        const parsed = JSON.parse(stored)
        token.value = parsed.token
        user.value = parsed.user

        // Convertimos el rol al comparar
        const role = parsed.user.idrol ? Number(parsed.user.idrol) : null
        if (role === ROLES.TEACHER) {
          maestro.value = parsed.maestro
        } else {
          maestro.value = null
        }
      } catch (error) {
        console.error('Error al cargar datos:', error)
        logout()
      }
    }
  }

  const persistSession = (data: AuthData, keepLoggedIn: boolean) => {
    const storage = keepLoggedIn ? localStorage : sessionStorage
    const role = data.user.idrol ? Number(data.user.idrol) : null

    storage.setItem(
      'userData',
      JSON.stringify({
        token: data.token,
        user: data.user,
        maestro: role === ROLES.TEACHER ? data.maestro : null,
      }),
    )
  }

  const login = async (authData: AuthData, keepLoggedIn: boolean) => {
    token.value = authData.token
    user.value = authData.user

    // Convertimos el rol al asignar
    const role = authData.user.idrol ? Number(authData.user.idrol) : null
    if (role === ROLES.TEACHER) {
      maestro.value = authData.maestro
    } else {
      maestro.value = null
    }

    persistSession(authData, keepLoggedIn)
    return role
  }

  const loginAndRedirect = async (authData: AuthData, keepLoggedIn: boolean) => {
    const role = await login(authData, keepLoggedIn)

    if (role === ROLES.ADMIN) {
      router.push({ name: 'dashboard' })
    } else if (role === ROLES.TEACHER) {
      router.push({ name: 'dashboard-teacher' })
    } else if (role === ROLES.SUPER) {
      router.push({ name: 'dashboard-super' })
    } else {
      router.push({ name: 'dashboard' })
    }

    return role
  }

  const logout = () => {
    token.value = null
    user.value = null
    maestro.value = null
    localStorage.removeItem('userData')
    sessionStorage.removeItem('userData')
    router.push('/login')
  }

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
    isSuper,
    initialize,
    login,
    loginAndRedirect,
    logout,
  }
})
