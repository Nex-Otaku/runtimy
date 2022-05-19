<template>
  <q-item
    clickable
    tag="a"
    :href="link"
    class="column wrap justify-start items-stretch content-center"
  >
    <q-item-section
      avatar
      class="items-center q-pa-none"
    >
      <q-icon :name="icon" />
    </q-item-section>

    <q-item-section
      class="items-center"
    >
      <q-item-label>{{ title }}</q-item-label>
    </q-item-section>
  </q-item>
</template>

<script>
import { defineComponent } from 'vue'

export default defineComponent({
  name: 'FooterActionButton',
  props: {
    title: {
      type: String,
      required: true
    },

    link: {
      type: String,
      required: true
    },

    icon: {
      type: String,
      required: true
    }
  }
})
</script>
