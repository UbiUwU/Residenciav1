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
  //Metodo de inicio de sesión con ID del usuario que se obtiene al iniciar sesión
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

  crearComision(data) {
    return apiClient.post('/comisiones', data)
  },

  getComisiones() {
    return apiClient.get('/comisiones')
  },

  actualizarMaestro(id, data) {
    return apiClient.put(`/maestros/${id}`, data)
  },

  eliminarMaestro(id) {
    return apiClient.delete(`/maestros/${id}`)
  },

  getAsignaturasPorMaestro(tarjeta) {
    return apiClient.get(`/asignaturas/maestro/${tarjeta}`)
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

  // Obtener todas las asignaturas
  getAsignaturas() {
    return apiClient.get('/asignaturas')
  },

  // Obtener asignaturas por carrera
  getAsignaturasByCarrera(claveCarrera) {
    return apiClient.get(`/asignaturas/carrera/${claveCarrera}`)
  },

  // Obtener asignatura por clave
  getAsignatura(clave) {
    return apiClient.get(`/asignaturas/${clave}`)
  },

  // Crear nueva asignatura
  crearAsignatura(data) {
    return apiClient.post('/asignaturas', data)
  },

  // Actualizar asignatura
  actualizarAsignatura(clave, data) {
    return apiClient.put(`/asignaturas/${clave}`, data)
  },

  // Eliminar asignatura
  eliminarAsignatura(clave) {
    return apiClient.delete(`/asignaturas/${clave}`)
  },

  getAsignaturaCompleta(clave) {
    return apiClient.get(`/asignaturas/complete/${clave}`)
  },

  getAsignaturaByTarjetaCompleta(clave) {
    return apiClient.get(`/asignaturas/maestro/${clave}`)
  },
  ///////
  getDetalleGruposByTarjeta(clave) {
    return apiClient.get(`/asignaturas/grupos/${clave}`)
  },

  crearCalificaciones(data) {
    return apiClient.post('/calificaciones', data)
  },

  getDetalleGruposPorCarreraByTarjeta(clave) {
    return apiClient.get(`/calificaciones/reporte/${clave}`)
  },


  // Obtener todas las carreras
  getCarreras() {
    return apiClient.get('/carreras');
  },

  // Obtener una carrera por su clave
  getCarrera(clave) {
    return apiClient.get(`/carreras/${clave}`);
  },

  // Crear una nueva carrera
  createCarrera(carreraData) {
    return apiClient.post('/carreras', carreraData);
  },

  // Actualizar una carrera existente
  updateCarrera(clave, carreraData) {
    return apiClient.put(`/carreras/${clave}`, carreraData);
  },

  // Eliminar una carrera
  deleteCarrera(clave) {
    return apiClient.delete(`/carreras/${clave}`);
  },
}
