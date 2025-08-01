<template>
  <div class="dashboard-container">
    <!-- Encabezado con controles de administración -->
    <VaCard class="mb-4">
      <VaCardContent class="flex items-center justify-between">
        <div>
          <h1 class="va-h3">Panel de Superusuario</h1>
          <p class="text-gray-500">Administración completa del sistema académico</p>
        </div>
        <div class="flex items-center gap-4">
          <VaSelect
            v-model="periodoSeleccionado"
            :options="periodosAcademicos"
            label="Período académico"
            class="w-48"
          />
          <VaButtonGroup>
            <VaButton preset="secondary" icon="refresh" @click="actualizarDatos"> Actualizar </VaButton>
            <VaButton preset="secondary" icon="settings" @click="mostrarConfigSistema"> Configuración </VaButton>
            <VaButton color="danger" icon="security" @click="mostrarHerramientasSeguridad"> Seguridad </VaButton>
          </VaButtonGroup>
        </div>
      </VaCardContent>
    </VaCard>

    <!-- Tarjetas resumen del sistema -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
      <VaCard color="background-secondary" @click="filtrarUsuarios('activos')">
        <VaCardContent>
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 mb-1">Usuarios activos</p>
              <h3 class="va-h3">{{ usuariosActivos }}</h3>
            </div>
            <VaIcon name="people" size="large" color="success" />
          </div>
          <p class="text-xs mt-2">{{ nuevosUsuarios7d }} nuevos en 7 días</p>
        </VaCardContent>
      </VaCard>

      <VaCard color="background-secondary" @click="filtrarDocumentos('pendientes')">
        <VaCardContent>
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 mb-1">Documentos pendientes</p>
              <h3 class="va-h3">{{ documentosPendientesSistema }}</h3>
            </div>
            <VaIcon name="pending_actions" size="large" color="warning" />
          </div>
          <p class="text-xs mt-2">{{ documentosAtrasados }} atrasados</p>
        </VaCardContent>
      </VaCard>

      <VaCard color="background-secondary" @click="mostrarReportes">
        <VaCardContent>
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 mb-1">Reportes generados</p>
              <h3 class="va-h3">{{ reportesGenerados }}</h3>
            </div>
            <VaIcon name="assessment" size="large" color="info" />
          </div>
          <p class="text-xs mt-2">{{ reportesPendientes }} pendientes</p>
        </VaCardContent>
      </VaCard>

      <VaCard color="background-secondary" @click="mostrarAlertasSistema">
        <VaCardContent>
          <div class="flex items-center justify-between">
            <div>
              <p class="text-gray-500 mb-1">Alertas del sistema</p>
              <h3 class="va-h3">{{ alertasSistema }}</h3>
            </div>
            <VaIcon name="warning" size="large" color="danger" />
          </div>
          <p class="text-xs mt-2">{{ alertasCriticas }} críticas</p>
        </VaCardContent>
      </VaCard>
    </div>

    <!-- Sección principal dividida -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
      <!-- Panel izquierdo - Acciones rápidas y auditoría -->
      <div class="lg:col-span-1">
        <!-- Acciones rápidas de administración -->
        <VaCard>
          <VaCardTitle>Acciones rápidas</VaCardTitle>
          <VaCardContent>
            <div class="grid grid-cols-2 gap-3">
              <VaButton
                v-for="accion in accionesRapidas"
                :key="accion.icono"
                :preset="accion.preset"
                :icon="accion.icono"
                class="h-24 flex flex-col items-center justify-center"
                @click="ejecutarAccion(accion.accion)"
              >
                <VaIcon :name="accion.icono" size="large" class="mb-2" />
                {{ accion.texto }}
              </VaButton>
            </div>
          </VaCardContent>
        </VaCard>

        <!-- Registro de actividad reciente -->
        <VaCard class="mt-6">
          <VaCardTitle>Actividad reciente</VaCardTitle>
          <VaCardContent>
            <VaTimeline vertical>
              <VaTimelineItem
                v-for="evento in actividadReciente"
                :key="evento.id"
                :color="getTimelineColor(evento.tipo)"
              >
                <template #before>
                  <div class="va-timeline-item__date text-sm text-gray-500">
                    {{ evento.hora }}
                  </div>
                </template>
                <template #after>
                  <div class="va-timeline-item__text">
                    <p class="font-medium">{{ evento.usuario }}</p>
                    <p class="text-sm">{{ evento.accion }}</p>
                    <VaButton
                      v-if="evento.detalle"
                      preset="plain"
                      size="small"
                      icon="visibility"
                      class="mt-1"
                      @click="verDetalleActividad(evento.id)"
                    >
                      Detalle
                    </VaButton>
                  </div>
                </template>
              </VaTimelineItem>
            </VaTimeline>
            <VaButton v-if="actividadReciente.length === 0" preset="plain" icon="history" class="w-full">
              No hay actividad reciente
            </VaButton>
          </VaCardContent>
        </VaCard>
      </div>

      <!-- Contenido principal -->
      <div class="lg:col-span-2">
        <!-- Gestión de usuarios -->
        <VaCard>
          <VaCardTitle>Gestión de usuarios</VaCardTitle>
          <VaCardContent>
            <div class="flex justify-between items-center mb-4">
              <VaButtonToggle v-model="filtroUsuarios" :options="opcionesFiltroUsuarios" size="small" />
              <div class="flex gap-2">
                <VaInput v-model="busquedaUsuarios" placeholder="Buscar usuario..." class="w-48" />
                <VaButton preset="primary" icon="add" @click="mostrarModalNuevoUsuario"> Nuevo </VaButton>
              </div>
            </div>

            <VaDataTable
              :items="usuariosFiltrados"
              :columns="columnasUsuarios"
              :per-page="5"
              selectable
              selected-color="primary"
              @selectionChange="seleccionUsuariosCambiada"
            >
              <template #cell(estado)="{ value }">
                <VaBadge :text="value" :color="getBadgeColor(value)" />
              </template>

              <template #cell(rol)="{ value }">
                <VaChip :color="getRolColor(value)" size="small">
                  {{ value }}
                </VaChip>
              </template>

              <template #cell(acciones)="{ row }">
                <VaButton
                  preset="plain"
                  icon="edit"
                  size="small"
                  class="mr-2"
                  color="warning"
                  @click="editarUsuario(row.id)"
                />
                <VaButton
                  preset="plain"
                  icon="delete"
                  size="small"
                  color="danger"
                  @click="confirmarEliminarUsuario(row.id)"
                />
                <VaButton
                  preset="plain"
                  icon="lock_reset"
                  size="small"
                  class="ml-2"
                  color="info"
                  @click="resetearContrasena(row.id)"
                />
              </template>
            </VaDataTable>
          </VaCardContent>
        </VaCard>

        <!-- Configuración del sistema -->
        <VaCard class="mt-6">
          <VaCardTitle>Configuración del sistema</VaCardTitle>
          <VaCardContent>
            <VaTabs v-model="tabConfiguracion" grow>
              <template #tabs>
                <VaTab v-for="tab in tabsConfiguracion" :key="tab.label">
                  {{ tab.label }}
                </VaTab>
              </template>
            </VaTabs>

            <div class="mt-4">
              <!-- Pestaña de parámetros generales -->
              <div v-if="tabConfiguracion === 0">
                <VaForm class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <VaInput v-model="configSistema.nombreInstitucion" label="Nombre de la institución" />
                  <VaInput v-model="configSistema.logoUrl" label="URL del logo" />

                  <VaSelect
                    v-model="configSistema.periodoActual"
                    :options="periodosAcademicos"
                    label="Período académico actual"
                  />

                  <VaInput
                    v-model="configSistema.diasEntregaDocumentos"
                    label="Días para entrega de documentos"
                    type="number"
                  />

                  <VaCheckbox v-model="configSistema.modoMantenimiento" label="Modo mantenimiento" />
                  <VaCheckbox v-model="configSistema.notificacionesActivas" label="Notificaciones activas" />

                  <div class="md:col-span-2">
                    <VaButton preset="primary" @click="guardarConfiguracion"> Guardar configuración </VaButton>
                  </div>
                </VaForm>
              </div>

              <!-- Pestaña de roles y permisos -->
              <div v-if="tabConfiguracion === 1">
                <VaDataTable :items="rolesPermisos" :columns="columnasRolesPermisos" :per-page="5">
                  <template #cell(permisos)="{ value }">
                    <div class="flex flex-wrap gap-1">
                      <VaChip v-for="permiso in value" :key="permiso" size="small">
                        {{ permiso }}
                      </VaChip>
                    </div>
                  </template>

                  <template #cell(acciones)="{ row }">
                    <VaButton
                      preset="plain"
                      icon="edit"
                      size="small"
                      class="mr-2"
                      color="warning"
                      @click="editarRol(row.id)"
                    />
                    <VaButton
                      v-if="!row.protegido"
                      preset="plain"
                      icon="delete"
                      size="small"
                      color="danger"
                      @click="confirmarEliminarRol(row.id)"
                    />
                  </template>
                </VaDataTable>

                <VaButton preset="primary" icon="add" class="mt-4" @click="mostrarModalNuevoRol"> Nuevo rol </VaButton>
              </div>

              <!-- Pestaña de integraciones -->
              <div v-if="tabConfiguracion === 2">
                <VaAlert color="info" class="mb-4" outline>
                  Configura las integraciones con otros sistemas institucionales
                </VaAlert>

                <VaList>
                  <VaListItem v-for="integracion in integraciones" :key="integracion.id">
                    <VaListItemSection>
                      <VaListItemLabel>{{ integracion.nombre }}</VaListItemLabel>
                      <VaListItemLabel caption>
                        {{ integracion.descripcion }}
                      </VaListItemLabel>
                    </VaListItemSection>

                    <VaListItemSection icon>
                      <VaSwitch v-model="integracion.activa" @click="toggleIntegracion(integracion.id)" />
                    </VaListItemSection>

                    <VaListItemSection icon>
                      <VaButton preset="plain" icon="settings" @click="configurarIntegracion(integracion.id)" />
                    </VaListItemSection>
                  </VaListItem>
                </VaList>
              </div>
            </div>
          </VaCardContent>
        </VaCard>
      </div>
    </div>

    <!-- Alertas y notificaciones del sistema -->
    <VaCard class="mt-6">
      <VaCardTitle class="flex items-center">
        <VaIcon name="notifications_active" class="mr-2" color="danger" />
        Alertas del sistema
      </VaCardTitle>
      <VaCardContent>
        <VaAlert
          v-for="(alert, index) in alertasSistemaUrgentes"
          :key="index"
          :color="alert.color"
          class="mb-3"
          border="left"
          @click="manejarAlertaSistema(alert.accion)"
        >
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <VaIcon :name="alert.icon" class="mr-2" />
              <div>
                <p class="font-medium">{{ alert.titulo }}</p>
                <p class="text-sm">{{ alert.mensaje }}</p>
              </div>
            </div>
            <div class="text-xs text-gray-500">{{ alert.fecha }}</div>
          </div>
          <template #close>
            <VaButton preset="plain" icon="close" size="small" @click.stop="eliminarAlertaSistema(index)" />
          </template>
        </VaAlert>

        <VaAlert v-if="alertasSistemaUrgentes.length === 0" color="success" border="left">
          No hay alertas críticas en el sistema
        </VaAlert>
      </VaCardContent>
    </VaCard>

    <!-- Modales -->
    <VaModal v-model="mostrarModalUsuario" size="large" hide-default-actions>
      <template #header>
        <h2 class="va-h4">{{ esNuevoUsuario ? 'Nuevo Usuario' : 'Editar Usuario' }}</h2>
      </template>

      <VaForm class="space-y-4">
        <VaInput v-model="usuarioEdit.nombre" label="Nombre completo" />

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <VaInput v-model="usuarioEdit.email" label="Correo electrónico" type="email" />
          <VaInput v-model="usuarioEdit.username" label="Nombre de usuario" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <VaSelect v-model="usuarioEdit.rol" :options="opcionesRoles" label="Rol de usuario" />
          <VaSelect v-model="usuarioEdit.estado" :options="opcionesEstadosUsuario" label="Estado" />
        </div>

        <VaInput v-if="esNuevoUsuario" v-model="usuarioEdit.contrasena" label="Contraseña temporal" type="password" />
      </VaForm>

      <template #footer>
        <VaButton preset="secondary" class="mr-2" @click="mostrarModalUsuario = false"> Cancelar </VaButton>
        <VaButton preset="primary" @click="guardarUsuario">
          {{ esNuevoUsuario ? 'Crear usuario' : 'Guardar cambios' }}
        </VaButton>
      </template>
    </VaModal>

    <VaModal v-model="mostrarModalRol" hide-default-actions>
      <template #header>
        <h2 class="va-h4">{{ esNuevoRol ? 'Nuevo Rol' : 'Editar Rol' }}</h2>
      </template>

      <VaForm class="space-y-4">
        <VaInput v-model="rolEdit.nombre" label="Nombre del rol" />

        <VaAlert color="info" outline class="mb-4"> Selecciona los permisos para este rol </VaAlert>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <VaCheckbox
            v-for="permiso in todosPermisos"
            :key="permiso.value"
            v-model="rolEdit.permisos"
            :label="permiso.label"
            :array-value="permiso.value"
          />
        </div>
      </VaForm>

      <template #footer>
        <VaButton preset="secondary" class="mr-2" @click="mostrarModalRol = false"> Cancelar </VaButton>
        <VaButton preset="primary" @click="guardarRol">
          {{ esNuevoRol ? 'Crear rol' : 'Guardar cambios' }}
        </VaButton>
      </template>
    </VaModal>

    <VaModal v-model="mostrarModalConfirmacion" hide-default-actions>
      <template #header>
        <h2 class="va-h4">{{ confirmacionTitulo }}</h2>
      </template>

      <p>{{ confirmacionMensaje }}</p>

      <template #footer>
        <VaButton preset="secondary" class="mr-2" @click="mostrarModalConfirmacion = false"> Cancelar </VaButton>
        <VaButton :color="confirmacionColor" @click="confirmarAccion">
          {{ confirmacionAccion }}
        </VaButton>
      </template>
    </VaModal>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

