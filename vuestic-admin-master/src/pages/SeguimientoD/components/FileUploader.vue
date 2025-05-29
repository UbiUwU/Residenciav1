<template>
  <va-card class="mb-6">
    <va-card-title>Subir Nueva Evidencia</va-card-title>
    <va-card-content>
      <va-file-upload
        v-model="files"
        type="gallery"
        dropzone
        multiple
        :file-types="['.pdf', '.doc', '.docx', '.ppt', '.pptx', '.xls', '.xlsx']"
        dropzone-text="Arrastra archivos aquí o haz clic para seleccionar"
        @files-added="handleFilesAdded"
      />

      <div class="flex justify-end mt-4">
        <va-button @click="uploadFiles" :disabled="files.length === 0"> Subir Archivos </va-button>
      </div>
    </va-card-content>
  </va-card>
</template>

<script setup>
import { ref } from 'vue'
import { useToast } from 'vuestic-ui'

const props = defineProps({
  materiaId: {
    type: Number,
    required: true,
  },
})

const emit = defineEmits(['file-uploaded'])
const { init } = useToast()
const files = ref([])

const handleFilesAdded = (newFiles) => {
  files.value = [...files.value, ...newFiles]
}

const uploadFiles = () => {
  // Simulación de subida de archivos
  files.value.forEach((file) => {
    setTimeout(() => {
      init({
        message: `${file.name} subido correctamente`,
        color: 'success',
      })
      emit('file-uploaded', file)
    }, 1000)
  })

  files.value = []
}
</script>
