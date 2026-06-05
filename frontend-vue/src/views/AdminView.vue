<template>
  <div class="admin-container">
    <h2 class="main-title">Gestión de Productos Autorizada</h2>
    
    <div class="crud-layout">
      <!-- FORMULARIO DE CREACIÓN/EDICIÓN -->
      <div class="form-card">
        <h3>{{ editandoId ? '📝 Editar Producto' : '📦 Nuevo Producto' }}</h3>
        <form @submit.prevent="onSubmit">
          
          <InputField
            label="Nombre del Producto:"
            name="nombre"
            v-model="nombre"
            :error="errors.nombre || erroresServidor.nombre?.[0]"
          />

          <InputField
            label="Precio:"
            name="precio"
            type="number"
            v-model.number="precio"
            :error="errors.precio || erroresServidor.precio?.[0]"
          />

          <InputField
            label="Stock:"
            name="stock"
            type="number"
            v-model.number="stock"
            :error="errors.stock || erroresServidor.stock?.[0]"
          />

          <button type="submit" class="btn-submit">
            {{ editandoId ? 'Actualizar Cambios' : 'Guardar' }}
          </button>
          <button v-if="editandoId" type="button" @click="limpiarFormulario" class="btn-cancel">
            Cancelar
          </button>
        </form>
      </div>

      <!-- TABLA DE PRODUCTOS -->
      <div class="table-card">
        <h3>Lista de Productos</h3>
        <table class="products-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Precio</th>
              <th>Stock</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="prod in productos" :key="prod.id">
              <td>{{ prod.id }}</td>
              <td>{{ prod.nombre }}</td>
              <td>${{ prod.precio }}</td>
              <td>{{ prod.stock }} unidades</td>
              <td>
                <button @click="cargarEdicion(prod)" class="btn-edit">Editar</button>
                <button @click="eliminarProducto(prod.id)" class="btn-delete">Eliminar</button>
              </td>
            </tr>
            <tr v-if="productos.length === 0">
              <td colspan="5" class="empty-msg">No hay productos registrados.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useForm, useField } from 'vee-validate'
import { productoSchema } from '@/schemas/productoSchema'
import InputField from '@/components/InputField.vue'

const productos = ref([])
const editandoId = ref(null)
const erroresServidor = ref({})

// Configuración de VeeValidate con Yup
const { handleSubmit, errors, resetForm, setValues } = useForm({
  validationSchema: productoSchema,
  initialValues: { nombre: '', precio: 0, stock: 1 }
})

const { value: nombre } = useField('nombre')
const { value: precio } = useField('precio')
const { value: stock } = useField('stock')

const obtenerProductos = async () => {
  try {
    const { data } = await axios.get('/productos')
    productos.value = data
  } catch (error) {
    console.error(error)
  }
}

// Envío unificado manejando éxitos y excepciones 422
const onSubmit = handleSubmit(async (values) => {
  try {
    erroresServidor.value = {}
    if (editandoId.value) {
      await axios.put(`/productos/${editandoId.value}`, values)
      alert("¡Producto actualizado!")
    } else {
      await axios.post('/productos', values)
      alert("¡Producto creado!")
    }
    limpiarFormulario()
    await obtenerProductos()
  } catch (e) {
    if (e.response?.status === 422) {
      erroresServidor.value = e.response.data.errors
    } else {
      alert("Ocurrió un error inesperado.")
    }
  }
})

const cargarEdicion = (prod) => {
  editandoId.value = prod.id
  setValues({
    nombre: prod.nombre,
    precio: prod.precio,
    stock: prod.stock
  })
}

const eliminarProducto = async (id) => {
  if (!confirm("¿Eliminar?")) return
  try {
    await axios.delete(`/productos/${id}`)
    await obtenerProductos()
  } catch (error) {
    console.error(error)
  }
}

const limpiarFormulario = () => {
  editandoId.value = null
  resetForm({ values: { nombre: '', precio: 0, stock: 1 } })
  erroresServidor.value = {}
}

onMounted(() => {
  obtenerProductos()
})
</script>

<style scoped>
.admin-container { padding: 20px; font-family: sans-serif; background-color: #f8fafc; min-height: 100vh; }
.main-title { font-size: 24px; color: #1e293b; margin-bottom: 25px; font-weight: bold; }
.crud-layout { display: grid; grid-template-columns: 1fr 2fr; gap: 25px; }
.form-card, .table-card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
h3 { margin-top: 0; margin-bottom: 20px; color: #334155; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px; }
.btn-submit { background-color: #10b981; color: white; border: none; padding: 10px 15px; border-radius: 4px; font-weight: bold; cursor: pointer; width: 100%; margin-bottom: 10px; }
.btn-cancel { background-color: #64748b; color: white; border: none; padding: 10px 15px; border-radius: 4px; font-weight: bold; cursor: pointer; width: 100%; }
.products-table { width: 100%; border-collapse: collapse; }
.products-table th, .products-table td { padding: 12px; border-bottom: 1px solid #e2e8f0; font-size: 14px; text-align: left; }
.products-table th { background-color: #f8fafc; color: #64748b; }
.btn-edit { background-color: #3b82f6; color: white; border: none; padding: 6px 12px; border-radius: 4px; margin-right: 5px; cursor: pointer; }
.btn-delete { background-color: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; }
</style>