// Datos del sistema
const periodoSeleccionado = ref('ENE-JUN 2025')
const periodosAcademicos = ref(['AGO-DIC 2025', 'ENE-JUN 2025', 'AGO-DIC 2024', 'ENE-JUN 2024'])

// Estadísticas del sistema
const usuariosActivos = ref(143)
const nuevosUsuarios7d = ref(7)
const documentosPendientesSistema = ref(87)
const documentosAtrasados = ref(23)
const reportesGenerados = ref(45)
const reportesPendientes = ref(12)
const alertasSistema = ref(8)
const alertasCriticas = ref(3)

// Configuración del sistema
const configSistema = ref({
  nombreInstitucion: 'Instituto Tecnológico Superior',
  logoUrl: '/img/logo-instituto.png',
  periodoActual: 'ENE-JUN 2025',
  diasEntregaDocumentos: 15,
  modoMantenimiento: false,
  notificacionesActivas: true,
})

// Acciones rápidas
const accionesRapidas = ref([
  {
    texto: 'Respaldar BD',
    icono: 'backup',
    preset: 'secondary',
    accion: 'respaldarBD',
  },
  {
    texto: 'Generar Reporte',
    icono: 'description',
    preset: 'secondary',
    accion: 'generarReporte',
  },
  {
    texto: 'Mantenimiento',
    icono: 'build',
    preset: 'secondary',
    accion: 'modoMantenimiento',
  },
  {
    texto: 'Auditoría',
    icono: 'security',
    preset: 'secondary',
    accion: 'ejecutarAuditoria',
  },
  {
    texto: 'Notificar a todos',
    icono: 'notifications',
    preset: 'secondary',
    accion: 'notificarTodos',
  },
  {
    texto: 'Nuevo período',
    icono: 'event',
    preset: 'secondary',
    accion: 'crearNuevoPeriodo',
  },
])

