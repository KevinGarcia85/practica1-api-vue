<template>
  <div id="app">
    <nav class="navbar">
      <div class="nav-container">
        <h1 class="logo">🏪 Tienda Virtual SPA</h1>
        <div class="nav-links">
          <router-link to="/" class="nav-item">Inicio</router-link>
          <router-link to="/admin" class="nav-item btn-admin">Panel Admin</router-link>
        </div>
      </div>
    </nav>

    <div class="container">
      <EstadoPedido />
    </div>

    <main class="main-content">
      <router-view></router-view>
    </main>
  </div>
</template>

<script setup>
// IMPORTAMOS el componente que creamos en los pasos anteriores
import EstadoPedido from './components/EstadoPedido.vue'
</script>

<style>
/* Tus estilos existentes... */
.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}
.navbar { background-color: #1e293b; padding: 15px 0; color: white; }
.nav-container { max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; padding: 0 20px; }
.logo { margin: 0; font-size: 20px; }
.nav-item { color: #94a3b8; text-decoration: none; margin-left: 20px; font-weight: 500; }
.nav-item:hover, .router-link-active { color: white; }
.btn-admin { background-color: #3b82f6; color: white; padding: 6px 12px; border-radius: 4px; }
.btn-admin:hover { background-color: #2563eb; }
.main-content { padding: 20px 0; }
</style>