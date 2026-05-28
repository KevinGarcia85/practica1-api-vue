<template>
  <div class="admin-wrapper">
    <aside class="sidebar">
      <div class="brand">
        <h3>🛡️ Panel Admin</h3>
      </div>
      <div class="user-info">
        <p>Operador:</p>
        <strong>{{ nombreUsuario }}</strong>
      </div>
      <nav class="sidebar-menu">
        <router-link to="/admin" class="menu-item">📦 Inventario CRUD</router-link>
        <router-link to="/catalogo" class="menu-item">🛒 Ver Tienda</router-link>
      </nav>
      <button @click="handleLogout" class="btn-logout-sidebar">Cerrar Sesión</button>
    </aside>

    <main class="main-content">
      <router-view></router-view>
    </main>
  </div>
</template>

<script>
import { computed } from 'vue'
import { useAuthStore } from '../../stores/auth'
import { useRouter } from 'vue-router'

export default {
  setup() {
    const auth = useAuthStore()
    const router = useRouter()

    const nombreUsuario = computed(() => auth.user?.name || 'Administrador')

    const handleLogout = async () => {
      await auth.logout()
      router.push('/login')
    }

    return { nombreUsuario, handleLogout }
  }
}
</script>

<style scoped>
.admin-wrapper { display: flex; min-height: 100vh; font-family: Arial, sans-serif; background: #f4f6f9; }
.sidebar { width: 260px; background: #2c3e50; color: white; display: flex; flex-direction: column; padding: 20px; box-sizing: border-box; }
.brand { text-align: center; border-bottom: 1px solid #34495e; padding-bottom: 15px; margin-bottom: 20px; }
.user-info { background: #34495e; padding: 12px; border-radius: 6px; font-size: 14px; margin-bottom: 25px; text-align: center; }
.user-info strong { font-size: 16px; color: #42b983; }
.sidebar-menu { display: flex; flex-direction: column; gap: 10px; flex-grow: 1; }
.menu-item { color: #ecf0f1; text-decoration: none; padding: 12px; border-radius: 4px; font-weight: bold; transition: 0.2s; }
.menu-item:hover, .menu-item.router-link-exact-active { background: #1abc9c; color: white; }
.btn-logout-sidebar { background: #e74c3c; color: white; border: none; padding: 12px; border-radius: 4px; font-weight: bold; cursor: pointer; width: 100%; transition: 0.2s; }
.btn-logout-sidebar:hover { background: #c0392b; }
.main-content { flex-grow: 1; padding: 30px; box-sizing: border-box; overflow-y: auto; }
</style>