<template>
  <q-page>

    <q-list>
      <q-item
        v-for="orderStatus in orderStatusStore.orderStatuses" :key="orderStatus.sort_index"
        style="border-bottom: solid 1px rgba(0, 0, 0, 0.12); flex-grow: 0"
      >
        <div class="column col-12">

          <div class="row justify-between q-mb-xs">
            <div style="font-size: 20px">
              500₽
            </div>
            <div
              class="text-grey-7"
              style="font-size: 15px"
            >
              №{{ orderStatus.order_number }}
            </div>
          </div>

          <div>
            <div
              v-if="orderStatus.isComing"
              class="text-positive"
              style="font-size: 15px"
            >
              {{ orderStatus.label }}
            </div>

            <div
              v-if="orderStatus.isCanceled"
              class="text-grey-7"
            >
              {{ orderStatus.label }}
            </div>
          </div>

          <div>
            <q-list>
              <q-item
                v-for="place in orderStatus.places" :key="place.sort_index"
                class="q-pl-none"
              >
                <q-item-section
                  class="q-mr-sm"
                  style="border-left: solid 3px black; flex-grow: 0"
                >
                </q-item-section>

                <q-item-section>
                  <div
                    class="text-left"
                    style="font-size: 18px"
                  >
                    {{ place.street_address }}
                  </div>

                  <div v-if="place.will_come_at">
                    <div
                      class="text-left text-grey-7 q-mb-xs"
                      style="font-size: 15px"
                    >
                      Курьер будет
                    </div>

                    <div
                      class="text-left"
                      style="font-size: 15px"
                    >
                      {{ place.will_come_at }}
                    </div>
                  </div>
                </q-item-section>
              </q-item>
            </q-list>
          </div>

        </div>

      </q-item>
    </q-list>

  </q-page>
</template>

<script>
import {defineComponent} from 'vue'
import {useOrderStatus} from "stores/order-status";

export default defineComponent({
  name: 'OrdersListPage',
  setup() {

    const orderStatusStore = useOrderStatus();

    return {
      orderStatusStore: orderStatusStore,
    }
  },
})

</script>
