<template>
  <q-page-container>

    <q-page
      class="flex flex-center column"
    >
      <q-input
        v-model="orderFormStore.description"
        label="Pina desc"
        style="width: 100%; max-width: 300px"
      >
      </q-input>
      <q-btn
        no-caps
        outline
        align="center"
        class="btn-fixed-width"
        color="primary"
        label="Test"
        style="width: 100%; max-width: 300px"
        @click="testClicked"
      />

      <q-input
        v-model="orderFormStore.nestedParent.nestedChild"
        label="Pina desc"
        style="width: 100%; max-width: 300px"
      >
      </q-input>
      <q-btn
        no-caps
        outline
        align="center"
        class="btn-fixed-width"
        color="primary"
        label="Test"
        style="width: 100%; max-width: 300px"
        @click="testClicked2"
      />


    </q-page>

  </q-page-container>

</template>

<script>
import {defineComponent} from 'vue'
import {useOrderForm} from 'src/stores/order-form'

export default defineComponent({
  name: 'PiniaPage',
  setup() {
    const orderFormStore = useOrderForm();

    const testClicked = () => {
      console.log(orderFormStore.description);
    }

    const testClicked2 = () => {
      console.log(orderFormStore.nestedParent.nestedChild);
    }

    return {
      orderFormStore: orderFormStore,
      testClicked: testClicked,
      testClicked2: testClicked2
    }
  }
})

</script>
