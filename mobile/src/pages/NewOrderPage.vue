<template>
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
      v-model="model"
      :options="options"
      label="Пешком"
      class="q-ma-sm"
      style="width: 100%; max-width: 300px"
      outlined
    />
    <q-select
      v-model="model"
      :options="options"
      label="Габариты"
      class="q-ma-sm"
      style="width: 100%; max-width: 300px"
      outlined
    />
    <q-select
      v-model="model"
      :options="options"
      label="До 1 кг"
      class="q-ma-sm"
      style="width: 100%; max-width: 300px"
      outlined
    />

    <div
      class="text-h6 text-left q-mt-md"
      style="width: 100%; max-width: 300px"
    >
      Откуда
    </div>

    <q-input
      v-model="text"
      label="Улица и номер дома"
      style="width: 100%; max-width: 300px"
    >
      <template #append>
        <q-icon name="place"/>
      </template>
    </q-input>

    <q-input
      v-model="text"
      label="Телефон"
      style="width: 100%; max-width: 300px"
    >
      <template #append>
        <q-icon name="phone"/>
      </template>
    </q-input>

    <q-input
      v-model="text"
      type="textarea"
      label="Поручение для курьера"
      style="width: 100%; max-width: 300px"
      autogrow
    >
    </q-input>

    <div
      class="text-h6 text-left q-mt-md"
      style="width: 100%; max-width: 300px"
    >
      Куда
    </div>

    <q-input
      v-model="text"
      label="Улица и номер дома"
      style="width: 100%; max-width: 300px"
    >
      <template #append>
        <q-icon name="place"/>
      </template>
    </q-input>

    <q-input
      v-model="text"
      label="Телефон"
      style="width: 100%; max-width: 300px"
    >
      <template #append>
        <q-icon name="phone"/>
      </template>
    </q-input>

    <q-input
      v-model="text"
      type="textarea"
      label="Поручение для курьера"
      style="width: 100%; max-width: 300px"
      autogrow
    >
    </q-input>

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

    <div
      class="text-h6 text-left q-mt-md"
      style="width: 100%; max-width: 300px"
    >
      Содержимое заказа
    </div>

    <q-input
      v-model="text"
      label="Что везём?"
      style="width: 100%; max-width: 300px"
    >
    </q-input>

    <q-input
      v-model="text"
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
        />
      </div>
    </div>

  </q-page>
</template>

<script>
import {defineComponent} from 'vue'

export default defineComponent({
  name: 'NewOrderPage'
})
</script>
