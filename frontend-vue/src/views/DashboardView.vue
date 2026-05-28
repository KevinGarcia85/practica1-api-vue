<template>
  <div class="container">
    <div class="navbar">
      <span>Bienvenido, <strong>{{ usuarioNombre }}</strong></span>
      <button @click="cerrarSesion" class="btn-logout">Cerrar Sesión</button>
    </div>

    <h1>Gestión de Productos Autenticada</h1>

    <div v-if="mensaje.texto" :class="['alert', mensaje.tipo]">
      {{ mensaje.texto }}
    </div>

    <div class="grid">
      <div class="card">
        <h2>{{ editando ? 'Editar Producto' : 'Nuevo Producto' }}</h2>
        <form @submit.prevent="guardarProducto">
          <div class="form-group">
            <label>Nombre:</label>
            <input type="text" v-model="form.nombre" required />
          </div>
          <div class="form-group">
            <label>Descripción:</label>
            <textarea v-model="form.descripcion"></textarea>
          </div>
          <div class="form-group">
            <label>Precio:</label>
            <input type="number" step="0.01" v-model="form.precio" required />
          </div>
          <div class="form-group">
            <label>Stock:</label>
            <input type="number" v-model="form.stock" required />
          </div>
          <div class="btn-group">
            <button type="submit" class="btn btn-success">{{ editando ? 'Actualizar' : 'Guardar' }}</button>
            <button type="button" v-if="editando" @click="cancelarEdicion" class="btn btn-secondary">Cancelar</button>
          </div>
        </form>
      </div>

      <div class="card">
        <h2>Lista de Productos</h2>
        <table>
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
            <tr v-for="producto in productos" :key="producto.id">
              <td>{{ producto.id }}</td>
              <td><strong>{{ producto.nombre }}</strong><br><small>{{ producto.descripcion }}</small></td>
              <td>${{ producto.precio }}</td>
              <td>{{ producto.stock }}</td>
              <td>
                <button @click="seleccionarProducto(producto)" class="btn btn-warning btn-sm">Editar</button>
                <button @click="eliminar(producto.id)" class="btn btn-danger btn-sm">Eliminar</button>
              </td>
            </tr>
            <tr v-if="productos.length === 0">
              <td colspan="5" style="text-align: center;">No hay productos registrados.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import { onMounted, ref, computed } from 'vue'
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'
import axios from 'axios'
import { getProductos, createProducto, updateProducto, deleteProducto } from '../services/productoService'

export default {
  setup() {
    const auth = useAuthStore()
    const router = useRouter()
    const productos = ref([])
    const editando = ref(false)
    const productoIdActual = ref(null)
    const mensaje = ref({ texto: '', tipo: '' })
    const form = ref({ nombre: '', descripcion: '', precio: 0, stock: 0 })

    const usuarioNombre = computed(() => auth.user?.name || 'Usuario')

    const cargarUsuario = async () => {
      if (!auth.user) {
        try {
          const res = await axios.get('http://localhost:8000/api/me', {
            headers: { Authorization: `Bearer ${auth.token}` }
          })
          auth.user = res.data
        } catch (e) {
          auth.logout()
          router.push('/login')
        }
      }
    }

    const cargarProductos = async () => {
      try {
        const response = await getProductos()
        productos.value = response.data
      } catch (error) {
        mostrarMensaje('Error al cargar productos del servidor', 'danger')
      }
    }

    const guardarProducto = async () => {
      try {
        if (editando.value) {
          await updateProducto(productoIdActual.value, form.value)
          mostrarMensaje('Producto actualizado con éxito', 'success')
        } else {
          await createProducto(form.value)
          mostrarMensaje('Producto guardado correctamente', 'success')
        }
        resetFormulario()
        cargarProductos()
      } catch (error) {
        mostrarMensaje('Error al guardar datos', 'danger')
      }
    }

    const seleccionarProducto = (producto) => {
      editando.value = true
      productoIdActual.value = producto.id
      form.value = { ...producto }
    }

    const eliminar = async (id) => {
      if (confirm('¿Estás seguro de eliminar este producto?')) {
        try {
          await deleteProducto(id)
          mostrarMensaje('Producto eliminado', 'success')
          cargarProductos()
        } catch (error) {
          mostrarMensaje('No se pudo completar la eliminación', 'danger')
        }
      }
    }

    const cerrarSesion = async () => {
      await auth.logout()
      router.push('/login')
    }

    const cancelarEdicion = () => resetFormulario()
    const resetFormulario = () => {
      form.value = { nombre: '', descripcion: '', precio: 0, stock: 0 }
      editando.value = false
      productoIdActual.value = null
    }

    const mostrarMensaje = (texto, tipo) => {
      mensaje.value = { texto, tipo }
      setTimeout(() => { mensaje.value = { texto: '', tipo: '' } }, 4000)
    }

    onMounted(async () => {
      await cargarUsuario()
      await cargarProductos()
    })

    return { productos, form, editando, mensaje, usuarioNombre, guardarProducto, seleccionarProducto, eliminar, cancelarEdicion, cerrarSesion }
  }
}
</script>

<style scoped>
.container { max-width: 1200px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif; color: #333; }
.navbar { display: flex; justify-content: space-between; align-items: center; background: #f8f9fa; padding: 12px 20px; border-radius: 6px; margin-bottom: 25px; border: 1px solid #e3e6f0; }
.btn-logout { background: #6c757d; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-weight: bold; }
.btn-logout:hover { background: #5a6268; }
h1 { text-align: center; }
.grid { display: grid; grid-template-columns: 1fr 2fr; gap: 20px; margin-top: 20px; }
.card { background: #fdfdfd; border: 1px solid #ddd; border-radius: 8px; padding: 20px; }
.form-group { margin-bottom: 15px; }
.form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
.form-group input, .form-group textarea { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
.btn-group { display: flex; gap: 10px; }
.btn { padding: 8px 12px; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; }
.btn-sm { padding: 4px 8px; font-size: 12px; margin-right: 5px;}
.btn-success { background: #28a745; color: white; }
.btn-warning { background: #ffc107; color: #212529; }
.btn-danger { background: #dc3545; color: white; }
.btn-secondary { background: #6c757d; color: white; }
table { width: 100%; border-collapse: collapse; }
table th, table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
table th { background: #f2f2f2; }
.alert { padding: 15px; margin-bottom: 20px; border-radius: 4px; text-align: center; }
.success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
.danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
</style>