<template>
  <div class="cart-page">
    <h2>🛒 Tu Carrito de Compras</h2>

    <!-- ESCENARIO A: El carrito está vacío -->
    <div v-if="carrito.items.length === 0" class="empty-cart">
      <p>No has agregado productos a tu carrito todavía.</p>
      <router-link to="/catalogo" class="btn-tienda">Volver al catálogo</router-link>
    </div>

    <!-- ESCENARIO B: El carrito tiene productos -->
    <div v-else class="cart-container">
      <table class="cart-table">
        <thead>
          <tr>
            <th>Producto</th>
            <th>Precio</th>
            <th>Cantidad</th>
            <th>Subtotal</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in carrito.items" :key="item.id">
            <td><strong>{{ item.nombre }}</strong></td>
            <td>${{ item.precio }}</td>
            <td>
              <div class="quantity-controls">
                <!-- Botón - para disminuir piezas -->
                <button @click="carrito.cambiarCantidad(item.id, item.cantidad - 1)" class="btn-qty">-</button>
                <span class="qty-value">{{ item.cantidad }}</span>
                <!-- Botón + para aumentar piezas -->
                <button @click="carrito.cambiarCantidad(item.id, item.cantidad + 1)" class="btn-qty">+</button>
              </div>
            </td>
            <td class="subtotal-val">${{ (item.precio * item.cantidad) }}</td>
            <td>
              <button @click="carrito.quitar(item.id)" class="btn-delete">🗑️ Eliminar</button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Resumen de Totales -->
      <div class="cart-summary">
        <div class="summary-row">
          <span>Artículos totales:</span>
          <strong>{{ carrito.totalItems }} pzas</strong>
        </div>
        <div class="summary-row total-row">
          <span>Total General:</span>
          <span class="total-price">${{ carrito.totalPrecio }}</span>
        </div>
        
        <div class="summary-actions">
          <button @click="carrito.vaciar()" class="btn-clear">Vaciar Carrito</button>
          <button @click="procesarCompra" class="btn-checkout">Finalizar Compra</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { useCarritoStore } from '../stores/carrito'
import axios from 'axios' // Importamos Axios para conectar con el backend

export default {
  name: 'CartView',
  setup() {
    const carrito = useCarritoStore()

    // Función conectada a la API de Laravel
    const procesarCompra = async () => {
      if (carrito.items.length === 0) return

      try {
        // Enviamos los productos al backend
        const respuesta = await axios.post('http://localhost:8000/api/pedidos', {
          items: carrito.items
        })

        // Si Laravel responde con éxito
        alert(`🛒 Laravel dice: ${respuesta.data.message}`)
        carrito.vaciar() // Vaciamos el store
        
      } catch (error) {
        console.error("Error al procesar el pedido en el servidor", error)
        alert("Hubo un error al conectar con Laravel para procesar tu compra.")
      }
    }

    return { carrito, procesarCompra }
  }
}
</script>

<style scoped>
.cart-page { max-width: 1000px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif; }
h2 { color: #2c3e50; border-bottom: 2px solid #ddd; padding-bottom: 10px; }
.empty-cart { text-align: center; padding: 40px; background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
.btn-tienda { display: inline-block; background: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-weight: bold; margin-top: 15px; }
.cart-container { display: flex; flex-direction: column; gap: 30px; margin-top: 20px; }
.cart-table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
.cart-table th, .cart-table td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
.cart-table th { background: #f8f9fa; color: #555; }
.quantity-controls { display: flex; align-items: center; gap: 10px; }
.btn-qty { background: #e0e0e0; border: none; padding: 5px 12px; font-size: 16px; cursor: pointer; border-radius: 4px; font-weight: bold; }
.btn-qty:hover { background: #d0d0d0; }
.qty-value { font-weight: bold; font-size: 16px; min-width: 20px; text-align: center; }
.subtotal-val { font-weight: bold; color: #2c3e50; }
.btn-delete { background: none; border: none; color: #e74c3c; cursor: pointer; font-weight: bold; }
.btn-delete:hover { text-decoration: underline; }
.cart-summary { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); align-self: flex-end; width: 100%; max-width: 350px; }
.summary-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 16px; }
.total-row { border-top: 2px solid #eee; padding-top: 15px; margin-top: 15px; font-weight: bold; }
.total-price { color: #28a745; font-size: 22px; }
.summary-actions { display: flex; flex-direction: column; gap: 10px; margin-top: 20px; }
.btn-checkout { background: #28a745; color: white; border: none; padding: 12px; border-radius: 6px; font-size: 16px; font-weight: bold; cursor: pointer; }
.btn-checkout:hover { background: #218838; }
.btn-clear { background: none; border: 1px solid #ccc; color: #777; padding: 8px; border-radius: 6px; cursor: pointer; }
.btn-clear:hover { background: #f8f9fa; color: #333; }
</style>