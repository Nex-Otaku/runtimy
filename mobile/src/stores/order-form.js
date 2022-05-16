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
          'sort_index': 1,
          'street_address': '',
          'phone_number': '',
          'courier_comment': ''
        },
        {
          'title': 'Куда',
          'sort_index': 2,
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
        const resultPlaces = [];

        for (const place of state.places) {
          resultPlaces.push({
            street_address: place.street_address,
            phone_number: place.phone_number,
            courier_comment: place.courier_comment
          });
        }

        return {
          transport_type: state.transport_type.value,
          size_type: state.size_type.value,
          weight_type: state.weight_type.value,
          description: state.description,
          price_of_package: state.price_of_package,
          places: resultPlaces
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