// Actividad reciente
const actividadReciente = ref([
  {
    id: 1,
    usuario: 'jperez',
    accion: 'Actualizó la configuración del sistema',
    tipo: 'configuracion',
    hora: '10:45 AM',
    detalle: true,
  },
  {
    id: 2,
    usuario: 'mgarcia',
    accion: 'Subió documentos de evaluación',
    tipo: 'documento',
    hora: '09:30 AM',
    detalle: false,
  },
  {
    id: 3,
    usuario: 'admin',
    accion: 'Eliminó usuario inactivo',
    tipo: 'usuario',
    hora: 'Ayer, 5:20 PM',
    detalle: true,
  },
  {
    id: 4,
    usuario: 'lrodriguez',
    accion: 'Generó reporte de comisiones',
    tipo: 'reporte',
    hora: 'Ayer, 3:15 PM',
    detalle: true,
  },
])

// Gestión de usuarios
const usuarios = ref([
  {
    id: 1,
    nombre: 'Juan Pérez',
    email: 'jperez@instituto.edu',
    username: 'jperez',
    rol: 'Administrador',
    estado: 'Activo',
    ultimoAcceso: '2024-06-15 10:45',
  },
  {
    id: 2,
    nombre: 'María García',
    email: 'mgarcia@instituto.edu',
    username: 'mgarcia',
    rol: 'Profesor',
    estado: 'Activo',
    ultimoAcceso: '2024-06-15 09:30',
  },
  {
    id: 3,
    nombre: 'Carlos López',
    email: 'clopez@instituto.edu',
    username: 'clopez',
    rol: 'Coordinador',
    estado: 'Inactivo',
    ultimoAcceso: '2024-05-20 14:15',
  },
  {
    id: 4,
    nombre: 'Laura Rodríguez',
    email: 'lrodriguez@instituto.edu',
    username: 'lrodriguez',
    rol: 'Director',
    estado: 'Activo',
    ultimoAcceso: '2024-06-14 15:20',
  },
  {
    id: 5,
    nombre: 'Pedro Sánchez',
    email: 'psanchez@instituto.edu',
    username: 'psanchez',
    rol: 'Profesor',
    estado: 'Pendiente',
    ultimoAcceso: '2024-06-10 08:45',
  },
])

