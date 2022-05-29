<template>
  <q-select
    v-model="orderFormStore.transport_type"
    :options="orderFormStore.transport_options"
    label="Транспорт"
    class="q-ma-sm"
    outlined
  />
  <q-select
    v-model="orderFormStore.size_type"
    :options="orderFormStore.size_options"
    label="Габариты"
    class="q-ma-sm"
    outlined
  />
  <q-select
    v-model="orderFormStore.weight_type"
    :options="orderFormStore.weight_options"
    label="Вес"
    class="q-ma-sm"
    outlined
  />
</template>

<script>
import {defineComponent} from 'vue'
import {useOrderForm} from 'src/stores/order-form'

export default defineComponent({
  name: 'OrderFormDeliveryOptions',
  setup() {
    const orderFormStore = useOrderForm();

    return {
      orderFormStore: orderFormStore,
    }
  },
})

</script>
