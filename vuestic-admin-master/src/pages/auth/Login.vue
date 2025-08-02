<template>
  <VaForm ref="form" @submit.prevent="submit">
    <h1 class="font-semibold text-4xl mb-4">Inicio Sesión</h1>
    <p class="text-base mb-4 leading-5">
      ¿Eres nuevo usuario? Registrate
      <RouterLink :to="{ name: 'signup' }" class="font-semibold text-primary">¡Aquí!</RouterLink>
    </p>

    <!-- Email Input -->
    <VaInput
      v-model="formData.email"
      :rules="[validators.required, validators.email]"
      class="mb-4"
      label="Correo Electrónico"
      type="email"
    />

    <!-- Password Input -->
    <VaValue v-slot="isPasswordVisible" :default-value="false">
      <VaInput
        v-model="formData.password"
        :rules="[validators.required]"
        :type="isPasswordVisible.value ? 'text' : 'password'"
        class="mb-4"
        label="Contraseña"
        @clickAppendInner.stop="isPasswordVisible.value = !isPasswordVisible.value"
      >
        <template #appendInner>
          <VaIcon class="cursor-pointer" color="secondary" />
        </template>
      </VaInput>
    </VaValue>

    <div class="auth-layout__options flex flex-col sm:flex-row items-start sm:items-center justify-between">
      <VaCheckbox
        v-model="formData.keepLoggedIn"
        class="mb-2 sm:mb-0"
        label="Mantenerme conectado en este dispositivo"
      />
      <RouterLink :to="{ name: 'recover-password' }" class="mt-2 sm:mt-0 sm:ml-1 font-semibold text-primary">
        ¿Has olvidado tu contraseña?
      </RouterLink>
    </div>

    <div class="flex justify-center mt-4">
      <VaButton class="w-full" :loading="loading" @click="submit">Iniciar sesión</VaButton>
    </div>
  </VaForm>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useForm, useToast } from 'vuestic-ui'
import { validators } from '../../services/utils'
import api from '../../services/api'
import { useAuthStore } from '../../services/auth'
import { ROLES } from '../../constants/roles'

const { validate } = useForm('form')
const { push } = useRouter()
const { init } = useToast()
const authStore = useAuthStore()

const loading = ref(false)
const formData = reactive({
  email: '',
  password: '',
  keepLoggedIn: false,
})

const submit = async () => {
  if (!validate()) return

  loading.value = true

  try {
    const response = await api.login(formData.email, formData.password)

    if (response.data.success) {
      // Realiza el login y obtén el rol del usuario
      const userRole = await authStore.login(
        {
          token: response.data.token,
          user: response.data.data.user,
          maestro: response.data.data.maestro,
        },
        formData.keepLoggedIn,
      )

      init({ message: 'Inicio de sesión exitoso', color: 'success' })
      const userRoleNumber = Number(userRole) // o parseInt(userRole, 10)

      switch (userRoleNumber) {
        case ROLES.ADMIN:
          push({ name: 'dashboard' })
          break
        case ROLES.TEACHER:
          push({ name: 'dashboard-teacher' })
          break
        case ROLES.SUPER:
          push({ name: 'dashboard-super' })
          break
        default:
          push({ name: '404' })
          init({
            message: 'Tu cuenta no tiene un rol asignado',
            color: 'warning',
          })
      }
    } else {
      init({ message: response.data.message || 'Error en el inicio de sesión', color: 'danger' })
    }
  } catch (error) {
    init({ message: 'Correo o contraseña inválidos', color: 'danger' })
    console.error('Login error:', error)
  } finally {
    loading.value = false
  }
}
</script>
