import {defineStore} from 'pinia'
import {api} from 'src/boot/axios'

export const useOrderView = defineStore(
  'order-view',
  {
    state: () => ({
      orderInfo: null,
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
