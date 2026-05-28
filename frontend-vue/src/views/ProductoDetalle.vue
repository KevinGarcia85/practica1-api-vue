<template>
  <div class="container-detalle">
    <div v-if="loading" class="loading">Cargando información del artículo...</div>
    <div v-else-if="error" class="error-msg">{{ error }}</div>
    <div v-else class="detalle-card">
      <h2>📦 {{ producto.nombre }}</h2>
      <hr>
      <p class="desc"><strong>Descripción:</strong> {{ producto.descripcion || 'Sin descripción disponible.' }}</p>
      <div class="meta-info">
        <p class="precio">Precio: <span>${{ producto.precio }}</span></p>
        <p class="stock">Disponibilidad actual: <span>{{ producto.stock }} unidades</span></p>
      </div>
      <button @click="volver" class="btn-back">⬅️ Volver al catálogo</button>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'

export default {
  setup() {
    const router = useRouter()
    const route = useRoute()
    const producto = ref(null)
    const loading = ref(true)
    const error = ref(null)

    const cargarDetalle = async () => {
      try {
        // Obtenemos el parámetro id directo desde la URL activa
        const id = route.params.id
        const res = await axios.get(`http://localhost:8000/api/productos/${id}`)
        producto.value = res.data
      } catch (err) {
        error.value = "No se pudo recuperar la información del producto seleccionado."
      } finally {
        loading.value = false
      }
    }

    const volver = () => {
      router.back() // Utiliza la pila del historial nativo para regresar de forma segura
    }

    onMounted(cargarDetalle)

    return { producto, loading, error, volver }
  }
}
</script>

<style scoped>
.container-detalle { font-family: Arial, sans-serif; display: flex; justify-content: center; align-items: center; min-height: 70vh; padding: 20px; }
.detalle-card { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 100%; max-width: 550px; color: #333; }
h2 { margin-top: 0; color: #111; }
.desc { font-size: 16px; line-height: 1.5; margin: 20px 0; color: #555; }
.meta-info { display: flex; justify-content: space-between; margin-bottom: 30px; background: #f8f9fa; padding: 15px; border-radius: 6px; }
.precio span { color: #28a745; font-weight: bold; font-size: 18px; }
.stock span { font-weight: bold; color: #333; }
.btn-back { background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; width: 100%; font-size: 15px; }
.loading, .error-msg { font-size: 18px; text-align: center; font-weight: bold; }
.error-msg { color: #dc3545; }
</style>