const columnasUsuarios = ref([
  { key: 'nombre', label: 'Nombre', sortable: true },
  { key: 'email', label: 'Email', sortable: true },
  { key: 'username', label: 'Usuario', sortable: true },
  { key: 'rol', label: 'Rol', sortable: true },
  { key: 'estado', label: 'Estado', sortable: true },
  { key: 'ultimoAcceso', label: 'Último acceso', sortable: true },
  { key: 'acciones', label: 'Acciones' },
])

const filtroUsuarios = ref('todos')
const opcionesFiltroUsuarios = ref([
  { label: 'Todos', value: 'todos' },
  { label: 'Activos', value: 'Activo' },
  { label: 'Inactivos', value: 'Inactivo' },
  { label: 'Pendientes', value: 'Pendiente' },
])

const busquedaUsuarios = ref('')

const usuariosFiltrados = computed(() => {
  let filtrados = usuarios.value

  // Aplicar filtro por estado
  if (filtroUsuarios.value !== 'todos') {
    filtrados = filtrados.filter((u) => u.estado === filtroUsuarios.value)
  }

  // Aplicar búsqueda
  if (busquedaUsuarios.value) {
    const termino = busquedaUsuarios.value.toLowerCase()
    filtrados = filtrados.filter(
      (u) =>
        u.nombre.toLowerCase().includes(termino) ||
        u.email.toLowerCase().includes(termino) ||
        u.username.toLowerCase().includes(termino),
    )
  }

  return filtrados
})

