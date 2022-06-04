<template>
  <q-page padding class="row">
    <div class="full-width column justify-center content-center items-center">
      <div>
        <q-form ref="pincodeLoginForm">
          <q-input
            v-model="authFormStore.pincode"
            label="Пинкод"
            :error-message="getPincodeInputError()"
            :error="hasErrorForPincodeInput()"
            reactive-rules
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

    if (authStore.phoneNumber === '') {
      router.push({name: 'login-phone'});
    }

    const resetForm = () => {
      nextTick(function () {
        pincodeLoginForm.value.resetValidation();
      })
    }

    nextTick(function () {
      resetForm();
    })

    let pincodeInputError = null;

    const getPincodeInputError = () => {
      if (pincodeInputError === null) {
        return '';
      }

      return pincodeInputError;
    }

    const hasErrorForPincodeInput = () => {
      return pincodeInputError !== null;
    }

    const submitValidForm = () => {
      authStore.loginPincode(authFormStore.pincode)
        .then(() => {
          resetForm();
          router.push({name: 'orders'});
        })
        .catch(error => {
          if (error.response && error.response.status === 400) {
            if (error.response.data && error.response.data.errors && error.response.data.errors.auth) {
              pincodeInputError = error.response.data.errors.auth;

              // Меняем значение поля на пустую строку и обратно, чтобы заново запустить функции валидации.
              const backup = authFormStore.pincode;
              authFormStore.pincode = '';
              authFormStore.pincode = backup;
            }
          }
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
      authStore,
      getPincodeInputError,
      hasErrorForPincodeInput,
    }
  }
})
</script>
