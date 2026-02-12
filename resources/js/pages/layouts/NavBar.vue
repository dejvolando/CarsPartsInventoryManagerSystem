<script setup lang="ts">
import {route} from "ziggy-js";
import { Link } from '@inertiajs/vue3';

</script>

<template>
  <nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top border-bottom">
    <div class="container">
      <Link class="navbar-brand fw-bold text-primary" :href="route('home')">
        <i class="bi bi-car-front-fill"></i>CarsPartsManager<i class="bi bi-wrench"></i>
      </Link>

      <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
        <div class="offcanvas-header border-bottom">
          <h5 class="offcanvas-title fw-bold" id="offcanvasNavbarLabel">Možnosti</h5>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body">
          <ul class="navbar-nav justify-content-end flex-grow-1 pe-3 gap-lg-2 gap-2 text-center">
            <Link class="nav-link bg-primary rounded text-white" :href="route('home')"><i class="bi bi-house-fill me-2"/>Domov</Link>
            <Link class="nav-link bg-primary rounded text-white" :href="route('cars.index')"> <i class="bi bi-car-front-fill me-2"/>Autá</Link>
            <Link class="nav-link bg-primary rounded text-white " :href="route('parts.index')"><i class="bi bi-wrench me-2"/>Diely</Link>
          </ul>
        </div>
      </div>
    </div>
  </nav>
</template>
