<template>
  <div class="flex flex-col md:flex-row md:items-center space-y-2 md:space-y-0 md:space-x-6 min-h-[36px] leading-5">
    <p class="font-bold w-[200px]">Name</p>
    <div class="flex-1">
      <div class="max-w-[748px]">
        {{ userName }}
      </div>
    </div>
    <VaButton :style="buttonStyles" class="w-fit h-fit" preset="primary" @click="emits('openNameModal')">
      Edit
    </VaButton>
  </div>
  <VaDivider />
  <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-6 min-h-[36px] leading-5">
    <p class="font-bold w-[200px]">Email</p>
    <div class="flex-1">
      <div class="max-w-[748px]">
        {{ email }}
      </div>
    </div>
  </div>
  <!-- Resto del template se mantiene igual -->
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { useToast } from 'vuestic-ui'
import { useUserStore } from '../../../stores/user-store'
import { useAuthStore } from '../../../stores/auth-store'

const { buttonStyles } = '../styles'

const userStore = useUserStore()
const authStore = useAuthStore()

const { init } = useToast()

// Usamos directamente los getters del store
const userName = computed(() => authStore.user?.token || 'Usuario')
const email = computed(() => authStore.user?.correo || 'Sin correo')

const twoFA = computed(() => {
  if (userStore.is2FAEnabled) {
    return {
      color: 'danger',
      button: 'Disable 2FA',
      content: 'Two-Factor Authentication (2FA) is now enabled for your account...',
    }
  } else {
    return {
      color: 'primary',
      button: 'Set up 2FA',
      content: 'Add an extra layer of security to your account...',
    }
  }
})

const toggle2FA = () => {
  userStore.toggle2FA()
  init({ 
    message: userStore.is2FAEnabled ? '2FA activado' : '2FA desactivado', 
    color: 'success' 
  })
}

const emits = defineEmits(['openNameModal', 'openResetPasswordModal'])
</script>