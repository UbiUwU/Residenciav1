import type { INavigationRoute } from './types'


export const SuperRoutes: INavigationRoute[] = [
  {
    name: 'dashboard-super',
    displayName: 'menu.dashboard',
    meta: {
      icon: 'vuestic-iconset-dashboard',
      allowedRoles: [5]
    }
  },
  
    {
    name: 'administracion',
    displayName: 'Administracion',
    meta: {
      icon: 'manage_accounts',
      allowedRoles: [5]
    },
    children: [
      
      {
        name: 'admin-departamentos',
        displayName: 'Departamentos',
        meta: { allowedRoles: [5] }
      },
     
      {
        name: 'admin-roles',
        displayName: 'Roles',
        meta: { allowedRoles: [5] }
      },
      {
        name: 'admin-usuarios',
        displayName: 'Usuarios',
        meta: { allowedRoles: [5] }
      },
      {
        name: 'admin-maestros',
        displayName: 'Maestros',
        meta: { allowedRoles: [5] }
      },
      {
        name: 'admin-periodos',
        displayName: 'Periodos',
        meta: { allowedRoles: [5] }
      }
    ]
  },


]