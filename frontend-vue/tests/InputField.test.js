import { describe, it, expect } from 'vitest'
import { mount } from '@vue/test-utils'
import InputField from '../src/components/InputField.vue'

describe('Componente InputField.vue', () => {
  it('renderiza la etiqueta label de forma correcta', () => {
    const wrapper = mount(InputField, {
      props: {
        label: 'Nombre del Producto',
        name: 'nombre',
        modelValue: ''
      }
    })
    expect(wrapper.text()).toContain('Nombre del Producto')
  })

  it('muestra el mensaje de error cuando se le pasa como prop', () => {
    const wrapper = mount(InputField, {
      props: {
        label: 'Precio',
        name: 'precio',
        modelValue: 0,
        error: 'El precio debe ser mayor a cero'
      }
    })
    expect(wrapper.text()).toContain('El precio debe ser mayor a cero')
    expect(wrapper.find('.error-msg').exists()).toBe(true)
  })
})