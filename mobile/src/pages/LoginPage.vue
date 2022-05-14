<template>
  <q-page padding>
    <q-btn label="Войти" @click="login"></q-btn>
    <q-btn label="Выйти" @click="logout"></q-btn>
    <q-btn label="Get User" @click="getUser"></q-btn>
  </q-page>
</template>

<script>
import {defineComponent} from "vue";
import {api} from 'src/boot/axios'

export default defineComponent({
  name: 'LoginPage',
  setup() {
    const form = {
      email: 'demo@mail.com',
      password: 'secret'
    }

    const login = () => {
      api.post('/api/login', form)
    }

    const logout = () => {
      api.post('/api/logout')
    }

    api.get('/sanctum/csrf-cookie')
      .then(response => {
        console.log(response)
      })

    const getUser = () => {
      api.get('/api/user')
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
