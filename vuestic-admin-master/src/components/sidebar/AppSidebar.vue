<template>
  <TeacherSidebar
    v-if="authStore.isTeacher"
    :visible="visible"
    :mobile="mobile"
    :minimized="minimized"
    @update:visible="$emit('update:visible', $event)"
  />
  <AdminSidebar
    v-else-if="authStore.isAdmin"
    :visible="visible"
    :mobile="mobile"
    :minimized="minimized"
    @update:visible="$emit('update:visible', $event)"
  />
</template>

<script setup lang="ts">
import { useAuthStore } from '../../services/auth'
import TeacherSidebar from './TeacherSidebar.vue'
import AdminSidebar from './AdminSidebar.vue'

defineProps({
  visible: { type: Boolean, default: true },
  mobile: { type: Boolean, default: false },
  minimized: { type: Boolean, default: false }
})

defineEmits(['update:visible'])

const authStore = useAuthStore()
</script>