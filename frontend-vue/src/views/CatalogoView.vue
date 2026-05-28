<template>
  <div class="page">
    <h2>Catálogo de Productos</h2>
    
    <!-- BUSCADOR EN TIEMPO REAL -->
    <div class="search-box">
      <input type="text" v-model="busqueda" placeholder="🔍 Buscar producto por nombre..." />
    </div>

    <div class="grid">
      <div v-for="p in productosFiltrados" :key="p.id" class="card-producto">
        <h3>{{ p.nombre }}</h3>
        <p class="precio">${{ p.precio }}</p>
        <p class="stock">Disponibles: {{ p.stock }} pzas</p>
        <router-link :to="'/catalogo/' + p.id" class="btn-detalle">Ver Detalles</router-link>
      </div>
      <p v-if="productosFiltrados.length === 0" style="grid-column: 1/-1;">No se encontraron productos que coincidan.</p>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'

export default {
  setup() {
    const productos = ref([])
    const busqueda = ref('')

    const cargarProductos = async () => {
      try {
        const res = await axios.get('http://localhost:8000/api/productos')
        productos.value = res.data
      } catch (err) {
        console.error("Error al traer el catálogo", err)
      }
    }

    const productosFiltrados = computed(() => {
      return productos.value.filter(p => 
        p.nombre.toLowerCase().includes(busqueda.value.toLowerCase())
      )
    })

    onMounted(cargarProductos)

    return { busqueda, productosFiltrados }
  }
}
</script>

<style scoped>
.page { font-family: Arial, sans-serif; max-width: 1000px; margin: 0 auto; padding: 20px; color: #333; }
.search-box { margin-bottom: 30px; text-align: center; }
.search-box input { width: 100%; max-width: 500px; padding: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 16px; }
.grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
.card-producto { background: white; padding: 20px; border-radius: 8px; border: 1px solid #ddd; box-shadow: 0 2px 5px rgba(0,0,0,0.05); display: flex; flex-direction: column; justify-content: space-between; }
.precio { font-size: 20px; font-weight: bold; color: #28a745; margin: 10px 0; }
.stock { font-size: 14px; color: #777; margin-bottom: 15px; }
.btn-detalle { display: block; text-align: center; background: #007bff; color: white; padding: 8px; text-decoration: none; border-radius: 4px; font-weight: bold; }
</style>