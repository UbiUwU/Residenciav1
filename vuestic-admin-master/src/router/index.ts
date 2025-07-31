/* eslint-disable prettier/prettier */
import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router'
import { useAuthStore } from '../services/auth'
import { ROLES } from '../constants/roles' // Importación de roles

// Layouts
import AuthLayout from '../layouts/AuthLayout.vue'
import AppLayout from '../layouts/AppLayout.vue'
import RouteViewComponent from '../layouts/RouterBypass.vue'

// Componentes de prueba
import Asignaturas from '../test/Asignaturas.vue'
import AsignaturaDetail from '../test/AsignaturaDetail.vue'
import departamento from '../test/departamentos.vue'
import InHorarioMaestro from '../test/IngresarHorario.vue'
import MateriasMaestroPage from '../pages/admin/pages/AsignaturasMaestros.vue'
import reporteFinal from '../pages/admin/pages/ReporteFinal.vue'
import visualizarhorario from '../test/visualizarhorario.vue'
import PDFView from '../pages/admin/pages/AsignaturaDetail.vue'
import maestro from '../pages/admin/pages/PaginaUsuarios.vue'
import usuarios from '../test/usuarios.vue'
import roles from '../test/roles.vue'
import avance from '../test/avanceprogra.vue'
import periodos from '../test/periodos.vue'
import eventosmdestino from '../test/tiposeventos_Destino.vue'
import horaraio2 from '../test/HorarioMaestro.vue'

