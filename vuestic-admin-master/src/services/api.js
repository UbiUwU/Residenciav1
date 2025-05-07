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
  getMaestros() {
    return apiClient.get('/maestros')
  },

  login(email, password) {
    return apiClient.post('/login', {
      correo: email,
      password: password,
    })
  },

  // Nuevos métodos para asignaturas
  getAsignaturas() {
    return apiClient.get('/asignaturas')
  },

  getAsignatura(clave) {
    return apiClient.get(`/asignaturas/${clave}`)
  },

  getPlantillas() {
    return apiClient.get('/plantillas')
  },

  crearPlantilla(formData) {
    return apiClient.post('/plantillas', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
  },
  
  actualizarPlantilla(id, data) {
    return apiClient.post(`/plantillas/${id}?_method=PUT`, data, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
  },

  eliminarPlantilla(id) {
    return apiClient.delete(`/plantillas/${id}`)
  }

}

