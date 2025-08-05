<template>
  <VaForm ref="form" @submit.prevent="submit">
    <h1 class="font-semibold text-4xl mb-4">Inicio Sesión</h1>
    <p class="text-base mb-4 leading-5">
      ¡Bienvenido, inicia sesión con tus credenciales!
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
import api from '../../services/apiJ'
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

      // Redirección basada en el rol del usuario
      switch (userRole) {
        case ROLES.ADMIN:
          push({ name: 'dashboard' })
          break
        case ROLES.TEACHER:
          push({ name: 'dashboard-teacher' })
          break
        //Rol de SuperUsuario agregado
        case ROLES.SUPER:
          push({ name: 'dashboard-super' })
          break
        default:
          // Si el rol no está definido, redirige a una página genérica
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
