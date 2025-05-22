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
  //Meotodo de inicio de sesión con ID del usuario que se obtiene al iniciar sesión
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

  getMaestro(id) {
    return apiClient.get(`/maestros/${id}`)
  },

  crearMaestro(data) {
    return apiClient.post('/maestros', data)
  },

  actualizarMaestro(id, data) {
    return apiClient.put(`/maestros/${id}`, data)
  },

  eliminarMaestro(id) {
    return apiClient.delete(`/maestros/${id}`)
  },

  // Asignaturas
  getAsignaturas() {
    return apiClient.get('/asignaturas')
  },

  getAsignatura(clave) {
    return apiClient.get(`/asignaturas/${clave}`)
  },

  // Horarios de Maestros /horarios/{maestro_id}
  getHorariosDeMaestro(maestroId) {
    return apiClient.get(`/horarios/${maestroId}`)
  },

  createHorarioMaestro(data) {
    return apiClient.post('/horarios', data)
  },

  updateHorarioMaestro(id, data) {
    return apiClient.put(`/horarios/${id}`, data)
  },

  deleteHorarioMaestro(id) {
    return apiClient.delete(`/horarios/${id}`)
  },

  //Departamentos
  getDepartamentos() {
    return apiClient.get('/departamentos')
  },
  crearDepartamento(data) {
    return apiClient.post('/departamentos', data)
  },
  actualizarDepartamento(id, data) {
    return apiClient.put(`/departamentos/${id}`, data)
  },
  eliminarDepartamento(id) {
    return apiClient.delete(`/departamentos/${id}`)
  },
  obtenerDepartamento(id) {
    return apiClient.get(`/departamentos/${id}`)
  },

  // Usuarios
  getUsuarios() {
    return apiClient.get('/usuarios')
  },

  getUsuario(id) {
    return apiClient.get(`/usuarios/${id}`)
  },

  crearUsuario(data) {
    return apiClient.post('/usuarios', data)
  },

  actualizarUsuario(id, data) {
    return apiClient.put(`/usuarios/${id}`, data)
  },

  eliminarUsuario(id) {
    return apiClient.delete(`/usuarios/${id}`)
  },

  // Métodos para roles
  getRoles() {
    return apiClient.get('/roles')
  },

  crearRol(data) {
    return apiClient.post('/roles', data)
  },

  actualizarRol(id, data) {
    return apiClient.put(`/roles/${id}`, data)
  },

  eliminarRol(id) {
    return apiClient.delete(`/roles/${id}`)
  },

  // períodos
  getPeriodos() {
    return apiClient.get('/periodos-escolares')
  },

  crearPeriodo(data) {
    return apiClient.post('/periodos-escolares', data)
  },

  actualizarPeriodo(id, data) {
    return apiClient.put(`/periodos-escolares/${id}`, data)
  },

  eliminarPeriodo(id) {
    return apiClient.delete(`/periodos-escolares/${id}`)
  },

  // Tipos de evento
  getTiposEvento() {
    return apiClient.get('/tipos-evento')
  },

  crearTipoEvento(data) {
    return apiClient.post('/tipos-evento', data)
  },

  actualizarTipoEvento(id, data) {
    return apiClient.put(`/tipos-evento/${id}`, data)
  },

  eliminarTipoEvento(id) {
    return apiClient.delete(`/tipos-evento/${id}`)
  },

  // Público destino
  getPublicosDestino() {
    return apiClient.get('/publicos-destino')
  },

  crearPublicoDestino(data) {
    return apiClient.post('/publicos-destino', data)
  },

  actualizarPublicoDestino(id, data) {
    return apiClient.put(`/publicos-destino/${id}`, data)
  },

  eliminarPublicoDestino(id) {
    return apiClient.delete(`/publicos-destino/${id}`)
  },

  getEventos() {
    return apiClient.get('/eventos')
  },
  // Crear un nuevo evento
  crearEvento(data) {
    return apiClient.post('/eventos', data)
  },

  // Actualizar un evento
  actualizarEvento(id, data) {
    return apiClient.put(`/eventos/${id}`, data)
  },

  // Eliminar un evento
  eliminarEvento(id) {
    return apiClient.delete(`/eventos/${id}`)
  }
}


