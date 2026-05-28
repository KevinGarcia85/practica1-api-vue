<template>
  <router-link to="/carrito" class="cart-link">
    <span class="cart-icon">🛒
      <!-- El badge solo se muestra si hay elementos en el carrito -->
      <span v-if="carrito.totalItems > 0" class="badge">
        {{ carrito.totalItems }}
      </span>
    </span>
  </router-link>
</template>

<script>
import { useCarritoStore } from '../stores/carrito'

export default {
  name: 'CartIcon',
  setup() {
    const carrito = useCarritoStore()
    return { carrito }
  }
}
</script>

<style scoped>
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