<template>
  <div class="catalogo-layout">
    <!-- Barra Lateral Exclusiva para los Filtros -->
    <aside class="sidebar-filtros">
      <FiltrosPanel :filtros="filtros" @limpiar="limpiarFiltros" />
    </aside>

    <!-- Espacio del Listado de Tarjetas -->
    <section class="main-catalogo">
      <h1 class="titulo-catalogo">Catálogo Profesional Full-Stack</h1>

      <!-- Animación Spinner de Espera -->
      <div v-if="cargando" class="loading-state">
        <p>🔄 Buscando y organizando los mejores resultados...</p>
      </div>

      <!-- Cuadrícula de Contenidos -->
      <div v-else-if="resultado.data && resultado.data.length > 0">
        <div class="grid-productos">
          <div v-for="producto in resultado.data" :key="producto.id" class="producto-card">
            <div class="img-wrapper">
              <!-- Renderiza la propiedad 'imagen' corregida de la base de datos -->
              <img :src="producto.imagen || 'https://via.placeholder.com/150'" class="img-item" />
            </div>
            <div class="card-body">
              <span class="badge-cat" v-if="producto.categoria">{{ producto.categoria.nombre }}</span>
              <h3 class="prod-title">{{ producto.nombre }}</h3>
              <p class="prod-desc">{{ producto.descripcion }}</p>
              <div class="prod-meta">
                <span class="prod-price">${{ producto.precio }}</span>
                <span class="prod-stock">Stock: {{ producto.stock }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Barra de Navegación de Paginación -->
        <PaginacionNav :meta="resultado.meta" @cambio-pagina="filtros.pagina = $event" />
      </div>

      <!-- Estado Vacío -->
      <div v-else class="empty-state">
        <p>📭 No hay coincidencias que cumplan con los filtros aplicados.</p>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios' // Importación corregida estándar
import { useFiltros } from '../composables/useFiltros'
import FiltrosPanel from '../components/FiltrosPanel.vue'
import PaginacionNav from '../components/PaginacionNav.vue'

const route = useRoute()
const { filtros, limpiarFiltros } = useFiltros()

const resultado = ref({ data: [], meta: {} })
const cargando = ref(false)

const cargarProductos = async () => {
  cargando.value = true
  try {
    const { data } = await axios.get('http://localhost:8000/api/productos', {
      params: {
        busqueda:     filtros.busqueda,
        categoria_id: filtros.categoria_id,
        precio_min:   filtros.precio_min,
        precio_max:   filtros.precio_max,
        orden:        filtros.orden,
        page:         filtros.pagina,
      }
    })
    resultado.value = data
  } catch (error) {
    console.error("Fallo al comunicarse con la API de productos:", error)
  } finally {
    cargando.value = false
  }
}

// Escucha activa de los parámetros de la URL para disparar la recarga (Sincronización total)
watch(() => route.query, () => {
  // Asegura que los filtros internos reflejen lo que hay en la URL al recargar
  filtros.busqueda = route.query.busqueda || ''
  filtros.categoria_id = route.query.categoria || ''
  filtros.precio_min = route.query.min || ''
  filtros.precio_max = route.query.max || ''
  filtros.orden = route.query.orden || 'nombre'
  filtros.pagina = Number(route.query.p) || 1

  cargarProductos()
}, { immediate: true })
</script>

<style scoped>
.catalogo-layout { display: grid; grid-template-columns: 280px 1fr; gap: 30px; padding: 30px; background-color: #f4f6f9; min-height: 100vh; font-family: sans-serif; }
.sidebar-filtros { position: sticky; top: 20px; }
.titulo-catalogo { text-align: left; margin-top: 0; color: #2c3e50; font-size: 24px; font-weight: bold; margin-bottom: 25px; }
.loading-state, .empty-state { background: white; padding: 60px; text-align: center; border-radius: 8px; color: #666; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
.grid-productos { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 20px; }
.producto-card { background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04); display: flex; flex-direction: column; }
.img-wrapper { background: #f8f9fa; height: 150px; display: flex; align-items: center; justify-content: center; border-bottom: 1px solid #f0f0f0; }
.img-item { max-height: 100%; max-width: 100%; object-fit: cover; }
.card-body { padding: 15px; text-align: left; display: flex; flex-direction: column; flex-grow: 1; }
.badge-cat { display: inline-block; background: #e8f4fd; color: #3498db; font-size: 10px; font-weight: bold; padding: 2px 6px; border-radius: 10px; margin-bottom: 8px; width: fit-content; text-transform: uppercase; }
.prod-title { font-size: 16px; color: #333; margin: 0 0 5px 0; font-weight: bold; }
.prod-desc { font-size: 12px; color: #777; margin: 0 0 15px 0; flex-grow: 1; line-height: 1.4; }
.prod-meta { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f4f6f9; padding-top: 10px; }
.prod-price { font-size: 18px; color: #2ecc71; font-weight: bold; }
.prod-stock { font-size: 11px; color: #999; }
</style>