<template>
  <div class="checkout-box">
    <h3>🛒 Finalizar Compra Simulada</h3>
    
    <div v-if="estado === 'formulario'" class="form-section">
      <div class="form-group">
        <label>Correo Electrónico:</label>
        <input v-model="email" type="email" placeholder="cliente@correo.com" class="form-control" />
      </div>
      <div class="form-group">
        <label>Total de la compra ($):</label>
        <input v-model.number="total" type="number" class="form-control" />
      </div>
      <button @click="procesarPago" class="btn-pay">Confirmar y Pagar</button>
    </div>

    <div v-else class="status-section">
      <p class="pedido-id">Pedido #{{ pedidoId }}</p>
      
      <div v-if="estado === 'procesando'" class="status-alert processing">
        <span class="spinner">⏳</span> Procesando tu pedido en segundo plano... Las colas de Laravel están trabajando.
      </div>
      
      <div v-if="estado === 'listo'" class="status-alert success">
        <span class="check">✅</span> ¡Pedido confirmado de forma asíncrona! El correo electrónico ha sido enviado con éxito.
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onUnmounted } from 'vue'
import axios from 'axios'

const email = ref('')
const total = ref(150.00)
const estado = ref('formulario') // formulario | procesando | listo
const pedidoId = ref(null)
let intervaloPolling = null

const procesarPago = async () => {
  if (!email.value) return alert("Por favor, ingresa un correo electrónico.")
  
  try {
    // URL corregida apuntando explícitamente al puerto 8000 de Laravel
    const { data } = await axios.post('http://127.0.0.1:8000/api/pedidos', {
      cliente_email: email.value,
      total: total.value
    })
    
    pedidoId.value = data.pedido.id
    estado.value = 'procesando'
    
    // Iniciar Polling automático cada 3 segundos
    iniciarPolling(data.pedido.id)
  } catch (error) {
    console.error("Error al registrar pedido:", error)
  }
}

const iniciarPolling = (id) => {
  intervaloPolling = setInterval(async () => {
    try {
      // URL corregida apuntando explícitamente al puerto 8000 de Laravel
      const { data } = await axios.get(`http://127.0.0.1:8000/api/pedidos/${id}`)
      
      // Si la columna timestamp ya no es null, significa que el Job se ejecutó
      if (data.email_enviado_at) {
        estado.value = 'listo'
        clearInterval(intervaloPolling)
      }
    } catch (e) {
      console.error("Error en polling:", e)
    }
  }, 3000)
}

onUnmounted(() => {
  if (intervaloPolling) clearInterval(intervaloPolling)
})
</script>

<style scoped>
.checkout-box { background: white; padding: 25px; border-radius: 8px; max-width: 500px; margin: 30px auto; box-shadow: 0 4px 10px rgba(0,0,0,0.08); font-family: sans-serif; }
h3 { margin-top: 0; color: #1e293b; margin-bottom: 20px; }
.form-group { margin-bottom: 15px; }
label { display: block; font-weight: bold; margin-bottom: 5px; color: #475569; font-size: 14px; }
.form-control { width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 4px; box-sizing: border-box; }
.btn-pay { width: 100%; padding: 12px; background-color: #3b82f6; color: white; border: none; font-weight: bold; border-radius: 4px; cursor: pointer; font-size: 15px; margin-top: 10px; }
.btn-pay:hover { background-color: #2563eb; }
.pedido-id { font-weight: bold; font-size: 18px; color: #3b4c66; text-align: center; margin-bottom: 15px; }
.status-alert { padding: 15px; border-radius: 6px; font-size: 14px; line-height: 1.5; text-align: center; }
.processing { background-color: #fef3c7; color: #d97706; border: 1px solid #fde68a; }
.success { background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
.spinner { display: inline-block; animation: spin 2s linear infinite; }
@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(362deg); } }
</style>