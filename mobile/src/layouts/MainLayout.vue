<template>
  <q-layout view="hHh lpR fFf">
    <q-header bordered class="bg-white text-black">
      <q-toolbar>
        <q-toolbar-title
          class="q-ml-sm q-mt-md q-mb-md"
          style="font-size: 28px; font-weight: bold"
        >
          {{ $route.meta.title }}
        </q-toolbar-title>

        <div>
          <q-btn
            :to="{ name: 'profile' }"
            flat
            dense
            round
            class="shadow-5"
            icon="person"
            aria-label="Профиль"
          >
          </q-btn>
        </div>
      </q-toolbar>

      <!--
      <q-tabs align="left" no-caps>
        <q-route-tab to="/page1" label="Page One" />
        <q-route-tab to="/page2" label="Page Two" />
        <q-route-tab to="/page3" label="Page Three" />
      </q-tabs>
      -->
    </q-header>

    <q-page-container>
      <router-view/>
    </q-page-container>

    <MainPageFooter/>
  </q-layout>
</template>

<script>
import {defineComponent} from 'vue'
import MainPageFooter from "components/MainPageFooter";

export default defineComponent({
  name: 'MainLayout',
  components: {MainPageFooter},
  setup() {
    return {
      // TODO
    }
  }
})
</script>