// Configuración del sistema - Roles y permisos
const tabConfiguracion = ref(0)
const tabsConfiguracion = ref([
  { label: 'Parámetros', icon: 'settings' },
  { label: 'Roles y permisos', icon: 'security' },
  { label: 'Integraciones', icon: 'sync_alt' },
])

const rolesPermisos = ref([
  {
    id: 1,
    nombre: 'Administrador',
    permisos: ['all'],
    protegido: true,
  },
  {
    id: 2,
    nombre: 'Director',
    permisos: ['ver_reportes', 'aprobar_documentos', 'gestionar_profesores'],
    protegido: false,
  },
  {
    id: 3,
    nombre: 'Coordinador',
    permisos: ['ver_reportes', 'gestionar_asignaturas', 'revisar_documentos'],
    protegido: false,
  },
  {
    id: 4,
    nombre: 'Profesor',
    permisos: ['subir_documentos', 'gestionar_evidencias', 'ver_calificaciones'],
    protegido: false,
  },
])

const columnasRolesPermisos = ref([
  { key: 'nombre', label: 'Nombre del rol', sortable: true },
  { key: 'permisos', label: 'Permisos' },
  { key: 'acciones', label: 'Acciones' },
])

const todosPermisos = ref([
  { value: 'all', label: 'Todos los permisos' },
  { value: 'ver_reportes', label: 'Ver reportes' },
  { value: 'gestionar_usuarios', label: 'Gestionar usuarios' },
  { value: 'gestionar_roles', label: 'Gestionar roles' },
  { value: 'subir_documentos', label: 'Subir documentos' },
  { value: 'aprobar_documentos', label: 'Aprobar documentos' },
  { value: 'revisar_documentos', label: 'Revisar documentos' },
  { value: 'gestionar_asignaturas', label: 'Gestionar asignaturas' },
  { value: 'gestionar_evidencias', label: 'Gestionar evidencias' },
  { value: 'ver_calificaciones', label: 'Ver calificaciones' },
  { value: 'editar_calificaciones', label: 'Editar calificaciones' },
  { value: 'configurar_sistema', label: 'Configurar sistema' },
])

// Integraciones
const integraciones = ref([
  {
    id: 1,
    nombre: 'Sistema de Contabilidad',
    descripcion: 'Integración con el sistema financiero institucional',
    activa: true,
  },
  {
    id: 2,
    nombre: 'Plataforma LMS',
    descripcion: 'Integración con la plataforma de aprendizaje Moodle',
    activa: false,
  },
  {
    id: 3,
    nombre: 'Correo Institucional',
    descripcion: 'Sincronización con cuentas de correo electrónico',
    activa: true,
  },
])

