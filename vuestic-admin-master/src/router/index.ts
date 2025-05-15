/* eslint-disable prettier/prettier */
import { createRouter, createWebHistory, RouteRecordRaw } from 'vue-router'
import { useAuthStore } from '../services/auth' // Importa tu store de autenticación

import AuthLayout from '../layouts/AuthLayout.vue'
import AppLayout from '../layouts/AppLayout.vue'
//Todo lo que sea test es para pruebas
import Asignaturas from '../test/Asignaturas.vue'
import AsignaturaDetail from '../test/AsignaturaDetail.vue'
import departamento from '../test/departamentos.vue'
import InHorarioMaestro from '../test/IngresarHorario.vue'
import perfil from '../test/perfil.vue'
import visualizarhorario from '../test/visualizarhorario.vue'
import Plantilla from '../pages/Plantilla/PlantillaAdmin.vue'

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
      {
        path: '/asignaturas',
        name: 'asignaturas',
        component: Asignaturas,
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
        path: '/departamento',
        name: 'departamento',
        component: departamento,
      },
      {
        path: '/InHorario',
        name: 'HorarioMaestro',
        component: InHorarioMaestro,
      },
      {
        path: 'plantillas',
        name: 'PlantillaAdmin',
        component: Plantilla,
      },
      {
        path: '/asignaturas/:clave',
        name: 'asignatura-detail',
        component: AsignaturaDetail,
        props: true,
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
        name: 'users',
        path: 'users',
        component: () => import('../pages/users/UsersPage.vue'),
        meta: { requiresAuth: true },
      },
      {
        name: 'projects',
        path: 'projects',
        component: () => import('../pages/projects/ProjectsPage.vue'),
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
