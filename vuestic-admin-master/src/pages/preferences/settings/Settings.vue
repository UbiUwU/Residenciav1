<template>
  <div class="flex flex-col md:flex-row md:items-center space-y-2 md:space-y-0 md:space-x-6 min-h-[36px] leading-5">
    <p class="font-bold w-[200px]">Name</p>
    <div class="flex-1">
      <div class="max-w-[748px]">{{ name }}</div>
    </div>
    <VaButton :style="buttonStyles" class="w-fit h-fit" preset="primary" @click="emits('openNameModal')">
      Edit
    </VaButton>
  </div>

  <VaDivider />

  <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-6 min-h-[36px] leading-5">
    <p class="font-bold w-[200px]">Email</p>
    <div class="flex-1">
      <div class="max-w-[748px]">{{ email }}</div>
    </div>
  </div>

  <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-6 min-h-[36px] leading-5">
    <p class="font-bold w-[200px]">Password</p>
    <div class="flex-1">
      <div class="max-w-[748px]">•••••••••••••</div>
    </div>
    <VaButton :style="buttonStyles" class="w-fit h-fit" preset="primary" @click="emits('openResetPasswordModal')">
      Reset Password
    </VaButton>
  </div>

  <VaDivider />

  <!-- Subida de firma -->
  <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-6 min-h-[36px] leading-5">
    <p class="font-bold w-[200px]">Firma</p>
    <div class="flex-1">
      <input
        type="file"
        accept="image/png"
        class="block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-dark"
        @change="onFileChange"
      />
      <div v-if="firmaUrl" class="mt-3">
        <img :src="firmaUrl" alt="Firma" class="max-h-[100px] border rounded" />
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref, onMounted } from 'vue'
import { buttonStyles } from '../styles'

const name = ref('')
const email = ref('')
const firmaUrl = ref<string | null>(null)

onMounted(() => {
  name.value = localStorage.getItem('user_name') || 'Sin nombre'
  email.value = localStorage.getItem('user_email') || 'Sin email'
  firmaUrl.value = localStorage.getItem('user_firma') || null
})

const onFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement
  const file = target.files?.[0]
  if (file && file.type === 'image/png') {
    const reader = new FileReader()
    reader.onload = () => {
      firmaUrl.value = reader.result as string
      localStorage.setItem('user_firma', firmaUrl.value) // Guardar en localStorage
    }
    reader.readAsDataURL(file)
  }
}

const emits = defineEmits(['openNameModal', 'openResetPasswordModal'])
</script>
