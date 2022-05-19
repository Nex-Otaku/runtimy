<template>
  <q-page padding>
    <q-btn
      @click="handleOrdersButtonClicked"
    >
      Заказы
    </q-btn>
  </q-page>
</template>

<script>
import {defineComponent} from 'vue'
import { useRouter } from 'vue-router'

export default defineComponent({
  name: 'RouterPage',
  setup() {

    const router = useRouter();

    const handleOrdersButtonClicked = () => {
      router.push({name: 'orders'});
    }

    return {
      handleOrdersButtonClicked: handleOrdersButtonClicked,
    }
  },
})

</script>
