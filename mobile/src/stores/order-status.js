import {defineStore} from 'pinia'
import {api} from 'src/boot/axios'

export const useOrderStatus = defineStore(
  'order-status',
  {
    state: () => ({
      orderStatuses: [],
    }),

    getters: {
      hasNoOrders: (state) => {
        return state.orderStatuses.length === 0;
      },
    },

    actions: {
      async fetch() {
        const response = await api.get('/api/order-status-list');

        if (response.data.result !== 'success') {
          console.error(response.data.message);
        }

        this.$patch({
          orderStatuses: response.data.data,
        })
      },
    }
  },
)
