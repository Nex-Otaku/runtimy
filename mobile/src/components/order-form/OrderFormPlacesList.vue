<template>
  <q-list>
    <q-item
      v-for="place in orderFormStore.places" :key="place.sort_index"
      class="q-pl-none"
    >
      <q-item-section
        style="border-left: solid 3px black; flex-grow: 0"
      >
      </q-item-section>

      <q-item-section>
        <div class="text-h6 text-left">
          {{ place.title }}
        </div>

        <q-input
          v-model="place.street_address"
          label="Улица и номер дома"
          :rules="[val => !!val || 'Адрес обязателен']"
        >
          <template #append>
            <q-icon name="place"/>
          </template>
        </q-input>

        <q-input
          v-model="place.phone_number"
          label="Телефон"
        >
          <template #append>
            <q-icon name="phone"/>
          </template>
        </q-input>

        <q-input
          v-model="place.courier_comment"
          type="textarea"
          label="Поручение для курьера"
          autogrow
        >
        </q-input>
      </q-item-section>
    </q-item>
  </q-list>
</template>

<script>
import {defineComponent} from 'vue'
import {useOrderForm} from 'src/stores/order-form'

export default defineComponent({
  name: 'OrderFormPlacesList',
  setup() {
    const orderFormStore = useOrderForm();

    return {
      orderFormStore: orderFormStore,
    }
  },
})

</script>
