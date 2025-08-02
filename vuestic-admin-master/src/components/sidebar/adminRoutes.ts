import type { INavigationRoute } from './types'

export const adminRoutes: INavigationRoute[] = [
  {
    name: 'dashboard',
    displayName: 'menu.dashboard',
    meta: {
      icon: 'vuestic-iconset-dashboard',
      allowedRoles: [3],
    },
  },
  // Sección Administración
  {
    name: 'administracion',
    displayName: 'Administracion',
    meta: {
      icon: 'manage_accounts',
      allowedRoles: [3],
    },
    children: [
      {
        name: 'Comisionar',
        displayName: 'Comisionar',
        meta: { allowedRoles: [3] },
      },
      {
        name: 'admin-reporte',
        displayName: 'Reporte Final',
        meta: { allowedRoles: [3] },
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
