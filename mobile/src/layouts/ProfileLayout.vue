<template>
  <q-layout view="hHh lpR fFf">
    <router-view/>

    <MainPageFooter/>
  </q-layout>
</template>

<script>
import {defineComponent} from 'vue'
import MainPageFooter from "components/MainPageFooter";

export default defineComponent({
  name: 'ProfileLayout',
  components: {MainPageFooter},
  setup() {
    return {
      // TODO
    }
  }
})
</script>
