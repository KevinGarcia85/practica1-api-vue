import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import HomeView from '../views/HomeView.vue'
import CatalogoView from '../views/CatalogoView.vue'
import ProductoDetalle from '../views/ProductoDetalle.vue'
import LoginView from '../views/LoginView.vue'
import RegisterView from '../views/RegisterView.vue'
import AdminLayout from '../views/admin/AdminLayout.vue'
import DashboardView from '../views/DashboardView.vue' 

const routes = [
  { path: '/', name: 'home', component: HomeView },
  { path: '/catalogo', name: 'catalogo', component: CatalogoView },
  { path: '/catalogo/:id', name: 'producto-detalle', component: ProductoDetalle, props: true },
  { path: '/login', name: 'login', component: LoginView },
  { path: '/register', name: 'register', component: RegisterView },
  {
    path: '/admin',
    component: AdminLayout,
    meta: { requiresAuth: true },
    children: [
      { path: '', name: 'admin-dashboard', component: DashboardView }
    ]
  },
  { path: '/:pathMatch(.*)*', redirect: '/' }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// GUARD GLOBAL DEFINITIVO CORREGIDO
router.beforeEach(async (to, from, next) => {
  const auth = useAuthStore()

  // Inicializar usuario si hay token guardado
  if (auth.token && !auth.user) {
    try {
      await auth.fetchUser()
    } catch (e) {
      console.error("No se pudo validar el usuario", e)
    }
  }

  if (to.meta.requiresAuth && !auth.isAuthenticated) {
    next({ path: '/login', query: { redirect: to.fullPath } })
  } else if ((to.name === 'login' || to.name === 'register') && auth.isAuthenticated) {
    next({ name: 'admin-dashboard' })
  } else {
    next()
  }
})

export default router