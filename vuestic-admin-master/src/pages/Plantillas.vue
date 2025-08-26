<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const plantillas = ref([]);
const cargando = ref(false);
const error = ref(null);

// Formulario para crear/editar
const form = ref({
    id: null,
    nombre: "",
    descripcion: "",
    archivo: null,
    tipo_id: "",
    estado_id: 1,
    periodo_escolar_id: ""
});

const modoEdicion = ref(false);
const terminoBusqueda = ref("");

// URL base de la API
const API_URL = "http://127.0.0.1:8000/api/plantillas";

// 🔹 Obtener todas las plantillas
const obtenerPlantillas = async () => {
    try {
        cargando.value = true;
        const { data } = await axios.get(API_URL);
        plantillas.value = data.data;
    } catch (err) {
        error.value = "Error al cargar plantillas";
    } finally {
        cargando.value = false;
    }
};

// 🔹 Guardar o actualizar
const guardarPlantilla = async () => {
    try {
        const formData = new FormData();
        formData.append("nombre", form.value.nombre);
        formData.append("descripcion", form.value.descripcion);
        formData.append("tipo_id", form.value.tipo_id);
        formData.append("estado_id", form.value.estado_id);
        formData.append("periodo_escolar_id", form.value.periodo_escolar_id);

        if (form.value.archivo) {
            formData.append("archivo", form.value.archivo);
        }

        if (modoEdicion.value) {
            await axios.post(`${API_URL}/${form.value.id}?_method=PUT`, formData, {
                headers: { "Content-Type": "multipart/form-data" }
            });
        } else {
            await axios.post(API_URL, formData, {
                headers: { "Content-Type": "multipart/form-data" }
            });
        }

        resetForm();
        obtenerPlantillas();
    } catch (err) {
        console.error(err.response?.data || err);
        error.value = "Error al guardar plantilla";
    }
};



// 🔹 Editar
const editarPlantilla = (plantilla) => {
    form.value = { ...plantilla };
    modoEdicion.value = true;
};

// 🔹 Eliminar
const eliminarPlantilla = async (id) => {
    if (!confirm("¿Seguro que deseas eliminar esta plantilla?")) return;
    try {
        await axios.delete(`${API_URL}/${id}`);
        obtenerPlantillas();
    } catch (err) {
        error.value = "Error al eliminar plantilla";
    }
};

// 🔹 Cambiar estado
const cambiarEstado = async (plantilla, nuevoEstado) => {
    try {
        await axios.put(`${API_URL}/${plantilla.id}/estado`, {
            estado_id: nuevoEstado
        });
        obtenerPlantillas();
    } catch (err) {
        error.value = "Error al cambiar estado";
    }
};

// 🔹 Buscar
const buscarPlantillas = async () => {
    if (terminoBusqueda.value.length < 2) {
        obtenerPlantillas();
        return;
    }
    try {
        const { data } = await axios.post(`${API_URL}/buscar`, {
            termino: terminoBusqueda.value
        });
        plantillas.value = data.data;
    } catch (err) {
        error.value = "Error en búsqueda";
    }
};

const resetForm = () => {
    form.value = {
        id: null,
        nombre: "",
        descripcion: "",
        archivo: null,
        tipo_id: "",
        estado_id: 1,
        periodo_escolar_id: ""
    };
    modoEdicion.value = false;
};


onMounted(() => {
    obtenerPlantillas();
});
</script>

<template>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Gestión de Plantillas</h1>

        <!-- Barra de búsqueda -->
        <div class="mb-4 flex gap-2">
            <input v-model="terminoBusqueda" @input="buscarPlantillas" placeholder="Buscar por nombre o descripción..."
                class="border rounded p-2 w-full" />
        </div>

        <!-- Formulario -->
        <div class="mb-6 border p-4 rounded-lg shadow">
            <h2 class="text-xl mb-2">
                {{ modoEdicion ? "Editar Plantilla" : "Nueva Plantilla" }}
            </h2>
            <form @submit.prevent="guardarPlantilla" class="grid gap-2">
                <input v-model="form.nombre" placeholder="Nombre" class="border p-2 rounded" required />
                <input v-model="form.descripcion" placeholder="Descripción" class="border p-2 rounded" />

                <input v-model="form.tipo_id" type="number" placeholder="Tipo ID" class="border p-2 rounded" required />
                <input v-model="form.periodo_escolar_id" type="number" placeholder="Periodo Escolar ID"
                    class="border p-2 rounded" />
                <input type="file" @change="e => form.archivo = e.target.files[0]" class="border p-2 rounded" />



                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    {{ modoEdicion ? "Actualizar" : "Crear" }}
                </button>
                <button v-if="modoEdicion" type="button" @click="resetForm"
                    class="bg-gray-500 text-white px-4 py-2 rounded">
                    Cancelar
                </button>
            </form>
            <div v-for="p in plantillas" :key="p.id" class="border p-3 mb-2 rounded flex justify-between">
                <div>
                    <h3 class="font-bold">{{ p.nombre }}</h3>
                    <p>{{ p.descripcion }}</p>
                    <a :href="`/storage/plantillas/${p.archivo}`" target="_blank" class="text-blue-600 underline">
                        Descargar archivo
                    </a>
                </div>
            </div>
        </div>

        <!-- Listado -->
        <div v-if="cargando">Cargando...</div>
        <div v-else class="grid gap-4">
            <div v-for="p in plantillas" :key="p.id"
                class="p-4 border rounded shadow flex justify-between items-center">
                <div>
                    <h3 class="font-bold">{{ p.nombre }}</h3>
                    <p class="text-sm text-gray-600">{{ p.descripcion }}</p>
                    <p class="text-xs">Archivo: {{ p.archivo }}</p>
                    <p class="text-xs">Estado: {{ p.estado?.nombre }}</p>
                </div>
                <div class="flex gap-2">
                    <button @click="editarPlantilla(p)" class="px-3 py-1 bg-yellow-500 text-white rounded">
                        Editar
                    </button>
                    <button @click="cambiarEstado(p, p.estado_id === 1 ? 2 : 1)"
                        class="px-3 py-1 bg-indigo-600 text-white rounded">
                        {{ p.estado_id === 1 ? "Desactivar" : "Activar" }}
                    </button>
                    <button @click="eliminarPlantilla(p.id)" class="px-3 py-1 bg-red-600 text-white rounded">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>

        <!-- Errores -->
        <div v-if="error" class="text-red-600 mt-4">
            {{ error }}
        </div>
    </div>
</template>
