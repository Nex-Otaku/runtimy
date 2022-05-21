import {defineStore} from 'pinia'
import {api} from 'src/boot/axios'

export const useOrderStatus = defineStore(
  'order-status',
  {
    state: () => ({
      orderStatuses: [
        {
          sort_index: 1,
          order_number: 29485,
          isComing: true,
          isCanceled: false,
          label: 'Курьер в пути',
          places: [
            {
              title: 'Откуда',
              sort_index: 1,
              street_address: 'г. Москва, ул Бронная М., д 24 стр 3',
              will_come_at: 'с 13:50 до 15:30',
            },
            {
              title: 'Куда',
              sort_index: 2,
              street_address: 'г. Москва, ул Полковская, д 3 стр 8',
            },
          ],
        },
        {
          sort_index: 2,
          order_number: 29486,
          isComing: false,
          isCanceled: true,
          label: 'Отменён',
          places: [
            {
              title: 'Откуда',
              sort_index: 1,
              street_address: 'г. Москва, ул Бронная М., д 24 стр 3',
              will_come_at: 'с 13:50 до 15:30',
            },
            {
              title: 'Куда',
              sort_index: 2,
              street_address: 'г. Москва, ул Полковская, д 3 стр 8',
            },
          ],
        },
      ],
    }),

    getters: {
    },

    actions: {
      fetch() {
        // TODO
      }
    }
  },
)
