import { defineStore } from 'pinia'
import axios from 'axios'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null,
    permisos: {
      crear: false,
      editar: false,
      eliminar: false
    }
  }),
  getters: {
    estaAutenticado: (state) => !!state.token,
  },
  actions: {
    async fetchUser() {
      if (!this.token) return
      try {
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
        const { data } = await axios.get('http://localhost:8000/api/user')
        
        this.user = data.user || null
        this.permisos = data.permisos || { crear: false, editar: false, eliminar: false }
      } catch (error) {
        console.error("Error al obtener usuario:", error)
        this.logout()
      }
    },
    async login(credenciales) {
      try {
        const { data } = await axios.post('http://localhost:8000/api/login', credenciales)
        this.token = data.token
        localStorage.setItem('token', data.token)
        axios.defaults.headers.common['Authorization'] = `Bearer ${data.token}`
        await this.fetchUser()
        return true
      } catch (error) {
        console.error("Error en login:", error)
        throw error
      }
    },
    async register(datosUsuario) {
      try {
        await axios.post('http://localhost:8000/api/register', datosUsuario)
        return true
      } catch (error) {
        console.error("Error en registro:", error)
        throw error
      }
    },
    logout() {
      this.user = null
      this.token = null
      this.permisos = { crear: false, editar: false, eliminar: false }
      localStorage.removeItem('token')
      delete axios.defaults.headers.common['Authorization']
    }
  }
})