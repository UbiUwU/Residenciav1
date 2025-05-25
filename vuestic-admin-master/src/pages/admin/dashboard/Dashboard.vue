<script lang="ts" setup>
import ProjectTable from './cards/ProjectTable.vue'
import MonthlyEarnings from './cards/MonthlyEarnings.vue'
</script>

<template>
  <h1 class="page-title font-bold">Dashboard</h1>
  <section class="flex flex-col gap-4">
    <MonthlyEarnings />
    <DataSection />
    <div class="flex flex-col md:flex-row gap-4">
      <RevenueByLocationMap class="w-full md:w-4/6" />
      <RegionRevenue class="w-full md:w-2/6" />
    </div>
    <div>
      <ProjectTable class="w-full" />
    </div>
    <div class="flex flex-col md:flex-row gap-4">
      <!-- <Timeline class="w-full md:w-1/2" /> -->
    </div>
  </section>
</template>
