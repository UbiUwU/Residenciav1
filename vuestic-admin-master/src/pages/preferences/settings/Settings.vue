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
</template>

<script lang="ts" setup>
import { ref, onMounted } from 'vue'
import { buttonStyles } from '../styles'


const name = ref('')
const email = ref('')

// Simular recuperación de datos desde localStorage
onMounted(() => {
  name.value = localStorage.getItem('user_name') || 'Sin nombre'
  email.value = localStorage.getItem('user_email') || 'Sin email'
})



const emits = defineEmits(['openNameModal', 'openResetPasswordModal'])
</script>
