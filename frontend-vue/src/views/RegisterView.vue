<template>
  <div class="auth-container">
    <div class="auth-card">
      <h2>📝 Crear Cuenta</h2>
      <p class="subtitle">Regístrate para obtener credenciales de acceso</p>
      
      <form @submit.prevent="handleRegister">
        <div class="form-group">
          <label>Nombre Completo</label>
          <input v-model="form.name" type="text" required placeholder="Tu nombre" class="form-control" />
        </div>

        <div class="form-group">
          <label>Correo Electrónico</label>
          <input v-model="form.email" type="email" required placeholder="correo@ejemplo.com" class="form-control" />
        </div>

        <div class="form-group">
          <label>Contraseña</label>
          <input v-model="form.password" type="password" required placeholder="Mínimo 6 caracteres" class="form-control" />
        </div>

        <button type="submit" class="btn-submit" :disabled="cargando">
          {{ cargando ? 'Registrando...' : 'Crear Cuenta' }}
        </button>
      </form>

      <p class="switch-auth">
        ¿Ya tienes cuenta? <RouterLink to="/login">Inicia Sesión aquí</RouterLink>
      </p>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'

const auth = useAuthStore()
const router = useRouter()
const cargando = ref(false)

const form = reactive({
  name: '',
  email: '',
  password: ''
})

const handleRegister = async () => {
  cargando.value = true
  try {
    const exito = await auth.register(form)
    if (exito) {
      alert("¡Registro exitoso! Ahora puedes iniciar sesión.")
      router.push('/login')
    }
  } catch (error) {
    alert("Error al registrar. Es posible que el correo ya esté en uso.")
  } finally {
    cargando.value = false
  }
}
</script>

<style scoped>
.auth-container { display: flex; align-items: center; justify-content: center; min-height: 80vh; background-color: #f4f6f9; font-family: sans-serif; }
.auth-card { background: white; padding: 35px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); width: 100%; max-width: 400px; text-align: center; }
.subtitle { color: #777; font-size: 14px; margin-bottom: 25px; }
.form-group { margin-bottom: 20px; text-align: left; }
label { display: block; font-weight: bold; margin-bottom: 6px; font-size: 13px; color: #444; }
.form-control { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
.btn-submit { background-color: #2ecc71; color: white; border: none; padding: 12px; width: 100%; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 15px; margin-top: 10px; }
.btn-submit:disabled { background-color: #95a5a6; }
.switch-auth { margin-top: 20px; font-size: 13px; color: #555; }
.switch-auth a { color: #3498db; text-decoration: none; font-weight: bold; }
</style>