import type { INavigationRoute } from './types'

export const teacherRoutes: INavigationRoute[] = [
  {
    name: 'dashboard-teacher',
    displayName: 'menu.dashboard',
    meta: {
      icon: 'vuestic-iconset-dashboard',
      allowedRoles: [2] // TEACHER
    }
  },
  // Sección "Generales"
  {
    name: 'Generales',
    displayName: 'Generales',
    meta: {
      icon: 'folder_open',
      allowedRoles: [2]
    },
    children: [
      {
        name: 'Horario',
        displayName: 'Horario',
        meta: { allowedRoles: [2] }
      },
      {
        name: 'Nombramientos',
        displayName: 'Nombramientos',
        meta: { allowedRoles: [2] }
      }
    ]
  },
  // Sección "Comisiones"
  {
    name: 'Comisiones',
    displayName: 'Comisiones',
    meta: {
      icon: 'folder',
      allowedRoles: [2]
    },
    children: [
      {
        name: 'comisiones',
        displayName: 'Comisiones',
        meta: { allowedRoles: [2] }
      },
      {
        name: 'constancias',
        displayName: 'Constancias',
        meta: { allowedRoles: [2] }
      }
    ]
  },
  // Sección S.I.G (Seguimiento docente)
  {
    name: 'sig',
    displayName: 'S.I.G',
    meta: {
      icon: 'folder_special',
      allowedRoles: [2]
    },
    children: [
      {
        name: 'avance-Programatico',
        displayName: 'Avance Programatico',
        meta: { allowedRoles: [2] }
      },
      {
        name: 'instrumentacion-didactica',
        displayName: 'Instrumentación Didáctica',
        meta: { allowedRoles: [2] }
      },
      {
        name: 'acuse-estudiante',
        displayName: 'Acuse Estudiante',
        meta: { allowedRoles: [2] }
      },
      {
        name: 'asesorias',
        displayName: 'Asesorias',
        meta: { allowedRoles: [2] }
      },
      {
        name: 'carpeta-evidencias',
        displayName: 'Carpeta Evidencias',
        meta: { allowedRoles: [2] }
      }
    ]
  },
  // Sección "Liberación de actividades"
  {
    name: 'Liberacion',
    displayName: 'Liberacion de actividades',
    meta: {
      icon: 'lock_open',
      allowedRoles: [2]
    },
    children: [
      {
        name: 'reporte-final',
        displayName: 'Reporte Final',
        meta: { allowedRoles: [2] }
      },
      {
        name: 'actas-calificaciones',
        displayName: 'Actas Calificaciones',
        meta: { allowedRoles: [2] }
      },
      {
        name: 'liberacion-actividades-d',
        displayName: 'Liberacion Docente',
        meta: { allowedRoles: [2] }
      },
      {
        name: 'liberacion-actividades-a',
        displayName: 'Liberacion Academica',
        meta: { allowedRoles: [2] }
      }
    ]
  },
  // Rutas genéricas del sistema
  {
    name: 'faq',
    displayName: 'menu.faq',
    meta: {
      icon: 'quiz',
      allowedRoles: [2, 3] // TEACHER y ADMIN
    }
  },
  {
    name: 'preferences',
    displayName: 'menu.preferences',
    meta: {
      icon: 'manage_accounts',
      allowedRoles: [2, 3]
    }
  },
  {
    name: 'settings',
    displayName: 'menu.settings',
    meta: {
      icon: 'settings',
      allowedRoles: [2, 3]
    }
  }
]