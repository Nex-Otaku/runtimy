<template>
  <q-page padding>
    <h1>Counter 1</h1>
    <q-item>
      {{ counterStore.counter }}
    </q-item>
    <q-btn @click="clickIncrement">
      Увеличить счётчик
    </q-btn>
    <q-btn @click="clickGotoPage2">
      Перейти на страницу 2
    </q-btn>
  </q-page>
</template>

<script>
import {defineComponent} from "vue";
import {useCounter} from "stores/counter";
import {useRouter} from "vue-router";

export default defineComponent({
  name: 'CounterFirstPage',
  setup() {
    const counterStore = useCounter();
    const router = useRouter();


    const clickGotoPage2 = () => {
      router.push({name: 'counter2'});
    }

    const clickIncrement = () => {
      counterStore.increment();
    }

    return {
      counterStore: counterStore,
      clickGotoPage2: clickGotoPage2,
      clickIncrement: clickIncrement,
    }
  },
})
</script>
