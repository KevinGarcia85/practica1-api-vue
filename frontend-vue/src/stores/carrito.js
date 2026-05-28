import { defineStore } from 'pinia'

export const useCarritoStore = defineStore('carrito', {
  state: () => ({
    items: JSON.parse(localStorage.getItem('carrito') || '[]')
  }),
  getters: {
    totalItems: (state) => state.items.reduce((suma, item) => suma + item.cantidad, 0),
    totalPrecio: (state) => state.items.reduce((suma, item) => suma + item.precio * item.cantidad, 0),
    cantidadDeProducto: (state) => (id) =>
      state.items.find(item => item.id === id)?.cantidad || 0,
  },

  actions: {
    agregar(producto) {
      const itemExistente = this.items.find(item => item.id === producto.id)
      
      if (itemExistente) {
        itemExistente.cantidad++
      } else {
        this.items.push({
          id: producto.id,
          nombre: producto.nombre,
          precio: producto.precio,
          cantidad: 1
        })
      }
      this.guardarLocalStorage()
    },

    quitar(id) {
      this.items = this.items.filter(item => item.id !== id)
      this.guardarLocalStorage()
    },

    cambiarCantidad(id, cantidad) {
      const item = this.items.find(item => item.id === id)
      if (item) {
        item.cantidad = cantidad
        if (item.cantidad <= 0) {
          this.quitar(id)
        }
      }
      this.guardarLocalStorage()
    },

    vaciar() {
      this.items = []
      this.guardarLocalStorage()
    },

    guardarLocalStorage() {
      localStorage.setItem('carrito', JSON.stringify(this.items))
    }
  }
})