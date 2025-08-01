<template>
  <VaCard class="mb-6">
    <VaCardTitle>Subir Nueva Evidencia</VaCardTitle>
    <VaCardContent>
      <VaFileUpload
        v-model="files"
        type="gallery"
        dropzone
        multiple
        :file-types="['.pdf', '.doc', '.docx', '.ppt', '.pptx', '.xls', '.xlsx']"
        dropzone-text="Arrastra archivos aquí o haz clic para seleccionar"
        @filesAdded="handleFilesAdded"
      />

      <div class="flex justify-end mt-4">
        <VaButton :disabled="files.length === 0" @click="uploadFiles"> Subir Archivos </VaButton>
      </div>
    </VaCardContent>
  </VaCard>
</template>

<script setup>
import { ref } from 'vue'
import { useToast } from 'vuestic-ui'

const emit = defineEmits(['file-uploaded'])
const { init } = useToast()
const files = ref([])

const handleFilesAdded = (newFiles) => {
  files.value = [...files.value, ...newFiles]
}

const uploadFiles = () => {
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
