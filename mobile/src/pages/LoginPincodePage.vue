<template>
  <q-page padding class="row">
    <div class="full-width column justify-center content-center items-center">
      <div>
        <q-form ref="pincodeLoginForm">
          <q-input
            v-model="authFormStore.pincode"
            label="Пинкод"
          />
          <div class="row justify-center q-mt-md">
            <q-btn
              color="primary"
              label="Отправить пинкод"
              @click="pincodeSubmitClicked"
            />
          </div>
        </q-form>
      </div>
    </div>
  </q-page>
</template>

<script>
import {defineComponent, nextTick, ref} from "vue";
import {useAuth} from "stores/auth";
import {useRouter} from "vue-router";
import {useAuthForm} from "stores/auth-form";

export default defineComponent({
  name: 'LoginPincodePage',
  setup() {
    const authStore = useAuth();
    const authFormStore = useAuthForm();
    const pincodeLoginForm = ref(null);
    const router = useRouter();

    const resetForm = () => {
      nextTick(function () {
        pincodeLoginForm.value.resetValidation();
      })
    }

    nextTick(function () {
      resetForm();
    })

    const submitValidForm = () => {
      authStore.loginPincode(authFormStore.pincode)
        .then(() => {
          resetForm();
          router.push({name: 'orders'});
        });
    }

    const pincodeSubmitClicked = () => {
      pincodeLoginForm.value.validate().then(success => {
        if (!success) {
          return;
        }

        submitValidForm();
      })
    }

    return {
      authFormStore,
      pincodeLoginForm,
      pincodeSubmitClicked,
    }
  }
})
</script>
