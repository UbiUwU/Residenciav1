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
          <VaIcon
            :name="isPasswordVisible.value ? 'mso-visibility_off' : 'mso-visibility'"
            class="cursor-pointer"
            color="secondary"
          />
        </template>
      </VaInput>
    </VaValue>

    <div class="auth-layout__options flex flex-col sm:flex-row items-start sm:items-center justify-between">
      <VaCheckbox v-model="formData.keepLoggedIn" class="mb-2 sm:mb-0" label="Mantenerme conectado en este dispositivo" />
      <RouterLink :to="{ name: 'recover-password' }" class="mt-2 sm:mt-0 sm:ml-1 font-semibold text-primary">
        ¿Has olvidado tu contraseña?
      </RouterLink>
    </div>

    <div class="flex justify-center mt-4">
      <VaButton class="w-full" :loading="loading" @click="submit">Login</VaButton>
    </div>
  </VaForm>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useForm, useToast } from 'vuestic-ui'
import { validators } from '../../services/utils'
import api from '../../services/api'
import { useAuthStore } from '../../stores/auth'

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
      authStore.login(response.data.data, formData.keepLoggedIn)
      init({ message: "You've successfully logged in", color: 'success' })
      push({ name: 'dashboard' }) // Redirige al dashboard después del login
    } else {
      init({ message: response.data.message || 'Login failed', color: 'danger' })
    }
  } catch (error) {
    init({ message: 'An error occurred during login', color: 'danger' })
    console.error('Login error:', error)
  } finally {
    loading.value = false
  }
}

</script>