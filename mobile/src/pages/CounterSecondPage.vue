<template>
  <q-page padding>
    <h1>Counter 2</h1>
    <q-item>
      {{ counterStore.counter }}
    </q-item>
    <q-btn @click="clickIncrement">
      Увеличить счётчик
    </q-btn>
    <q-btn @click="clickGotoPage1">
      Перейти на страницу 1
    </q-btn>
  </q-page>
</template>

<script>
import {defineComponent} from "vue";
import {useCounter} from "stores/counter";
import {useRouter} from "vue-router";

export default defineComponent({
  name: 'CounterSecondPage',
  setup() {
    const counterStore = useCounter();
    const router = useRouter();


    const clickGotoPage1 = () => {
      router.push({name: 'counter1'});
    }

    const clickIncrement = () => {
      counterStore.increment();
    }

    return {
      counterStore: counterStore,
      clickGotoPage1: clickGotoPage1,
      clickIncrement: clickIncrement,
    }
  },
})
</script>