const routes: Array<RouteRecordRaw> = [
  {
    path: '/:pathMatch(.*)*',
    redirect: (to) => {
      const authStore = useAuthStore()
      return authStore.isAdmin ? 
        { name: 'dashboard' } : 
        { name: 'dashboard-teacher' }
    },
  },
  
  // Rutas públicas de autenticación
  {
    path: '/auth',
    component: AuthLayout,
    children: [
      {
        name: 'login',
        path: 'login',
        component: () => import('../pages/auth/Login.vue')
      },
      {
        name: 'signup',
        path: 'signup',
        component: () => import('../pages/auth/Signup.vue')
      },
      {
        name: 'recover-password',
        path: 'recover-password',
        component: () => import('../pages/auth/RecoverPassword.vue')
      },
      {
        name: 'recover-password-email',
        path: 'recover-password-email',
        component: () => import('../pages/auth/CheckTheEmail.vue')
      },
      {
        path: '',
        redirect: { name: 'login' }
      },
    ]
  },

  // Rutas compartidas para ambos roles
  {
    path: '/',
    component: AppLayout,
    meta: { requiresAuth: true },
    children: [
      // FAQ (puede ser pública o requerir autenticación)
      {
        name: 'faq',
        path: 'faq',
        component: () => import('../pages/faq/FaqPage.vue'),
        meta: { requiresAuth: false } // Opcional: hacerla pública
      },
      
      // Otras rutas compartidas que requieren autenticación
      {
        name: 'settings',
        path: 'settings',
        component: () => import('../pages/settings/Settings.vue')
      },
      {
        name: 'preferences',
        path: 'preferences',
        component: () => import('../pages/preferences/Preferences.vue')
      },
     
    ]
  },

  // Área de Administrador
  {
    name: 'admin',
    path: '/admin',
    component: AppLayout,
    redirect: { name: 'dashboard' },
    meta: { requiresAuth: true, allowedRoles: [ROLES.ADMIN] },
    children: [
      {
        name: 'dashboard',
        path: 'dashboard',
        component: () => import('../pages/admin/dashboard/Dashboard.vue')
      },
      {
        name: 'Comisionar',
        path: 'Comisionar',
        component: () => import('../pages/admin/pages/Comisionar.vue')
      },
      {
        name: 'admin-usuarios',
        path: 'usuarios',
        component: usuarios
      },
      {
        name: 'admin-departamentos',
        path: 'departamentos',
        component: departamento
      },
      {
        name: 'admin-roles',
        path: 'roles',
        component: roles
      },
      {
        name: 'admin-periodos',
        path: 'periodos',
        component: periodos
      },
      {
        name: 'admin-maestros',
        path: 'maestros',
        component: maestro
      },
      {
        path: '/materiasMaestro/:tarjeta',
        name: 'materiasMaestro',
        component: MateriasMaestroPage,
      },
      {
        path: '/reporte-final/:tarjeta',
        name: 'reporteFinal',
        component: reporteFinal,
      },
      {
        path: '/pdf/:tarjeta/:tipo',
        name: 'pdf',
        component: PDFView,
      }
      
    ]
  },

  // Área de Maestro
  {
    name: 'teacher',
    path: '/teacher',
    component: AppLayout,
    redirect: { name: 'dashboard-teacher' },
    meta: { requiresAuth: true, allowedRoles: [ROLES.TEACHER] },
    children: [
      {
        name: 'dashboard-teacher',
        path: 'dashboard-teacher',
        component: () => import('../pages/maestro/dashboard/Dashboard.vue')
      },
      {
        name: 'Nombramientos',
        path: 'Nombramientos',
        component: () => import('../pages/maestro/Generales/Nombramientos.vue')
      },
      {
        name: 'Horario',
        path: 'Horario',
        component: () => import('../pages/maestro/Generales/Horario.vue')
      },
      {
        name: 'comisiones',
        path: 'comisiones',
        component: () => import('../pages/maestro/Comisiones/Comisiones.vue')
      },
      {
        name: 'constancias',
        path: 'constancias',
        component: () => import('../pages/maestro/Comisiones/Constancias.vue')
      },
      {
        name: 'avance-Programatico',
        path: 'avance-Programatico',
        component: () => import('../pages/maestro/SeguimientoD/AvanceProgramatico.vue')
      },
      {
        name: 'asesorias',
        path: 'asesorias',
        component: () => import('../pages/maestro/SeguimientoD/Asesorias.vue')
      },
      {
        name: 'carpeta-evidencias',
        path: 'carpeta-evidencias',
        component: () => import('../pages/maestro/SeguimientoD/CarpetaEvidencias.vue')
      },
      {
        name: 'instrumentacion-didactica',
        path: 'instrumentacion-didactica',
        component: () => import('../pages/maestro/SeguimientoD/InstrumentacionDidactica.vue')
      },

      {
        name: 'acuse-estudiante',
        path: 'acuse-estudiante',
        component: () => import('../pages/maestro/SeguimientoD/AcuseEstudiante.vue')
      }
    ]
  },

  // Seguimiento Docente (Teacher)
  {
    path: '/seguimiento',
    component: RouteViewComponent,
    meta: { requiresAuth: true, allowedRoles: [ROLES.TEACHER] },
    children: [
      {
        name: 'evaluacion-diagnostica',
        path: 'evaluacion-diagnostica',
        component: () => import('../pages/maestro/SeguimientoD/EvaluacionDiagnostica.vue')
      },
      {
        name: 'calificaciones-parciales',
        path: 'calificaciones-parciales',
        component: () => import('../pages/maestro/SeguimientoD/CalificacionesParciales.vue')
      }
    ]
  },

  // Liberación de actividades (Teacher)
  {
    path: '/liberacion',
    component: RouteViewComponent,
    meta: { requiresAuth: true, allowedRoles: [ROLES.TEACHER] },
    children: [
      {
        name: 'reporte-final',
        path: 'reporte-final',
        component: () => import('../pages/maestro/Liberacion/ReporteFinal.vue')
      },
      {
        name: 'actas-calificaciones',
        path: 'actas-calificaciones',
        component: () => import('../pages/maestro/Liberacion/ActasCalificaciones.vue')
      },
      {
        name: 'liberacion-actividades-d',
        path: 'liberacion-actividades-d',
        component: () => import('../pages/maestro/Liberacion/LiberacionActividadesD.vue')
      },
      {
        name: 'liberacion-actividades-a',
        path: 'liberacion-actividades-a',
        component: () => import('../pages/maestro/Liberacion/LiberacionActividadesA.vue')
      }
    ]
  },

  // Rutas de prueba (solo desarrollo)
  ...(process.env.NODE_ENV === 'development' ? [
    {
      path: '/test',
      component: AppLayout,
      children: [
        {
          path: 'asignaturas',
          name: 'asignaturas',
          component: Asignaturas
        },
        {
          path: 'asignaturas/:clave',
          name: 'asignatura-detail',
          component: AsignaturaDetail,
          props: true
        },
        {
          path: 'asignaturas/complete/:clave',
          name: 'asignatura-completa',
          component: () => import('../test/asignaturas2.vue')
        },
        {
          path: 'visualizar-horario',
          name: 'visualizar-horario',
          component: visualizarhorario
        },
        {
          path: 'ingresar-horario',
          name: 'ingresar-horario',
          component: InHorarioMaestro
        },
        {
          path: 'eventos-destino',
          name: 'eventos-destino',
          component: eventosmdestino
        },
        {
          path: 'avance',
          name: 'avance',
          component: avance
        },
        {
          path: 'horario-maestro',
          name: 'horario-maestro',
          component: horaraio2
        }
      ]
    }
  ] : []),

  // Rutas de error
  {
    name: 'unauthorized',
    path: '/unauthorized',
    component: () => import('../pages/404.vue')
  },
  {
    name: '404',
    path: '/404',
    component: () => import('../pages/404.vue')
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else {
      return { top: 0 }
    }
  }
})

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  
  // No aplicar redirección para rutas públicas
  if (!to.meta.requiresAuth) {
    return next()
  }

  // Si no está autenticado, redirigir a login
  if (!authStore.isAuthenticated) {
    return next({ name: 'login', query: { redirect: to.fullPath } })
  }

  // Verificar roles solo si la ruta los requiere
  if (to.meta.allowedRoles) {
    if (!authStore.userRole) {
      // Intentar restaurar sesión si no hay rol
      if (authStore.restoreSession()) {
        return next(to.fullPath) // Reintentar navegación
      }
      return next({ name: 'login' })
    }
    
    if (!to.meta.allowedRoles.includes(authStore.userRole)) {
      return next({ name: 'unauthorized' })
    }
  }

  next()
})

export default router