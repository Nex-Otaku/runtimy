import {defineStore} from 'pinia'

export const useAuth = defineStore(
  'auth',
  {
    state: () => ({
      isLoggedIn: false,
      role: 'guest',
    }),

    getters: {
      isCustomer: (state) => {
        return state.role === 'customer';
      },
      isCourier: (state) => {
        return state.role === 'courier';
      },
    },

    actions: {
      clearLoginState() {
        this.$patch({
          isLoggedIn: false,
          role: 'guest',
        })
      },

      async fetch() {
        // TODO
      },
    }
  },
)