// Alertas del sistema
const alertasSistemaUrgentes = ref([
  {
    titulo: 'Espacio en disco bajo',
    mensaje: 'Solo queda el 10% de espacio disponible en el servidor principal',
    color: 'danger',
    icon: 'storage',
    fecha: 'Hoy, 09:15 AM',
    accion: 'verAlmacenamiento',
  },
  {
    titulo: 'Intento de acceso no autorizado',
    mensaje: 'Se detectaron 3 intentos fallidos para el usuario admin desde IP 192.168.1.45',
    color: 'warning',
    icon: 'security',
    fecha: 'Ayer, 11:30 PM',
    accion: 'verSeguridad',
  },
  {
    titulo: 'Actualización disponible',
    mensaje: 'Nueva versión 2.3.1 del sistema académico disponible para instalación',
    color: 'info',
    icon: 'system_update',
    fecha: '15/06/2024',
    accion: 'verActualizaciones',
  },
])

// Modales
const mostrarModalUsuario = ref(false)
const esNuevoUsuario = ref(false)
const usuarioEdit = ref({
  id: null,
  nombre: '',
  email: '',
  username: '',
  rol: '',
  estado: 'Activo',
  contrasena: '',
})

const opcionesRoles = computed(() => rolesPermisos.value.map((r) => r.nombre))
const opcionesEstadosUsuario = ref(['Activo', 'Inactivo', 'Pendiente', 'Suspendido'])

const mostrarModalRol = ref(false)
const esNuevoRol = ref(false)
const rolEdit = ref({
  id: null,
  nombre: '',
  permisos: [],
  protegido: false,
})

const mostrarModalConfirmacion = ref(false)
const confirmacionTitulo = ref('')
const confirmacionMensaje = ref('')
const confirmacionAccion = ref('')
const confirmacionColor = ref('danger')
const accionConfirmada = ref(null)

// Funciones
const getBadgeColor = (estado) => {
  const estados = {
    Activo: 'success',
    Inactivo: 'warning',
    Pendiente: 'info',
    Suspendido: 'danger',
    Completado: 'success',
    'En progreso': 'warning',
    Atrasado: 'danger',
  }
  return estados[estado] || 'primary'
}

const getRolColor = (rol) => {
  const roles = {
    Administrador: 'danger',
    Director: 'primary',
    Coordinador: 'info',
    Profesor: 'success',
  }
  return roles[rol] || 'background-secondary'
}

const getTimelineColor = (tipo) => {
  const tipos = {
    configuracion: 'info',
    documento: 'warning',
    usuario: 'danger',
    reporte: 'success',
  }
  return tipos[tipo] || 'primary'
}

const ejecutarAccion = (accion) => {
  switch (accion) {
    case 'respaldarBD':
      iniciarRespaldoBD()
      break
    case 'generarReporte':
      generarReporteSistema()
      break
    case 'modoMantenimiento':
      toggleModoMantenimiento()
      break
    case 'ejecutarAuditoria':
      ejecutarAuditoriaSistema()
      break
    case 'notificarTodos':
      mostrarModalNotificacion()
      break
    case 'crearNuevoPeriodo':
      crearNuevoPeriodo()
      break
  }
}

const iniciarRespaldoBD = () => {
  mostrarConfirmacion(
    'Iniciar respaldo de base de datos',
    '¿Estás seguro que deseas iniciar un respaldo completo de la base de datos? Esta operación puede tomar varios minutos.',
    'Iniciar respaldo',
    'info',
    () => {
      // Lógica para respaldar BD
      console.log('Iniciando respaldo de BD...')
    },
  )
}

const generarReporteSistema = () => {
  router.push('/reportes/generar')
}

const toggleModoMantenimiento = () => {
  const nuevoEstado = !configSistema.value.modoMantenimiento
  mostrarConfirmacion(
    nuevoEstado ? 'Activar modo mantenimiento' : 'Desactivar modo mantenimiento',
    nuevoEstado
      ? 'Al activar el modo mantenimiento, solo los administradores podrán acceder al sistema hasta que se desactive. ¿Deseas continuar?'
      : '¿Deseas desactivar el modo mantenimiento y permitir el acceso a todos los usuarios?',
    nuevoEstado ? 'Activar' : 'Desactivar',
    'warning',
    () => {
      configSistema.value.modoMantenimiento = nuevoEstado
      // Lógica adicional para cambiar modo mantenimiento
    },
  )
}

