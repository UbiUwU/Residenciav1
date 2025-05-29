<script setup lang="ts">
import { onMounted } from 'vue'
import MaestrosTable from './widgets/MaestrosTable.vue'
import { useMaestros } from './composables/useMaestros'
import { useToast } from 'vuestic-ui'
import { watchEffect } from 'vue'

const { maestros, isLoading, error, fetchMaestros } = useMaestros()
const { init: notify } = useToast()

// Cargar los maestros cuando el componente se monta
onMounted(() => {
  fetchMaestros()
})

watchEffect(() => {
  if (error.value) {
    notify({
      message: error.value.message,
      color: 'danger',
    })
  }
})
</script>

<template>
  <h1 class="page-title">Maestros</h1>

  <VaCard>
    <VaCardContent>
      <MaestrosTable :maestros="maestros" :loading="isLoading" />
    </VaCardContent>
  </VaCard>
</template>
