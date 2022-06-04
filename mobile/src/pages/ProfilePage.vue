<template>
  <q-header bordered class="bg-white text-black">
    <q-toolbar>
      <q-toolbar-title
        class="q-ml-sm q-mt-md q-mb-md"
        style="font-size: 28px; font-weight: bold"
      >
        {{ profileStore.name }}
      </q-toolbar-title>
    </q-toolbar>
  </q-header>

  <q-page-container>
    <q-page padding class="row">
      <div class="full-width column justify-center content-center items-center">
        <q-btn
          color="primary"
          label="Выйти"
          @click="logoutClicked"
        />
      </div>
    </q-page>
  </q-page-container>
</template>

<script>
import {defineComponent} from 'vue'
import {useProfile} from "stores/profile";
import {useAuth} from "stores/auth";
import {useRouter} from "vue-router";

export default defineComponent({
  name: 'ProfilePage',
  setup() {
    const authStore = useAuth();
    const router = useRouter();

    const profileStore = useProfile();
    profileStore.fetch();

    const logoutClicked = () => {
      authStore.logout()
        .then(() => {
          router.push({name: 'login-phone'});
        })
    }

    return {
      profileStore,
      logoutClicked
    }
  },
})

</script>
