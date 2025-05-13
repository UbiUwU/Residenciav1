/* eslint-disable prettier/prettier */
import axios from 'axios'

const apiClient = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json',
  },
})

apiClient.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('authToken')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  },
  (error) => {
    return Promise.reject(error)
  },
)

export default {
  // Autenticación
  login(email, password) {
    return apiClient.post('/login', {
      correo: email,
      password: password,
    })
  },
  // Agregar este nuevo método
  getUserData() {
    return apiClient.get('/me')
  },
  getMe() {
    return apiClient.get('/me')
  },
  // Maestros
  getMaestros() {
    return apiClient.get('/maestros')
  },

  // Asignaturas
  getAsignaturas() {
    return apiClient.get('/asignaturas')
  },

  getAsignatura(clave) {
    return apiClient.get(`/asignaturas/${clave}`)
  },
}
