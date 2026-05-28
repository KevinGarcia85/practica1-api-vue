import axios from 'axios'
import router from '../router'

const api = axios.create({
  baseURL: 'http://localhost:8000/api'
})

// Adjuntar Token Bearer automáticamente si existe en el localStorage
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token')
    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  },
  (error) => {
    throw error
  }
)

// Interceptar respuestas para atrapar el error 401 (Sesión caducada o inválida)
api.interceptors.response.use(
  (response) => {
    return response
  },
  (error) => {
    // Si el servidor responde que no está autorizado (401), limpiamos y mandamos al login
    if (error.response && error.response.status === 401) {
      localStorage.removeItem('token')
      router.push('/login')
    }
    // Lanzamos el error hacia adelante de forma limpia sin usar Promise.reject
    throw error
  }
)

export const getProductos = () => api.get('/productos')
export const createProducto = (data) => api.post('/productos', data)
export const updateProducto = (id, data) => api.put(`/productos/${id}`, data)
export const deleteProducto = (id) => api.delete(`/productos/${id}`)