const ejecutarAuditoriaSistema = () => {
  mostrarConfirmacion(
    'Ejecutar auditoría del sistema',
    'Esta acción generará un reporte detallado de todas las actividades y configuraciones del sistema. ¿Deseas continuar?',
    'Ejecutar auditoría',
    'info',
    () => {
      // Lógica para ejecutar auditoría
      console.log('Ejecutando auditoría del sistema...')
    },
  )
}

const mostrarModalNotificacion = () => {
  // Implementar lógica para mostrar modal de notificación
  console.log('Mostrar modal para enviar notificación a todos los usuarios')
}

const crearNuevoPeriodo = () => {
  router.push('/periodos')
}

const verDetalleActividad = (id) => {
  const actividad = actividadReciente.value.find((a) => a.id === id)
  if (actividad) {
    mostrarConfirmacion(
      'Detalle de actividad',
      `Usuario: ${actividad.usuario}\nAcción: ${actividad.accion}\nHora: ${actividad.hora}`,
      'Cerrar',
      'info',
      null,
      false,
    )
  }
}

const filtrarUsuarios = (filtro) => {
  filtroUsuarios.value = filtro === 'activos' ? 'Activo' : filtroUsuarios.value
}

const filtrarDocumentos = (filtro) => {
  // Implementar lógica de filtrado de documentos
  console.log(`Filtrando documentos por: ${filtro}`)
}

const mostrarReportes = () => {
  router.push('/reportes')
}

const mostrarAlertasSistema = () => {
  router.push('/alertas')
}

const mostrarConfigSistema = () => {
  tabConfiguracion.value = 0
}

const mostrarHerramientasSeguridad = () => {
  tabConfiguracion.value = 1
}

const mostrarModalNuevoUsuario = () => {
  esNuevoUsuario.value = true
  usuarioEdit.value = {
    id: null,
    nombre: '',
    email: '',
    username: '',
    rol: rolesPermisos.value[0].nombre,
    estado: 'Activo',
    contrasena: 'Temp1234',
  }
  mostrarModalUsuario.value = true
}

const editarUsuario = (id) => {
  const usuario = usuarios.value.find((u) => u.id === id)
  if (usuario) {
    esNuevoUsuario.value = false
    usuarioEdit.value = { ...usuario, contrasena: '' }
    mostrarModalUsuario.value = true
  }
}

const guardarUsuario = () => {
  if (esNuevoUsuario.value) {
    // Agregar nuevo usuario
    const nuevoId = Math.max(...usuarios.value.map((u) => u.id)) + 1
    usuarios.value.push({
      id: nuevoId,
      nombre: usuarioEdit.value.nombre,
      email: usuarioEdit.value.email,
      username: usuarioEdit.value.username,
      rol: usuarioEdit.value.rol,
      estado: usuarioEdit.value.estado,
      ultimoAcceso: new Date().toISOString(),
    })
  } else {
    // Actualizar usuario existente
    const index = usuarios.value.findIndex((u) => u.id === usuarioEdit.value.id)
    if (index !== -1) {
      usuarios.value[index] = {
        ...usuarios.value[index],
        nombre: usuarioEdit.value.nombre,
        email: usuarioEdit.value.email,
        username: usuarioEdit.value.username,
        rol: usuarioEdit.value.rol,
        estado: usuarioEdit.value.estado,
      }
    }
  }
  mostrarModalUsuario.value = false
}

const confirmarEliminarUsuario = (id) => {
  const usuario = usuarios.value.find((u) => u.id === id)
  if (usuario) {
    mostrarConfirmacion(
      'Eliminar usuario',
      `¿Estás seguro que deseas eliminar al usuario ${usuario.nombre} (${usuario.username})? Esta acción no se puede deshacer.`,
      'Eliminar',
      'danger',
      () => eliminarUsuario(id),
    )
  }
}

const eliminarUsuario = (id) => {
  usuarios.value = usuarios.value.filter((u) => u.id !== id)
}

const resetearContrasena = (id) => {
  const usuario = usuarios.value.find((u) => u.id === id)
  if (usuario) {
    mostrarConfirmacion(
      'Resetear contraseña',
      `Se generará una contraseña temporal para ${usuario.nombre}. ¿Deseas continuar?`,
      'Resetear',
      'warning',
      () => {
        // Lógica para resetear contraseña
        console.log(`Contraseña reseteada para usuario ${usuario.username}`)
      },
    )
  }
}

