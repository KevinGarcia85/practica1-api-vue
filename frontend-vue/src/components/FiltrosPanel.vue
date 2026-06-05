<template>
  <div class="filtros-panel">
    <h3>🔍 Filtros Avanzados</h3>

    <!-- Búsqueda por texto -->
    <div class="form-group">
      <label>Buscar Producto:</label>
      <input 
        v-model="inputBusqueda" 
        type="text" 
        placeholder="Ej. Camisa, TV..." 
        class="form-control"
      />
    </div>

    <!-- Selector de Categorías -->
    <div class="form-group">
      <label>Categoría:</label>
      <select v-model="filtros.categoria_id" @change="resetPagina" class="form-control">
        <option value="">Todas las categorías</option>
        <option v-for="cat in categorias" :key="cat.id" :value="cat.id">
          {{ cat.nombre }}
        </option>
      </select>
    </div>

    <!-- Rango de Precios -->
    <div class="form-group">
      <label>Precio Rango:</label>
      <div class="rango-inputs">
        <input 
          v-model.number="filtros.precio_min" 
          @input="resetPagina" 
          type="number" 
          placeholder="Min" 
          class="form-control"
        />
        <input 
          v-model.number="filtros.precio_max" 
          @input="resetPagina" 
          type="number" 
          placeholder="Max" 
          class="form-control"
        />
      </div>
    </div>

    <!-- Selector de Ordenamiento -->
    <div class="form-group">
      <label>Ordenar por:</label>
      <select v-model="filtros.orden" @change="resetPagina" class="form-control">
        <option value="nombre">Nombre A-Z</option>
        <option value="precio_asc">Precio: Menor a Mayor</option>
        <option value="precio_desc">Precio: Mayor a Menor</option>
      </select>
    </div>

    <!-- Botón Limpiar -->
    <button class="btn-limpiar" @click="$emit('limpiar')">
      🧹 Limpiar Filtros
    </button>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import axios from 'axios'

const props = defineProps({
  filtros: { type: Object, required: true }
})

const emit = defineEmits(['limpiar'])

const categorias = ref([])
const inputBusqueda = ref(props.filtros.busqueda)
let debounceTimeout = null

// Efecto Debounce de 300ms solicitado en los requisitos
watch(inputBusqueda, (nuevoValor) => {
  clearTimeout(debounceTimeout)
  debounceTimeout = setTimeout(() => {
    props.filtros.busqueda = nuevoValor
    resetPagina()
  }, 300)
})

// Sincronizar campo si los filtros se limpian externamente
watch(() => props.filtros.busqueda, (val) => {
  inputBusqueda.value = val
})

const resetPagina = () => {
  props.filtros.pagina = 1
}

onMounted(async () => {
  try {
    const { data } = await axios.get('http://localhost:8000/api/categorias')
    categorias.value = data
  } catch (error) {
    console.error("Error cargando categorías en el panel:", error)
  }
})
</script>

<style scoped>
.filtros-panel { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); text-align: left; }
.filtros-panel h3 { margin-top: 0; color: #2c3e50; font-size: 18px; border-bottom: 2px solid #f4f6f9; padding-bottom: 10px; }
.form-group { margin-bottom: 15px; }
label { display: block; font-weight: bold; font-size: 13px; color: #555; margin-bottom: 5px; }
.form-control { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 13px; }
.rango-inputs { display: flex; gap: 8px; }
.btn-limpiar { width: 100%; background-color: #e74c3c; color: white; border: none; padding: 10px; border-radius: 4px; font-weight: bold; cursor: pointer; margin-top: 10px; transition: background 0.2s; }
.btn-limpiar:hover { background-color: #c0392b; }
</style>