<script setup>
import { RouterLink, RouterView } from 'vue-router'
</script>
<template>
  <header>
    <nav>
      <RouterLink :to="{ name: 'home' }" class="nav-link">Home</RouterLink>
      <RouterLink :to="{ name: 'about' }" class="nav-link">About</RouterLink>
      <RouterLink :to="{ name: 'dashboard' }" class="nav-link">Dashboard</RouterLink>
    </nav>
  </header>
  <RouterView />
</template>
<style scoped></style>
