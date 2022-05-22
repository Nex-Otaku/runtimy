<template>
  <q-header
    class="bg-white text-black"
    style="height: 83px"
  >
    <div
      class="row q-pt-lg"
    >
      <q-toolbar
        class="q-pa-none"
        style="height: 100%"
      >
        <div class="col">
          <q-btn
            :to="{ name: 'orders' }"
            flat
            round
            icon="west"
          />
        </div>
        <div class="col q-pt-lg">
          <q-toolbar-title
            class="absolute-center"
            style="font-size: 19px; font-weight: bold"
          >
            Новый заказ
          </q-toolbar-title>
        </div>
        <div
          class="col"
          style="text-align: end"
        >
          <q-btn
            flat
            no-caps
            @click="handleResetButtonClicked"
          >
            Очистить
          </q-btn>
        </div>
      </q-toolbar>

    </div>
  </q-header>


  <q-page-container>

    <q-page class="fit column wrap justify-center items-start content-center q-pb-md">
      <div style="width: 100%; max-width: 300px;">

      <div class="text-caption q-ma-sm text-grey-7">
        Заказ заберёт и доставит ближайший свободный курьер
      </div>

      <q-form ref="newOrderForm" class="column">

        <q-select
          v-model="orderFormStore.transport_type"
          :options="transport_options"
          label="Транспорт"
          class="q-ma-sm"
          outlined
        />
        <q-select
          v-model="orderFormStore.size_type"
          :options="size_options"
          label="Габариты"
          class="q-ma-sm"
          outlined
        />
        <q-select
          v-model="orderFormStore.weight_type"
          :options="weight_options"
          label="Вес"
          class="q-ma-sm"
          outlined
        />


        <!-- Начало блока адресов -->

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

        <div class="text-left q-mt-md q-pa-md text-grey-6 text-weight-bold">
          Дополнительно
        </div>

        <q-btn
          outline
          align="left"
          class="btn-fixed-width"
          color="primary"
          icon="add"
          label="Добавить адрес"
          @click="handleAddPlaceButtonClicked"
        />

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

        <div class="row items-center">
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
              @click="handleSubmitButtonClicked"
            />
          </div>
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
import {useRouter} from 'vue-router'

export default defineComponent({
  name: 'NewOrderPage',
  setup() {
    const $q = useQuasar();
    const orderFormStore = useOrderForm();
    const router = useRouter();
    const newOrderForm = ref(null);

    const resetForm = () => {
      orderFormStore.$reset();

      nextTick(function () {
        newOrderForm.value.resetValidation();
      })
    }

    const handleResetButtonClicked = () => {
      resetForm();
    }

    const submitValidForm = () => {
      orderFormStore.createOrder();
      resetForm();
      router.push({name: 'orders'});

      nextTick(function () {
        $q.notify({
          message: 'Заказ отправлен!',
          icon: 'check',
          color: 'positive'
        })
      });
    }

    const handleSubmitButtonClicked = () => {
      newOrderForm.value.validate().then(success => {
        if (!success) {
          return;
        }

        submitValidForm();
      })
    }

    const handleAddPlaceButtonClicked = () => {
      orderFormStore.addPlace();
    }

    return {
      newOrderForm: newOrderForm,
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
      handleResetButtonClicked: handleResetButtonClicked,
      handleAddPlaceButtonClicked: handleAddPlaceButtonClicked,
    }
  },
})

</script>
