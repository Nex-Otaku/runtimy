<template>
  <q-page padding>
    <q-btn label="Войти" @click="login"></q-btn>
    <q-btn label="Выйти" @click="logout"></q-btn>
    <q-btn label="Get User" @click="getUser"></q-btn>
  </q-page>
</template>

<script>
import {defineComponent} from "vue";
import axios from "axios";

export default defineComponent({
  name: 'LoginPage',
  setup() {
    const form = {
      email: 'demo@mail.com',
      password: 'secret'
    }

    const login = () => {
      axios.post(process.env.API_URL + '/api/login', form)
    }

    const logout = () => {
      axios.post(process.env.API_URL + '/api/logout')
    }

    axios.get(process.env.API_URL + '/sanctum/csrf-cookie')
      .then(response => {
        console.log(response)
      })

    const getUser = () => {
      axios.get(process.env.API_URL + '/api/user')
        .then(response => {
          console.log(response.data)
        })
    }

    return {
      login,
      logout,
      getUser
    }
  }
})
</script>