const mostrarModalNuevoRol = () => {
  esNuevoRol.value = true
  rolEdit.value = {
    id: null,
    nombre: '',
    permisos: [],
    protegido: false,
  }
  mostrarModalRol.value = true
}

const editarRol = (id) => {
  const rol = rolesPermisos.value.find((r) => r.id === id)
  if (rol) {
    esNuevoRol.value = false
    rolEdit.value = { ...rol }
    mostrarModalRol.value = true
  }
}

const guardarRol = () => {
  if (esNuevoRol.value) {
    // Agregar nuevo rol
    const nuevoId = Math.max(...rolesPermisos.value.map((r) => r.id)) + 1
    rolesPermisos.value.push({
      id: nuevoId,
      nombre: rolEdit.value.nombre,
      permisos: rolEdit.value.permisos,
      protegido: false,
    })
  } else {
    // Actualizar rol existente
    const index = rolesPermisos.value.findIndex((r) => r.id === rolEdit.value.id)
    if (index !== -1) {
      rolesPermisos.value[index] = {
        ...rolesPermisos.value[index],
        nombre: rolEdit.value.nombre,
        permisos: rolEdit.value.permisos,
      }
    }
  }
  mostrarModalRol.value = false
}

const confirmarEliminarRol = (id) => {
  const rol = rolesPermisos.value.find((r) => r.id === id)
  if (rol) {
    mostrarConfirmacion(
      'Eliminar rol',
      `¿Estás seguro que deseas eliminar el rol "${rol.nombre}"? Los usuarios con este rol perderán sus permisos.`,
      'Eliminar',
      'danger',
      () => eliminarRol(id),
    )
  }
}

const eliminarRol = (id) => {
  rolesPermisos.value = rolesPermisos.value.filter((r) => r.id !== id)
}

const toggleIntegracion = (id) => {
  const integracion = integraciones.value.find((i) => i.id === id)
  if (integracion) {
    console.log(`Integración ${integracion.nombre} ${integracion.activa ? 'activada' : 'desactivada'}`)
  }
}

const configurarIntegracion = (id) => {
  const integracion = integraciones.value.find((i) => i.id === id)
  if (integracion) {
    mostrarConfirmacion(
      `Configurar ${integracion.nombre}`,
      'Esta función abrirá el panel de configuración avanzada para esta integración.',
      'Configurar',
      'info',
      () => {
        console.log(`Configurando integración: ${integracion.nombre}`)
      },
    )
  }
}

const guardarConfiguracion = () => {
  mostrarConfirmacion(
    'Guardar configuración',
    '¿Deseas guardar los cambios en la configuración del sistema?',
    'Guardar',
    'info',
    () => {
      console.log('Configuración guardada:', configSistema.value)
    },
  )
}

const manejarAlertaSistema = (accion) => {
  switch (accion) {
    case 'verAlmacenamiento':
      router.push('/sistema/almacenamiento')
      break
    case 'verSeguridad':
      router.push('/sistema/seguridad')
      break
    case 'verActualizaciones':
      router.push('/sistema/actualizaciones')
      break
  }
}

const eliminarAlertaSistema = (index) => {
  alertasSistemaUrgentes.value.splice(index, 1)
}

const mostrarConfirmacion = (titulo, mensaje, accion, color, callback = true) => {
  confirmacionTitulo.value = titulo
  confirmacionMensaje.value = mensaje
  confirmacionAccion.value = accion
  confirmacionColor.value = color
  accionConfirmada.value = callback
  mostrarModalConfirmacion.value = true
}

const confirmarAccion = () => {
  if (accionConfirmada.value) {
    accionConfirmada.value()
  }
  mostrarModalConfirmacion.value = false
}

const seleccionUsuariosCambiada = (selectedItems) => {
  console.log('Usuarios seleccionados:', selectedItems)
}

const actualizarDatos = () => {
  // Lógica para actualizar datos del dashboard
  console.log('Actualizando datos del sistema...')
}
</script>

<style scoped>
.dashboard-container {
  max-width: 1800px;
  margin: 0 auto;
  padding: 1rem;
}

.va-card {
  margin-bottom: 1.5rem;
  transition:
    transform 0.2s,
    box-shadow 0.2s;
}

.va-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.va-card[color='background-secondary'] {
  cursor: pointer;
}

.va-alert {
  margin-bottom: 0.75rem;
  cursor: pointer;
}

.va-alert:hover {
  opacity: 0.9;
}

.va-timeline-item__date {
  min-width: 80px;
}

.va-list-item {
  padding: 0.75rem 0;
}
</style>
