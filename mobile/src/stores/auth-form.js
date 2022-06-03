import {defineStore} from 'pinia'

export const useAuthForm = defineStore(
  'auth-form',
  {
    state: () => ({
      phoneNumber: '',
      pincode: '',
    }),
  },
)
