<template>
  <q-page>
    <q-list>
      <q-item
        v-for="place in places" :key="place.sort_index"
        style="flex-direction: column"
      >
        <div class="text-h6 text-left">
          {{ place.title }}
        </div>

        <q-input
          v-model="place.street_address"
          :data-id="'streetAddressInput' + place.sort_index"
          label="Улица и номер дома"
          :rules="[val => !!val || 'Адрес обязателен']"
        >
        </q-input>

        <q-input
          v-model="place.street_address"
          label="Улица и номер дома2"
          :rules="[val => !!val || 'Адрес обязателен']"
        >
        </q-input>
      </q-item>
    </q-list>

    <q-btn
      label="Очистить"
      @click="handleResetButtonClicked"
    />
  </q-page>
</template>

<script>
import {ref} from 'vue'
import {defineComponent} from 'vue'

export default defineComponent({
  name: 'LoopPage',
  setup() {

    const places = ref([
      {
        'title': 'Откуда',
        'sort_index': 1,
        'street_address': '',
      },
      {
        'title': 'Куда',
        'sort_index': 2,
        'street_address': '',
      },
    ])

    const handleResetButtonClicked = () => {
      for (const place of places.value) {
        const addressInput = document.querySelectorAll('[data-id="streetAddressInput' + place.sort_index + '"]')[0].__vueParentComponent.proxy;

        addressInput.resetValidation();
      }
    }

    return {
      handleResetButtonClicked: handleResetButtonClicked,
      places,
    }
  },
})
</script>
