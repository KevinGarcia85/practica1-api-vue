import { defineStore } from 'pinia'
import axios from 'axios'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user:  null,
    token: localStorage.getItem('token') || null,
  }),
  getters: {
    isAuthenticated: (state) => !!state.token,
  },
  actions: {
    async login(credentials) {
      try {
        const response = await axios.post('http://localhost:8000/api/login', credentials)
        this.token = response.data.token
        this.user = response.data.user
        localStorage.setItem('token', response.data.token)
        return response.data
      } catch (error) {
        throw error.response?.data?.message || 'Error al iniciar sesión'
      }
    },
    async register(data) {
      try {
        const response = await axios.post('http://localhost:8000/api/register', data)
        this.token = response.data.token
        this.user = response.data.user
        localStorage.setItem('token', response.data.token)
        return response.data
      } catch (error) {
        throw error.response?.data?.errors || 'Error en el registro'
      }
    },
    async logout() {
      try {
        await axios.post('http://localhost:8000/api/logout', {}, {
            headers: { Authorization: `Bearer ${this.token}` }
        })
      } catch (error) {
        // Continuar de todos modos
      } finally { // <--- Asegúrate de que diga 'finally' y no 'fill'
        this.token = null
        this.user = null
        localStorage.removeItem('token')
      }
    }
  }
})