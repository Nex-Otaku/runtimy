import {defineStore} from 'pinia'
import {api} from 'src/boot/axios'

export const useOrderForm = defineStore(
  'order-form',
  {
    state: () => ({
      transport_type: {
        label: 'Пешком',
        value: 'feet'
      },
      size_type: {
        label: 'Мелкий',
        value: 'small'
      },
      weight_type: {
        label: 'До 1 кг',
        value: '1kg'
      },
      places: [
        {
          'title': 'Откуда',
          'street_address': '',
          'phone_number': '',
          'courier_comment': ''
        },
        {
          'title': 'Куда',
          'street_address': '',
          'phone_number': '',
          'courier_comment': ''
        },
      ],
      description: '',
      price_of_package: ''
    }),

    getters: {
      form: (state) => {
        return {
          transport_type: state.transport_type.value,
          size_type: state.size_type.value,
          weight_type: state.weight_type.value,
          description: state.description,
          price_of_package: state.price_of_package,
        }
      }
    },

    actions: {
      createOrder() {
        api.post('/api/new-order', this.form)
      }
    }
  },
)
