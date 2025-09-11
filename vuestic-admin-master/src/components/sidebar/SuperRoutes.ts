import type { INavigationRoute } from './types'

export const SuperRoutes: INavigationRoute[] = [
  {
    name: 'dashboard-super',
    displayName: 'menu.dashboard',
    meta: {
      icon: 'vuestic-iconset-dashboard',
      allowedRoles: [5],
    },
  },

  {
    name: 'administracion',
    displayName: 'Gestionar Sistema',
    meta: {
      icon: 'manage_accounts',
      allowedRoles: [5],
    },
    children: [
      {
        name: 'super-roles',
        displayName: 'Roles',
        meta: { allowedRoles: [5] },
      },
      {
        name: 'super-periodos',
        displayName: 'Periodos',
        meta: { allowedRoles: [5] },
      },
      {
        name: 'super-maestros',
        displayName: 'Maestros',
        meta: { allowedRoles: [5] },
      },
      {
        name: 'super-evento',
        displayName: 'Evento',
        meta: { allowedRoles: [5] },
      },
      {
        name: 'asignaturas',
        displayName: 'Asignaturas',
        meta: { allowedRoles: [5] },
      },
      {
        name: 'super-usuario',
        displayName: 'Añadir Usuario',
        meta: { allowedRoles: [5] },
      },
    ],
  },

  // Rutas compartidas (las mismas que en teacherRoutes)
  {
    name: 'settings',
    displayName: 'menu.settings',
    meta: {
      icon: 'settings',
      allowedRoles: [2, 3],
    },
  },
  {
    name: 'preferences',
    displayName: 'menu.preferences',
    meta: {
      icon: 'manage_accounts',
      allowedRoles: [2, 3],
    },
  },
  {
    name: 'faq',
    displayName: 'menu.faq',
    meta: {
      icon: 'quiz',
      allowedRoles: [2, 3],
    },
  },
]
