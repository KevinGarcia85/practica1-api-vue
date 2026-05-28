import { defineStore } from 'pinia'
import axios from 'axios'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null,
  }),
  getters: {
    isAuthenticated: (state) => !!state.token,
  },
  actions: {
    // 1. Iniciar Sesión
    async login(credentials) {
      try {
        const response = await axios.post('http://localhost:8000/api/login', credentials)
        this.token = response.data.token
        this.user = response.data.user
        localStorage.setItem('token', this.token)
      } catch (error) {
        throw error.response?.data?.message || 'Error al iniciar sesión'
      }
    },

    // 2. Registrar Usuario
    async register(data) {
      try {
        const response = await axios.post('http://localhost:8000/api/register', data)
        this.token = response.data.token
        this.user = response.data.user
        localStorage.setItem('token', this.token)
      } catch (error) {
        throw error.response?.data?.message || 'Error en el registro'
      }
    },

    // 3. Recuperar perfil del usuario activo (LA QUE FALTABA)
    async fetchUser() {
      if (!this.token) return
      try {
        const response = await axios.get('http://localhost:8000/api/me', {
          headers: { Authorization: `Bearer ${this.token}` }
        })
        this.user = response.data
      } catch (error) {
        // Si el token es inválido o expiró, limpiamos todo
        this.logout()
      }
    },

    // 4. Cerrar Sesión
    async logout() {
      try {
        if (this.token) {
          await axios.post('http://localhost:8000/api/logout', {}, {
            headers: { Authorization: `Bearer ${this.token}` }
          })
        }
      } catch (error) {
        console.error("Error al revocar token en servidor", error)
      } finally {
        this.token = null
        this.user = null
        localStorage.removeItem('token')
      }
    }
  }
})