<template>
  <div class="login-container">
    <div class="login-card">
      <h2>🔐 Iniciar Sesión</h2>
      <p class="subtitle">Ingresa al panel administrativo de la tienda</p>
      
      <!-- Mensaje de error si falla el Login -->
      <div v-if="error" class="alert-error">
        ⚠️ {{ error }}
      </div>

      <form @submit.prevent="handleLogin">
        <div class="form-group">
          <label for="email">Correo Electrónico</label>
          <input 
            type="email" 
            id="email" 
            v-model="form.email" 
            placeholder="ejemplo@correo.com" 
            required 
          />
        </div>

        <div class="form-group">
          <label for="password">Contraseña</label>
          <input 
            type="password" 
            id="password" 
            v-model="form.password" 
            placeholder="••••••••" 
            required 
          />
        </div>

        <button type="submit" :disabled="loading" class="btn-login">
          {{ loading ? 'Autenticando...' : 'Ingresar al Panel' }}
        </button>
      </form>
      
      <div class="login-footer">
        <p>¿No tienes una cuenta? <router-link to="/register">Regístrate aquí</router-link></p>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue'
import { useAuthStore } from '../stores/auth'
import { useRouter, useRoute } from 'vue-router'

export default {
  setup() {
    const auth = useAuthStore()
    const router = useRouter()
    const route = useRoute()
    
    const form = ref({ 
      email: '', 
      password: '' 
    })
    const error = ref(null)
    const loading = ref(false)

    const handleLogin = async () => {
      loading.value = true
      error.value = null
      try {
        await auth.login(form.value)
        
        // Redirección inteligente post-login según la guía de Practica03_Vue_Router_SPA.docx
        const redirectTo = route.query.redirect || '/admin'
        router.push(redirectTo)
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
.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 70vh;
  padding: 20px;
}
.login-card {
  background: white;
  padding: 35px;
  border-radius: 10px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.08);
  width: 100%;
  max-width: 400px;
}
h2 {
  margin: 0 0 5px 0;
  color: #2c3e50;
  text-align: center;
}
.subtitle {
  color: #7f8c8d;
  font-size: 14px;
  text-align: center;
  margin-bottom: 25px;
}
.form-group {
  margin-bottom: 20px;
}
.form-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 600;
  color: #34495e;
  font-size: 14px;
}
.form-group input {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 15px;
  box-sizing: border-box;
}
.form-group input:focus {
  border-color: #3498db;
  outline: none;
}
.btn-login {
  background: #3498db;
  color: white;
  border: none;
  padding: 12px;
  border-radius: 6px;
  font-weight: bold;
  font-size: 16px;
  cursor: pointer;
  width: 100%;
  margin-top: 10px;
  transition: background 0.2s;
}
.btn-login:hover {
  background: #2980b9;
}
.btn-login:disabled {
  background: #bdc3c7;
  cursor: not-allowed;
}
.alert-error {
  background-color: #f8d7da;
  color: #721c24;
  padding: 12px;
  border-radius: 6px;
  font-size: 14px;
  margin-bottom: 20px;
  border: 1px solid #f5c6cb;
}
.login-footer {
  margin-top: 25px;
  text-align: center;
  font-size: 14px;
  color: #7f8c8d;
}
.login-footer a {
  color: #3498db;
  text-decoration: none;
  font-weight: bold;
}
.login-footer a:hover {
  text-decoration: underline;
}
</style>