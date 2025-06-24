<template>
  <div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Gestión de Maestros</h1>

    <!-- Botón para abrir formulario -->
    <button class="mb-4 bg-blue-600 text-white px-4 py-2 rounded" @click="abrirFormulario()">Nuevo Maestro</button>

    <!-- Tabla de Maestros -->
    <table class="table-auto w-full border">
      <thead class="bg-gray-200">
        <tr>
          <th class="px-4 py-2">Tarjeta</th>
          <th class="px-4 py-2">Nombre</th>
          <th class="px-4 py-2">RFC</th>
          <th class="px-4 py-2">Departamento</th>
          <th class="px-4 py-2">Email</th>
          <th class="px-4 py-2">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="maestro in maestros" :key="maestro.tarjeta">
          <td class="border px-4 py-2">{{ maestro.tarjeta }}</td>
          <td class="border px-4 py-2">
            {{ maestro.nombre }} {{ maestro.apellidopaterno }} {{ maestro.apellidomaterno }}
          </td>
          <td class="border px-4 py-2">{{ maestro.rfc }}</td>
          <td class="border px-4 py-2">{{ maestro.nombre_departamento }}</td>
          <td class="border px-4 py-2">{{ maestro.correo }}</td>
          <td class="border px-4 py-2">
            <button @click="editarMaestro(maestro)" class="bg-yellow-500 text-white px-2 py-1 rounded mr-2">
              Editar
            </button>
            <button @click="eliminar(maestro.tarjeta)" class="bg-red-600 text-white px-2 py-1 rounded">Eliminar</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Formulario de Crear/Editar -->
    <div v-if="mostrarFormulario" class="mt-6 bg-gray-100 p-4 rounded">
      <h2 class="text-xl mb-4">{{ editando ? 'Editar' : 'Crear' }} Maestro</h2>
      <form @submit.prevent="guardarMaestro">
        <!-- User Section -->
        <div class="mb-4 p-4 bg-white rounded">
          <h3 class="font-bold mb-2">Datos de Usuario</h3>
          <div class="mb-2">
            <label>Email</label>
            <input v-model="form.correo" type="email" class="w-full p-2 border rounded" required />
          </div>
          <div class="mb-2">
            <label>Contraseña</label>
            <input
              v-model="form.password"
              type="password"
              class="w-full p-2 border rounded"
              :required="!editando"
              :disabled="editando"
            />
          </div>
          <div class="mb-2">
            <label>Rol</label>
            <select v-model="form.id_rol" class="w-full p-2 border rounded" required>
              <option value="2">Maestro</option>
            </select>
          </div>
        </div>

        <!-- Teacher Section -->
        <div class="mb-4 p-4 bg-white rounded">
          <h3 class="font-bold mb-2">Datos del Maestro</h3>
          <div class="mb-2">
            <label>Tarjeta</label>
            <input
              v-model="form.tarjeta"
              :disabled="editando"
              type="number"
              class="w-full p-2 border rounded"
              required
            />
          </div>
          <div class="mb-2">
            <label>Nombre</label>
            <input v-model="form.nombre" type="text" class="w-full p-2 border rounded" required />
          </div>
          <div class="mb-2">
            <label>Apellido Paterno</label>
            <input v-model="form.apellidopaterno" type="text" class="w-full p-2 border rounded" required />
          </div>
          <div class="mb-2">
            <label>Apellido Materno</label>
            <input v-model="form.apellidomaterno" type="text" class="w-full p-2 border rounded" required />
          </div>
          <div class="mb-2">
            <label>RFC</label>
            <input v-model="form.rfc" type="text" class="w-full p-2 border rounded" required />
          </div>
          <div class="mb-2">
            <label>Departamento</label>
            <select v-model="form.id_departamento" class="w-full p-2 border rounded" required>
              <option value="1">Sistemas y Computación</option>
            </select>
          </div>
        </div>

        <!-- Escolaridad Section -->
        <div class="mb-4 p-4 bg-white rounded">
          <h3 class="font-bold mb-2">Escolaridad</h3>
          <div class="grid grid-cols-2 gap-4">
            <!-- Licenciatura -->
            <div>
              <label>Licenciatura</label>
              <input v-model="form.escolaridad_licenciatura" type="text" class="w-full p-2 border rounded" />
            </div>
            <div>
              <label>Estado Licenciatura</label>
              <select v-model="form.estado_licenciatura" class="w-full p-2 border rounded">
                <option value="C">Concluida</option>
                <option value="E">En curso</option>
              </select>
            </div>

            <!-- Especialización -->
            <div>
              <label>Especialización</label>
              <input v-model="form.escolaridad_especializacion" type="text" class="w-full p-2 border rounded" />
            </div>
            <div>
              <label>Estado Especialización</label>
              <select v-model="form.estado_especializacion" class="w-full p-2 border rounded">
                <option value="C">Concluida</option>
                <option value="E">En curso</option>
              </select>
            </div>

            <!-- Maestría -->
            <div>
              <label>Maestría</label>
              <input v-model="form.escolaridad_maestria" type="text" class="w-full p-2 border rounded" />
            </div>
            <div>
              <label>Estado Maestría</label>
              <select v-model="form.estado_maestria" class="w-full p-2 border rounded">
                <option value="C">Concluida</option>
                <option value="E">En curso</option>
              </select>
            </div>

            <!-- Doctorado -->
            <div>
              <label>Doctorado</label>
              <input v-model="form.escolaridad_doctorado" type="text" class="w-full p-2 border rounded" />
            </div>
            <div>
              <label>Estado Doctorado</label>
              <select v-model="form.estado_doctorado" class="w-full p-2 border rounded">
                <option value="C">Concluida</option>
                <option value="E">En curso</option>
              </select>
            </div>
          </div>
        </div>

        <div class="mt-4">
          <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded mr-2">Guardar</button>
          <button type="button" @click="cancelar" class="bg-gray-400 text-white px-4 py-2 rounded">Cancelar</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import api from '../services/api'

