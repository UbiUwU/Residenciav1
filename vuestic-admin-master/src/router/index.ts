/* eslint-disable prettier/prettier */
import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router'
import { useAuthStore } from '../services/auth' // Importa tu store de autenticación

import AuthLayout from '../layouts/AuthLayout.vue'
import AppLayout from '../layouts/AppLayout.vue'
//Todo lo que sea test es para pruebas
import Asignaturas from '../test/Asignaturas.vue'
import AsignaturasMaestro from '../pages/admin/pages/AsignaturasMaestros.vue'
import AsignaturaDetail from '../test/AsignaturaDetail.vue'
import departamento from '../test/departamentos.vue'
import InHorarioMaestro from '../test/IngresarHorario.vue'
import perfil from '../test/perfil.vue'
import visualizarhorario from '../test/visualizarhorario.vue'
import maestro from '../test/maestro.vue'
import usuarios from '../test/usuarios.vue'
import Plantilla from '../pages/Plantilla/PlantillaAdmin.vue'
import roles from '../test/roles.vue'
import avance from '../test/avanceprogra.vue'
import periodos from '../test/periodos.vue'
import eventosmdestino from '../test/tiposeventos_Destino.vue'
import horaraio2 from '../test/HorarioMaestro.vue'

import RouteViewComponent from '../layouts/RouterBypass.vue'

