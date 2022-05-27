<template>
  <q-page padding class="row">
    <div class="full-width column justify-center content-center items-center">
      <div>
        <q-btn label="Войти" @click="login"></q-btn>
      </div>
    </div>
  </q-page>
</template>

<script>
import {defineComponent} from "vue";
import {api} from 'src/boot/axios'
import {useRouter} from "vue-router";

export default defineComponent({
  name: 'FastLoginPage',
  setup() {
    const router = useRouter();

    const form = {
      email: 'demo@mail.com',
      password: 'secret'
    }

    const login = () => {
      api.get('/sanctum/csrf-cookie')
        .then(() => {
          api.post('/api/login', form)
            .then(() => {
              router.push({name: 'orders'});
            })
        })
    }


    return {
      login,
    }
  }
})
</script>
