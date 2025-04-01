/* eslint-disable prettier/prettier */
// stores/auth.ts
import { defineStore } from 'pinia'
import { ref } from 'vue'
import router from '../router'
interface User {
  idusuario: number
  correo: string
  token: string
  idrol: number
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

  function logout() {
    user.value = null
    isAuthenticated.value = false
    localStorage.removeItem('authToken')
    localStorage.removeItem('userData')
    sessionStorage.removeItem('authToken')
    sessionStorage.removeItem('userData')
    router.push({ name: 'login' })
  }

  function initialize() {
    const token = localStorage.getItem('authToken') || sessionStorage.getItem('authToken')
    const userData = JSON.parse(localStorage.getItem('userData') || sessionStorage.getItem('userData') || 'null')

    if (token && userData) {
      user.value = userData
      isAuthenticated.value = true
    }
  }

  return { user, isAuthenticated, login, logout, initialize }
})
