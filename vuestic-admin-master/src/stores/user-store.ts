// stores/user-store.ts
import { defineStore } from 'pinia'
import { useAuthStore } from './auth-store'

export const useUserStore = defineStore('user', {
  state: () => ({
    memberSince: '8/12/2020', // Puedes obtener esto de tu API
    pfp: 'https://picsum.photos/id/22/200/300', // Imagen por defecto
    is2FAEnabled: false,
  }),

  getters: {
    userName: () => {
      const authStore = useAuthStore()
      return authStore.user?.token || 'Usuario' // Usa el token como nombre temporal
    },
    email: () => {
      const authStore = useAuthStore()
      return authStore.user?.correo || 'Sin correo'
    },
  },

  actions: {
    toggle2FA() {
      this.is2FAEnabled = !this.is2FAEnabled
    },

    async updateProfile(data: { name?: string; email?: string }) {
      // Aquí puedes implementar la llamada a la API para actualizar el perfil
      const authStore = useAuthStore()
      if (data.name) {
        // Actualizar nombre en el backend
      }
      if (data.email) {
        // Actualizar email en el backend
      }
    },
  },
})
