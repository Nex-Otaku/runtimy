<template>
  <OrderFormHeader
    v-if="orderFormStore.isLoaded"
    :title="'№' + orderFormStore.orderNumber"
    @reset="handleResetButtonClicked"
  />

  <q-page-container>

    <q-page class="fit column wrap justify-center items-start content-center q-pb-md">
      <div style="width: 100%; max-width: 300px;">

        <div class="text-caption q-ma-sm text-grey-7">
          Заказ заберёт и доставит ближайший свободный курьер
        </div>

        <q-form ref="editOrderForm" class="column">

          <OrderFormDeliveryOptions/>

          <!-- Начало блока адресов -->

          <OrderFormPlacesList/>

          <!-- Конец блока адресов -->


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

          <!-- TODO Блок оплаты -->
          <div
            class="q-mt-xl q-mb-sm bg-grey-5"
            style="height: 1px;width: 100%;"
          >
          </div>

          <div class="row justify-center">
            <q-btn
              no-caps
              outline
              align="center"
              class="btn-fixed-width"
              color="primary"
              label="Сохранить заказ"
              @click="handleSubmitButtonClicked"
            />
          </div>

        </q-form>
      </div>
    </q-page>

  </q-page-container>

</template>

<script>
import {defineComponent} from 'vue'
import {ref} from 'vue'
import {useOrderForm} from 'src/stores/order-form'
import {useQuasar} from 'quasar'
import {nextTick} from 'vue'
import {useRoute, useRouter} from 'vue-router'
import OrderFormHeader from "components/order-form/OrderFormHeader";
import OrderFormPlacesList from "components/order-form/OrderFormPlacesList";
import OrderFormDeliveryOptions from "components/order-form/OrderFormDeliveryOptions";

export default defineComponent({
  name: 'EditOrderPage',
  components: {OrderFormDeliveryOptions, OrderFormPlacesList, OrderFormHeader},
  setup() {
    const $q = useQuasar();
    const orderFormStore = useOrderForm();
    const router = useRouter();
    const editOrderForm = ref(null);

    const route = useRoute();
    const orderId = route.params.id;
    orderFormStore.loadOrder(orderId);

    const resetForm = () => {
      editOrderForm.value.resetValidation();
      orderFormStore.loadOrder(orderId);
    }

    const handleResetButtonClicked = () => {
      resetForm();
    }

    const submitValidForm = () => {
      orderFormStore.updateOrder(orderId)
        .then(() => {
          resetForm();
          router.push({name: 'orders'});

          nextTick(function () {
            $q.notify({
              message: 'Заказ обновлён!',
              icon: 'check',
              color: 'positive'
            })
          });
        });
    }

    const handleSubmitButtonClicked = () => {
      editOrderForm.value.validate().then(success => {
        if (!success) {
          return;
        }

        submitValidForm();
      })
    }

    return {
      editOrderForm: editOrderForm,
      orderFormStore: orderFormStore,
      handleSubmitButtonClicked: handleSubmitButtonClicked,
      handleResetButtonClicked: handleResetButtonClicked,
    }
  },
})

</script>
