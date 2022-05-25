import {defineStore} from 'pinia'
import {api} from 'src/boot/axios'

export const useOrderView = defineStore(
  'order-view',
  {
    state: () => ({
      //orderInfo: null,
      orderInfo: {
        order_number: 29485,
        order_status_label: 'Курьер в пути',
        order_price: 500,
        is_coming_next_place: true,
        order_next_place_address: 'ул Бронная М., д 24',
        next_place_coming_time_from: 'с 13:50 до 15:30',
        courier_name: 'Иван Иванов',
        courier_avatar: '',
        courier_phone_number: '+7 (920) 777 55 44',
        order_created_at: '05.07.2021 13:48',
        transport_type_and_weight_type: 'Пешком, До 1 кг',
        description: 'Вещи',
        payment_type: 'Картой онлайн',
        places: [
          {
            sort_index: 1,
            street_address: 'ул Бронная М., д 24 стр 3',
            phone_number: '+7 (920) 777 55 44',
            courier_comment: '',
          },
          {
            sort_index: 2,
            street_address: 'ул Полковская, д 3 стр 8',
            phone_number: '+7 (920) 777 55 44',
            courier_comment: '',
          },
        ],
      },
    }),

    getters: {
    },

    actions: {
      async fetch(orderId) {
        const response = await api.get('/api/view-order/' + orderId);

        if (response.data.result !== 'success') {
          console.error(response.data.message);
        }

        this.$patch({
          orderInfo: response.data.data,
        })
      },
    }
  },
)
