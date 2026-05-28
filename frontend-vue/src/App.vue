<template>
  <div id="app">
    <!-- NAVBAR PÚBLICA -->
    <nav v-if="!esRutaAdmin" class="navbar-publica">
      <div class="nav-container">
        <router-link to="/" class="logo">🏪 Mi Tienda SPA</router-link>
        <div class="enlaces">
          <router-link to="/">Inicio</router-link>
          <router-link to="/catalogo">Catálogo</router-link>
          
          <!-- ÍCONO DEL CARRITO (Inyectado directamente aquí) -->
          <router-link to="/carrito" class="cart-link">
            <span class="cart-icon">🛒
              <span v-if="carrito.totalItems > 0" class="badge">
                {{ carrito.totalItems }}
              </span>
            </span>
          </router-link>
          
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
import { useCarritoStore } from './stores/carrito' // Importamos el store que ya funciona

export default {
  setup() {
    const route = useRoute()
    const carrito = useCarritoStore() // Conectamos el carrito directamente aquí
    const esRutaAdmin = computed(() => route.path.startsWith('/admin'))

    return { esRutaAdmin, carrito }
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
.enlaces {
  display: flex;
  align-items: center;
}
.enlaces a, .enlaces .cart-link {
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
.enlaces .btn-admin-nav:hover {
  background-color: #34495e;
}
.contenido-principal {
  padding: 10px 0;
}

/* Estilos del ícono del carrito */
.cart-link {
  text-decoration: none;
  display: inline-block;
}
.cart-icon {
  font-size: 22px;
  position: relative;
  cursor: pointer;
}
.badge {
  background-color: #e74c3c;
  color: white;
  font-size: 11px;
  font-weight: bold;
  border-radius: 50%;
  padding: 2px 6px;
  position: absolute;
  top: -8px;
  right: -10px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}
</style>