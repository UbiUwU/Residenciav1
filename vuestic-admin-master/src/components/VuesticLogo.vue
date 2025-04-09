<template>
  <div class="role-container">
    <template v-if="role === 'admin'">
      <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="8" r="4"></circle>
        <path d="M6 21v-2a6 6 0 0 1 12 0v2"></path>
      </svg>
      <span class="role-text">Admin</span>
    </template>

    <template v-else-if="role === 'maestro'">
      <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M3 8l9-5 9 5-9 5-9-5z"></path>
        <path d="M3 8v6a9 9 0 0 0 18 0V8"></path>
        <path d="M21 12a9 9 0 0 1-18 0"></path>
      </svg>  
      <span class="role-text">Maestro</span>
    </template>
  </div>
</template>

<script>
export default {
  data() {
    return {
      role: "admin", // Aquí puedes cambiarlo a "maestro" para probar
    };
  },
};
</script>

<style scoped>
.role-container {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 18px;
  font-weight: bold;
}

.role-text {
  color: #333;
}
</style>
