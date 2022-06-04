import {defineStore} from 'pinia'
import {api} from "boot/axios";

export const useAuth = defineStore(
  'auth',
  {
    state: () => ({
      isLoggedIn: false,
      role: 'guest',
      phoneNumber: '',
      pincode: '',
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

      async logout() {
        return api.post('/api/logout')
          .then(async () => {
            await this.clearLoginState();
          });
      },

      loginToRole(role) {
        this.$patch({
          isLoggedIn: true,
          role: role,
        })
      },

      async loginPhone(phoneNumber) {
        this.$patch({
          phoneNumber: phoneNumber,
        })

        return api.get('/sanctum/csrf-cookie')
          .then(() => {
            api.post('/api/login-phone', {
              phone: this.phoneNumber
            })
          })
      },

      async loginPincode(pincode) {
        this.$patch({
          pincode: pincode,
        })

        await api.post('/api/login-pincode', {
          phone: this.phoneNumber,
          pincode: this.pincode
        });

        this.loginToRole('customer');
      },
    }
  },
)
