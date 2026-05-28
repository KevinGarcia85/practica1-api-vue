<template>
  <div id="app">
    <!-- NAVBAR PÚBLICA -->
    <nav v-if="!esRutaAdmin" class="navbar-publica">
      <div class="nav-container">
        <router-link to="/" class="logo">🏪 Mi Tienda SPA</router-link>
        <div class="enlaces">
          <router-link to="/">Inicio</router-link>
          <router-link to="/catalogo">Catálogo</router-link>
          <router-link to="/login" class="btn-admin-nav">Panel Admin</router-link>
        </div>
      </div>
    </nav>

    <!-- CONTENEDOR DE VISTAS -->
    <div class="contenido-principal">
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
import { computed } from 'vue'
import { useRoute } from 'vue-router'

export default {
  setup() {
    const route = useRoute()
    const esRutaAdmin = computed(() => route.path.startsWith('/admin'))

    return { esRutaAdmin }
  }
}
</script>

<style>
body {
  margin: 0;
  font-family: 'Segoe UI', Roboto, Arial, sans-serif;
  background-color: #f4f6f9;
  color: #333;
}
.navbar-publica {
  background-color: #ffffff;
  box-shadow: 0 2px 4px rgba(0,0,0,0.08);
  padding: 15px 0;
  margin-bottom: 20px;
}
.nav-container {
  max-width: 1100px;
  margin: 0 auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 20px;
}
.logo {
  font-size: 20px;
  font-weight: bold;
  color: #2c3e50;
  text-decoration: none;
}
.enlaces a {
  color: #5a6c7d;
  text-decoration: none;
  font-weight: 600;
  margin-left: 20px;
  padding: 6px 12px;
  border-radius: 4px;
}
.enlaces a:hover, .enlaces .router-link-exact-active {
  color: #007bff;
  background-color: #f0f7ff;
}
.enlaces .btn-admin-nav {
  background-color: #2c3e50;
  color: #fff !important;
}
.contenido-principal {
  padding: 10px 0;
}
</style>