import { useAuthStore } from '../stores/auth'

export const vCan = {
  mounted(el, binding) {
    const authStore = useAuthStore()
    const permisoRequerido = binding.value

    if (!authStore || !authStore.permisos) {
      el.style.display = 'none'
      return
    }

    if (!authStore.permisos[permisoRequerido]) {
      el.style.display = 'none'
    }
  },
  updated(el, binding) {
    const authStore = useAuthStore()
    const permisoRequerido = binding.value

    if (!authStore || !authStore.permisos) {
      el.style.display = 'none'
      return
    }

    if (!authStore.permisos[permisoRequerido]) {
      el.style.display = 'none'
    } else {
      el.style.display = ''
    }
  }
}