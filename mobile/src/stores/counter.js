import {defineStore} from 'pinia'

export const useCounter = defineStore(
  'counter',
  {
    state: () => ({
      counter: 0,
    }),

    getters: {
    },

    actions: {
      increment() {
        this.counter = this.counter + 1;
      },
    }
  },
)
