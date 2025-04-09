/* eslint-disable prettier/prettier */
import axios from 'axios'

const apiClient = axios.create({
  // eslint-disable-next-line prettier/prettier
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
    // eslint-disable-next-line prettier/prettier
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
}
