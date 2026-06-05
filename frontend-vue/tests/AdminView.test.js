import { describe, it, expect, vi } from 'vitest'
import { mount, flushPromises } from '@vue/test-utils'
import axios from 'axios'
import AdminView from '../src/views/AdminView.vue'

// Simulamos axios para que no intente consultar al servidor real durante la prueba
vi.mock('axios')

describe('Vista AdminView.vue con API Mock', () => {
  it('solicita los productos al backend y los dibuja en la tabla', async () => {
    axios.get.mockResolvedValue({
      data: [
        { id: 1, nombre: 'Monitor Gamer', precio: 350.00, stock: 15 }
      ]
    })

    const wrapper = mount(AdminView)
    await flushPromises() // Espera que se resuelva la promesa de Axios

    expect(axios.get).toHaveBeenCalledWith('/productos')
    expect(wrapper.text()).toContain('Monitor Gamer')
    expect(wrapper.text()).toContain('$350')
  })
})