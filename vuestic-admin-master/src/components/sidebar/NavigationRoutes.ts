export interface INavigationRoute {
  name: string
  displayName: string
  meta: { icon: string }
  children?: INavigationRoute[]
}

export default {
  root: {
    name: '/',
    displayName: 'navigationRoutes.home',
  },
  routes: [
    {
      name: 'dashboard',
      displayName: 'menu.dashboard',
      meta: {
        icon: 'vuestic-iconset-dashboard',
      },
    },

    //Rutas para generales
    {
      name: 'Generales',
      displayName: 'Generales',
      meta: {
        icon: 'folder_open',
      },
      children: [
        {
          name: 'Horario',
          displayName: 'Horario',
        },
        {
          name: 'Nombramientos',
          displayName: 'Nombramientos',
        },
      ],
    },

    //Rutas para comisiones

    {
      name: 'Comisiones',
      displayName: 'Comisiones',
      meta: {
        icon: 'folder',
      },
      children: [
        {
          name: 'Comisiones',
          displayName: 'Comisiones',
        },
        {
          name: 'Constancias',
          displayName: 'Constancias',
        },
      ],
    },

    //Rutas para S.I.G
    {
      name: 'sig',
      displayName: 'S.I.G',
      meta: {
        icon: 'folder_special',
      },
      children: [
        {
          name: 'AvanceProgramatico',
          displayName: 'Avance Programatico',
        },
        {
          name: 'InstrumendacionDidactica',
          displayName: 'Instrumentación Didáctica', // Emoji de construcción
        },
        {
          name: 'AcuseEstudiante',
          displayName: 'Acuse Estudiante',
        },
        //testeo de paginas "asignaturas" "maestro" "periodos" "departamentos"
        {
          name: 'asignaturas',
          displayName: '🛠️Asignaturas',
        },
        {
          name: 'CarpetaEvidencias',
          displayName: 'Carpeta Evidencias',
        },
        {
          name: 'Asesorias',
          displayName: 'Asesorias',
        },
      ],
    },
    //Rutas para "Projectos Individuales"
    {
      name: 'projects',
      displayName: 'Projectos individuales',
      meta: {
        icon: 'folder_shared',
      },
    },

    ////Rutas para "Liberacion de actividades"
    {
      name: 'Liberacion',
      displayName: '🛠️Liberacion de actividades',
      meta: {
        icon: 'lock_open',
      },
      children: [
        {
          name: 'ReporteFinal',
          displayName: 'Reporte Final',
        },
        {
          name: 'ActasCalificaciones',
          displayName: 'Actas Calificaciones',
        },
        {
          name: 'LiberacionActividadesD',
          displayName: 'Liberacion Docente',
        },
        {
          name: 'LiberacionActividadesA',
          displayName: 'Liberacion Academica',
        },
      ],
    },

    {
      name: 'users',
      displayName: 'menu.users',
      meta: {
        icon: 'group',
      },
    },
    {
      name: 'comisionar',
      displayName: 'Comisionar',
      meta: {
        icon: 'group',
      },
    },

    

    //Rutas para la seccion de pagos (que no se plenea usar)
    /*  {
      name: 'payments',
      displayName: 'menu.payments',
      meta: {
        icon: 'credit_card',
      },
      children: [
        {
          name: 'payment-methods',
          displayName: 'menu.payment-methods',
        },
        {
          name: 'pricing-plans',
          displayName: 'menu.pricing-plans',
        },
        {
          name: 'billing',
          displayName: 'menu.billing',
        },
      ],
    }, */

    {
      name: 'faq',
      displayName: 'menu.faq',
      meta: {
        icon: 'quiz',
      },
    },
    {
      name: '404',
      displayName: 'menu.404',
      meta: {
        icon: 'vuestic-iconset-files',
      },
    },
    {
      name: 'preferences',
      displayName: 'menu.preferences',
      meta: {
        icon: 'manage_accounts',
      },
    },
    {
      name: 'settings',
      displayName: 'menu.settings',
      meta: {
        icon: 'settings',
      },
    },
  ] as INavigationRoute[],
}