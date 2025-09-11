<template>
  <div class="p-4">
    <VaCard>
      <VaCardTitle> Formulario de evento </VaCardTitle>
      <VaCardContent>
        <form class="grid grid-cols-1 md:grid-cols-2 gap-6" @submit.prevent="submitForm">
          <VaInput v-model="form.nombre" label="Nombre del evento" required />

          <VaInput v-model="form.descripcion" label="Descripcion del evento" required />

          <div class="col-span-1 md:col-span-2 flex justify-end gap-2 mt-4">
            <VaButton preset="secondary" @click="resetForm">Cancelar</VaButton>
            <VaButton type="submit" color="primary">Guardar</VaButton>
          </div>
        </form>
      </VaCardContent>
    </VaCard>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useToast } from 'vuestic-ui'
import api from '../../services/api'

const { init } = useToast()

interface FormData {
  nombre: string
  descripcion: string
  estado: string
}

const form = ref<FormData>({
  nombre: '',
  descripcion: '',
  estado: 'activo',
})

const submitForm = async () => {
  try {
    const payload = {
      nombre: form.value.nombre,
      descripcion: form.value.descripcion,
      estado: form.value.estado,
    }
    console.log('Payload a enviar:', payload)

    await api.crearEvento(payload)
    init({ message: 'Comisión registrada exitosamente.', color: 'success' })
    resetForm()
  } catch (err) {
    console.error(err)
    init({ message: 'Error al guardar la comisión.', color: 'danger' })
  }
}

const resetForm = () => {
  form.value = {
    nombre: '',
    descripcion: '',
    estado: 'activo',
  }
}
</script>