const maestros = ref([])
const departamentos = ref([{ id_departamento: 1, nombre: 'Sistemas y Computación' }]) // Solo un departamento
const mostrarFormulario = ref(false)
const editando = ref(false)

const form = ref({
  // User fields
  correo: '',
  password: '',
  id_rol: 2, // Default role for teachers

  // Teacher fields
  tarjeta: '',
  nombre: '',
  apellidopaterno: '',
  apellidomaterno: '',
  rfc: '',
  id_departamento: 1, // Default department

  // Education fields
  escolaridad_licenciatura: '',
  estado_licenciatura: 'C',
  escolaridad_especializacion: '',
  estado_especializacion: 'C',
  escolaridad_maestria: '',
  estado_maestria: 'C',
  escolaridad_doctorado: '',
  estado_doctorado: 'C',
})


onMounted(() => {
  cargarMaestros()
})

const cargarMaestros = async () => {
  const { data } = await api.getMaestros()
  
  
  maestros.value = data.data.map(maestro => {
   
    maestro.nombre_departamento = 'Sistemas y Computación'
    maestro.id_departamento = 1
    
  
    if (!maestro.correo) {
      const nombre = maestro.nombre.toLowerCase().split(' ')[0]
      const apellido = maestro.apellidopaterno.toLowerCase()
      maestro.correo = `${nombre}.${apellido}@tecnm.mx`
      
      
      let counter = 1
      let tempEmail = maestro.correo
      while (maestros.value.some(m => m.correo === tempEmail)) {
        tempEmail = `${nombre}.${apellido}${counter}@tecnm.mx`
        counter++
      }
      maestro.correo = tempEmail
    }
    
    return maestro
  })
}

const abrirFormulario = () => {
  resetForm()
  mostrarFormulario.value = true
  editando.value = false
}

const editarMaestro = (maestro) => {
  form.value = {
    ...form.value,
    tarjeta: maestro.tarjeta,
    nombre: maestro.nombre,
    apellidopaterno: maestro.apellidopaterno,
    apellidomaterno: maestro.apellidomaterno,
    rfc: maestro.rfc,
    id_departamento: 1, 
    escolaridad_licenciatura: maestro.escolaridad_licenciatura,
    estado_licenciatura: maestro.estado_licenciatura,
    escolaridad_especializacion: maestro.escolaridad_especializacion,
    estado_especializacion: maestro.estado_especializacion,
    escolaridad_maestria: maestro.escolaridad_maestria,
    estado_maestria: maestro.estado_maestria,
    escolaridad_doctorado: maestro.escolaridad_doctorado,
    estado_doctorado: maestro.estado_doctorado,
    correo: maestro.correo,
  }
  mostrarFormulario.value = true
  editando.value = true
}

const guardarMaestro = async () => {
  try {
   
    if (!editando.value && !form.value.correo) {
      const nombre = form.value.nombre.toLowerCase().split(' ')[0]
      const apellido = form.value.apellidopaterno.toLowerCase()
      form.value.correo = `${nombre}.${apellido}@tecnm.mx`
      
      // Verificar si el correo ya existe
      let counter = 1
      let tempEmail = form.value.correo
      while (maestros.value.some(m => m.correo === tempEmail)) {
        tempEmail = `${nombre}.${apellido}${counter}@tecnm.mx`
        counter++
      }
      form.value.correo = tempEmail
    }
    
    if (editando.value) {
      await api.actualizarMaestro(form.value.tarjeta, form.value)
    } else {
      await api.crearMaestro(form.value)
    }
    await cargarMaestros()
    cancelar()
  } catch (error) {
    alert('Error al guardar maestro: ' + error.message)
  }
}

const eliminar = async (id) => {
  if (confirm('¿Está seguro de eliminar este maestro?')) {
    await api.eliminarMaestro(id)
    await cargarMaestros()
  }
}

const cancelar = () => {
  mostrarFormulario.value = false
  resetForm()
}

const resetForm = () => {
  form.value = {
    correo: '',
    password: '',
    id_rol: 2,
    tarjeta: '',
    nombre: '',
    apellidopaterno: '',
    apellidomaterno: '',
    rfc: '',
    id_departamento: 1,
    escolaridad_licenciatura: '',
    estado_licenciatura: 'C',
    escolaridad_especializacion: '',
    estado_especializacion: 'C',
    escolaridad_maestria: '',
    estado_maestria: 'C',
    escolaridad_doctorado: '',
    estado_doctorado: 'C',
  }
}
</script>

<style scoped>
input,
select {
  margin-top: 4px;
}
</style>