<template>
  <VaLayout v-if="breakpoint.lgUp" class="h-screen bg-[var(--va-background-secondary)]">
    <template #left>
      <RouterLink
        class="h-full relative block overflow-hidden"
        style="width: 35vw"
        to="/"
        aria-label="Visit homepage"
      >
        <!-- Imagen de fondo que cubre todo el espacio -->
        <img 
          src="../img/Login.jpg" 
          alt="Background" 
          class="absolute inset-0 w-full h-full object-cover"
        />
        <!-- Logo/Marca opcional centrado sobre la imagen -->
        
      </RouterLink>
    </template>
    <template #content>
      <main class="h-full flex items-center justify-center mx-auto max-w-[420px]">
        <RouterView />
      </main>
    </template>
  </VaLayout>

  <VaLayout v-else class="h-screen bg-[var(--va-background-secondary)]">
    <template #content>
      <div class="p-4">
        <main class="h-full flex flex-row items-center justify-start mx-auto max-w-[420px]">
          <div class="flex flex-col items-start">
            <RouterLink class="py-4" to="/" aria-label="Visit homepage">
              <img src="../img/Login.jpg" alt="Logo" class="h-10 mb-2 object-contain" />
            </RouterLink>
            <RouterView />
          </div>
        </main>
      </div>
    </template>
  </VaLayout>
</template>

<script lang="ts" setup>
import { useBreakpoint } from 'vuestic-ui'

const breakpoint = useBreakpoint()
</script>