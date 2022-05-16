<template>

  <q-header elevated>
    <q-toolbar>
      <div class="col">
        <q-btn flat round dense icon="west"/>
      </div>
      <div class="col">
        <q-toolbar-title class="absolute-center">
          Новый заказ
        </q-toolbar-title>
      </div>
      <div
        class="col"
        style="text-align: end"
      >
        <q-btn
          flat
          dense
          no-caps
          @click="orderFormStore.$reset()"
        >
          Очистить
        </q-btn>
      </div>
    </q-toolbar>
  </q-header>


  <q-page-container>

    <q-page
      class="flex flex-center column"
    >
      <div
        class="text-caption q-ma-sm text-grey-7"
        style="width: 100%; max-width: 300px"
      >
        Заказ заберёт и доставит ближайший свободный курьер
      </div>

      <q-select
        v-model="orderFormStore.transport_type"
        :options="transport_options"
        label="Транспорт"
        class="q-ma-sm"
        style="width: 100%; max-width: 300px"
        outlined
      />
      <q-select
        v-model="orderFormStore.size_type"
        :options="size_options"
        label="Габариты"
        class="q-ma-sm"
        style="width: 100%; max-width: 300px"
        outlined
      />
      <q-select
        v-model="orderFormStore.weight_type"
        :options="weight_options"
        label="Вес"
        class="q-ma-sm"
        style="width: 100%; max-width: 300px"
        outlined
      />


      <!-- Начало блока адресов -->

      <q-list
        style="width: 100%; max-width: 300px"
      >
        <q-item
          v-for="place in orderFormStore.places" :key="place.sort_index"
          class="q-pl-none"
        >
          <q-item-section
            style="border-left: solid 3px black; flex-grow: 0"
          >
          </q-item-section>

          <q-item-section>
            <div
              class="text-h6 text-left"
              style="width: 100%; max-width: 300px"
            >
              {{ place.title }}
            </div>

            <q-input
              v-model="place.street_address"
              label="Улица и номер дома"
              style="width: 100%; max-width: 300px"
            >
              <template #append>
                <q-icon name="place"/>
              </template>
            </q-input>

            <q-input
              v-model="place.phone_number"
              label="Телефон"
              style="width: 100%; max-width: 300px"
            >
              <template #append>
                <q-icon name="phone"/>
              </template>
            </q-input>

            <q-input
              v-model="place.courier_comment"
              type="textarea"
              label="Поручение для курьера"
              style="width: 100%; max-width: 300px"
              autogrow
            >
            </q-input>
          </q-item-section>
        </q-item>
      </q-list>
      <!--
      <div
        class="text-left q-mt-md q-pa-md text-grey-6 text-weight-bold"
        style="width: 100%; max-width: 300px"
      >
        Дополнительно
      </div>

      <q-btn
        outline
        align="left"
        class="btn-fixed-width"
        color="primary"
        icon="add"
        label="Добавить адрес"
        style="width: 100%; max-width: 300px"
      />
      -->

      <!-- Конец блока адресов -->


      <div
        class="text-h6 text-left q-mt-md"
        style="width: 100%; max-width: 300px"
      >
        Содержимое заказа
      </div>

      <q-input
        v-model="orderFormStore.description"
        label="Что везём?"
        style="width: 100%; max-width: 300px"
      >
      </q-input>

      <q-input
        v-model="orderFormStore.price_of_package"
        label="Ценность (Сумма)"
        style="width: 100%; max-width: 300px"
        bottom-slots
      >
        <template #hint>
          Если груз потеряется или будет повреждён, вернём до 50000₽ в течение трёх рабочих дней.
        </template>
      </q-input>

      <!-- TODO Блок оплаты -->
      <div
        class="q-mt-xl q-mb-sm bg-grey-5"
        style="height: 1px;
      width: 100%;
      max-width: 300px"
      >
      </div>

      <div
        class="row items-center"
        style="width: 100%; max-width: 300px"
      >
        <div class="col-sm">
          от 500₽
        </div>
        <q-space></q-space>
        <div class="col-8">
          <q-btn
            no-caps
            outline
            align="center"
            class="btn-fixed-width"
            color="primary"
            label="Отправить заказ"
            style="width: 100%; max-width: 300px"
            @click="handleSubmitButtonClicked"
          />
        </div>
      </div>

    </q-page>

  </q-page-container>

</template>

<script>
import {ref} from 'vue'
import {defineComponent} from 'vue'
import {useOrderForm} from 'src/stores/order-form'
import {useQuasar} from 'quasar'

export default defineComponent({
  name: 'NewOrderPage',
  setup() {
    const $q = useQuasar();
    const orderFormStore = useOrderForm();

    const handleSubmitButtonClicked = () => {
      orderFormStore.createOrder();

      $q.notify({
        message: 'Заказ отправлен!',
        icon: 'check',
        color: 'positive'
      })
    }

    return {
      text: ref(null),
      orderFormStore: orderFormStore,
      transport_options: [
        {
          label: 'Пешком',
          value: 'feet'
        },
        {
          label: 'Легковой',
          value: 'passenger'
        },
        {
          label: 'Грузовой',
          value: 'cargo'
        },
      ],
      size_options: [
        {
          label: 'Мелкий',
          value: 'small'
        },
        {
          label: 'Средний',
          value: 'medium'
        },
        {
          label: 'Крупный',
          value: 'large'
        },
        {
          label: 'Очень крупный',
          value: 'extra-large'
        },
      ],
      weight_options: [
        {
          label: 'До 1 кг',
          value: '1kg'
        },
        {
          label: 'До 5 кг',
          value: '5kg'
        },
        {
          label: 'До 10 кг',
          value: '10kg'
        },
      ],
      handleSubmitButtonClicked: handleSubmitButtonClicked,
    }
  }
})

</script>
