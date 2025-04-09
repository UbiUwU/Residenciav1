// stores/auth-store.ts
import { defineStore } from 'pinia'
import { ref } from 'vue'
import router from '../router'
import api from '../services/api'

interface User {
  idusuario: number
  correo: string
  token: string
  idrol: number
  nombre?: string
  avatar?: string
  fecha_registro?: string
}

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null)
  const isAuthenticated = ref(false)

  function login(userData: User, rememberMe: boolean) {
    user.value = userData
    isAuthenticated.value = true

    if (rememberMe) {
      localStorage.setItem('authToken', userData.token)
      localStorage.setItem('userData', JSON.stringify(userData))
    } else {
      sessionStorage.setItem('authToken', userData.token)
      sessionStorage.setItem('userData', JSON.stringify(userData))
    }
  }

  async function fetchUserProfile() {
    try {
      const response = await api.get('/user/profile')
      if (response.data.success) {
        user.value = { ...user.value, ...response.data.data }
      }
    } catch (error) {
      console.error('Error fetching user profile:', error)
    }
  }

  // Resto de las funciones...

  return {
    user,
    isAuthenticated,
    login,
    logout,
    initialize,
    fetchUserProfile,
  }
})
