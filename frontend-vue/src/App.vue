<template>
  <div class="container">
    <h1>Gestión de Productos (CRUD)</h1>

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
            <button type="submit" class="btn btn-success">
              {{ editando ? 'Actualizar' : 'Guardar' }}
            </button>
            <button type="button" v-if="editando" @click="cancelarEdicion" class="btn btn-secondary">
              Cancelar
            </button>
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
              <td>
                <strong>{{ producto.nombre }}</strong><br />
                <small>{{ producto.descripcion }}</small>
              </td>
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
import { onMounted, ref } from 'vue';
import { getProductos, createProducto, updateProducto, deleteProducto } from './services/productoService';

export default {
  setup() {
    // Estados
    const productos = ref([]);
    const editando = ref(false);
    const productoIdActual = ref(null);
    const mensaje = ref({ texto: '', tipo: '' });

    const form = ref({
      nombre: '',
      descripcion: '',
      precio: 0,
      stock: 0
    });

    // Cargar productos al iniciar
    const cargarProductos = async () => {
      try {
        const response = await getProductos();
        productos.value = response.data;
      } catch (error) {
        mostrarMensaje('Error al cargar los productos', 'danger');
      }
    };

    // Guardar (Crear o Editar)
    const guardarProducto = async () => {
      try {
        if (editando.value) {
          await updateProducto(productoIdActual.value, form.value);
          mostrarMensaje('Producto actualizado con éxito', 'success');
        } else {
          await createProducto(form.value);
          mostrarMensaje('Producto creado con éxito', 'success');
        }
        resetFormulario();
        cargarProductos();
      } catch (error) {
        mostrarMensaje('Hubo un error al procesar la solicitud', 'danger');
      }
    };

    // Seleccionar para editar
    const seleccionarProducto = (producto) => {
      editando.value = true;
      productoIdActual.value = producto.id;
      form.value = {
        nombre: producto.nombre,
        descripcion: producto.descripcion,
        precio: producto.precio,
        stock: producto.stock
      };
    };

    // Eliminar con confirmación
    const eliminar = async (id) => {
      if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
        try {
          await deleteProducto(id);
          mostrarMensaje('Producto eliminado correctamente', 'success');
          cargarProductos();
        } catch (error) {
          mostrarMensaje('No se pudo eliminar el producto', 'danger');
        }
      }
    };

    // Cancelar edición
    const cancelarEdicion = () => {
      resetFormulario();
    };

    // Limpiar formulario
    const resetFormulario = () => {
      form.value = { nombre: '', descripcion: '', precio: 0, stock: 0 };
      editando.value = false;
      productoIdActual.value = null;
    };

    // Feedback visual
    const mostrarMensaje = (texto, tipo) => {
      mensaje.value = { texto, tipo };
      setTimeout(() => {
        mensaje.value = { texto: '', tipo: '' };
      }, 4000);
    };

    onMounted(() => {
      cargarProductos();
    });

    return {
      productos,
      form,
      editando,
      mensaje,
      guardarProducto,
      seleccionarProducto,
      eliminar,
      cancelarEdicion
    };
  }
};
</script>

<style scoped>
/* Estilos Básicos para presentación limpia */
.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
  font-family: Arial, sans-serif;
  color: #333333;
}
h1 {
  text-align: center;
  color: #333;
}
.grid {
  display: grid;
  grid-template-columns: 1fr 2fr;
  gap: 20px;
  margin-top: 20px;
}
@media (max-width: 768px) {
  .grid { grid-template-columns: 1fr; }
}
.card {
  background: #fdfdfd;
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}
.form-group {
  margin-bottom: 15px;
}
.form-group label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
}
.form-group input, .form-group textarea {
  width: 100%;
  padding: 8px;
  box-sizing: border-box;
  border: 1px solid #ccc;
  border-radius: 4px;
}
.btn-group {
  display: flex;
  gap: 10px;
}
.btn {
  padding: 8px 12px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: bold;
}
.btn-sm { padding: 4px 8px; font-size: 12px; margin-right: 5px;}
.btn-success { background: #28a745; color: white; }
.btn-warning { background: #ffc107; color: #212529; }
.btn-danger { background: #dc3545; color: white; }
.btn-secondary { background: #6c757d; color: white; }
table {
  width: 100%;
  border-collapse: collapse;
}
table th, table td {
  border: 1px solid #ddd;
  padding: 10px;
  text-align: left;
}
table th { background: #f2f2f2; }
.alert {
  padding: 15px;
  margin-bottom: 20px;
  border-radius: 4px;
  text-align: center;
}
.success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
.danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
</style>