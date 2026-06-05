<template>
  <div class="crud-container">
    <!-- FORMULARIO NUEVO PRODUCTO -->
    <div class="white-card form-card">
      <h3>Nuevo Producto</h3>
      <form @submit.prevent="guardarProducto">
        <div class="form-group">
          <label>Nombre:</label>
          <input v-model="form.nombre" type="text" required class="form-control" />
        </div>

        <div class="form-group">
          <label>Descripción:</label>
          <textarea v-model="form.descripcion" class="form-control" rows="2"></textarea>
        </div>

        <div class="form-group">
          <label>Precio:</label>
          <input v-model.number="form.precio" type="number" step="0.01" min="0" required class="form-control" />
        </div>

        <div class="form-group">
          <label>Stock:</label>
          <input v-model.number="form.stock" type="number" min="0" required class="form-control" />
        </div>

        <!-- CASILLA MULTIMEDIA PARA LA IMAGEN -->
        <div class="form-group file-group">
          <label class="file-label">Imagen del Producto:</label>
          <input type="file" accept="image/*" @change="onImageChange" class="file-input" />
        </div>

        <!-- VISTA PREVIA REACTIVA -->
        <div v-if="preview" class="image-preview">
          <span>Vista previa:</span>
          <img :src="preview" alt="Preview" class="img-thumb" />
        </div>

        <button type="submit" class="btn-guardar">Guardar</button>
      </form>
    </div>

    <!-- TABLA DE PRODUCTOS -->
    <div class="white-card list-card">
      <h3>Lista de Productos</h3>
      <table class="products-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Stock</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="producto in listaProductos" :key="producto.id">
            <td>{{ producto.id }}</td>
            <td>
              <strong>{{ producto.nombre }}</strong>
              <small class="desc-muted">{{ producto.descripcion }}</small>
            </td>
            <td>${{ producto.precio }}</td>
            <td>{{ producto.stock }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import axios from 'axios'

const form = reactive({
  nombre: '',
  descripcion: '',
  precio: 0,
  stock: 0
})

const listaProductos = ref([])
const imagen = ref(null)
const preview = ref(null)

// Captura el archivo binario seleccionado por el usuario
const onImageChange = (e) => {
  const file = e.target.files[0]
  if (!file) return
  imagen.value = file
  preview.value = URL.createObjectURL(file)
}

const cargarProductos = async () => {
  try {
    const respuesta = await axios.get('http://localhost:8000/api/productos')
    listaProductos.value = respuesta.data.data || respuesta.data
  } catch (error) {
    console.error("Error al cargar productos", error)
  }
}

// Envío obligatorio mediante FormData para soportar archivos
const guardarProducto = async () => {
  try {
    const fd = new FormData()
    fd.append('nombre', form.nombre)
    fd.append('descripcion', form.descripcion || '')
    fd.append('precio', form.precio)
    fd.append('stock', form.stock)
    
    if (imagen.value) {
      fd.append('imagen', imagen.value)
    }

    await axios.post('http://localhost:8000/api/productos', fd, {
      headers: { 'Content-Type': 'multipart/form-data' }
    })

    alert("¡Producto creado con imagen exitosamente!")
    
    // Limpiar campos
    form.nombre = ''
    form.descripcion = ''
    form.precio = 0
    form.stock = 0
    imagen.value = null
    preview.value = null

    cargarProductos()
  } catch (error) {
    console.error(error)
    alert("Error al guardar el producto.")
  }
}

onMounted(() => {
  cargarProductos()
})
</script>

<style scoped>
.crud-container { display: grid; grid-template-columns: 1fr 1.5fr; gap: 20px; align-items: start; padding: 10px; }
.white-card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
.form-group { margin-bottom: 12px; text-align: left; }
label { display: block; font-weight: bold; margin-bottom: 4px; color: #333; font-size: 14px; }
.form-control { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
.file-group { background: #f9f9f9; padding: 10px; border-radius: 4px; border: 1px dashed #bbb; }
.file-input { margin-top: 5px; }
.image-preview { text-align: center; margin: 10px 0; }
.img-thumb { max-width: 80px; max-height: 80px; object-fit: cover; border-radius: 4px; }
.btn-guardar { background-color: #2ecc71; color: white; border: none; padding: 10px; border-radius: 4px; font-weight: bold; width: 100%; cursor: pointer; }
.products-table { width: 100%; border-collapse: collapse; }
.products-table th, .products-table td { padding: 10px; border-bottom: 1px solid #eee; text-align: left; }
.desc-muted { display: block; color: #777; font-size: 12px; }
</style>