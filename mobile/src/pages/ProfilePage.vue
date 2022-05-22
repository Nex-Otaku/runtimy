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
    <q-page padding>
      <!-- content -->
    </q-page>
  </q-page-container>
</template>

<script>
import {defineComponent} from 'vue'
import {useProfile} from "stores/profile";

export default defineComponent({
  name: 'ProfilePage',
  setup() {

    const profileStore = useProfile();
    profileStore.fetch();

    return {
      profileStore: profileStore,
    }
  },
})

</script>
