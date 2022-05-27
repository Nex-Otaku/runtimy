<template>
  <q-header
    v-if="orderViewStore.orderInfo"
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
            №{{ orderViewStore.orderInfo.order_number }}
          </q-toolbar-title>
        </div>
      </q-toolbar>

    </div>
  </q-header>


  <q-page-container
    v-if="orderViewStore.orderInfo"
  >

    <q-page class="fit column wrap items-start content-center q-pb-md">
      <div style="width: 100%;">

        <div
          class="bg-black text-white q-pl-lg q-pt-xs"
          style="height: 31px; font-size: 15px; font-weight: bold;"
        >
          {{ orderViewStore.orderInfo.order_status_label }}
        </div>

        <div
          class="q-pl-lg"
        >
          <div
            class="q-mt-sm"
            style="font-size: 20px;"
          >
            {{ orderViewStore.orderInfo.order_price }}₽
          </div>

          <div
            v-if="orderViewStore.orderInfo.is_coming_next_place"
            class="q-mb-md"
          >
            <div
              class="q-mt-sm text-grey-7"
              style="font-size: 15px;"
            >
              Следующий адрес
            </div>

            <div
              style="font-size: 18px;"
            >
              {{ orderViewStore.orderInfo.order_next_place_address }}
            </div>

            <div
              class="q-mt-sm q-mb-xs text-grey-7"
              style="font-size: 15px;"
            >
              Курьер будет
            </div>

            <div
              style="font-size: 15px;"
            >
              {{ orderViewStore.orderInfo.next_place_coming_time }}
            </div>
          </div>

          <div
            style="border-top: solid 1px rgba(0, 0, 0, 0.22);"
          >
            <q-btn
              :to="{ name: 'edit-order' }"
              flat
              no-caps
              align="left"
              class="q-pl-none full-width"
              style="font-size: 18px;"
            >
              Редактировать
            </q-btn>
          </div>

          <div
            style="border-top: solid 1px rgba(0, 0, 0, 0.22);"
          >
            <q-btn
              :to="{ name: 'cancel-order' }"
              flat
              no-caps
              align="left"
              class="q-pl-none full-width"
              style="font-size: 18px;"
              @click="clickCancelOrder"
            >
              Отменить доставку
            </q-btn>
          </div>

        </div>


        <div
          class="q-pl-lg bg-grey-3"
          style="padding-bottom: 6px; padding-top: 7px;
          font-size: 18px; font-weight: bold;"
        >
          Курьер
        </div>

        <div
          v-if="orderViewStore.orderInfo.is_assigned_courier"
          class="row content-stretch"
          style="padding-top: 16px;
                padding-bottom: 22px;
                padding-left: 23px;
                "
        >
          <div
            class="col"
            style="flex-grow: 0;
                flex-shrink: 0;
                flex-basis: 50px;
                "
          >
            <div
              style="background-color: #C4C4C4;
              width: 50px;
              height: 50px;
              border-radius: 50%;
              "
            >
            </div>
            <!-- Аватар -->
            <!-- {{ orderViewStore.orderInfo.courier_avatar }} -->

            <!--
            <q-item-section avatar>
              <q-avatar>
                <img src="https://cdn.quasar.dev/img/boy-avatar.png">
              </q-avatar>
            </q-item-section>
            -->

          </div>
          <div
            class="col"
            style="padding-left: 18px;"
          >
            <div class="column full-height justify-center">
              <div
                style="font-size: 18px; font-weight: bold;
                      line-height: 20px;
                      "
              >
                {{ orderViewStore.orderInfo.courier_name }}
              </div>
              <div
                style="font-size: 18px;
                      line-height: 20px;
                      "
              >
                {{ orderViewStore.orderInfo.courier_phone_number }}
              </div>
            </div>
          </div>
          <div
            class="col"
            style="max-width: 48px;"
          >
            <div class="column full-height justify-center">
              <q-btn
                icon="phone_in_talk"
                flat
                round
                @click="clickCallCourier"
              ></q-btn>
            </div>
          </div>
        </div>


        <div
          v-if="!orderViewStore.orderInfo.is_assigned_courier"
          class="row content-stretch q-pl-lg q-pt-md q-pb-md"
        >
          <div
            class="text-grey-7"
            style="font-size: 18px; line-height: 20px;"
          >
            Курьер не назначен
          </div>
        </div>

        <div
          class="full-width inset-shadow shadow-2 bg-grey-3"
          style="height: 40px;"
        ></div>

        <div class="q-pl-lg">

          <div
            class="q-mt-sm text-grey-7"
            style="font-size: 18px;"
          >
            Информация
          </div>

          <div
            class="q-mt-sm text-grey-7"
            style="font-size: 15px;"
          >
            Создан
          </div>

          <div
            style="font-size: 15px;"
          >
            {{ orderViewStore.orderInfo.order_created_at }}
          </div>

          <div
            class="q-mt-sm text-grey-7"
            style="font-size: 15px;"
          >
            Способ доставки и вес
          </div>

          <div
            style="font-size: 18px;"
          >
            {{ orderViewStore.orderInfo.transport_type_and_weight_type }}
          </div>

          <div
            v-if="orderViewStore.orderInfo.description"
            class="q-mt-sm text-grey-7"
            style="font-size: 15px;"
          >
            Содержимое
          </div>

          <div
            style="font-size: 18px;"
          >
            {{ orderViewStore.orderInfo.description }}
          </div>

          <div
            class="q-mt-sm text-grey-7"
            style="font-size: 15px;"
          >
            Способ оплаты
          </div>

          <div
            style="font-size: 18px;"
          >
            {{ orderViewStore.orderInfo.payment_type }}
          </div>
        </div>

        <div
          class="q-mt-sm full-width inset-shadow shadow-2 bg-grey-3"
          style="height: 40px;"
        ></div>

        <q-list>
          <q-expansion-item
            v-for="place in orderViewStore.orderInfo.places" :key="place.sort_index"
            header-class="q-pl-none"
            style="border-bottom: solid 1px rgba(0, 0, 0, 0.22);"
          >
            <template #header>
              <q-item-section
                style="flex-grow: 0; flex-shrink: 0; flex-basis: 63px;"
              >
                <div
                  class="column justify-center items-center
                      q-ml-lg
                      "
                  style="height: 35px; width: 35px;
                      border-radius: 50%;
                      border: black solid 2px;
                      "
                >
                  <div style="margin-top: 1px; font-size: 18px;">
                    {{ place.sort_index }}
                  </div>
                </div>
              </q-item-section>

              <q-item-section>
                <div
                  class="q-ml-sm text-left self-start"
                  style="font-size: 18px;"
                >
                  {{ place.street_address }}
                </div>
              </q-item-section>
            </template>

            <div
              class="q-pl-lg q-pb-sm"
              style="border-top: solid 1px rgba(0, 0, 0, 0.22);"
            >
              <div
                v-if="!place.phone_number && !place.courier_comment"
                class="q-mt-sm text-grey-7"
                style="font-size: 15px;"
              >
                Дополнительных данных нет
              </div>

              <div
                v-if="place.phone_number"
                class="q-mt-sm text-grey-7"
                style="font-size: 15px;"
              >
                Телефон
              </div>

              <div
                style="font-size: 18px;"
              >
                {{ place.phone_number }}
              </div>

              <div
                v-if="place.courier_comment"
                class="q-mt-sm text-grey-7"
                style="font-size: 15px;"
              >
                Комментарий для курьера
              </div>

              <div
                style="font-size: 18px;"
              >
                {{ place.courier_comment }}
              </div>
            </div>
          </q-expansion-item>
        </q-list>
      </div>
    </q-page>

  </q-page-container>

</template>

<script>
import {defineComponent} from 'vue'
import {useOrderView} from 'src/stores/order-view'
import {useRoute} from 'vue-router'
import {api} from "boot/axios";
import {useQuasar} from "quasar";
import {openURL} from 'quasar'

export default defineComponent({
  name: 'ViewOrderPage',
  setup() {
    const $q = useQuasar();
    const route = useRoute();
    const orderViewStore = useOrderView();
    const orderId = route.params.id;
    orderViewStore.fetch(orderId);

    const clickCancelOrder = () => {
      api.post('/api/cancel-order/' + orderId)
        .then(() => {
          orderViewStore.fetch(orderId);

          $q.notify({
            message: 'Заказ отменен!',
            icon: 'check',
            color: 'positive'
          })
        })
    }

    const clickCallCourier = () => {
      openURL('tel:' + orderViewStore.orderInfo.courier_phone_number_uri);
    }

    return {
      orderViewStore: orderViewStore,
      clickCancelOrder: clickCancelOrder,
      clickCallCourier: clickCallCourier,
    }
  },
})

</script>
