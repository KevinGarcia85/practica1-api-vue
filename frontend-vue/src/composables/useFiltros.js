import { reactive, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'

export function useFiltros() {
  const route  = useRoute()
  const router = useRouter()

  const filtros = reactive({
    busqueda:     route.query.busqueda     || '',
    categoria_id: route.query.categoria    || '',
    precio_min:   route.query.min          || '',
    precio_max:   route.query.max          || '',
    orden:        route.query.orden        || 'nombre',
    pagina:       Number(route.query.p)    || 1,
  })

  // Sincronizar filtros → Cambios reflejados en la URL
  watch(filtros, () => {
    router.push({
      query: {
        busqueda:  filtros.busqueda     || undefined,
        categoria: filtros.categoria_id || undefined,
        min:       filtros.precio_min   || undefined,
        max:       filtros.precio_max   || undefined,
        orden:     filtros.orden !== 'nombre' ? filtros.orden : undefined,
        p:         filtros.pagina > 1   ? filtros.pagina : undefined,
      }
    })
  })

  const limpiarFiltros = () => {
    filtros.busqueda = ''
    filtros.categoria_id = ''
    filtros.precio_min = ''
    filtros.precio_max = ''
    filtros.orden = 'nombre'
    filtros.pagina = 1
  }

  return { filtros, limpiarFiltros }
}