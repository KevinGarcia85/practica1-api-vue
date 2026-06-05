<template>
  <div class="white-card form-card">
    <h3>Nuevo Producto</h3>
    <form @submit.prevent="handleSubmit">
      
      <!-- PASO 4.7: COMPONENTE SELECT OBLIGATORIO PARA CATEGORÍAS -->
      <div class="form-group">
        <label>Categoría del Producto:</label>
        <select v-model="form.categoria_id" class="form-control select-control" required>
          <option value="">-- Selecciona una Categoría --</option>
          <option v-for="cat in listaCategorias" :key="cat.id" :value="cat.id">
            {{ cat.nombre }}
          </option>
        </select>
      </div>

      <div class="form-group">
        <label>Nombre del Artículo:</label>
        <input v-model="form.nombre" type="text" required class="form-control" placeholder="Ej. Teclado Mecánico" />
      </div>

      <div class="form-group">
        <label>Descripción:</label>
        <textarea v-model="form.descripcion" class="form-control" rows="2" placeholder="Detalles..."></textarea>
      </div>

      <div class="form-group">
        <label>Precio Unitario:</label>
        <input v-model.number="form.precio" type="number" step="0.01" min="0" required class="form-control" />
      </div>

      <div class="form-group">
        <label>Stock Inicial:</label>
        <input v-model.number="form.stock" type="number" min="0" required class="form-control" />
      </div>

      <!-- Mantener compatibilidad con fotos multimedia (Práctica 5) -->
      <div class="form-group file-group">
        <label>Imagen del Producto:</label>
        <input type="file" accept="image/*" @change="onFileChange" class="file-input" />
      </div>

      <button type="submit" class="btn-guardar" :disabled="cargando">
        {{ cargando ? 'Sincronizando...' : 'Guardar Producto' }}
      </button>
    </form>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import axios from 'axios'

defineProps({
  cargando: { type: Boolean, default: false }
})

const emit = defineEmits(['guardar'])
const listaCategorias = ref([])

const form = reactive({
  categoria_id: '',
  nombre: '',
  descripcion: '',
  precio: 0,
  stock: 0
})

const imagenFile = ref(null)

// Trae las categorías disponibles para llenar el <select>
const cargarCategoriasEnFormulario = async () => {
  try {
    const { data } = await axios.get('http://localhost:8000/api/categorias')
    listaCategorias.value = data.data
  } catch (error) {
    console.error("No se pudieron cargar las categorías en el selector:", error)
  }
}

const onFileChange = (e) => {
  const file = e.target.files[0]
  if (file) imagenFile.value = file
}

const handleSubmit = () => {
  // Construcción del FormData empaquetando la nueva llave categoria_id
  const fd = new FormData()
  fd.append('categoria_id', form.categoria_id)
  fd.append('nombre', form.nombre)
  fd.append('descripcion', form.descripcion || '')
  fd.append('precio', form.precio)
  fd.append('stock', form.stock)
  
  if (imagenFile.value) {
    fd.append('imagen', imagenFile.value)
  }

  emit('guardar', fd)

  // Limpieza del formulario
  form.categoria_id = ''
  form.nombre = ''
  form.descripcion = ''
  form.precio = 0
  form.stock = 0
  imagenFile.value = null
}

onMounted(() => {
  cargarCategoriasEnFormulario()
})
</script>

<style scoped>
.white-card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.04); }
h3 { margin-top: 0; color: #333; font-size: 20px; border-bottom: 2px solid #f4f6f9; padding-bottom: 10px; margin-bottom: 15px; font-weight: bold; }
.form-group { margin-bottom: 14px; text-align: left; }
label { display: block; font-weight: bold; margin-bottom: 5px; color: #444; font-size: 13px; }
.form-control { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; font-size: 14px; }
.form-control:focus { border-color: #3498db; outline: none; }
.select-control { background-color: #fff; cursor: pointer; color: #444; font-weight: 500; }
.file-group { background: #f9f9f9; padding: 10px; border-radius: 5px; border: 1px dashed #ccc; }
.file-input { margin-top: 5px; font-size: 12px; cursor: pointer; }
.btn-guardar { background-color: #2ecc71; color: white; border: none; padding: 12px; border-radius: 5px; font-weight: bold; width: 100%; cursor: pointer; font-size: 15px; margin-top: 10px; }
.btn-guardar:hover { background-color: #27ae60; }
.btn-guardar:disabled { background-color: #95a5a6; cursor: not-allowed; }
</style>