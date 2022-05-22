import {defineStore} from 'pinia'

export const useProfile = defineStore(
  'profile',
  {
    state: () => ({
      name: 'Демо',
    }),

    getters: {
    },

    actions: {
      async fetch() {
        // TODO
      },
    }
  },
)
