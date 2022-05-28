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
            {{ title }}
          </q-toolbar-title>
        </div>
        <div
          class="col"
          style="text-align: end"
        >
          <q-btn
            flat
            no-caps
            @click="$emit('reset')"
          >
            Очистить
          </q-btn>
        </div>
      </q-toolbar>

    </div>
  </q-header>
</template>

<script>
export default {
  name: 'OrderFormHeader',
  props: {
    title: {
      type: String,
      default: '',
    },
  },
  emits: ['reset'],
  setup () {
    return {
    }
  }
}
</script>
