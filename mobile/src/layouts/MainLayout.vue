<template>
  <q-layout view="hHh lpR fFf">
    <q-header bordered class="bg-white text-black">
      <q-toolbar>
        <q-toolbar-title>
          {{ $route.meta.title }}
        </q-toolbar-title>

        <div>
          <q-btn
            flat
            dense
            round
            class="shadow-5"
            icon="man"
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

    <q-footer bordered class="bg-white text-black">
      <q-toolbar class="justify-between">

        <FooterActionButton
          icon="man"
          link="/#/my/orders"
          title="Заказы"
        />
        <FooterActionButton
          icon="add"
          link="/#/dialog/new-order"
          title="Новый заказ"
        />
        <FooterActionButton
          icon="message"
          link="/#/my/chat"
          title="Чат"
        />
      </q-toolbar>
    </q-footer>
  </q-layout>
</template>

<script>
import {defineComponent} from 'vue'
import FooterActionButton from "components/FooterActionButton";

export default defineComponent({
  name: 'MainLayout',
  components: {FooterActionButton},
  setup() {
    return {
      // TODO
    }
  }
})
</script>
