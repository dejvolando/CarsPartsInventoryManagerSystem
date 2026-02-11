<script setup lang="ts">
import ToastContainer from "vue-toastflow/src/components/ToastContainer.vue";
import NavBar from "@/pages/layouts/NavBar.vue";
</script>

<template>
  <ToastContainer/>
  <div class="d-flex flex-column min-vh-100 bg-light">
    <NavBar/>

    <main class="py-2">
      <slot/>
    </main>

    <footer class="bg-white text-center py-3 border-top text-muted mt-auto">
      <small> 2026 CarsPartsManager System by Dávid Poľa</small>
    </footer>
  </div>
</template>