const routes: Array<RouteRecordRaw> = [
  {
    path: '/:pathMatch(.*)*',
    redirect: { name: 'dashboard' },
  },
  {
    name: 'admin',
    path: '/',
    component: AppLayout,
    redirect: { name: 'dashboard' },
    meta: { requiresAuth: true }, // Indica que esta ruta requiere autenticación
    children: [
      {
        name: 'dashboard',
        path: 'dashboard',
        component: () => import('../pages/admin/dashboard/Dashboard.vue'),
        meta: { requiresAuth: true }, // Requiere autenticación
      },

      //Rutas para la lista "Generales"
      {
        name: 'Nombramientos',
        path: 'Nombramientos',
        component: () => import('../pages/Generales/Nombramientos.vue'),
        meta: { requiresAuth: true },
      },

      {
        name: 'Horario',
        path: 'Horario',
        component: () => import('../pages/Generales/Horario.vue'),
        meta: { requiresAuth: true },
      },

      {
        name: 'Comisiones',
        path: 'Comisiones',
        component: () => import('../pages/Comisiones/Comisiones.vue'),
        meta: { requiresAuth: true },
      },
      {
        name: 'Constancias',
        path: 'Constancias',
        component: () => import('../pages/Comisiones/Constancias.vue'),
      },
      //Ruta y vista para el avance programatico
      {
        name: 'AvanceProgramatico',
        path: 'AvanceProgramatico',
        component: () => import('../pages/SeguimientoD/AvanceProgramatico.vue'),
        meta: { requiresAuth: true },
      },
      //Ruta para asesorias
      {
        name: 'Asesorias',
        path: 'Asesorias',
        component: () => import('../pages/SeguimientoD/Asesorias.vue'),
        meta: { requiresAuth: true },
      },
      {
        name: 'CarpetaEvidencias',
        path: 'CarpetaEvidencias',
        component: () => import('../pages/SeguimientoD/CarpetaEvidencias.vue'),
        meta: { requiresAuth: true },
      },
      {
        name: 'InstrumendacionDidactica',
        path: 'InstrumendacionDidactica',
        component: () => import('../pages/SeguimientoD/InstrumentacionDidactica.vue'),
      },
      {
        name: 'MateriaEvidenciasView',
        path: 'MateriaEvidenciasView',
        component: () => import('../pages/SeguimientoD/MateriaEvidenciasView.vue'),
        meta: { requiresAuth: true },
      },

      {
        name: 'AcuseEstudiante',
        path: 'AcuseEstudiante',
        component: () => import('../pages/SeguimientoD/AcuseEstudiante.vue'),
        meta: { requiresAuth: true },
      },

      {
        path: '/asignaturas',
        name: 'asignaturas',
        component: Asignaturas,
      },
      {
        path: '/roles',
        name: 'roles',
        component: roles,
      },
      {
        path: '/horario',
        name: 'horario',
        component: visualizarhorario,
      },
      {
        path: '/perfil',
        name: 'perfil',
        component: perfil,
      },
      {
        path: '/eventosmdestino',
        name: 'eventosmdestino',
        component: eventosmdestino,
      },
      {
        path: '/periodos',
        name: 'periodos',
        component: periodos,
      },
      {
        path: '/avance',
        name: 'avance',
        component: avance,
      },
      {
        path: '/departamento',
        name: 'departamento',
        component: departamento,
      },
      {
        path: '/InHorario',
        name: 'HorarioMaestro',
        component: horaraio2,
      },
      {
        path: '/maestro',
        name: 'maestro',
        component: maestro,
      },
      {
        path: '/usuarios',
        name: 'usuarios',
        component: usuarios,
      },
      {
        path: '/asignaturas/:clave',
        name: 'asignatura',
        component: AsignaturaDetail,
        props: true,
      },

      {
        path: '/asignaturas/complete/:clave',
        name: 'AsignaturaCompleta',
        component: () => import('../test/asignaturas2.vue'),
      },

      {
        name: 'settings',
        path: 'settings',
        component: () => import('../pages/settings/Settings.vue'),
        meta: { requiresAuth: true },
      },
      {
        name: 'preferences',
        path: 'preferences',
        component: () => import('../pages/preferences/Preferences.vue'),
        meta: { requiresAuth: true },
      },
      {
        name: 'Usuarios',
        path: 'Usuarios',
        component: () => import('../pages/users/PaginaUsuarios.vue'),
        meta: { requiresAuth: true },
      },
      {
        name: 'projects',
        path: 'projects',
        component: () => import('../pages/projects/ProjectsPage.vue'),
      },
      {
        path: '/asignaturas/maestro/:tarjeta',
        name: 'materiasMaestro',
        component: () => import('../pages/admin/pages/AsignaturasMaestros.vue')
      },
      {
        path: '/pdf',
        name: 'pdf',
        component: () => import('../pages/admin/pages/AsignaturaDetail.vue')
      },
      {
        name: 'Asignaturas',
        path: 'Asignaturas',
        component: Asignaturas,
      },
      {
        name: 'payments',
        path: '/payments',
        component: RouteViewComponent,
        children: [
          {
            name: 'payment-methods',
            path: 'payment-methods',
            component: () => import('../pages/payments/PaymentsPage.vue'),
          },
          {
            name: 'billing',
            path: 'billing',
            component: () => import('../pages/billing/BillingPage.vue'),
          },
          {
            name: 'pricing-plans',
            path: 'pricing-plans',
            component: () => import('../pages/pricing-plans/PricingPlans.vue'),
          },
        ],
      },
      {
        name: 'faq',
        path: '/faq',
        component: () => import('../pages/faq/FaqPage.vue'),
      },
    ],
  },

  {
    path: '/auth',
    component: AuthLayout,
    children: [
      {
        name: 'login',
        path: 'login',
        component: () => import('../pages/auth/Login.vue'),
      },
      {
        name: 'signup',
        path: 'signup',
        component: () => import('../pages/auth/Signup.vue'),
      },
      {
        name: 'recover-password',
        path: 'recover-password',
        component: () => import('../pages/auth/RecoverPassword.vue'),
      },
      {
        name: 'recover-password-email',
        path: 'recover-password-email',
        component: () => import('../pages/auth/CheckTheEmail.vue'),
      },
      {
        path: '',
        redirect: { name: 'login' },
      },
    ],
  },

  //Rutas para las pantallas de "Comisiones"

  //Rutas para las pantallas S.I.G

  {
    path: '/SeguimientoD',
    component: RouteViewComponent,
    children: [
      {
        name: 'EvaluacionDiagnostica',
        path: 'EvaluacionDiagnostica',
        component: () => import('../pages/SeguimientoD/EvaluacionDiagnostica.vue'),
      },

      {
        name: 'CalificacionesParciales',
        path: 'CalificacionesParciales',
        component: () => import('../pages/SeguimientoD/CalificacionesParciales.vue'),
      },

      //{
      //path: '',
      //redirect: { name: 'login' },
      //},
    ],
  },

  //Rutas para las pantallas de "liberacion de actividades"

  {
    path: '/Liberacion',
    component: RouteViewComponent,
    children: [
      {
        name: 'ReporteFinal',
        path: 'ReporteFinal',
        component: () => import('../pages/Liberacion/ReporteFinal.vue'),
      },
      {
        name: 'ActasCalificaciones',
        path: 'ActasCalificaciones',
        component: () => import('../pages/Liberacion/ActasCalificaciones.vue'),
      },
      {
        name: 'LiberacionActividadesD',
        path: 'LiberacionActividadesD',
        component: () => import('../pages/Liberacion/LiberacionActividadesD.vue'),
      },
      {
        name: 'LiberacionActividadesA',
        path: 'LiberacionActividadesA',
        component: () => import('../pages/Liberacion/LiberacionActividadesA.vue'),
      },

      //{
      //path: '',
      //redirect: { name: 'login' },
      //},
    ],
  },

  {
    name: '404',
    path: '/404',
    component: () => import('../pages/404.vue'),
  },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

// **Guarda de navegación para redirigir a usuarios no autenticados**
router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  const isAuthenticated = authStore.isAuthenticated

  if (to.meta.requiresAuth && !isAuthenticated) {
    next({ name: 'login' }) // Redirige al login si no está autenticado
  } else {
    next() // Permite la navegación si está autenticado o la ruta no necesita autenticación
  }
})

export default router
