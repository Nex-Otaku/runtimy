<template>
  <q-footer bordered class="bg-white text-black">
    <q-toolbar class="justify-between">

      <FooterActionButton
        icon="list"
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
</template>

<script>
import {defineComponent} from 'vue'
import FooterActionButton from "components/FooterActionButton";

export default defineComponent({
  name: 'MainPageFooter',
  components: {FooterActionButton},
  setup() {
    return {
      // TODO
    }
  }
})
</script>
