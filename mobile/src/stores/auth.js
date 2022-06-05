import {defineStore} from 'pinia'
import {api} from "boot/axios";

import { LocalStorage } from 'quasar'

const authStoreKey = 'auth-store';

const getDefaultState = () => {
  return {
    isLoggedIn: false,
    role: 'guest',
    phoneNumber: '',
    pincode: '',
  };
}

const loadState = () => {
  const value = LocalStorage.getItem(authStoreKey);

  if (value === null) {
    return getDefaultState();
  }

  return value;
}

const saveState = (state) => {
  LocalStorage.set(authStoreKey, state);
}

const clearSavedState = () => {
  LocalStorage.remove(authStoreKey);
}

export const useAuth = defineStore(
  'auth',
  {
    state: () => {
      return loadState();
    },

    getters: {
      isCustomer: (state) => {
        return state.role === 'customer';
      },
      isCourier: (state) => {
        return state.role === 'courier';
      },

      currentState: (state) => {
        return {
          isLoggedIn: state.isLoggedIn,
          role: state.role,
          phoneNumber: state.phoneNumber,
          pincode: state.pincode,
        }
      }
    },

    actions: {
      _clearLoginState() {
        this.$patch(getDefaultState());
        clearSavedState();
      },

      _loginToRole(role) {
        this.$patch({
          isLoggedIn: true,
          role: role,
        })

        saveState(this.currentState);
      },

      async logout() {
        return api.post('/api/logout')
          .then(async () => {
            await this._clearLoginState();
          });
      },

      localLogout() {
        this._clearLoginState();
      },

      async loginPhone(phoneNumber) {
        this.$patch({
          phoneNumber: phoneNumber,
        })

        return api.get('/sanctum/csrf-cookie')
          .then(() => {
            return api.post('/api/login-phone', {
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

        this._loginToRole('customer');
      },
    }
  },
)
