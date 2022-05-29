<template>
  <div class="text-h6 text-left q-mt-md">
    Содержимое заказа
  </div>

  <q-input
    v-model="orderFormStore.description"
    label="Что везём?"
  >
  </q-input>

  <q-input
    v-model="orderFormStore.price_of_package"
    label="Ценность (Сумма)"
    bottom-slots
  >
    <template #hint>
      Если груз потеряется или будет повреждён, вернём до 50000₽ в течение трёх рабочих дней.
    </template>
  </q-input>
</template>

<script>
import {defineComponent} from 'vue'
import {useOrderForm} from 'src/stores/order-form'

export default defineComponent({
  name: 'OrderFormPackageOptions',
  setup() {
    const orderFormStore = useOrderForm();

    return {
      orderFormStore: orderFormStore,
    }
  },
})

</script>
