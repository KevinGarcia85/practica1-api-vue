<template>
  <div class="auth-container">
    <div class="auth-card">
      <h2>Iniciar Sesión</h2>
      <div v-if="error" class="alert alert-danger">{{ error }}</div>
      <form @submit.prevent="handleLogin">
        <div class="form-group">
          <label>Correo Electrónico:</label>
          <input type="email" v-model="form.email" required placeholder="ejemplo@correo.com" />
        </div>
        <div class="form-group">
          <label>Contraseña:</label>
          <input type="password" v-model="form.password" required />
        </div>
        <button type="submit" class="btn btn-primary" :disabled="loading">
          {{ loading ? 'Ingresando...' : 'Entrar' }}
        </button>
      </form>
      <p>¿No tienes cuenta? <router-link to="/register">Regístrate aquí</router-link></p>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue'
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'

export default {
  setup() {
    const auth = useAuthStore()
    const router = useRouter()
    const form = ref({ email: '', password: '' })
    const error = ref(null)
    const loading = ref(false)

    const handleLogin = async () => {
      loading.value = true
      error.value = null
      try {
        await auth.login(form.value)
        router.push('/dashboard')
      } catch (err) {
        error.value = err
      } finally {
        loading.value = false
      }
    }

    return { form, error, loading, handleLogin }
  }
}
</script>

<style scoped>
.auth-container { display: flex; justify-content: center; align-items: center; min-height: 80vh; font-family: Arial, sans-serif; }
.auth-card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 100%; max-width: 400px; color: #333; }
.form-group { margin-bottom: 15px; }
.form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
.form-group input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
.btn { width: 100%; padding: 10px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; color: white; background: #007bff; margin-top: 10px; }
.btn:disabled { background: #cccccc; }
.alert-danger { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px; text-align: center; font-size: 14px; }
p { text-align: center; margin-top: 15px; font-size: 14px; }
</style>