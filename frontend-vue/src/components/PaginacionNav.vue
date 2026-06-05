<template>
  <div class="paginacion-container" v-if="meta && meta.last_page > 1">
    <!-- Ir a la Primera Página -->
    <button 
      class="pag-btn" 
      :disabled="meta.current_page === 1" 
      @click="cambiarPagina(1)"
    >
      « Primera
    </button>

    <!-- Anterior -->
    <button 
      class="pag-btn" 
      :disabled="meta.current_page === 1" 
      @click="cambiarPagina(meta.current_page - 1)"
    >
      ‹ Ant
    </button>

    <!-- Info del Estado -->
    <span class="pag-info">
      Página <strong>{{ meta.current_page }}</strong> de <strong>{{ meta.last_page }}</strong> 
      <small class="total-txt">({{ meta.total }} productos)</small>
    </span>

    <!-- Siguiente -->
    <button 
      class="pag-btn" 
      :disabled="meta.current_page === meta.last_page" 
      @click="cambiarPagina(meta.current_page + 1)"
    >
      Sig ›
    </button>

    <!-- Ir a la Última Página -->
    <button 
      class="pag-btn" 
      :disabled="meta.current_page === meta.last_page" 
      @click="cambiarPagina(meta.last_page)"
    >
      Última »
    </button>
  </div>
</template>

<script setup>
defineProps({
  meta: { type: Object, required: true }
})

const emit = defineEmits(['cambio-pagina'])

const cambiarPagina = (paginaDestino) => {
  emit('cambio-pagina', paginaDestino)
}
</script>

<style scoped>
.paginacion-container { display: flex; justify-content: center; align-items: center; gap: 8px; margin-top: 30px; padding: 15px 0; }
.pag-btn { background-color: white; border: 1px solid #ccc; color: #333; padding: 6px 12px; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 13px; transition: all 0.2s; }
.pag-btn:hover:not(:disabled) { background-color: #3498db; color: white; border-color: #3498db; }
.pag-btn:disabled { color: #ccc; cursor: not-allowed; background-color: #f9f9f9; border-color: #eaeaea; }
.pag-info { font-size: 14px; color: #555; }
.total-txt { display: block; color: #888; font-size: 11px; }
</style>